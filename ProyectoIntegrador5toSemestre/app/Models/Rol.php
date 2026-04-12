<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'rol',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }

}
