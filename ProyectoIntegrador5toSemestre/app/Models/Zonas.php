<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zonas extends Model
{
    use HasFactory;

    protected $table = 'zonas';
    protected $primaryKey = 'id_zona';

    protected $fillable = [
        'zona',
        'tipo_zona',
    ];

    public function zonasTipo()
    {
        return $this->belongsTo(ZonasTipo::class, 'tipo_zona', 'id_tipo');
    }
}
