<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablecerIdioma
{
    /**
     * Aplica el idioma elegido por el visitante; si no ha elegido, el del usuario
     * identificado y, si no, el idioma base.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();
        $idioma = $request->session()->get('idioma', $usuario?->idioma ?? config('bolets.idioma_base'));

        if (! array_key_exists($idioma, config('bolets.idiomas'))) {
            $idioma = config('bolets.idioma_base');
        }

        app()->setLocale($idioma);

        // El idioma que el usuario usa en la web es también el de sus correos.
        if ($usuario && $usuario->idioma !== $idioma) {
            $usuario->forceFill(['idioma' => $idioma])->save();
        }

        return $next($request);
    }
}