@extends('layouts.publica')

@section('contenido')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-bosque">{{ __("Benvinguts") }}</h1>
    <p class="mt-1 text-marron">{{ __("nova web responsive") }}</p>

    <section class="mt-8 space-y-4">
        <h2 class="text-xl font-semibold text-ocre">{{ __("Inici") }}</h2>
        <p>{{ __("Deixant a un costat hipotètiques receptes culinàries de l'antic Egipte o bé, misterioses fórmules d'ofrenes als déus, bé per a ungüents de la primitiva farmacopea, o per a l'obtenció de al-lucinògens,") }} <strong>{{ __("la primera notícia sobre els bolets ens arriba de la Mitologia hel-lènica") }}</strong> {{ __("sobre la llegenda de l'heroi grec Perseo qui al matar sense pretendre'l al seu avi Acrisio, mogut pel remordiment va voler intercanviar el seu regne amb el de Megapente. Durant el camí, esgotat i assedegat sort va tenir de l'aigua que va poder beure emmagatzemada en el barret d'un bolet de regular dimensió.") }}</p>
        <p>{{ __("Per a commemorar l'esdeveniment va fundar una ciutat que va cridar Miceba que en grec significa fong (mykés), d'aquí deriva la paraula Micologia.") }} <strong>{{ __("El terme fong deriva de 'fungus' que significa portadors de mort a causa de la toxicitat de nombrosos bolets") }}</strong>.</p>
        <p>{{ __("El gust pels bolets va existir per descomptat al món romà, així els fongs són esmentats pràcticament en totes les cultures des de l'època imperial fins el Renaixement és no obstant això durant el segle XVIII quan es van iniciar veritablement les classificacions científiques a través del francès Pierre Bulliard i l'italià Pier Antonio Michelli considerats els pares de la Micologia, tasca que van continuar Lucien Quélet, Elías Fries,...") }}</p>
    </section>

    <section class="mt-8 space-y-4">
        <h2 class="text-xl font-semibold text-ocre">{{ __("Què són") }}</h2>
        <p><strong>{{ __("Els fongs") }}</strong> {{ __("són un grup d'organismes que de sempre s'havien inclòs en el món dels vegetals, però que") }} <strong>{{ __("actualment es consideren com un regne independent") }}</strong> {{ __("per les seves peculiars característiques: no realitzen la fotosíntesis, molts no tenen cel·lulosa en la paret de les seves cèl·lules, la seva substància de reserva és el glicogen (substància típica dels animals) i") }} <strong>{{ __("es reprodueixen per espores.") }}</strong></p>
        <p>{{ __("Les espores són cèl·lules reproductores envoltades d'unes capes que les permeten resistir condicions molt desfavorables de temperatura i humitat, però que") }} <strong>{{ __("quan les condicions són bones germinen") }}</strong> {{ __("i originen un nou individu.") }}</p>
        <p>{{ __("Quan arriba l'època de la reproducció formen cossos reproductors anomenats esporangis, on es formaran les espores. Els fongs") }} <strong>{{ __("viuen sempre de matèria orgànica") }}</strong>, {{ __("ja sigui procedent d'organismes vius (fongs paràsits) o de restes d'organismes (fongs sapròfits).") }}</p>
    </section>

    <section class="mt-8">
        <h2 class="text-xl font-semibold text-ocre">{{ __("Tipus de bolets") }}</h2>
        <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($tipos as $tipo)
                <div>
                    <a href="{{ route('tipo', $tipo) }}">
                        <img src="{{ asset('imagenes/inicio/' . $tipo->miniatura) }}" alt="{{ ucfirst(__($tipo->grupo)) }}" class="w-full rounded-lg bg-beige">
                    </a>
                    <h3 class="mt-2 text-lg font-semibold"><a href="{{ route('tipo', $tipo) }}" class="hover:text-ocre">{{ ucfirst(__($tipo->grupo)) }}</a></h3>
                    <p class="text-sm">{{ $tipo->traduccion()?->resumen }} <a href="{{ route('tipo', $tipo) }}" class="text-ocre hover:underline">[+]</a></p>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
