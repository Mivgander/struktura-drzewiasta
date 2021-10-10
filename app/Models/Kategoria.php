<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoria extends Model
{
    use HasFactory;

    protected $table = 'kategorie';

    protected $fillable = [
        'kategoria'
    ];

    public $timestamps = false;

    public function rodzic()
    {
        return $this->hasMany(RelacjaKategorii::class, 'rodzic');
    }

    public function dziecko()
    {
        return $this->hasOne(RelacjaKategorii::class, 'dziecko');
    }
}
