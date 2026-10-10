@extends('layouts.publica')

@section('titulo', __('Més') . ' · ' . __('Bolets comestibles de Mallorca'))

@section('contenido')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-bosque">{{ __("Més") }}</h1>
    <p class="mt-1 text-marron">{{ __("Avisos i agraïments") }}</p>

    <section class="mt-8">
        <h2 class="text-xl font-semibold text-ocre">{{ __("Atenció") }}</h2>
        <ul class="mt-2 list-disc list-inside space-y-1">
            <li>{{ __("Mai fiar-se de la identificació d'un bolet, per una simple foto.") }}</li>
            <li>{{ __("Si no està completament segur del bolet no s'ha de consumir, les conseqüències poden ser molt greus.") }}</li>
            <li>{{ __("La majoria dels bolets, especialment els petits, són impossibles d'identificar, sense un estudi sota el microscopi.") }}</li>
            <li>{{ __("Segueixi les recomanacions que es fan en la fitxes dels bolets.") }}</li>
        </ul>
    </section>

    <section class="mt-8">
        <h2 class="text-xl font-semibold text-ocre">{{ __("Agraïments") }}</h2>
        <ul class="mt-2 list-disc list-inside space-y-1">
            <li>{{ __("Primer de tot he de donar les gràcies a Carles Constantino i Josep Lleonard Siquier per haver fet unns llibres com el Bolets de les Balears.") }}</li>
            <li>{{ __("Gràcies tambè a Juan Muñoz per l'idea, sense ell tampoc existiria aquesta web.") }}</li>
            <li>{{ __("Gràcies a Pancrazio Campagna per permetre l'ús de les seves imatges.") }}</li>
            <li>{{ __("Gràcies a tots els fotògrafs que han compartit el seu treball en wikicommons.") }}</li>
            <li>{{ __("Gràcies a tot aquells recol·lectors de bolets que ho fan amb mesura i que ajuden a la seva conservació.") }}</li>
            <li>{{ __("Un salut i gràcies pel la visita.") }}</li>
        </ul>
    </section>
</div>
@endsection
