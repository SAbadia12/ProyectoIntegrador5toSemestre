<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Zonas;

class ZonasTipo extends Model
{
    //
    use HasFactory;

    protected $table = 'zonas_tipos';
    protected $primaryKey = 'id_tipo';

    protected $fillable = [
        'tipo',
    ];

    public function zonas()
    {
        return $this->hasMany(Zonas::class, 'tipo_zona', 'id_tipo');
    }
    
}
