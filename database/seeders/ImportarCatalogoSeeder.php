<?php

namespace Database\Seeders;

use App\Models\Especie;
use App\Models\EspecieTraduccion;
use Illuminate\Database\Seeder;

class ImportarCatalogoSeeder extends Seeder
{
    /**
     * Fichero JSON de cada idioma en database/data. Todos comparten el id de la especie.
     */
    private const FICHEROS = [
        'ca' => 'bolets.json',
        'es' => 'setas.json',
    ];

    /**
     * Importa el catálogo de la web estática. Los datos comunes salen del JSON
     * del idioma base y los textos de cada idioma, de su propio JSON.
     * Conserva el id del JSON, que es el que usan las fotos (b_{id}.jpg).
     */
    public function run(): void
    {
        $base = config('bolets.idioma_base');

        Especie::unguarded(function () use ($base) {
            foreach ($this->leer(self::FICHEROS[$base]) as $s) {
                $valoracion = mb_strtolower(trim($s['comestible'] ?? ''));
                $valoracion = ['bona' => 'bo', 'protegit' => 'protegida'][$valoracion] ?? $valoracion;
                $autor = html_entity_decode(strip_tags($s['autor'] ?? ''));
                $autor = trim(preg_replace('/^foto de\s+/i', '', $autor));

                Especie::updateOrCreate(
                    ['id' => (int) $s['id']],
                    [
                        'nombre_cientifico' => trim($s['cientifico']),
                        'grupo' => mb_strtolower(trim($s['tipo'])),
                        'valoracion' => $valoracion,
                        'autor_foto' => $autor ?: null,
                    ]
                );
            }
        });

        foreach (self::FICHEROS as $idioma => $fichero) {
            $especies = $this->leer($fichero);

            foreach ($especies as $s) {
                $comun = $this->texto($s['comun'] ?? null);

                EspecieTraduccion::updateOrCreate(
                    ['especie_id' => (int) $s['id'], 'idioma' => $idioma],
                    [
                        'nombre_comun' => mb_strtolower($comun ?? '') === 'z' ? null : $comun,
                        'habitat' => $this->texto($s['ubicacion'] ?? null),
                        'descripcion' => $this->texto($s['datos'] ?? null),
                        'palabras_clave' => $this->texto($s['busqueda'] ?? null),
                    ]
                );
            }

            $this->command->info(count($especies) . " especies importadas en {$idioma}.");
        }
    }

    private function leer(string $fichero): array
    {
        return json_decode(file_get_contents(database_path("data/{$fichero}")), true, flags: JSON_THROW_ON_ERROR);
    }

    private function texto(?string $valor): ?string
    {
        $valor = trim($valor ?? '');

        return $valor === '' ? null : $valor;
    }
}
