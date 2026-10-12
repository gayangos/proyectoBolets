<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indice extends Model
{
    protected $table = 'indices';

    protected $fillable = ['estacion', 'fecha', 'api10', 'temperatura'];

    protected $casts = [
        'fecha' => 'date',
        'api10' => 'float',
        'temperatura' => 'float',
    ];
}