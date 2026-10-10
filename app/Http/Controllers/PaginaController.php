<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\View\View;

class PaginaController extends Controller
{
    public function inicio(): View
    {
        $tipos = Tipo::with('traducciones')->orderBy('clave')->get();

        return view('paginas.inicio', compact('tipos'));
    }

    public function tipo(Tipo $tipo): View
    {
        $tipo->load('traducciones');

        return view('paginas.tipo', compact('tipo'));
    }

    public function mes(): View
    {
        return view('paginas.mes');
    }
}