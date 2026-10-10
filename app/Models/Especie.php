<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Especie extends Model
{
    protected $table = 'especies';

    protected $fillable = [
        'nombre_cientifico',
        'grupo',
        'valoracion',
        'autor_foto',
        'foto',
        'provincia',
        'tipo_clima',
        'temporada_inicio',
        'temporada_fin',
        'arbolado',
        'umbral_api10',
        'en_avisos',
    ];

    protected function casts(): array
    {
        return [
            'temporada_inicio' => 'integer',
            'temporada_fin' => 'integer',
            'umbral_api10' => 'decimal:1',
            'en_avisos' => 'boolean',
        ];
    }

    /**
     * Textos de la especie en cada idioma.
     */
    public function traducciones(): HasMany
    {
        return $this->hasMany(EspecieTraduccion::class);
    }

    /**
     * Textos en el idioma base. Lo usa el panel para listar y buscar.
     */
    public function traduccionBase(): HasOne
    {
        return $this->hasOne(EspecieTraduccion::class)->where('idioma', config('bolets.idioma_base'));
    }

    /**
     * Traducción en el idioma activo o, si no existe, en el idioma base.
     */
    public function traduccion(?string $idioma = null): ?EspecieTraduccion
    {
        $idioma ??= app()->getLocale();

        return $this->traducciones->firstWhere('idioma', $idioma)
            ?? $this->traducciones->firstWhere('idioma', config('bolets.idioma_base'));
    }

    /**
     * Un campo traducido: nombre_comun, habitat, descripcion o palabras_clave.
     */
    public function texto(string $campo): ?string
    {
        return $this->traduccion()?->{$campo};
    }

    public function nombreComunVisible(): string
    {
        return $this->texto('nombre_comun') ?? '—';
    }
}
