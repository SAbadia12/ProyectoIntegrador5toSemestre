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
        'gravedad',
        'descripcion',
    ];

    protected $casts = [
        'gravedad' => 'integer',
    ];

    /**
     * Relación many-to-many con Ubicaciones.
     * Un delito puede ocurrir en múltiples ubicaciones,
     * y una ubicación puede tener múltiples delitos.
     * Cada relación tiene su propia fecha.
     */
    public function ubicaciones()
    {
        return $this->belongsToMany(
            Ubicacion::class,
            'delito_ubicacion',
            'id_delito',
            'id_ubicacion'
        )->withPivot('fecha')
         ->withTimestamps();
    }
}
