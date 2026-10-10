<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    /**
     * Campos por los que se puede ordenar el listado.
     */
    private const ORDENES = ['cientifico', 'comun', 'grupo', 'valoracion'];

    /**
     * Listado público con búsqueda, filtro por grupo y orden.
     */
    public function index(Request $request)
    {
        $busqueda = trim($request->query('q', ''));
        $grupo = $request->query('grupo', '');
        $orden = in_array($request->query('orden'), self::ORDENES, true) ? $request->query('orden') : 'cientifico';

        $especies = Especie::query()
            ->with('traducciones')
            ->when($busqueda !== '', function ($consulta) use ($busqueda) {
                $consulta->where(function ($c) use ($busqueda) {
                    $c->where('nombre_cientifico', 'like', "%{$busqueda}%")
                        ->orWhereHas('traducciones', function ($t) use ($busqueda) {
                            $t->where('nombre_comun', 'like', "%{$busqueda}%")
                                ->orWhere('palabras_clave', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->when($grupo !== '', fn ($consulta) => $consulta->where('grupo', $grupo))
            ->when(in_array($orden, ['grupo', 'valoracion'], true), fn ($consulta) => $consulta->orderBy($orden))
            ->orderBy('nombre_cientifico')
            ->get();

        // El nombre común depende del idioma: se ordena ya traducido y las especies sin nombre van al final.
        if ($orden === 'comun') {
            $especies = $especies->sortBy(fn ($e) => [$e->texto('nombre_comun') === null, mb_strtolower($e->texto('nombre_comun') ?? '')])->values();
        }

        $grupos = Especie::query()->distinct()->orderBy('grupo')->pluck('grupo');

        return view('catalogo.index', compact('especies', 'grupos', 'busqueda', 'grupo', 'orden'));
    }

    /**
     * Ficha de una especie.
     */
    public function show(Especie $especie)
    {
        $especie->load('traducciones');

        return view('catalogo.show', compact('especie'));
    }
}
