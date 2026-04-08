<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class punto_cardinal extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];
}
