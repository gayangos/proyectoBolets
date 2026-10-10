<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EspecieTraduccion extends Model
{
    protected $table = 'especies_traducciones';

    protected $fillable = [
        'especie_id',
        'idioma',
        'nombre_comun',
        'habitat',
        'descripcion',
        'palabras_clave',
    ];

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }
}
