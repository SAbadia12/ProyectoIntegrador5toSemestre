<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Ubicacion - sigue el MER del proyecto.
 *
 * Tiene 3 FKs:
 *   - nivel (NivelRiesgo)
 *   - puntoCardinal (PuntoCardinal)
 *   - subzona (Subzonas, modelo de tu compañero)
 */
class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';

    protected $fillable = [
        'direccion',
        'latitud',
        'longitud',
        'id_nivel',
        'id_punto_cardinal',
        'id_subzona',
    ];

    protected $casts = [
        'latitud'  => 'float',
        'longitud' => 'float',
    ];

    public function nivel()
    {
        return $this->belongsTo(NivelRiesgo::class, 'id_nivel', 'id_nivel_riesgo');
    }

    public function puntoCardinal()
    {
        return $this->belongsTo(PuntoCardinal::class, 'id_punto_cardinal', 'id_punto_cardinal');
    }

    public function subzona()
    {
        return $this->belongsTo(Subzonas::class, 'id_subzona', 'id_subzona');
    }

    /**
     * Relación many-to-many con Delitos.
     * Una ubicación puede tener múltiples delitos,
     * y un delito puede ocurrir en múltiples ubicaciones.
     * Cada relación tiene su propia fecha.
     */
    public function delitos()
    {
        return $this->belongsToMany(
            Delito::class,
            'delito_ubicacion',
            'id_ubicacion',
            'id_delito'
        )->withPivot('fecha')
         ->withTimestamps();
    }
}
