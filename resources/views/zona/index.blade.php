@extends('layouts.publica')

@section('contenido')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-ocre mb-6">{{ __('La meva zona') }}</h1>

    <div class="mb-8">
        <h2 class="font-semibold text-marron mb-1">{{ __('Com es calculen els avisos') }}</h2>
        <p>{{ __('Ajuda dades') }}</p>
        <p class="mt-1">{{ __('Ajuda API10') }}</p>
        <p class="mt-1 font-mono">{{ __('Ajuda fórmula') }}</p>
        <p class="mt-1">{{ __('Ajuda condicions', ['minima' => config('bolets.temperatura.minima'), 'maxima' => config('bolets.temperatura.maxima')]) }}</p>
    </div>

    @if (session('estado'))
        <p class="mb-4 text-bosque">{{ session('estado') }}</p>
    @endif

    <h2 class="text-xl font-semibold text-marron mb-2">{{ __('Avisos') }}</h2>

    <div id="mapa" class="h-96 rounded-lg mb-6 z-0"></div>

    @if ($avisos->isEmpty())
        <p class="mb-8">{{ __('No hi ha avisos als teus municipis.') }}</p>
    @else
        <p class="mb-4">{{ __('Pluja registrada fins al :data. És probable que hi surtin aquests bolets:', ['data' => \Illuminate\Support\Carbon::parse($fecha)->subDay()->format('d/m/Y')]) }}</p>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach ($avisos as $municipio => $lista)
                <div class="bg-white border border-beige rounded-lg p-4">
                    <h3 class="font-semibold text-black uppercase mb-2">{{ $municipio }} <span class="text-sm font-normal text-marron">· API10 {{ number_format($lista->first()->api10, 1, ',', '') }}</span></h3>
                    <ul class="space-y-1">
                        @foreach ($lista as $aviso)
                            <li>
                                <a href="{{ route('catalogo.show', $aviso->especie) }}" class="hover:text-ocre">{{ $aviso->especie->nombre_cientifico }}</a>
                                @if ($aviso->especie->texto('nombre_comun'))
                                    <span class="text-sm text-marron">({{ $aviso->especie->texto('nombre_comun') }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
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

    <form method="POST" action="{{ route('zona.especies') }}" class="mt-8">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-semibold text-marron mb-2">{{ __('Els meus bolets') }}</h2>
        <p class="mb-4">{{ __('Marca els bolets dels quals vols rebre avisos. Si no en marques cap, rebràs avisos de tots.') }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-6">
            @foreach ($setas as $seta)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="especies[]" value="{{ $seta->id }}" class="rounded border-beige text-ocre focus:ring-ocre" @checked(in_array($seta->id, $setasElegidas))>
                    <span>
                        {{ $seta->nombre_cientifico }}
                        @if ($seta->texto('nombre_comun'))
                            <span class="text-sm text-marron">({{ $seta->texto('nombre_comun') }})</span>
                        @endif
                    </span>
                </label>
            @endforeach
        </div>

        <button type="submit" class="px-4 py-2 bg-bosque text-white rounded hover:bg-ocre">{{ __('Desa') }}</button>
    </form>

    <form method="POST" action="{{ route('zona.frecuencia') }}" class="mt-8">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-semibold text-marron mb-2">{{ __('Avisos per correu') }}</h2>
        <p class="mb-4">{{ __('Ajuda correu') }}</p>

        @php($frecuencia = old('aviso_frecuencia', auth()->user()->aviso_frecuencia))

        <div class="space-y-2 mb-6">
            <label class="flex items-center gap-2">
                <input type="radio" name="aviso_frecuencia" value="nunca" class="border-beige text-ocre focus:ring-ocre" @checked($frecuencia === 'nunca')>
                {{ __('No vull rebre correus') }}
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="aviso_frecuencia" value="diaria" class="border-beige text-ocre focus:ring-ocre" @checked($frecuencia === 'diaria')>
                {{ __('Cada dia') }}
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="aviso_frecuencia" value="semanal" class="border-beige text-ocre focus:ring-ocre" @checked($frecuencia === 'semanal')>
                {{ __('Un cop per setmana, el') }}
                <select name="aviso_dia_semana" class="rounded border-beige focus:ring-ocre focus:border-ocre py-1">
                    @foreach ([1 => 'dilluns', 2 => 'dimarts', 3 => 'dimecres', 4 => 'dijous', 5 => 'divendres', 6 => 'dissabte', 7 => 'diumenge'] as $numero => $dia)
                        <option value="{{ $numero }}" @selected(old('aviso_dia_semana', auth()->user()->aviso_dia_semana) == $numero)>{{ __($dia) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="aviso_frecuencia" value="dias" class="border-beige text-ocre focus:ring-ocre" @checked($frecuencia === 'dias')>
                {{ __('Cada') }}
                <input type="number" name="aviso_cada_dias" min="2" max="30" value="{{ old('aviso_cada_dias', auth()->user()->aviso_cada_dias ?? 10) }}" class="w-20 rounded border-beige focus:ring-ocre focus:border-ocre py-1">
                {{ __('dies') }}
            </label>
        </div>

        <button type="submit" class="px-4 py-2 bg-bosque text-white rounded hover:bg-ocre">{{ __('Desa') }}</button>
    </form>

    <div class="mt-8 pt-4 border-t border-beige flex gap-6">
        <a href="{{ route('cuaderno') }}" class="text-bosque hover:text-ocre">{{ __('El meu quadern') }}</a>
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
<script>
    // Mapa de la zona: gris, municipios no elegidos; verde, elegidos; ocre, elegidos con avisos.
    document.addEventListener('DOMContentLoaded', () => {
        const zona = @json(config('bolets.zona'));
        const puntos = @json($puntos);
        const textos = { senseAvisos: @json(__('Sense avisos')) };

        const mapa = L.map('mapa').fitBounds([[zona.latitud[0], zona.longitud[0]], [zona.latitud[1], zona.longitud[1]]]);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap',
        }).addTo(mapa);

        puntos.forEach((p) => {
            const conAvisos = p.setas.length > 0;
            const color = conAvisos ? '#C09537' : (p.elegido ? '#31401A' : '#9CA3AF');

            const circulo = L.circleMarker([p.lat, p.lon], {
                radius: conAvisos ? 9 : (p.elegido ? 7 : 4),
                color: color,
                fillColor: color,
                fillOpacity: 0.8,
                weight: 1,
            }).addTo(mapa);

            const contenido = document.createElement('div');
            const titulo = document.createElement('strong');
            titulo.textContent = p.nombre;
            contenido.append(titulo);

            if (conAvisos) {
                const lista = document.createElement('ul');
                p.setas.forEach((s) => {
                    const item = document.createElement('li');
                    item.textContent = s;
                    lista.append(item);
                });
                contenido.append(lista);
            } else if (p.elegido) {
                contenido.append(document.createElement('br'), textos.senseAvisos);
            }

            circulo.bindPopup(contenido);
        });
    });
</script>
@endsection
