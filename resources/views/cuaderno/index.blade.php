@extends('layouts.publica')

@section('contenido')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="cuaderno(@js($config))">
    <h1 class="text-3xl font-bold text-ocre mb-6">{{ __('El meu quadern') }}</h1>

    <p class="mb-6">{{ __('Ajuda quadern') }}</p>

    <p x-show="error" x-text="error" class="mb-4 text-red-700"></p>

    {{-- Primera vez: aviso y creación de la contraseña del cuaderno. --}}
    <div x-show="! creado" class="max-w-md">
        <h2 class="font-semibold text-marron mb-1">{{ __('Abans de començar') }}</h2>
        <p class="mb-4">{{ __('Avís quadern') }}</p>

        <label class="block mb-2">
            {{ __('Contrasenya del quadern') }}
            <input type="password" x-model="contrasena" autocomplete="new-password" class="mt-1 w-full rounded border-beige focus:ring-ocre focus:border-ocre">
        </label>
        <label class="block mb-4">
            {{ __('Repeteix la contrasenya') }}
            <input type="password" x-model="repetida" autocomplete="new-password" class="mt-1 w-full rounded border-beige focus:ring-ocre focus:border-ocre">
        </label>
        <label class="flex items-start gap-2 mb-4">
            <input type="checkbox" x-model="aceptado" class="mt-1 rounded border-beige text-ocre focus:ring-ocre">
            {{ __('Entenc que si oblido la contrasenya perdré les troballes.') }}
        </label>
        <button type="button" @click="crear()" :disabled="ocupado" class="px-4 py-2 bg-bosque text-white rounded hover:bg-ocre">{{ __('Crea el quadern') }}</button>
    </div>

    {{-- Cuaderno creado pero cerrado: se pide la contraseña para descifrar. --}}
    <div x-show="creado && ! clave" class="max-w-md">
        <label class="block mb-4">
            {{ __('Contrasenya del quadern') }}
            <input type="password" x-model="contrasena" @keydown.enter="abrir()" autocomplete="current-password" class="mt-1 w-full rounded border-beige focus:ring-ocre focus:border-ocre">
        </label>
        <button type="button" @click="abrir()" :disabled="ocupado" class="px-4 py-2 bg-bosque text-white rounded hover:bg-ocre">{{ __('Obre el quadern') }}</button>
    </div>

    {{-- Cuaderno abierto. --}}
    <div x-show="clave">
        <div class="grid gap-4 sm:grid-cols-2 mb-6 max-w-3xl">
            <label class="block">
                {{ __('Espècie') }}
                <select x-model="especie" class="mt-1 w-full rounded border-beige focus:ring-ocre focus:border-ocre">
                    <option value=""></option>
                    @foreach ($especies as $especie)
                        <option value="{{ $especie }}">{{ $especie }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block">
                {{ __('Notes') }}
                <input type="text" x-model="notas" maxlength="500" class="mt-1 w-full rounded border-beige focus:ring-ocre focus:border-ocre">
            </label>
        </div>
        <button type="button" @click="desar()" :disabled="ocupado" class="mb-8 px-4 py-2 bg-bosque text-white rounded hover:bg-ocre">{{ __('Desa la troballa aquí') }}</button>

        <div x-ref="mapa" class="h-96 rounded-lg mb-6 z-0"></div>

        <p x-show="hallazgos.length === 0">{{ __('Encara no hi ha troballes.') }}</p>

        <ul class="divide-y divide-beige">
            <template x-for="h in hallazgos" :key="h.id">
                <li class="py-2 flex items-center justify-between gap-4">
                    <span>
                        <span class="font-medium" x-text="h.especie"></span>
                        <span class="text-sm text-marron" x-text="'· ' + h.fecha"></span>
                        <span class="block text-sm" x-show="h.notas" x-text="h.notas"></span>
                    </span>
                    <button type="button" @click="esborrar(h)" class="text-sm text-bosque hover:text-ocre">{{ __('Esborra') }}</button>
                </li>
            </template>
        </ul>
    </div>
</div>
@endsection
