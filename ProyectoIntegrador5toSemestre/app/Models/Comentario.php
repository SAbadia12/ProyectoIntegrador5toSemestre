<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';
    protected $primaryKey = 'id_comentario';

    protected $fillable = [
        'nombre',
        'email',
        'contenido',
        'estado',
    ];

    /**
     * Estados posibles del comentario.
     */
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_APROBADO  = 'aprobado';
    public const ESTADO_RECHAZADO = 'rechazado';

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', self::ESTADO_APROBADO);
    }
}
