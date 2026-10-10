<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipo extends Model
{
    protected $table = 'tipos';

    protected $fillable = [
        'clave',
        'grupo',
        'miniatura',
        'especie_id',
    ];

    /**
     * Textos del tipo en cada idioma.
     */
    public function traducciones(): HasMany
    {
        return $this->hasMany(TipoTraduccion::class);
    }

    /**
     * Especie de la que sale la foto grande.
     */
    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }

    /**
     * Traducción en el idioma activo o, si no existe, en el idioma base.
     */
    public function traduccion(?string $idioma = null): ?TipoTraduccion
    {
        $idioma ??= app()->getLocale();

        return $this->traducciones->firstWhere('idioma', $idioma)
            ?? $this->traducciones->firstWhere('idioma', config('bolets.idioma_base'));
    }
}