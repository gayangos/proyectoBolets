<?php

namespace App\Console\Commands;

use App\Models\Indice;
use App\Models\Lluvia;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CalcularApi10 extends Command
{
    protected $signature = 'api10:calcular';

    protected $description = 'Calcula el índice API10 y la temperatura media reciente de cada estación';

    // API10 = Σ k^i · P_i, con P_i la lluvia de hace i días.
    private const K = 0.93;

    private const DIAS = 10;

    public function handle(): int
    {
        $diasTemperatura = config('bolets.temperatura.dias');
        $calculados = 0;

        foreach (Lluvia::distinct()->pluck('estacion') as $estacion) {
            $datos = Lluvia::where('estacion', $estacion)->get()
                ->keyBy(fn (Lluvia $l) => $l->fecha->toDateString());

            $primera = Carbon::parse($datos->keys()->min());
            $ultima = Carbon::parse($datos->keys()->max());

            // El índice de un día usa la lluvia de los 10 días anteriores; el último es el día siguiente al último dato.
            for ($dia = $primera->copy()->addDays(self::DIAS); $dia->lte($ultima->copy()->addDay()); $dia->addDay()) {
                $api10 = 0;
                $temperaturas = [];

                for ($i = 1; $i <= self::DIAS; $i++) {
                    $registro = $datos[$dia->copy()->subDays($i)->toDateString()] ?? null;
                    $api10 += self::K ** $i * ($registro?->precipitacion ?? 0);

                    if ($i <= $diasTemperatura && $registro?->temperatura !== null) {
                        $temperaturas[] = $registro->temperatura;
                    }
                }

                Indice::updateOrCreate(
                    ['estacion' => $estacion, 'fecha' => $dia->toDateString()],
                    [
                        'api10' => round($api10, 2),
                        'temperatura' => $temperaturas ? round(array_sum($temperaturas) / count($temperaturas), 1) : null,
                    ],
                );
                $calculados++;
            }
        }

        $this->info("{$calculados} índices API10 calculados.");

        return self::SUCCESS;
    }
}