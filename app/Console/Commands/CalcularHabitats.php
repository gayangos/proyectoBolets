<?php

namespace App\Console\Commands;

use App\Models\Habitat;
use App\Models\Municipio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CalcularHabitats extends Command
{
    protected $signature = 'habitat:calcular';

    protected $description = 'Calcula las hectáreas de cada hábitat por municipio con el mapa de ocupación del suelo SIOSE 2017 de la IDEIB';

    private const URL = 'https://ideib.caib.es/geoserveis/rest/services/public/GOIB_SIOSE17_IB/MapServer/0/query';

    /**
     * Municipios cuyo código en SIOSE no coincide con la parte final del código INE.
     */
    private const CODIGOS_SIOSE = [
        '07901' => 66, // Ariany
    ];

    public function handle(): int
    {
        $habitats = config('bolets.habitats');

        // Se borran los hábitats que ya no están en la configuración.
        Habitat::whereNotIn('habitat', array_keys($habitats))->delete();

        foreach (Municipio::orderBy('nombre')->get() as $municipio) {
            // En SIOSE el municipio es la parte final del código INE (07020 → 20), salvo excepciones.
            $codigo = self::CODIGOS_SIOSE[$municipio->codigo_ine] ?? (int) substr($municipio->codigo_ine, 2);

            $respuesta = Http::timeout(120)->connectTimeout(30)->retry(3, 5000)->get(self::URL, [
                'where' => "MUNICIPIO = {$codigo}",
                'groupByFieldsForStatistics' => 'ID_COBERTURA_MAX',
                'outStatistics' => json_encode([[
                    'statisticType' => 'sum',
                    'onStatisticField' => 'SUPERF_M2',
                    'outStatisticFieldName' => 'm2',
                ]]),
                'f' => 'json',
            ])->throw()->json();

            if (isset($respuesta['error'])) {
                $this->error("{$municipio->nombre}: {$respuesta['error']['message']}");

                return self::FAILURE;
            }

            // Metros cuadrados por código de cobertura.
            $superficies = collect($respuesta['features'] ?? [])
                ->map(fn (array $f) => array_change_key_case($f['attributes']))
                ->mapWithKeys(fn (array $a) => [$a['id_cobertura_max'] => $a['m2']]);

            $resumen = [];

            foreach ($habitats as $habitat => $codigos) {
                $hectareas = round($superficies->only($codigos)->sum() / 10000, 1);

                Habitat::updateOrCreate(
                    ['municipio_id' => $municipio->id, 'habitat' => $habitat],
                    ['hectareas' => $hectareas]
                );

                $resumen[] = "{$habitat} {$hectareas}";
            }

            $this->line("{$municipio->nombre}: ".implode(' · ', $resumen).' ha');
        }

        return self::SUCCESS;
    }
}