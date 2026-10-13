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

    protected $description = 'Envía por correo a cada usuario los avisos del día de sus municipios y setas';

    public function handle(): int
    {
        $fecha = Aviso::max('fecha');

        if (! $fecha) {
            $this->error('No hay avisos generados.');

            return self::FAILURE;
        }

        $enviados = 0;

        foreach (User::whereHas('municipios')->get() as $usuario) {
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
                ->locale(config('bolets.idioma_base'))
                ->send(new AvisosDelDia($usuario, $avisos, $fecha));

            $enviados++;
        }

        $this->info("{$enviados} correos enviados con los avisos del ".Carbon::parse($fecha)->format('d/m/Y').'.');

        return self::SUCCESS;
    }
}