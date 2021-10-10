<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacjaKategorii extends Model
{
    use HasFactory;

    protected $table = 'relacje_kategorii';

    protected $fillable = [
        'rodzic',
        'dziecko'
    ];

    public $timestamps = false;

    public function kategoriaRodzica()
    {
        return $this->belongsTo(Kategoria::class, 'rodzic');
    }

    public function kategoriaDziecka()
    {
        return $this->belongsTo(Kategoria::class, 'dziecko');
    }
}
