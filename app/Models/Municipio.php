<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Municipio extends Model
{
    protected $table = 'municipios';

    protected $fillable = [
        'codigo_ine',
        'nombre',
        'provincia',
        'estacion',
    ];

    /* Usuarios que siguen el municipio. */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}