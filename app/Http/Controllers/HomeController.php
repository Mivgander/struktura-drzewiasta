<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategoria;
use App\Models\RelacjaKategorii;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    /**
     * Strona głowna
    */
    function index()
    {
        $kategorie = Kategoria::where('id', '>=', 1)->with(['rodzic', 'dziecko'])->get();

        return view('index', [
            'kategorie' => $kategorie
        ]);
    }

    /**
     * Dodawanie kategorii
    */
    function dodajKategorie(Request $request)
    {
        $this->validate($request, [
            'nowaKategoria' => 'bail|required|string|regex:/^[A-ZĄĘŻŹĆŃÓŁa-zęóąłżźćń0-9_\s\-.,]+$/|max:80',
            'rodzicKategoria' => 'bail|required|numeric'
        ],
        [
            'nowaKategoria.required' => 'Nazwa kategorii jest wymagana',
            'nowaKategoria.string' => 'Nazwa kategorii musi być ciągiem znaków',
            'nowaKategoria.regex' => 'Użyto niewłaściwych znaków w nazwie',
            'nowaKategoria.max' => 'Nazwa kategorii nie może być dłuższa niż :max znaków',
            'rodzicKategoria.required' => 'Wystąpił problem z dodaniem kategorii. Odświerz stronę i spróbuj ponownie',
            'rodzicKategoria.numeric' => 'Wystąpił błąd z dodaniem kategorii. Odświerz stronę i spróbuj ponownie'
        ]);

        // Sprawdzanie czy podana nazwa istnieje na tym poziomie
        if($request->rodzicKategoria == '-24')
        {
            foreach(Kategoria::has('dziecko', '=', '0')->get() as $row)
            {
                if($row->kategoria == $request->nowaKategoria)
                    throw ValidationException::withMessages(['nowaKategoria' => 'Podana nazwa już istnieje na tym poziomie']);
            }
        }
        else if(!$this->sprawdzNazwe(Kategoria::find($request->rodzicKategoria), $request->nowaKategoria))
        {
            throw ValidationException::withMessages(['nowaKategoria' => 'Podana nazwa już istnieje na tym poziomie']);
        }

        // Dodanie nowej kategorii
        $nowaKategoria = Kategoria::create([
            'kategoria' => $request->nowaKategoria
        ]);

        // Jeżeli kategoria ma być tą główną to nie tworzymy relacji
        if($request->rodzicKategoria != '-24')
        {
            RelacjaKategorii::create([
                'rodzic' => $request->rodzicKategoria,
                'dziecko' => $nowaKategoria->id
            ]);
        }

        return back()->with('sukces', 'Kategoria została dodana');
    }

    /**
     * Zmienianie nazwy kategorii
     */
    function zmienNazweKategorii(Request $request)
    {
        $this->validate($request, [
            'nowaNazwaKategorii' => 'bail|required|string|regex:/^[A-ZĄĘŻŹĆŃÓŁa-zęóąłżźćń0-9_\s\-.,]+$/|max:80',
            'idKategorii' => 'bail|required|numeric'
        ],
        [
            'nowaNazwaKategorii.required' => 'Nazwa kategorii jest wymagana',
            'nowaNazwaKategorii.string' => 'Nazwa kategorii musi być ciągiem znaków',
            'nowaNazwaKategorii.regex' => 'Użyto niewłaściwych znaków w nazwie',
            'nowaNazwaKategorii.max' => 'Nazwa kategorii nie może być dłuższa niż :max znaków',
            'idKategorii.required' => 'Wystąpił problem z dodaniem kategorii. Odświerz stronę i spróbuj ponownie',
            'idKategorii.numeric' => 'Wystąpił błąd z dodaniem kategorii. Odświerz stronę i spróbuj ponownie'
        ]);

        // Zmieniana kategoria
        $kategoria = Kategoria::find($request->idKategorii);

        // Sprawdzanie czy podana nazwa występuje na tym poziomie
        if($kategoria->dziecko == null)
        {
            foreach(Kategoria::has('dziecko', '=', '0')->get() as $row)
            {
                if($row->kategoria == $request->nowaNazwaKategorii)
                    throw ValidationException::withMessages(['nowaNazwaKategorii' => 'Podana nazwa już istnieje na tym poziomie']);
            }
        }
        else if(!$this->sprawdzNazwe($kategoria->dziecko->kategoriaRodzica, $request->nowaNazwaKategorii))
            throw ValidationException::withMessages(['nowaNazwaKategorii' => 'Podana nazwa już istnieje na tym poziomie']);

        // Zmienianie nazwy
        $kategoria->kategoria = $request->nowaNazwaKategorii;
        $kategoria->save();

        return back()->with('sukces', 'Nazwa kategorii została zmieniona');
    }

    /**
     * Przenoszenie kategorii
     */
    function zmienRodzicaKategorii(Request $request)
    {
        $this->validate($request, [
            'nowaNazwaRodzica' => 'bail|required|string',
            'akcja' => 'bail|in:wszystko,podkategorie',
            'mojaKategoria' => 'bail|required|numeric'
        ],
        [
            'nowaNazwaRodzica.required' => 'Nazwa kategorii jest wymagana',
            'nowaNazwaRodzica.string' => 'Nazwa kategorii musi być ciągiem znaków',
            'akcja.in' => 'Podana akcja nie istnieje',
            'mojaKategoria.required' => 'Wystąpił problem z dodaniem kategorii. Odświerz stronę i spróbuj ponownie',
            'mojaKategoria.numeric' => 'Wystąpił błąd z dodaniem kategorii. Odświerz stronę i spróbuj ponownie'
        ]);

        // Sprawdzanie czy podana ścieżka istnieje
        $nowyRodzic = false;
        if($request->nowaNazwaRodzica != '*')
        {
            $nowyRodzic = $this->checkPath($request->nowaNazwaRodzica, null);
            if($nowyRodzic == false) throw ValidationException::withMessages(['nowaNazwaRodzica' => 'Podana ścieżka nie istnieje']);
        }

        // Sprawdzanie czy użytkownik chce przenieść kategorie do jej podkategorii
        if(str_contains($request->nowaNazwaRodzica, $this->napiszSciezke(Kategoria::find($request->mojaKategoria))))
        {
            throw ValidationException::withMessages(['nowaNazwaRodzica' => 'Nie możesz przenieść kategorii do niej samej lub do jej podkategorii']);
        }

        // Jeżeli użytkownik chce przenieść wszystko lub przenoszona kategoria nie ma podkategorii
        if($request->akcja == 'wszystko' || !$request->akcja)
        {
            // Jeżeli wpisano * usuwa się relację gdzie jest dzieckiem, aby kategoria stała się główną
            if($request->nowaNazwaRodzica == '*')
            {
                // Sprawdzanie czy podana nazwa występuje na tym poziomie
                $ja = Kategoria::find($request->mojaKategoria)->get();
                foreach(Kategoria::has('dziecko', '=', '0')->get() as $row)
                {
                    if($row->kategoria == $ja->kategoria)
                        throw ValidationException::withMessages(['mojaKategoria' => 'Podana nazwa już istnieje na tym poziomie']);
                }

                RelacjaKategorii::where('dziecko', $request->mojaKategoria)->delete();
            }
            else
            {
                // Sprawdzanie czy podana nazwa istnieje u rodzica
                if(!$this->sprawdzNazwe($nowyRodzic, Kategoria::where('id', $request->mojaKategoria)->get()[0]->kategoria))
                    throw ValidationException::withMessages(['nowaNazwaRodzica' => 'Nazwa przenoszonej kategorii już istnieje u rodzica']);

                // Jeżeli kategoria jest dzieckiem kogoś to zmienia się relacje
                if(RelacjaKategorii::where('dziecko', $request->mojaKategoria)->count() > 0)
                {
                    RelacjaKategorii::where('dziecko', $request->mojaKategoria)->update(['rodzic' => $nowyRodzic->id]);
                }
                else // Jeżeli jest kategorią główną, dodaje się ralacje
                {
                    RelacjaKategorii::create([
                        'rodzic' => $nowyRodzic->id,
                        'dziecko' => $request->mojaKategoria
                    ]);
                }
            }

            if(!$request->akcja) return back()->with('sukces', 'Kategoria została przeniesiona');
            else return back()->with('sukces', 'Kategoria razem z podkategoriami zostały przeniesione');
        }
        else if($request->akcja == 'podkategorie') // Jeżeli użytkownik chce przenieść tylko podkategorie
        {
            // Jeżeli wpisano * usuwa się relację gdzie jest dzieckiem, aby kategoria stała się główną
            if($request->nowaNazwaRodzica == '*')
            {
                // Sprawdzanie czy któraś z nazw podkategorii występuje na tym poziomie
                foreach(Kategoria::has('dziecko', '=', '0')->get() as $row)
                {
                    foreach(RelacjaKategorii::where('rodzic', $request->mojaKategoria)->get() as $dziecko)
                    if($row->kategoria == $dziecko->kategoriaDziecka->kategoria)
                        throw ValidationException::withMessages(['mojaKategoria' => 'Podana nazwa już istnieje na tym poziomie']);
                }

                RelacjaKategorii::where('rodzic', $request->mojaKategoria)->delete();
            }
            else
            {
                // Sprawdzanie czy nazwy podkategorii istnieją u rodzica
                foreach(RelacjaKategorii::where('rodzic', $request->mojaKategoria)->get() as $row)
                {
                    if(!$this->sprawdzNazwe($nowyRodzic, $row->kategoriaDziecka->kategoria))
                        throw ValidationException::withMessages(['nowaNazwaRodzica' => 'Nazwa przenoszonej kategorii już istnieje u rodzica']);
                }

                RelacjaKategorii::where('rodzic', $request->mojaKategoria)->update(['rodzic' => $nowyRodzic->id]);
            }

            return back()->with('sukces', 'Podkategorie zostały przeniesione');
        }
    }

    /**
     * Usuwanie kategorii
     */
    function usunKategorie(Request $request)
    {
        // Jeżeli użytkownik usuwa kategorie bez podkategorii
        if($request->usunKategoria)
        {
            $this->validate($request, [
                'usunKategoria' => 'bail|required|numeric'
            ],
            [
                'usunKategoria.required' => 'Wystąpił problem z usunięciem kategorii. Odświerz stronę i spróbuj ponownie',
                'usunKategoria.numeric' => 'Wystąpił błąd z usunięciem kategorii. Odświerz stronę i spróbuj ponownie'
            ]);

            $kategoria = Kategoria::find($request->usunKategoria);
            $kategoria->delete();

            return back()->with('sukces', 'Kategoria została usunięta');
        }
        else // Jeżeli użytkownik usuwa kategorie z podkategoriami
        {
            $this->validate($request, [
                'usunKategoria2' => 'bail|required|numeric',
                'akcja' => 'bail|required|in:usun,usun_podkategorie,przenies',
            ],
            [
                'akcja.required' => 'Musisz wybrać rodzaj akcji',
                'akcja.in' => 'Podana akcja nie istnieje',
                'usunKategoria2.required' => 'Wystąpił problem z usunięciem kategorii. Odświerz stronę i spróbuj ponownie',
                'usunKategoria2.numeric' => 'Wystąpił błąd z usunięciem kategorii. Odświerz stronę i spróbuj ponownie'
            ]);

            // Jeżeli użytkownik chce przenieść podkategorie
            if($request->akcja == 'przenies')
            {
                $this->validate($request, [
                    'nowaNazwaRodzica' => [
                        'bail',
                        'string',
                    ]
                ],
                [
                    'nowaNazwaRodzica.string' => 'Nazwa kategorii musi być ciągiem znaków',
                ]);

                // Jeżeli wpisano ścieżkę
                if($request->nowaNazwaRodzica != '*')
                {
                    // Sprwadzanie czy podana ścieżka istnieje
                    $nowyRodzic = $this->checkPath($request->nowaNazwaRodzica, null);
                    if($nowyRodzic == false)
                        throw ValidationException::withMessages(['nowaNazwaRodzica' => 'Podana ścieżka nie istnieje']);

                    // Sprawdzanie czy użytkownik chce przenieść kategorie do jej podkategorii
                    if(str_contains($request->nowaNazwaRodzica, $this->napiszSciezke(Kategoria::find($request->usunKategoria2))))
                    {
                        throw ValidationException::withMessages(['nowaNazwaRodzica' => 'Nie możesz przenieść kategorii do niej samej lub do jej podkategorii']);
                    }

                    RelacjaKategorii::where('rodzic', $request->usunKategoria2)->update(['rodzic' => $nowyRodzic->id]);
                }
                else if($request->nowaNazwaRodzica == '*') // Jeżeli wpisano * usuwa się relację gdzie jest dzieckiem, aby kategoria stała się główną
                {
                    // Sprawdzanie czy nazwy podkategorii istnieją u rodzica
                    foreach(Kategoria::has('dziecko', '=', '0')->get() as $row)
                    {
                        foreach(RelacjaKategorii::where('rodzic', $request->usunKategoria2)->get() as $dziecko)
                        if($row->kategoria == $dziecko->kategoriaDziecka->kategoria)
                            throw ValidationException::withMessages(['mojaKategoria' => 'Podana nazwa już istnieje na tym poziomie']);
                    }

                    RelacjaKategorii::where('rodzic', $request->usunKategoria2)->delete();
                }

                $kategoria = Kategoria::find($request->usunKategoria2);
                $kategoria->delete();

                return back()->with('sukces', 'Kategoria została usunięta, a podkategorie przeniesione');
            }
            else if($request->akcja == 'usun') // Jeżeli użytkownik chce usunąć wszystko
            {
                $ja = Kategoria::find($request->usunKategoria2);
                $this->usunWszystko($ja);

                return back()->with('sukces', 'Kategoria oraz jej podkategorie zostały usunięte');
            }
            else if($request->akcja = 'usun_podkategorie') // Jeżeli użytkownik chce usunąć tylko podkategorie
            {
                $ja = Kategoria::find($request->usunKategoria2);
                foreach($ja->rodzic as $row)
                {
                    $this->usunWszystko($row->kategoriaDziecka);
                }

                return back()->with('sukces', 'Podkategorie zostały usunięte');
            }
        }
    }

    ////////////////////////
    // FUNKCJE POMOCNICZE //
    ////////////////////////

    /**
     * Sprawdzanie czy podana ścieżka istnieje. Jeżeli tak, zwraca ostatnią kategorie w ścieżce
     *
     * @param string $path ścieżka wpisana przez użytkownika
     * @param \App\Models\Kategoria $rodzic rodzic następej sprawdzanej kategorii.
     * Przy wywoływaniu funkcji nie podawać. Jest to zmienna pomocnicza do rekurencji
    */
    function checkPath($path, $rodzic = null) : \App\Models\Kategoria|false
    {
        $znak = strpos($path, '/');
        if($znak !== false)
        {
            $kategoria = Kategoria::where('kategoria', substr($path, 0, $znak));
            $wynik = '';
            if($rodzic != null)
            {
                $wynik = $this->sprawdzRodzica($kategoria->get(), $rodzic->id);
            }

            if($kategoria->count() == 0
            || ($rodzic != null && $wynik == null))
            {
                return false;
            }


            if($rodzic != null) return $this->checkPath(substr($path, $znak+1), $wynik);
            else return $this->checkPath(substr($path, $znak+1), $kategoria->get()[0]);
        }
        else
        {
            $kategoria = Kategoria::where('kategoria', $path);
            $wynik = '';
            if($rodzic != null)
            {
                $wynik = $this->sprawdzRodzica($kategoria->get(), $rodzic->id);
            }

            if($kategoria->count() == 1 && $wynik === '')
            {
                return  $kategoria->get()[0];
            }
            else if(($kategoria->count() === 0) || ($kategoria->count() > 0 && $wynik === null))
            {
                return false;
            }
            else
            {
                return $wynik;
            }
        }
    }

    /**
     * Sprawdza czy któraś z podanych kategorii jest dzieckiem rodzica o danym id. Jeżeli tak, zwraca tą kategorię
     *
     * @param \App\Models\Kategoria $grupa kategorie, które sprawdza funkcja
     * @param int $rodzic_id id domniemanego rodzica
     */
    function sprawdzRodzica($grupa ,$rodzic_id) : \App\Models\Kategoria|null
    {
        foreach($grupa as $row)
        {
            if($row->dziecko->rodzic == $rodzic_id)
            {
                return $row;
            }
        }

        return null;
    }

    /**
     * Sprawdza czy rodzic posiada podkategorie o podanej nazwie
     *
     * @param \App\Models\Kategoria $rodzic kategoria
     * @param string $nazwa nazwa do sprawdzenia
     */
    function sprawdzNazwe($rodzic, $nazwa) : bool
    {
        foreach($rodzic->rodzic as $row)
        {
            if($row->kategoriaDziecka->kategoria == $nazwa)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Usuwa wszystkie podkategorie rodzica oraz jego
     *
     * @param \App\Models\Kategoria $kategoria rodzic do usunięcia
     */
    function usunWszystko($kategoria)
    {
        if(count($kategoria->rodzic) == 0)
        {
            $kategoria->delete();
        }
        else
        {
            foreach($kategoria->rodzic as $row)
            {
                $this->usunWszystko($row->kategoriaDziecka);
            }
            $kategoria->delete();
        }
    }

    /**
     * Zwraca ścieżkę danej kategorii
     *
     * @param \App\Models\Kategoria $kategoria kategoria, której ścieżka ma być napisana
     * @param string $scieżka zwracana ścieżka.
     * Przy wywoływaniu funkcji nie podawać. Jest to zmienna pomocnicza do rekurencji
     */
    function napiszSciezke($kategoria, $sciezka = '') : string
    {
        if($sciezka == '')
        {
            $sciezka = $kategoria->kategoria;
        }
        else
        {
            $sciezka = $kategoria->kategoria . '/' . $sciezka;
        }

        if($kategoria->dziecko != null)
        {
            return $this->napiszSciezke($kategoria->dziecko->kategoriaRodzica, $sciezka);
        }
        else
        {
            return  $sciezka;
        }
    }
}
