<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Zonas;
use App\Models\SubzonasTipo;

class Subzonas extends Model
{
    use HasFactory;

    protected $table = 'subzonas';
    protected $primaryKey = 'id_subzona';

    protected $fillable = [
        'subzona',
        'id_zona',
        'tipo_subzona',
    ];

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'id_zona', 'id_zona');
    }

    public function subzonasTipo()
    {
        return $this->belongsTo(SubzonasTipo::class, 'tipo_subzona', 'id_subtipo');
    }
}
