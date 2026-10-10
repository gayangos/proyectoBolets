<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', __('Bolets comestibles de Mallorca'))</title>
    <link rel="icon" href="{{ asset('imagenes/marca/isologo.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-tierra flex flex-col min-h-screen">
    <header class="border-b border-beige">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="{{ url('/') }}">
                <img src="{{ asset('imagenes/marca/logo.svg') }}" alt="{{ __('Bolets comestibles de Mallorca') }}" class="h-12">
            </a>
            <nav class="flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('catalogo.index') }}" class="hover:text-ocre {{ request()->routeIs('catalogo.*') ? 'text-ocre' : '' }}">{{ __('Bolets') }}</a>
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

    <footer class="bg-bosque text-beige text-sm">
        <div class="max-w-7xl mx-auto px-4 py-6">
            {{ __('Bolets comestibles de Mallorca') }} · David Gayangos
        </div>
    </footer>
</body>
</html>
