<?php

namespace App\Console\Commands;

use App\Models\Lluvia;
use App\Models\Municipio;
use App\Services\Aemet;
use Illuminate\Console\Command;
use Throwable;

class DescargarLluvia extends Command
{
    protected $signature = 'lluvia:descargar {--dias=15 : Días hacia atrás que se piden a AEMET}';

    protected $description = 'Descarga de AEMET la lluvia diaria de las estaciones asignadas a los municipios';

    public function handle(Aemet $aemet): int
    {
        $inicio = now()->subDays((int) $this->option('dias'))->toDateString() . 'T00:00:00UTC';
        $fin = now()->subDay()->toDateString() . 'T23:59:59UTC';

        $estaciones = Municipio::whereNotNull('estacion')->distinct()->pluck('estacion');

        // Estaciones separadas por comas, como mucho 25 por petición.
        $dias = $estaciones->chunk(25)
            ->flatMap(fn ($grupo) => $aemet->get("valores/climatologicos/diarios/datos/fechaini/{$inicio}/fechafin/{$fin}/estacion/{$grupo->implode(',')}"));
        $guardados = 0;

        foreach ($dias as $dia) {
            $mm = $this->milimetros($dia['prec'] ?? null);

            if ($mm === null) {
                continue;
            }

            Lluvia::updateOrCreate(
                ['estacion' => $dia['indicativo'], 'fecha' => $dia['fecha']],
                ['precipitacion' => $mm],
            );
            $guardados++;
        }

        foreach ($estaciones->diff($dias->pluck('indicativo')) as $estacion) {
            $this->warn("{$estacion}: sin datos");
        }

        $this->info("{$guardados} días de lluvia guardados de {$estaciones->count()} estaciones.");

        return self::SUCCESS;
    }

    /**
     * AEMET da la lluvia como texto: "2,4", "Ip" (inapreciable) o "Acum" (acumulada de varios días).
     */
    private function milimetros(?string $valor): ?float
    {
        if ($valor === 'Ip') {
            return 0.0;
        }

        $valor = str_replace(',', '.', (string) $valor);

        return is_numeric($valor) ? (float) $valor : null;
    }
}