<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_comuna';

    protected $fillable = [
        'nombre',
        'numero',
        'latitud',
        'longitud',
        'geojson',
        'id_nivel_riesgo',
        'descripcion',
    ];

    protected $casts = [
        'numero'   => 'integer',
        'latitud'  => 'float',
        'longitud' => 'float',
    ];

    public function nivelRiesgo()
    {
        return $this->belongsTo(NivelRiesgo::class, 'id_nivel_riesgo', 'id_nivel_riesgo');
    }

    public function estaciones()
    {
        return $this->hasMany(EstacionPolicia::class, 'id_comuna', 'id_comuna');
    }
}
