<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\Hallazgo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CuadernoController extends Controller
{
    public function index(Request $request): View
    {
        $usuario = $request->user();

        return view('cuaderno.index', [
            'especies' => Especie::orderBy('nombre_cientifico')->pluck('nombre_cientifico'),
            // Todo lo que necesita el navegador para descifrar; la clave nunca llega al servidor.
            'config' => [
                'sal' => $usuario->cuaderno_sal,
                'verificador' => $usuario->cuaderno_verificador,
                'hallazgos' => $usuario->hallazgos()->latest()->get()->map(fn (Hallazgo $h) => [
                    'id' => $h->id,
                    'iv' => $h->iv,
                    'datos' => $h->datos,
                    'fecha' => $h->created_at->timezone('Europe/Madrid')->format('d/m/Y H:i'),
                ]),
                'zona' => config('bolets.zona'),
                'csrf' => csrf_token(),
                'rutas' => [
                    'crear' => route('cuaderno.crear'),
                    'guardar' => route('cuaderno.guardar'),
                    'borrar' => route('cuaderno.borrar', '__ID__'),
                ],
                'textos' => [
                    'incorrecta' => __('Contrasenya incorrecta.'),
                    'noCoinciden' => __('Les contrasenyes no coincideixen.'),
                    'corta' => __('La contrasenya ha de tenir almenys 8 caràcters.'),
                    'aviso' => __("Has d'acceptar l'avís."),
                    'especie' => __('Tria una espècie.'),
                    'sinGps' => __("No s'ha pogut obtenir la posició."),
                    'confirmar' => __('Vols esborrar aquesta troballa?'),
                    'error' => __("No s'ha pogut desar. Torna-ho a provar."),
                ],
            ],
        ]);
    }

    /**
     * Activa el cuaderno. La contraseña llega solo esta vez, para comprobar que no es
     * la de entrar a la web, y no se guarda.
     */
    public function crear(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'sal' => ['required', 'string', 'max:44'],
            'verificador' => ['required', 'string', 'max:1000'],
            'contrasena' => ['required', 'string', 'min:8'],
        ]);

        $usuario = $request->user();
        abort_if($usuario->cuaderno_sal, 409);

        if (Hash::check($datos['contrasena'], $usuario->password)) {
            return response()->json(['message' => __('Quadern contrasenya diferent')], 422);
        }

        $usuario->forceFill([
            'cuaderno_sal' => $datos['sal'],
            'cuaderno_verificador' => $datos['verificador'],
        ])->save();

        return response()->json(['ok' => true]);
    }

    public function guardar(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'iv' => ['required', 'string', 'max:24'],
            'datos' => ['required', 'string', 'max:10000'],
        ]);

        $hallazgo = $request->user()->hallazgos()->create($datos);

        return response()->json([
            'id' => $hallazgo->id,
            'fecha' => $hallazgo->created_at->timezone('Europe/Madrid')->format('d/m/Y H:i'),
        ], 201);
    }

    public function borrar(Request $request, Hallazgo $hallazgo): Response
    {
        abort_unless($hallazgo->user_id === $request->user()->id, 404);

        $hallazgo->delete();

        return response()->noContent();
    }
}