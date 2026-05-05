<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Delito - representa un delito reportado en una comuna.
 * (Antes se llamaba Incidente; renombrado para mejor claridad de dominio.)
 */
class Delito extends Model
{
    use HasFactory;

    protected $table = 'delitos';
    protected $primaryKey = 'id_delito';

    protected $fillable = [
        'tipo',
        'id_comuna',
        'fecha',
        'gravedad',
        'descripcion',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'gravedad' => 'integer',
    ];

    /**
     * Lista canónica de tipos de delito (para selects, filtros, validación).
     */
    public static function tipos(): array
    {
        return [
            'Hurto a personas',
            'Hurto a residencias',
            'Hurto a comercio',
            'Hurto de vehículos',
            'Hurto de motos',
            'Homicidio',
            'Lesiones personales',
            'Violencia intrafamiliar',
            'Extorsión',
            'Secuestro',
        ];
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'id_comuna', 'id_comuna');
    }
}
