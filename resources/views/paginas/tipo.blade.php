@extends('layouts.publica')

@section('titulo', ucfirst(__($tipo->grupo)) . ' · ' . __('Bolets comestibles'))

@section('contenido')
<div class="max-w-5xl mx-auto px-4 py-8">
    <a href="{{ route('inicio') }}" class="text-sm text-marron hover:text-ocre">&larr; {{ __("Torna a l'inici") }}</a>

    <div class="mt-4 grid gap-8 md:grid-cols-2">
        <img src="{{ asset('imagenes/grandes/b_' . $tipo->especie_id . '.jpg') }}" alt="{{ ucfirst(__($tipo->grupo)) }}" class="w-full rounded-lg bg-beige">

        <div>
            <h1 class="text-3xl font-bold text-ocre">{{ ucfirst(__($tipo->grupo)) }}</h1>
            <div class="mt-6 space-y-3 text-sm">
                {!! $tipo->traduccion()?->texto !!}
            </div>
        </div>
    </div>
</div>
@endsection
