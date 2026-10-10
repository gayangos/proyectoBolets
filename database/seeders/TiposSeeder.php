<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TiposSeeder extends Seeder
{
    /**
     * Tipos de bolets de la portada, con los textos de la web estática.
     * La foto grande es la de la especie indicada (b_{especie_id}.jpg).
     */
    private const TIPOS = [
        [
            'clave' => 'afiloforals', 'grupo' => 'afil·loforals', 'miniatura' => 'afilo.jpg', 'especie_id' => 22,
            'textos' => [
                'ca' => [
                    'resumen' => 'Aquests són fongs llenyosos que en la major part creixen en forma de crosta...',
                    'texto' => '<p>Aquests són fongs llenyosos que en la major part creixen en forma de crosta.</p><p>Són fongs amb el himeni format per porus, tubs, aculis, plecs o llisos.</p>',
                ],
                'es' => [
                    'resumen' => 'Estos son hongos leñosos que en la mayor parte crecen en forma de costra...',
                    'texto' => '<p>Estos son hongos leñosos que en la mayor parte crecen en forma de costra.</p><p>Son hongos con el himenio formado por poros, tubos, aguijones, pliegues o lisos.</p>',
                ],
            ],
        ],
        [
            'clave' => 'agaricals', 'grupo' => 'agaricals', 'miniatura' => 'aga.jpg', 'especie_id' => 17,
            'textos' => [
                'ca' => [
                    'resumen' => 'Els agaricals són un ordre de la classe dels basidiomicets que inclouen algunes...',
                    'texto' => '<p>Els agaricals són un ordre de la classe dels basidiomicets que inclouen algunes de les espècies més familiars de bolets.</p><p>Els bolets d\'aquest ordre són carnosos, putrescibles, de carn homogènia, amb la superfície fèrtil situada sobre làmines protegides per un barret sostingut per una cama central.</p><p><strong>Les agaricals recullen unes 4.000 espècies</strong>, una quarta part dels basidiomicets coneguts.</p>',
                ],
                'es' => [
                    'resumen' => 'Los agaricales son un orden de la clase de los basidiomicetos que incluyen...',
                    'texto' => '<p>Los agaricales son un orden de la clase de los basidiomicetos que incluyen algunas de las especies más familiares de setas.</p><p>Las setas de este orden son carnosas, putrescibles, de carne homogénea, con la superficie fértil ubicada sobre láminas protegidas por un sombrero sostenido por una pierna central.</p><p><strong>Los agaricales recogen unas 4.000 especies</strong>, una cuarta parte de los basidiomicetos conocidos.</p>',
                ],
            ],
        ],
        [
            'clave' => 'ascomicets', 'grupo' => 'ascomicets', 'miniatura' => 'asc.jpg', 'especie_id' => 3,
            'textos' => [
                'ca' => [
                    'resumen' => 'Les hifes d\'aquests fongs tenen les cèl·lules separades per les seves membranes...',
                    'texto' => '<p>Les hifes d\'aquests fongs tenen les cèl·lules separades per les seves membranes, encara que poden presentar un orifici que les comuniqui.</p><p><strong>Els més coneguts són els llevats</strong>, responsables dels processos de fermentació del pa de les begudes alcohòliques, les floridures verdes, les tòfones (trufas en castellà) molt apreciades en gastronomia, i les múrgoles, uns bolets en forma de rusc (en castellà se\'n diu colmenillas) i que surten per primavera.</p>',
                ],
                'es' => [
                    'resumen' => 'Las hifas de estos hongos tienen las células separadas por sus membranas...',
                    'texto' => '<p>Las hifas de estos hongos tienen las células separadas por sus membranas, aunque pueden presentar un orificio que las comunique.</p><p><strong>Los más conocidos son las levaduras</strong>, responsables de los procesos de fermentación del pan de las bebidas alcohólicas, los mohos verdes, las trufas (trufas en castellano) muy apreciadas en gastronomía, y las colmenillas, unas setas en forma de colmena y que salen por primavera.</p>',
                ],
            ],
        ],
        [
            'clave' => 'boletals', 'grupo' => 'boletals', 'miniatura' => 'bol.jpg', 'especie_id' => 23,
            'textos' => [
                'ca' => [
                    'resumen' => 'Ordre de basidiomicets himenomicets integrat per fongs de carpòfor carnós...',
                    'texto' => '<p>Ordre de basidiomicets himenomicets integrat per fongs de carpòfor carnós, amb les làmines generalment anastomitzades que formen una capa de tubs separable, encara que existeixen bolets d\'aquest ordre amb himeni laminat com el Gonphidius o el Paxilus.</p>',
                ],
                'es' => [
                    'resumen' => 'Orden de basidiomicetos himenomicetos integrado por hongos de carpóforo...',
                    'texto' => '<p>Orden de basidiomicetos himenomicetos integrado por hongos de carpóforo carnoso, con las láminas generalmente anastomitzadas que forman una capa de tubos separable, aunque existen setas de este orden con himenio laminado como el Gonphidius o el Paxilus.</p>',
                ],
            ],
        ],
        [
            'clave' => 'gasteromicets', 'grupo' => 'gasteromicets', 'miniatura' => 'gas.jpg', 'especie_id' => 87,
            'textos' => [
                'ca' => [
                    'resumen' => 'Ordre de basidiomicets amb cossos esporífers més o menys indehiscents...',
                    'texto' => '<p>Ordre de basidiomicets amb cossos esporífers més o menys indehiscents, els quals consten de peridi i de gleva. El himeni està tancat dins un basidiocarp i mai queda al descobert.</p>',
                ],
                'es' => [
                    'resumen' => 'Orden de basidiomicetos con cuerpos esporíferos más o menos...',
                    'texto' => '<p>Orden de basidiomicetos con cuerpos esporíferos más o menos indehiscentes, los cuales constan de peridio y de gleba. El himenio está cerrado en un Basidiocarpo y nunca queda al descubierto.</p>',
                ],
            ],
        ],
        [
            'clave' => 'heterobasidiomicets', 'grupo' => 'heterobasidiomicets', 'miniatura' => 'het.jpg', 'especie_id' => 53,
            'textos' => [
                'ca' => [
                    'resumen' => 'La classe Heterobasidiomicets (Heterobasidiomycetes) és una divisió...',
                    'texto' => '<p>La classe Heterobasidiomicets (Heterobasidiomycetes) és una divisió taxonòmica composta per fongs gelatinosos de la subdivisió Hymenomycotina de la divisió Basidiomycota, al regne dels fongs.</p>',
                ],
                'es' => [
                    'resumen' => 'La clase Heterobasidiomicetos (Heterobasidiomycetes) es una...',
                    'texto' => '<p>La clase Heterobasidiomicetos (Heterobasidiomycetes) es una división taxonómica compuesta por hongos gelatinosos de la subdivisión Hymenomycotina de la división Basidiomycota, al reino de los hongos.</p>',
                ],
            ],
        ],
        [
            'clave' => 'russulals', 'grupo' => 'russulals', 'miniatura' => 'rus.jpg', 'especie_id' => 82,
            'textos' => [
                'ca' => [
                    'resumen' => 'Grup format pels gèneres Lactarius i Russula. Els primers segreguen...',
                    'texto' => '<p>Grup format pels gèneres Lactarius i Russula. Els primers segreguen un làtex al tallar-los i tots dos tenen la carn esmicoladissa.</p><p><strong>Les blaves i els esclata-sangs pertanyen a aquest grup</strong>.</p>',
                ],
                'es' => [
                    'resumen' => 'Grupo formado por los géneros Lactarius y Russula. Los primeros segregan...',
                    'texto' => '<p>Grupo formado por los géneros Lactarius y Russula. Los primeros segregan un látex al cortarlos y ambos tienen la carne quebradiza.</p><p><strong>Las rúsulas y los níscalos pertenecen a este grupo</strong>.</p>',
                ],
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::TIPOS as $datos) {
            $tipo = Tipo::updateOrCreate(
                ['clave' => $datos['clave']],
                [
                    'grupo' => $datos['grupo'],
                    'miniatura' => $datos['miniatura'],
                    'especie_id' => $datos['especie_id'],
                ]
            );

            foreach ($datos['textos'] as $idioma => $textos) {
                $tipo->traducciones()->updateOrCreate(['idioma' => $idioma], $textos);
            }
        }

        $this->command->info(Tipo::count() . ' tipos importados.');
    }
}