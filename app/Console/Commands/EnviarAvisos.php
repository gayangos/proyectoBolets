<?php

namespace App\Console\Commands;

use App\Mail\AvisosDelDia;
use App\Models\Aviso;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EnviarAvisos extends Command
{
    protected $signature = 'avisos:enviar';

    protected $description = 'Envía por correo a cada usuario los avisos del día de sus municipios y setas, según la frecuencia que ha elegido';

    public function handle(): int
    {
        $fecha = Aviso::max('fecha');

        if (! $fecha) {
            $this->error('No hay avisos generados.');

            return self::FAILURE;
        }

        $hoy = Carbon::today('Europe/Madrid');
        $enviados = 0;

        foreach (User::whereHas('municipios')->where('aviso_frecuencia', '!=', 'nunca')->get() as $usuario) {
            if (! $this->toca($usuario, $hoy)) {
                continue;
            }

            $municipios = $usuario->municipios()->pluck('municipios.id');
            $setas = $usuario->especies()->pluck('especies.id');

            // Mismos avisos que ve en La meva zona: si no ha elegido setas, todas.
            $avisos = Aviso::with(['municipio', 'especie.traducciones'])
                ->where('fecha', $fecha)
                ->whereIn('municipio_id', $municipios)
                ->when($setas->isNotEmpty(), fn ($consulta) => $consulta->whereIn('especie_id', $setas))
                ->get()
                ->sortBy(fn (Aviso $a) => $a->especie->nombre_cientifico)
                ->groupBy(fn (Aviso $a) => $a->municipio->nombre)
                ->sortKeys();

            if ($avisos->isEmpty()) {
                continue;
            }

            Mail::to($usuario)
                ->locale($usuario->idioma)
                ->send(new AvisosDelDia($usuario, $avisos, $fecha));

            $usuario->forceFill(['ultimo_aviso' => $hoy->toDateString()])->save();
            $enviados++;
        }

        $this->info("{$enviados} correos enviados con los avisos del ".Carbon::parse($fecha)->format('d/m/Y').'.');

        return self::SUCCESS;
    }

    // Diaria: cada día. Semanal: el día elegido (1 = lunes … 7 = domingo).
    // Cada X días: si nunca se le ha enviado o ya han pasado X días desde el último correo.
    private function toca(User $usuario, Carbon $hoy): bool
    {
        return match ($usuario->aviso_frecuencia) {
            'diaria' => true,
            'semanal' => $hoy->isoWeekday() === (int) $usuario->aviso_dia_semana,
            'dias' => ! $usuario->ultimo_aviso || Carbon::parse($usuario->ultimo_aviso)->addDays((int) $usuario->aviso_cada_dias)->lte($hoy),
            default => false,
        };
    }
}