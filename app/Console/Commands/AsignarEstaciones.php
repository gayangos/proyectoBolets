<?php

namespace App\Console\Commands;

use App\Models\Municipio;
use App\Services\Aemet;
use Illuminate\Console\Command;

class AsignarEstaciones extends Command
{
    protected $signature = 'municipios:estaciones';

    protected $description = 'Comprueba los municipios con AEMET y asigna a cada uno la estación más cercana';

    public function handle(Aemet $aemet): int
    {
        // Maestro de municipios de AEMET, por código INE ("id07040" → "07040").
        $maestro = collect($aemet->get('maestro/municipios'))
            ->keyBy(fn (array $m) => substr($m['id'], 2));

            $estaciones = collect($aemet->get('valores/climatologicos/inventarioestaciones/todasestaciones'))
            ->map(fn (array $e) => $e + ['lat' => $this->grados($e['latitud']), 'lon' => $this->grados($e['longitud'])])
            ->filter(fn (array $e) => $e['lat'] > 39.2 && $e['lat'] < 40.0 && $e['lon'] > 2.3 && $e['lon'] < 3.5);

        $this->info($estaciones->count() . ' estaciones en Mallorca.');

        foreach (Municipio::orderBy('nombre')->get() as $municipio) {
            $datos = $maestro->get($municipio->codigo_ine);

            if (! $datos) {
                $this->error("{$municipio->codigo_ine} {$municipio->nombre}: no existe en AEMET");
                continue;
            }

            $lat = (float) $datos['latitud_dec'];
            $lon = (float) $datos['longitud_dec'];

            $estacion = $estaciones
                ->map(fn (array $e) => $e + ['km' => $this->distancia($lat, $lon, $e['lat'], $e['lon'])])
                ->sortBy('km')
                ->first();

            $municipio->update(['estacion' => $estacion['indicativo']]);

            $this->line(sprintf(
                '%s %s (AEMET: %s) → %s %s, %.1f km',
                $municipio->codigo_ine,
                $municipio->nombre,
                $datos['nombre'],
                $estacion['indicativo'],
                $estacion['nombre'],
                $estacion['km'],
            ));
        }

        return self::SUCCESS;
    }

    /**
     * Coordenada de AEMET en formato GGMMSS + hemisferio ("393302N") a grados decimales.
     */
    private function grados(string $valor): float
    {
        $grados = (int) substr($valor, 0, 2) + (int) substr($valor, 2, 2) / 60 + (int) substr($valor, 4, 2) / 3600;

        return in_array(substr($valor, -1), ['S', 'W']) ? -$grados : $grados;
    }

    /**
     * Distancia en km entre dos puntos (fórmula del haversine).
     */
    private function distancia(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return 6371 * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}