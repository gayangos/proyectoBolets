<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Aviso;

class ZonaController extends Controller
{
    public function index(Request $request): View
    {
        $elegidos = $request->user()->municipios()->pluck('municipios.id')->all();
        $fecha = Aviso::max('fecha');

        // Avisos del último día calculado en los municipios del usuario, agrupados por municipio.
        $avisos = Aviso::with(['municipio', 'especie.traducciones'])
            ->whereIn('municipio_id', $elegidos)
            ->where('fecha', $fecha)
            ->get()
            ->sortBy(fn (Aviso $a) => $a->especie->nombre_cientifico)
            ->groupBy(fn (Aviso $a) => $a->municipio->nombre)
            ->sortKeys();

        return view('zona.index', [
            'municipios' => Municipio::orderBy('nombre')->get(),
            'elegidos' => $elegidos,
            'avisos' => $avisos,
            'fecha' => $fecha,
        ]);
    }

    public function municipios(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'municipios' => ['array'],
            'municipios.*' => ['integer', 'exists:municipios,id'],
        ]);

        $request->user()->municipios()->sync($datos['municipios'] ?? []);

        return back()->with('estado', __('Municipis desats.'));
    }
}