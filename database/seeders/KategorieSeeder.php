<?php

namespace Database\Seeders;

use App\Models\Kategoria;
use Illuminate\Database\Seeder;

class KategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategoria::create([ 'kategoria' => 'Sporty' ]);
        Kategoria::create([ 'kategoria' => 'Piłka nożna' ]);
        Kategoria::create([ 'kategoria' => 'Ekstraklasa' ]);
        Kategoria::create([ 'kategoria' => 'Bundesliga' ]);
        Kategoria::create([ 'kategoria' => 'Premier League' ]);
        Kategoria::create([ 'kategoria' => 'Piłka ręczna' ]);
        Kategoria::create([ 'kategoria' => 'Siatkówka' ]);
        Kategoria::create([ 'kategoria' => 'Filmy' ]); // 8
        Kategoria::create([ 'kategoria' => 'Gatunki' ]);
        Kategoria::create([ 'kategoria' => 'Komedia' ]);
        Kategoria::create([ 'kategoria' => 'Dramat' ]);
        Kategoria::create([ 'kategoria' => 'Horror' ]);
        Kategoria::create([ 'kategoria' => 'Science fiction' ]); // 13
        Kategoria::create([ 'kategoria' => 'Gwiezdne wojny' ]);
        Kategoria::create([ 'kategoria' => 'Interstellar' ]);
        Kategoria::create([ 'kategoria' => 'Obsada' ]);
        Kategoria::create([ 'kategoria' => 'Matthew McConaughey' ]);
        Kategoria::create([ 'kategoria' => 'Anne Hathaway' ]);
        Kategoria::create([ 'kategoria' => 'Jessica Chastain' ]);
        Kategoria::create([ 'kategoria' => 'Skazani na Shawshank' ]); // 20
        Kategoria::create([ 'kategoria' => 'Nagrody' ]);
        Kategoria::create([ 'kategoria' => 'ASC - Najlepsze zdjęcia do filmu fabularnego' ]);
        Kategoria::create([ 'kategoria' => 'Brązowa Żaba - Najlepszy operator' ]);
        Kategoria::create([ 'kategoria' => 'Lech poznań' ]); // 24
        Kategoria::create([ 'kategoria' => 'Legia Warszawa' ]);
        Kategoria::create([ 'kategoria' => 'Lechia Gdańsk' ]);
        Kategoria::create([ 'kategoria' => 'Artur Boruc' ]);
        Kategoria::create([ 'kategoria' => 'Mahir Emreli' ]);
        Kategoria::create([ 'kategoria' => 'Kacper Skibicki' ]);
        Kategoria::create([ 'kategoria' => 'Muzyka' ]);
    }
}
