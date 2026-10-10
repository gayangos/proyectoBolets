<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoTraduccion extends Model
{
    protected $table = 'tipos_traducciones';

    protected $fillable = [
        'tipo_id',
        'idioma',
        'resumen',
        'texto',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }
}