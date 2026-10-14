<?php

namespace App\Console\Commands;

use App\Models\Municipio;
use App\Services\Aemet;
use Illuminate\Console\Command;

class CargarMunicipios extends Command
{
    protected $signature = 'municipios:cargar';

    protected $description = 'Da de alta los municipios de la zona configurada a partir del maestro de municipios de AEMET';

    public function handle(Aemet $aemet): int
    {
        $zona = config('bolets.zona');

        // Municipios del maestro de AEMET cuyo centro cae dentro de la zona.
        $maestro = collect($aemet->get('maestro/municipios'))
            ->filter(fn (array $m) => $m['latitud_dec'] > $zona['latitud'][0] && $m['latitud_dec'] < $zona['latitud'][1]
                && $m['longitud_dec'] > $zona['longitud'][0] && $m['longitud_dec'] < $zona['longitud'][1]);

        $nuevos = 0;

        foreach ($maestro as $datos) {
            // Los que ya existen no se tocan, para conservar el nombre que tienen.
            $municipio = Municipio::firstOrCreate(
                ['codigo_ine' => substr($datos['id'], 2)],
                [
                    'nombre' => $this->nombre($datos['nombre']),
                    'provincia' => $zona['provincia'],
                    'latitud' => $datos['latitud_dec'],
                    'longitud' => $datos['longitud_dec'],
                ],
            );

            if ($municipio->wasRecentlyCreated) {
                $this->line("{$municipio->codigo_ine} {$municipio->nombre}");
                $nuevos++;
            }
        }

        $this->info("{$maestro->count()} municipios en {$zona['nombre']}, {$nuevos} nuevos.");

        return self::SUCCESS;
    }

    /**
     * AEMET pone el artículo al final ("Pobla, Sa"): se pasa delante ("Sa Pobla").
     */
    private function nombre(string $nombre): string
    {
        if (! str_contains($nombre, ', ')) {
            return $nombre;
        }

        [$resto, $articulo] = explode(', ', $nombre, 2);

        return $articulo . (str_ends_with($articulo, "'") ? '' : ' ') . $resto;
    }
}