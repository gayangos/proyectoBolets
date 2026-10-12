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

    protected $description = 'Genera los avisos de los municipios cuyo API10 supera el umbral de una especie en temporada, con temperatura adecuada';

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

        $indices = Indice::whereDate('fecha', $fecha)->get()->keyBy('estacion');
        $avisos = 0;

        // Se rehacen los avisos del día para que no queden avisos antiguos si cambia el índice.
        DB::transaction(function () use ($fecha, $especies, $indices, &$avisos) {
            Aviso::whereDate('fecha', $fecha)->delete();

            foreach (Municipio::whereNotNull('estacion')->get() as $municipio) {
                $indice = $indices[$municipio->estacion] ?? null;

                if ($indice === null || ! $this->temperaturaAdecuada($indice->temperatura)) {
                    continue;
                }

                foreach ($especies as $especie) {
                    if ($indice->api10 > (float) $especie->umbral_api10) {
                        Aviso::create([
                            'municipio_id' => $municipio->id,
                            'especie_id' => $especie->id,
                            'fecha' => $fecha->toDateString(),
                            'api10' => $indice->api10,
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
     * Sin dato de temperatura no se descarta el aviso; con dato, debe estar en el rango de config/bolets.php.
     */
    private function temperaturaAdecuada(?float $temperatura): bool
    {
        return $temperatura === null
            || ($temperatura >= config('bolets.temperatura.minima') && $temperatura <= config('bolets.temperatura.maxima'));
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