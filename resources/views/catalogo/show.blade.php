@extends('layouts.publica')

@section('titulo', $especie->nombre_cientifico . ' · ' . __('Bolets comestibles de Mallorca'))

@php
    $mesos = [1 => 'gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre'];
    $foto = $especie->foto ?? 'b_' . $especie->id . '.jpg';
@endphp

@section('contenido')
<div class="max-w-5xl mx-auto px-4 py-8">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('catalogo.index') }}" class="text-sm text-marron hover:text-ocre">&larr; {{ __('Torna al catàleg') }}</a>

    <div class="mt-4 grid gap-8 md:grid-cols-2">
        <figure>
            <img src="{{ asset('imagenes/grandes/' . $foto) }}" alt="{{ $especie->nombre_cientifico }}" class="w-full rounded-lg bg-beige">
            @if ($especie->autor_foto)
                <figcaption class="mt-2 text-xs text-marron">{{ __('Autor foto') }}: {{ $especie->autor_foto }}</figcaption>
            @endif
        </figure>

        <div>
        <h1 class="text-3xl font-bold italic text-ocre">{{ $especie->nombre_cientifico }}</h1>

            <dl class="mt-6 grid grid-cols-3 gap-y-3 text-sm">
                <dt class="font-medium">{{ __('Nom comú') }}</dt>
                <dd class="col-span-2">{{ $especie->nombreComunVisible() }}</dd>
                <dt class="font-medium">{{ __('Classe') }}</dt>
                <dd class="col-span-2">{{ __($especie->grupo) }}</dd>
                @if ($especie->texto('habitat'))
                    <dt class="font-medium">{{ __('Ubicació') }}</dt>
                    <dd class="col-span-2">{{ $especie->texto('habitat') }}</dd>
                @endif
                <dt class="font-medium">{{ __('Valoració') }}</dt>
                <dd class="col-span-2">{{ __($especie->valoracion) }}</dd>
                @if ($especie->temporada_inicio && $especie->temporada_fin)
                    <dt class="font-medium">{{ __('Temporada') }}</dt>
                    <dd class="col-span-2">{{ __('De :inici a :fi', ['inici' => __($mesos[$especie->temporada_inicio]), 'fi' => __($mesos[$especie->temporada_fin])]) }}</dd>
                @endif
            </dl>

            @if ($especie->texto('descripcion'))
                <h2 class="mt-6 font-medium">{{ __('Comestible') }}</h2>
                <p class="mt-1 leading-relaxed">{{ $especie->texto('descripcion') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
