<?php

namespace App\Console\Commands;

use App\Models\Aviso;
use App\Models\Especie;
use App\Models\Indice;
use App\Models\Municipio;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GenerarAvisos extends Command
{
    protected $signature = 'avisos:generar {--fecha= : Día del índice API10 (por defecto, el último calculado)}';

    protected $description = 'Genera los avisos de los municipios cuyo API10 supera el umbral de una especie en temporada';

    public function handle(): int
    {
        $fecha = $this->option('fecha') ?? Indice::max('fecha');

        if (! $fecha) {
            $this->error('No hay índices API10 calculados.');

            return self::FAILURE;
        }

        $fecha = Carbon::parse($fecha);

        $especies = Especie::where('en_avisos', true)->get()
            ->filter(fn (Especie $e) => $this->enTemporada($e, $fecha->month));

        $indices = Indice::whereDate('fecha', $fecha)->pluck('api10', 'estacion');
        $avisos = 0;

        // Se rehacen los avisos del día para que no queden avisos antiguos si cambia el índice.
        DB::transaction(function () use ($fecha, $especies, $indices, &$avisos) {
            Aviso::whereDate('fecha', $fecha)->delete();

            foreach (Municipio::whereNotNull('estacion')->get() as $municipio) {
                $api10 = $indices[$municipio->estacion] ?? null;

                if ($api10 === null) {
                    continue;
                }

                foreach ($especies as $especie) {
                    if ((float) $api10 > (float) $especie->umbral_api10) {
                        Aviso::create([
                            'municipio_id' => $municipio->id,
                            'especie_id' => $especie->id,
                            'fecha' => $fecha->toDateString(),
                            'api10' => $api10,
                        ]);
                        $avisos++;
                    }
                }
            }
        });

        $this->info("{$avisos} avisos generados para el {$fecha->format('d/m/Y')} ({$especies->count()} especies en temporada).");

        return self::SUCCESS;
    }

    /**
     * La temporada puede pasar de un año al siguiente (por ejemplo, de noviembre a febrero).
     */
    private function enTemporada(Especie $especie, int $mes): bool
    {
        $inicio = $especie->temporada_inicio;
        $fin = $especie->temporada_fin;

        return $inicio <= $fin
            ? $mes >= $inicio && $mes <= $fin
            : $mes >= $inicio || $mes <= $fin;
    }
}