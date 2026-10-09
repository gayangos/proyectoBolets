<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'nombre_cientifico', 'nombre_comun', 'grupo', 'valoracion', 'habitat',
    'descripcion', 'autor_foto', 'foto', 'palabras_clave', 'provincia', 'tipo_clima',
    'temporada_inicio', 'temporada_fin', 'arbolado', 'umbral_api10', 'en_avisos',
])]
class Especie extends Model
{
    protected $table = 'especies';

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
     * Nombre común, o un guion si la especie no tiene.
     */
    public function nombreComunVisible(): string
    {
        return $this->nombre_comun ?? '';
    }
}
