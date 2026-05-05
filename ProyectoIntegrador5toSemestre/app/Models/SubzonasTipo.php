<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubzonasTipo extends Model
{
    use HasFactory;

    protected $table = 'subzonas_tipos';
    protected $primaryKey = 'id_subtipo';

    protected $fillable = [
        'subtipo',
    ];
}
