<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', __('Bolets comestibles'))</title>
    <link rel="icon" href="{{ asset('imagenes/marca/isologo.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-tierra flex flex-col min-h-screen">
    <header x-data="{ abierto: false }" class="border-b border-beige">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('imagenes/marca/logo.svg') }}" alt="{{ __('Bolets comestibles') }}" class="h-12">
            </a>
            {{-- En móvil el menú se pliega tras este botón. --}}
            <button type="button" @click="abierto = ! abierto" :aria-expanded="abierto"
                    class="md:hidden flex items-center gap-2 rounded-full border border-tierra px-4 py-2 text-sm font-medium">
                {{ __('Menú') }}
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path x-show="! abierto" stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="abierto" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
            <nav :class="abierto ? 'flex' : 'hidden'"
                 class="w-full flex-col items-start gap-4 pb-2 text-sm font-medium md:flex md:w-auto md:flex-row md:items-center md:gap-6 md:pb-0">
                <a href="{{ route('inicio') }}" class="hover:text-ocre {{ request()->routeIs('inicio') ? 'text-ocre' : '' }}">{{ __('Inici') }}</a>
                <a href="{{ route('catalogo.index') }}" class="hover:text-ocre {{ request()->routeIs('catalogo.*') ? 'text-ocre' : '' }}">{{ __('Bolets') }}</a>
                <a href="{{ route('mes') }}" class="hover:text-ocre {{ request()->routeIs('mes') ? 'text-ocre' : '' }}">{{ __('Més') }}</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-ocre">{{ __('La meva zona') }}</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-ocre">{{ __('Entra') }}</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-bosque text-white px-3 py-2 hover:bg-oliva">{{ __("Registra't") }}</a>
                @endauth
                <span class="flex gap-2 text-xs uppercase">
                    @foreach (config('bolets.idiomas') as $codigo => $nombre)
                        <a href="{{ route('idioma', $codigo) }}" title="{{ $nombre }}"
                           class="{{ app()->getLocale() === $codigo ? 'text-ocre font-semibold' : 'hover:text-ocre' }}">{{ $codigo }}</a>
                    @endforeach
                </span>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        @yield('contenido')
    </main>

    <footer class="bg-bosque text-white text-sm">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center">
            {{ __('Bolets comestibles') }} · David Gayangos · <a href="mailto:gayangos@gmail.com" class="hover:text-ocre">{{ __('Contacte') }}</a>
        </div>
    </footer>
</body>
</html>
