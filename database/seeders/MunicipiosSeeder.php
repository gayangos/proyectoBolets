<?php

namespace Database\Seeders;

use App\Models\Municipio;
use Illuminate\Database\Seeder;

class MunicipiosSeeder extends Seeder
{
    /**
     * Municipios de Mallorca con su código INE, que es el que usa AEMET.
     */
    private const MUNICIPIOS = [
        '07001' => 'Alaró',
        '07003' => 'Alcúdia',
        '07004' => 'Algaida',
        '07005' => 'Andratx',
        '07901' => 'Ariany',
        '07006' => 'Artà',
        '07007' => 'Banyalbufar',
        '07008' => 'Binissalem',
        '07009' => 'Búger',
        '07010' => 'Bunyola',
        '07011' => 'Calvià',
        '07012' => 'Campanet',
        '07013' => 'Campos',
        '07014' => 'Capdepera',
        '07016' => 'Consell',
        '07017' => 'Costitx',
        '07018' => 'Deià',
        '07019' => 'Escorca',
        '07020' => 'Esporles',
        '07021' => 'Estellencs',
        '07022' => 'Felanitx',
        '07025' => 'Fornalutx',
        '07027' => 'Inca',
        '07028' => 'Lloret de Vistalegre',
        '07029' => 'Lloseta',
        '07030' => 'Llubí',
        '07031' => 'Llucmajor',
        '07033' => 'Manacor',
        '07034' => 'Mancor de la Vall',
        '07035' => 'Maria de la Salut',
        '07036' => 'Marratxí',
        '07038' => 'Montuïri',
        '07039' => 'Muro',
        '07040' => 'Palma',
        '07041' => 'Petra',
        '07044' => 'sa Pobla',
        '07042' => 'Pollença',
        '07043' => 'Porreres',
        '07045' => 'Puigpunyent',
        '07059' => 'ses Salines',
        '07049' => 'Sant Joan',
        '07051' => 'Sant Llorenç des Cardassar',
        '07053' => 'Santa Eugènia',
        '07055' => 'Santa Margalida',
        '07056' => 'Santa Maria del Camí',
        '07057' => 'Santanyí',
        '07058' => 'Selva',
        '07047' => 'Sencelles',
        '07060' => 'Sineu',
        '07061' => 'Sóller',
        '07062' => 'Son Servera',
        '07063' => 'Valldemossa',
        '07065' => 'Vilafranca de Bonany',
    ];

    public function run(): void
    {
        foreach (self::MUNICIPIOS as $codigo => $nombre) {
            Municipio::updateOrCreate(['codigo_ine' => $codigo], ['nombre' => $nombre]);
        }

        $this->command->info(Municipio::count() . ' municipios importados.');
    }
}