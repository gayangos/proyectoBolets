<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class ConfigurarHabitatsSeeder extends Seeder
{
    /**
     * Hábitat de cada especie con avisos (claves de config/bolets.php 'habitats').
     */
    public function run(): void
    {
        $habitats = [
            'Lactarius sanguifluus' => ['pinar'],
            'Lactarius semisanguifluus' => ['pinar'],
            'Lactarius vinosus' => ['pinar'],
            'Lactarius quietus' => ['encinar'],
            'Russula grisea' => ['encinar'],
            'Russula ilicis' => ['encinar'],
            'Russula insignis' => ['encinar'],
            'Russula olivacea' => ['encinar'],
            'Russula delica' => ['pinar', 'encinar'],
            'Russula aurea' => ['encinar'],
            'Russula cyanoxantha' => ['encinar', 'pinar'],
            'Cantharellus cibarius' => ['encinar', 'pinar'],
            'Hydnum repandum' => ['encinar', 'pinar'],
            'Hydnum rufescens' => ['encinar', 'pinar'],
            'Ramaria flava' => ['encinar', 'pinar'],
            'Clavulina cinerea' => ['encinar', 'pinar'],
        ];

        foreach ($habitats as $nombre => $arbolado) {
            $actualizadas = Especie::where('nombre_cientifico', $nombre)->update(['arbolado' => json_encode($arbolado)]);

            if ($actualizadas === 0) {
                $this->command->warn("No se encuentra {$nombre}");
            }
        }
    }
}