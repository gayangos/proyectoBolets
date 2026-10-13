<x-mail::message>
# {{ __('Avisos de bolets') }}

{{ __('Pluja registrada fins al :data. És probable que hi surtin aquests bolets:', ['data' => $dataPluja]) }}

@foreach ($avisos as $municipio => $lista)
**{{ mb_strtoupper($municipio) }}**

@foreach ($lista as $aviso)
- {{ $aviso->especie->nombre_cientifico }}{{ $aviso->especie->texto('nombre_comun') ? ' ('.$aviso->especie->texto('nombre_comun').')' : '' }}
@endforeach

@endforeach
<x-mail::button :url="route('dashboard')">
{{ __('La meva zona') }}
</x-mail::button>

{{ __('Pots canviar els municipis i els bolets a La meva zona.') }}
</x-mail::message>
