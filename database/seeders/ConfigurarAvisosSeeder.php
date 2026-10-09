<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class ConfigurarAvisosSeeder extends Seeder
{
    /**
     * Especies de los avisos: [mes de inicio, mes de fin, umbral API10], según los requisitos.
     */
    public function run(): void
    {
        $avisos = [
            // Lactarius (valores de Lactarius sanguifluus)
            'Lactarius sanguifluus' => [9, 11, 12],
            'Lactarius semisanguifluus' => [9, 11, 12],
            'Lactarius vinosus' => [9, 11, 12],
            'Lactarius quietus' => [9, 11, 12],

            // Russula (valores de Russula spp.)
            'Russula grisea' => [9, 11, 8],
            'Russula ilicis' => [9, 11, 8],
            'Russula insignis' => [9, 11, 8],
            'Russula olivacea' => [9, 11, 8],
            'Russula delica' => [9, 11, 8],
            'Russula aurea' => [9, 11, 8],
            'Russula cyanoxantha' => [9, 11, 8],

            // Resto de especies de la tabla de requisitos
            'Cantharellus cibarius' => [9, 11, 10],
            'Hydnum repandum' => [9, 11, 10],
            'Hydnum rufescens' => [9, 10, 12],
            'Ramaria flava' => [10, 11, 10],
            'Clavulina cinerea' => [10, 12, 8],
        ];

        foreach ($avisos as $nombre => [$inicio, $fin, $umbral]) {
            Especie::where('nombre_cientifico', $nombre)->update([
                'en_avisos' => true,
                'temporada_inicio' => $inicio,
                'temporada_fin' => $fin,
                'umbral_api10' => $umbral,
            ]);
        }

        $this->command->info(Especie::where('en_avisos', true)->count() . ' especies configuradas para avisos.');
    }
}