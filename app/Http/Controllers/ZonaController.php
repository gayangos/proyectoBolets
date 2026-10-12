<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZonaController extends Controller
{
    public function index(Request $request): View
    {
        return view('zona.index', [
            'municipios' => Municipio::orderBy('nombre')->get(),
            'elegidos' => $request->user()->municipios()->pluck('municipios.id')->all(),
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