<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lluvia extends Model
{
    protected $table = 'lluvias';

    protected $fillable = ['estacion', 'fecha', 'precipitacion'];

    protected $casts = [
        'fecha' => 'date',
        'precipitacion' => 'float',
    ];
}