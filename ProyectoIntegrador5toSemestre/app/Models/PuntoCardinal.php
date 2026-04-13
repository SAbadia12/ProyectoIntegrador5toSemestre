<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PuntoCardinal extends Model
{
    //
    use HasFactory;

    protected $table = 'puntos_cardinales';

    protected $primaryKey = 'id_punto_cardinal';

    protected $fillable = [
        'nombre',
    ];
}
