<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\Especie;
use App\Models\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZonaController extends Controller
{
    public function index(Request $request): View
    {
        $elegidos = $request->user()->municipios()->pluck('municipios.id')->all();
        $setasElegidas = $request->user()->especies()->pluck('especies.id')->all();
        $fecha = Aviso::max('fecha');

        // Avisos del último día calculado en los municipios del usuario, agrupados por municipio.
        // Si el usuario no ha elegido setas, se muestran todas.
        $avisos = Aviso::with(['municipio', 'especie.traducciones'])
            ->whereIn('municipio_id', $elegidos)
            ->when($setasElegidas, fn ($consulta) => $consulta->whereIn('especie_id', $setasElegidas))
            ->where('fecha', $fecha)
            ->get()
            ->sortBy(fn (Aviso $a) => $a->especie->nombre_cientifico)
            ->groupBy(fn (Aviso $a) => $a->municipio->nombre)
            ->sortKeys();

        $municipios = Municipio::orderBy('nombre')->get();

        // Puntos del mapa: todos los municipios con coordenadas, si el usuario los sigue y sus setas con aviso.
        $puntos = $municipios->whereNotNull('latitud')->map(fn (Municipio $m) => [
            'nombre' => $m->nombre,
            'lat' => (float) $m->latitud,
            'lon' => (float) $m->longitud,
            'elegido' => in_array($m->id, $elegidos),
            'setas' => $avisos->get($m->nombre, collect())->map(fn (Aviso $a) => $a->especie->nombre_cientifico)->values(),
        ])->values();

        return view('zona.index', [
            'municipios' => $municipios,
            'puntos' => $puntos,
            'elegidos' => $elegidos,
            'setas' => Especie::with('traducciones')->where('en_avisos', true)->orderBy('nombre_cientifico')->get(),
            'setasElegidas' => $setasElegidas,
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

    public function especies(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'especies' => ['array'],
            'especies.*' => ['integer', 'exists:especies,id'],
        ]);

        $request->user()->especies()->sync($datos['especies'] ?? []);

        return back()->with('estado', __('Bolets desats.'));
    }

    public function frecuencia(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'aviso_frecuencia' => ['required', 'in:nunca,diaria,semanal,dias'],
            'aviso_dia_semana' => ['nullable', 'required_if:aviso_frecuencia,semanal', 'integer', 'between:1,7'],
            'aviso_cada_dias' => ['nullable', 'required_if:aviso_frecuencia,dias', 'integer', 'between:2,30'],
        ]);

        $request->user()->forceFill($datos)->save();

        return back()->with('estado', __('Avisos per correu desats.'));
    }
}