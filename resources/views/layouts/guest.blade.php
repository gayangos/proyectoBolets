@extends('layouts.publica')

@section('contenido')
<div class="flex justify-center px-4 py-12">
    <div class="w-full sm:max-w-md px-6 py-6 bg-white border border-beige rounded-lg">
        {{ $slot }}
    </div>
</div>
@endsection