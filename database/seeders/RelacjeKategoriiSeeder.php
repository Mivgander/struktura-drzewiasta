<?php

namespace Database\Seeders;

use App\Models\RelacjaKategorii;
use Illuminate\Database\Seeder;

class RelacjeKategoriiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RelacjaKategorii::create([ 'rodzic' => 1, 'dziecko' => 2 ]);
        RelacjaKategorii::create([ 'rodzic' => 1, 'dziecko' => 6 ]);
        RelacjaKategorii::create([ 'rodzic' => 1, 'dziecko' => 7 ]);
        RelacjaKategorii::create([ 'rodzic' => 2, 'dziecko' => 3 ]);
        RelacjaKategorii::create([ 'rodzic' => 2, 'dziecko' => 4 ]);
        RelacjaKategorii::create([ 'rodzic' => 2, 'dziecko' => 5 ]);
        RelacjaKategorii::create([ 'rodzic' => 8, 'dziecko' => 9 ]);
        RelacjaKategorii::create([ 'rodzic' => 9, 'dziecko' => 10 ]);
        RelacjaKategorii::create([ 'rodzic' => 9, 'dziecko' => 11 ]);
        RelacjaKategorii::create([ 'rodzic' => 9, 'dziecko' => 12 ]);
        RelacjaKategorii::create([ 'rodzic' => 9, 'dziecko' => 13 ]);
        RelacjaKategorii::create([ 'rodzic' => 13, 'dziecko' => 14 ]);
        RelacjaKategorii::create([ 'rodzic' => 13, 'dziecko' => 15 ]);
        RelacjaKategorii::create([ 'rodzic' => 15, 'dziecko' => 16 ]);
        RelacjaKategorii::create([ 'rodzic' => 16, 'dziecko' => 17 ]);
        RelacjaKategorii::create([ 'rodzic' => 16, 'dziecko' => 18 ]);
        RelacjaKategorii::create([ 'rodzic' => 16, 'dziecko' => 19 ]);
        RelacjaKategorii::create([ 'rodzic' => 11, 'dziecko' => 20 ]);
        RelacjaKategorii::create([ 'rodzic' => 20, 'dziecko' => 21 ]);
        RelacjaKategorii::create([ 'rodzic' => 21, 'dziecko' => 22 ]);
        RelacjaKategorii::create([ 'rodzic' => 21, 'dziecko' => 23 ]);
        RelacjaKategorii::create([ 'rodzic' => 3, 'dziecko' => 24 ]);
        RelacjaKategorii::create([ 'rodzic' => 3, 'dziecko' => 25 ]);
        RelacjaKategorii::create([ 'rodzic' => 3, 'dziecko' => 26 ]);
        RelacjaKategorii::create([ 'rodzic' => 25, 'dziecko' => 27 ]);
        RelacjaKategorii::create([ 'rodzic' => 25, 'dziecko' => 28 ]);
        RelacjaKategorii::create([ 'rodzic' => 25, 'dziecko' => 29 ]);
    }
}
