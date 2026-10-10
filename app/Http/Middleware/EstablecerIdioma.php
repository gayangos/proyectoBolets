<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablecerIdioma
{
    /**
     * Aplica el idioma elegido por el visitante o, si no ha elegido, el idioma base.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idioma = $request->session()->get('idioma', config('bolets.idioma_base'));

        if (! array_key_exists($idioma, config('bolets.idiomas'))) {
            $idioma = config('bolets.idioma_base');
        }

        app()->setLocale($idioma);

        return $next($request);
    }
}
