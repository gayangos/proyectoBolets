<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class ImportarCatalogoSeeder extends Seeder
{
    /**
     * Importa el catálogo de la web estática (database/data/bolets.json).
     * Se puede ejecutar varias veces: actualiza por nombre científico.
     */
    public function run(): void
    {
        $ruta = database_path('data/bolets.json');
        $especies = json_decode(file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);

        foreach ($especies as $s) {
            $comun = trim($s['comun'] ?? '');
            $comestible = mb_strtolower(trim($s['comestible'] ?? ''));
            $comestible = ['bona' => 'bo', 'protegit' => 'protegida'][$comestible] ?? $comestible;
            $autor = html_entity_decode(strip_tags($s['autor'] ?? ''));
            $autor = trim(preg_replace('/^foto de\s+/i', '', $autor));

            Especie::updateOrCreate(
                ['nombre_cientifico' => trim($s['cientifico'])],
                [
                    'nombre_comun' => in_array(mb_strtolower($comun), ['', 'z'], true) ? null : $comun,
                    'grupo' => mb_strtolower(trim($s['tipo'])),
                    'valoracion' => $comestible,
                    'habitat' => trim($s['ubicacion'] ?? '') ?: null,
                    'descripcion' => trim($s['datos'] ?? '') ?: null,
                    'autor_foto' => $autor ?: null,
                    'palabras_clave' => trim($s['busqueda'] ?? '') ?: null,
                ]
            );
        }

        $this->command->info(count($especies) . ' especies importadas.');
    }
}