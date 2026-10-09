<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class ImportarCatalogoSeeder extends Seeder
{
    /**
     * Importa el catálogo de la web estática (database/data/bolets.json).
     * Conserva el id del JSON, que es el que usan las fotos (b_{id}.jpg).
     */
    public function run(): void
    {
        $ruta = database_path('data/bolets.json');
        $especies = json_decode(file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);

        Especie::unguarded(function () use ($especies) {
            foreach ($especies as $s) {
                $comun = trim($s['comun'] ?? '');
                $valoracion = mb_strtolower(trim($s['comestible'] ?? ''));
                $valoracion = ['bona' => 'bo', 'protegit' => 'protegida'][$valoracion] ?? $valoracion;
                $autor = html_entity_decode(strip_tags($s['autor'] ?? ''));
                $autor = trim(preg_replace('/^foto de\s+/i', '', $autor));

                Especie::updateOrCreate(
                    ['id' => (int) $s['id']],
                    [
                        'nombre_cientifico' => trim($s['cientifico']),
                        'nombre_comun' => in_array(mb_strtolower($comun), ['', 'z'], true) ? null : $comun,
                        'grupo' => mb_strtolower(trim($s['tipo'])),
                        'valoracion' => $valoracion,
                        'habitat' => trim($s['ubicacion'] ?? '') ?: null,
                        'descripcion' => trim($s['datos'] ?? '') ?: null,
                        'autor_foto' => $autor ?: null,
                        'palabras_clave' => trim($s['busqueda'] ?? '') ?: null,
                    ]
                );
            }
        });

        $this->command->info(count($especies) . ' especies importadas.');
    }
}