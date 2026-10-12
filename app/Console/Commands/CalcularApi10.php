<?php

namespace App\Console\Commands;

use App\Models\Indice;
use App\Models\Lluvia;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CalcularApi10 extends Command
{
    protected $signature = 'api10:calcular';

    protected $description = 'Calcula el índice API10 de cada estación con la lluvia guardada';

    // API10 = Σ k^i · P_i, con P_i la lluvia de hace i días.
    private const K = 0.93;

    private const DIAS = 10;

    public function handle(): int
    {
        $calculados = 0;

        foreach (Lluvia::distinct()->pluck('estacion') as $estacion) {
            $lluvia = Lluvia::where('estacion', $estacion)->get()
                ->mapWithKeys(fn (Lluvia $l) => [$l->fecha->toDateString() => $l->precipitacion]);

            $primera = Carbon::parse($lluvia->keys()->min());
            $ultima = Carbon::parse($lluvia->keys()->max());

            // El índice de un día usa la lluvia de los 10 días anteriores; el último es el día siguiente al último dato.
            for ($dia = $primera->copy()->addDays(self::DIAS); $dia->lte($ultima->copy()->addDay()); $dia->addDay()) {
                $api10 = 0;

                for ($i = 1; $i <= self::DIAS; $i++) {
                    $api10 += self::K ** $i * ($lluvia[$dia->copy()->subDays($i)->toDateString()] ?? 0);
                }

                Indice::updateOrCreate(
                    ['estacion' => $estacion, 'fecha' => $dia->toDateString()],
                    ['api10' => round($api10, 2)],
                );
                $calculados++;
            }
        }

        $this->info("{$calculados} índices API10 calculados.");

        return self::SUCCESS;
    }
}