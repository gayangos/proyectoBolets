<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class Aemet
{
    /**
     * Consulta la API de AEMET OpenData. Responde en dos pasos: primero
     * devuelve una URL temporal y en esa URL están los datos.
     */
    public function get(string $ruta): array
    {
        $respuesta = Http::withHeaders(['api_key' => config('services.aemet.key')])
            ->timeout(30)
            ->retry(3, 2000)
            ->get(config('services.aemet.url') . '/' . ltrim($ruta, '/'))
            ->throw()
            ->json();

        if (($respuesta['estado'] ?? null) !== 200) {
            throw new RuntimeException('AEMET: ' . ($respuesta['descripcion'] ?? 'respuesta inesperada'));
        }

        $cuerpo = Http::timeout(60)
            ->retry(3, 2000)
            ->get($respuesta['datos'])
            ->throw()
            ->body();

        // Los datos llegan en ISO-8859-15.
        return json_decode(mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-15'), true, flags: JSON_THROW_ON_ERROR);
    }
}