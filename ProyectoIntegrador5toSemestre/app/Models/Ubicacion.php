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
 *   - zona (Zonas, modelo de tu compañero)
 */
class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';

    protected $fillable = [
        'direccion',
        'id_nivel',
        'id_punto_cardinal',
        'id_zona',
    ];

    public function nivel()
    {
        return $this->belongsTo(NivelRiesgo::class, 'id_nivel', 'id_nivel_riesgo');
    }

    public function puntoCardinal()
    {
        return $this->belongsTo(PuntoCardinal::class, 'id_punto_cardinal', 'id_punto_cardinal');
    }

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'id_zona', 'id_zona');
    }
}
