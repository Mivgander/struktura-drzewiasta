<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Drzewo kategorii</title>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/moje.css') }}">
</head>
<body>
    <main class="fs-3">
        <div id="app">
            <app
                props_kategorie='@json($kategorie)'
            />
        </div>

        {{-- Dodawanie kategorii --}}
        <div id="dodajKategorie" class="w-100 h-100 justify-content-center align-items-center">
            <div class="opcje rounded text-white p-3 w-auto">
                <div class="d-flex justify-content-between align-items-center pb-4">
                    <button onclick="zamknij('#dodajKategorie')" class="fs-4 myButton">X</button>
                    <h3 class="text-center m-0 ms-2">Wpisz nazwę kategorii, którą chcesz dodać</h3>
                </div>
                <form action="{{ url('kategoria') }}" method="POST">
                    <div class="d-flex w-100">
                        @csrf
                        <input type="text" class="form-control w-100 fs-5" name="nowaKategoria" required autocomplete="off">
                        <input type="hidden" value="" name="rodzicKategoria" id="rodzicKategoria">
                        <button type="submit" class="myButton ms-2">Dodaj</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Zmiana nazwy --}}
        <div id="zmienNazweKategorii" class="w-100 h-100 justify-content-center align-items-center">
            <div class="opcje rounded text-white p-3 w-auto">
                <div class="d-flex justify-content-between align-items-center pb-4">
                    <button onclick="zamknij('#zmienNazweKategorii')" class="fs-4 myButton">X</button>
                    <h3 class="text-center m-0 ms-2">Wpisz nową nazwę kategorii</h3>
                </div>
                <form action="{{ url('kategoria/nazwa') }}" method="POST">
                    <div class="d-flex w-100">
                        @csrf
                        <input type="text" class="form-control w-100" name="nowaNazwaKategorii" required autocomplete="off">
                        <input type="hidden" value="" name="idKategorii" id="idKategorii">
                        <button type="submit" class="myButton ms-2">Zmień</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Zmiana rodzica --}}
        <div id="zmienRodzicaKategorii" class="w-100 h-100 justify-content-center align-items-center">
            <div class="opcje rounded text-white p-3 w-auto">
                <div class="d-flex justify-content-between align-items-center pb-4">
                    <button onclick="zamknij('#zmienRodzicaKategorii')" class="fs-4 myButton">X</button>
                    <h3 class="text-center m-0 ms-2">Podaj ścieżkę do której chcesz przenieść</h3>
                </div>
                <form action="{{ url('kategoria/rodzic') }}" method="POST">
                    <div id="dodatkoweOpcjeRodzica" style="display: none">
                        <div class="fs-4">
                            <div class="form-check">
                                <label class="form-check-label" for="rodzicRadio1">Przenieś wszystko</label>
                                <input class="form-check-input" type="radio" id="rodzicRadio1" name="akcja" value="wszystko">
                            </div>
                            <div class="form-checke">
                                <input class="form-check-input" type="radio" id="rodzicRadio2" name="akcja" value="podkategorie">
                                <label class="form-check-label" for="rodzicRadio2">Przenieś tylko podkategorie</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex w-100">
                        @csrf
                        <input type="text" class="form-control w-100" name="nowaNazwaRodzica" required autocomplete="off" placeholder="nazwa1/nazwa2/nazwa3...">
                        <input type="hidden" value="" name="mojaKategoria" id="mojaKategoria">
                        <button type="submit" class="myButton ms-2">Przenieś</button>
                    </div>
                    <div class="form-text fs-6">Wpisz * jeżeli chcesz aby kategoria nie miała rodzica</div>
                </form>
            </div>
        </div>

        {{-- Usuwanie kategorii --}}
        <div id="usunKategorie" class="w-100 h-100 justify-content-center align-items-center">
            <div class="opcje rounded text-white p-3 w-auto">
                <div id="stadardoweOpcje">
                    <div class="d-flex justify-content-between align-items-center pb-4">
                        <button onclick="zamknij('#usunKategorie')" class="fs-4 myButton">X</button>
                        <h3 class="text-center w-100 m-0 ms-2">Czy na pewno chcesz usunąć?</h3>
                    </div>
                    <form action="{{ url('kategoria/usun') }}" method="POST" class="text-center">
                        @method('DELETE')
                        <div class="d-flex justify-content-center w-100">
                            @csrf
                            <input type="hidden" value="" name="usunKategoria" id="usunKategoria">
                            <button type="submit" class="myButton">Usuń</button>
                        </div>
                    </form>
                </div>
                <div id="opcjeUsuwania">
                    <div class="d-flex justify-content-between align-items-center pb-4">
                        <button onclick="zamknij('#usunKategorie')" class="fs-4 myButton">X</button>
                        <h3 class="text-center w-100 m-0 ms-2">Podana kategoria posada podkategorie.<br> Co chcesz zrobić?</h3>
                    </div>
                    <form action="{{ url('kategoria/usun') }}" method="POST">
                        @method('DELETE')
                        <div class="fs-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="inlineRadio1" name="akcja" value="usun" required>
                                <label class="form-check-label" for="inlineRadio1">Usuń wszystko</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="inlineRadio3" name="akcja" value="usun_podkategorie" required>
                                <label class="form-check-label" for="inlineRadio3">Usuń podkategorie</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="inlineRadio2" name="akcja" value="przenies">
                                <label class="form-check-label" for="inlineRadio2">Przenieś podkategorie</label>
                            </div>
                        </div>
                        <div id="nowaKategoriaDlaSubPanel" style="display: none" class="mb-2">
                            <input type="text" class="form-control w-100 mb-1 fs-5" id="nowaKategoriaDlaSub" name="nowaNazwaRodzica" placeholder="nazwa1/nazwa2/nazwa3..." autocomplete="off">
                            <div class="form-text fs-6">Wpisz * jeżeli chcesz aby podkategorie nie miały rodzica</div>
                        </div>
                        <div class="d-flex justify-content-center w-100">
                            @csrf
                            <input type="hidden" value="" name="usunKategoria2" id="usunKategoria2">
                            <button type="submit" class="myButton">Usuń</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Błędy i sukcesy --}}
        @if ($errors->any())
            <div id="divError" class="w-100 alert-danger p-2 mt-2 text-center fs-4">
                {{ $errors->first() }}
                <button onclick="zamknij('#divError')" class="btn btn-outline-danger">X</button>
            </div>
        @endif
        @if(session()->has('sukces'))
            <div id="sukces" class="w-100 alert-success p-2 mt-2 text-center fs-4">
                {{ session()->get('sukces')}}
                <button onclick="zamknij('#sukces')" class="btn btn-outline-success">X</button>
            </div>
        @endif

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            function zamknij(el)
            {
                $(el).hide();
            }

            $(function() {
                $('.more').on('click', function() {
                    $('.fas', this)
                        .toggleClass('fa-chevron-right')
                        .toggleClass('fa-chevron-down');
                    $($(this).attr('id-toggle')).toggle(200);
                });

                $('.all').on('click', function() {
                    var buttons = document.getElementsByClassName('more');

                    for(var i=0; i<buttons.length; i++)
                        buttons[i].click();
                });

                $('.plus').on('click', function() {
                    $("#dodajKategorie").css('display', 'flex');
                    $("#rodzicKategoria").attr('value', $(this).attr('id-rodzic'));
                });

                $('.zmienNazwe').on('click', function() {
                    $("#zmienNazweKategorii").css('display', 'flex');
                    $("#idKategorii").attr('value', $(this).attr('id-rodzic'));
                });

                $('.zmienRodzica').on('click', function() {
                    if($(this).attr('czy-rodzic') == 'true')
                    {
                        $('#dodatkoweOpcjeRodzica').show();
                        $('#rodzicRadio1').prop('required', true);
                    }
                    else
                    {
                        $('#dodatkoweOpcjeRodzica').hide();
                        $('#rodzicRadio1').prop('required', false);
                    }

                    $("#zmienRodzicaKategorii").css('display', 'flex');
                    $("#mojaKategoria").attr('value', $(this).attr('id-moje'));
                });

                $('.usunKategorie').on('click', function() {
                    if($(this).attr('czy-rodzic') == 'true')
                    {
                        $('#stadardoweOpcje').hide();
                        $('#opcjeUsuwania').show();
                    }
                    else
                    {
                        $('#stadardoweOpcje').show();
                        $('#opcjeUsuwania').hide();
                    }

                    $('#usunKategorie').css('display', 'flex');
                    $("#usunKategoria").attr('value', $(this).attr('id-moje'));
                    $("#usunKategoria2").attr('value', $(this).attr('id-moje'));
                });

                $('#inlineRadio1').on('click', function() {
                    $('#nowaKategoriaDlaSubPanel').hide();
                    $('#nowaKategoriaDlaSub').prop('required', false);
                });

                $('#inlineRadio3').on('click', function() {
                    $('#nowaKategoriaDlaSubPanel').hide();
                    $('#nowaKategoriaDlaSub').prop('required', false);
                });

                $('#inlineRadio2').on('click', function() {
                    $('#nowaKategoriaDlaSubPanel').show();
                    $('#nowaKategoriaDlaSub').prop('required', true);
                });

                $('#iks').on('click', function(){
                    $('.alert-dager').hide();
                });
            });
        </script>
    </main>
</body>
</html>
