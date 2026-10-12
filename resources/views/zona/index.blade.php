@extends('layouts.publica')

@section('contenido')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-ocre mb-6">{{ __('La meva zona') }}</h1>

    @if (session('estado'))
        <p class="mb-4 text-bosque">{{ session('estado') }}</p>
    @endif

    <form method="POST" action="{{ route('zona.municipios') }}">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-semibold text-marron mb-2">{{ __('Els meus municipis') }}</h2>
        <p class="mb-4">{{ __('Marca els municipis on vols rebre avisos.') }}</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 mb-6">
            @foreach ($municipios as $municipio)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="municipios[]" value="{{ $municipio->id }}" class="rounded border-beige text-ocre focus:ring-ocre" @checked(in_array($municipio->id, $elegidos))>
                    {{ $municipio->nombre }}
                </label>
            @endforeach
        </div>

        <button type="submit" class="px-4 py-2 bg-bosque text-white rounded hover:bg-ocre">{{ __('Desa') }}</button>
    </form>

    <div class="mt-8 pt-4 border-t border-beige flex gap-6">
        <a href="{{ route('profile.edit') }}" class="text-bosque hover:text-ocre">{{ __('El meu perfil') }}</a>

        @if (auth()->user()->is_admin)
            <a href="{{ url('/admin') }}" class="text-bosque hover:text-ocre">{{ __('Administració') }}</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-bosque hover:text-ocre">{{ __('Surt') }}</button>
        </form>
    </div>
</div>
@endsection
