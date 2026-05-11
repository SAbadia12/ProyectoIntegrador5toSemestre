<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstacionPolicia extends Model
{
    use HasFactory;

    protected $table = 'estaciones_policia';
    protected $primaryKey = 'id_estacion';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'latitud',
        'longitud',
        'id_subzona',
    ];

    protected $casts = [
        'latitud'  => 'float',
        'longitud' => 'float',
    ];

    public function subzona()
    {
        return $this->belongsTo(Subzonas::class, 'id_subzona', 'id_subzona');
    }
}
