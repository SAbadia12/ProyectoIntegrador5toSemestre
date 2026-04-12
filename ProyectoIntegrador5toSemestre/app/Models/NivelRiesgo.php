<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NivelRiesgo extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_nivel_riesgo';

    protected $fillable = [
        'nivel',
        'color',
    ];
}