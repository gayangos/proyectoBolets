<?php

namespace App\Console\Commands;

use App\Models\Municipio;
use App\Services\Aemet;
use Illuminate\Console\Command;

class AsignarEstaciones extends Command
{
    protected $signature = 'municipios:estaciones';

    protected $description = 'Comprueba los municipios con AEMET, guarda sus coordenadas y asigna a cada uno la estación más cercana';

    public function handle(Aemet $aemet): int
    {
        // Maestro de municipios de AEMET, por código INE ("id07040" → "07040").
        $maestro = collect($aemet->get('maestro/municipios'))
            ->keyBy(fn (array $m) => substr($m['id'], 2));

        // AEMET no escribe la provincia siempre igual, así que se filtra por las coordenadas de la zona configurada.
        $zona = config('bolets.zona');
        $estaciones = collect($aemet->get('valores/climatologicos/inventarioestaciones/todasestaciones'))
            ->map(fn (array $e) => $e + ['lat' => $this->grados($e['latitud']), 'lon' => $this->grados($e['longitud'])])
            ->filter(fn (array $e) => $e['lat'] > $zona['latitud'][0] && $e['lat'] < $zona['latitud'][1]
                && $e['lon'] > $zona['longitud'][0] && $e['lon'] < $zona['longitud'][1]);

        // Solo sirven las estaciones que publican lluvia diaria (algunas no tienen datos).
        $inicio = now()->subDays(15)->toDateString() . 'T00:00:00UTC';
        $fin = now()->subDay()->toDateString() . 'T23:59:59UTC';
        // AEMET admite como mucho 25 estaciones por petición.
        $conDatos = $estaciones->pluck('indicativo')
            ->chunk(25)
            ->flatMap(fn ($grupo) => $aemet->get("valores/climatologicos/diarios/datos/fechaini/{$inicio}/fechafin/{$fin}/estacion/{$grupo->implode(',')}"))
            ->pluck('indicativo')
            ->unique();
        $estaciones = $estaciones->whereIn('indicativo', $conDatos);

        $this->info($estaciones->count() . " estaciones en {$zona['nombre']} con lluvia diaria.");

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

            $municipio->update(['estacion' => $estacion['indicativo'], 'latitud' => $lat, 'longitud' => $lon]);

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