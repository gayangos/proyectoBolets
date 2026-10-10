@extends('layouts.publica')

@section('titulo', __('Bolets') . ' · ' . __('Bolets comestibles de Mallorca'))

@section('contenido')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-bosque">{{ __('Bolets') }}</h1>
    <p class="mt-1 text-marron">{{ __('Tots els bolets comestibles i classes.') }}</p>

    <form method="GET" action="{{ route('catalogo.index') }}" class="mt-6 grid gap-4 sm:grid-cols-4 items-end">
        <div class="sm:col-span-2">
            <label for="q" class="block text-sm font-medium">{{ __('Recerca') }}</label>
            <input type="search" id="q" name="q" value="{{ $busqueda }}" placeholder="{{ __('Nom científic, nom comú o paraula clau') }}"
                   class="mt-1 w-full rounded-md border-beige focus:border-ocre focus:ring-ocre">
        </div>
        <div>
            <label for="grupo" class="block text-sm font-medium">{{ __('Filtrar') }}</label>
            <select id="grupo" name="grupo" class="mt-1 w-full rounded-md border-beige focus:border-ocre focus:ring-ocre">
                <option value="">{{ __('Tots') }}</option>
                @foreach ($grupos as $g)
                    <option value="{{ $g }}" @selected($g === $grupo)>{{ ucfirst(__($g)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="orden" class="block text-sm font-medium">{{ __('Ordenar') }}</label>
            <select id="orden" name="orden" class="mt-1 w-full rounded-md border-beige focus:border-ocre focus:ring-ocre">
                <option value="cientifico" @selected($orden === 'cientifico')>{{ __('Nom científic') }}</option>
                <option value="comun" @selected($orden === 'comun')>{{ __('Nom comú') }}</option>
                <option value="grupo" @selected($orden === 'grupo')>{{ __('Classe') }}</option>
                <option value="valoracion" @selected($orden === 'valoracion')>{{ __('Valoració') }}</option>
            </select>
        </div>
        <div class="sm:col-span-4 flex gap-3">
            <button type="submit" class="rounded-md bg-ocre text-white px-4 py-2 font-medium hover:bg-marron">{{ __('Aplica') }}</button>
            <a href="{{ route('catalogo.index') }}" class="rounded-md border border-beige px-4 py-2 hover:border-ocre">{{ __('Neteja') }}</a>
        </div>
    </form>

    <p class="mt-6 text-sm text-marron">{{ __(':n espècies', ['n' => $especies->count()]) }}</p>

    <div class="mt-4 grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($especies as $especie)
            <article class="rounded-lg border border-beige overflow-hidden">
                <a href="{{ route('catalogo.show', $especie) }}">
                    <img src="{{ asset('imagenes/miniaturas/' . ($especie->foto ?? 'b_' . $especie->id . '.jpg')) }}"
                         alt="{{ $especie->nombre_cientifico }}" loading="lazy" class="w-full h-48 object-cover bg-beige hover:opacity-90">
                </a>
                <div class="p-4 text-sm space-y-1">
                    <h2 class="text-lg font-semibold italic text-ocre">
                        <a href="{{ route('catalogo.show', $especie) }}" class="hover:text-marron">{{ $especie->nombre_cientifico }}</a>
                    </h2>
                    <p>{{ $especie->nombreComunVisible() }}</p>
                </div>
            </article>
        @empty
            <p class="col-span-full text-marron">{{ __('No hi ha cap bolet que coincideixi amb la recerca.') }}</p>
        @endforelse
    </div>
</div>
@endsection
