<?php

return [

    /*
     * Idiomas disponibles en la web. Para añadir uno nuevo basta con
     * una línea aquí, su fichero lang/xx.json y sus traducciones de especies.
     */
    'idiomas' => [
        'ca' => 'Català',
        'es' => 'Castellano',
    ],

    /*
     * Idioma por defecto y de reserva cuando falta una traducción.
     */
    'idioma_base' => 'ca',

    /*
     * Temperatura media (°C) de los últimos días necesaria para generar avisos:
     * con frío o calor intensos las setas no salen aunque haya llovido.
     */
    'temperatura' => [
        'dias' => 7,
        'minima' => 8,
        'maxima' => 24,
    ],

    /*
     * Zona que cubre la aplicación. Sus coordenadas filtran los municipios
     * y las estaciones de AEMET y sirven para centrar el mapa.
     */
    'zona' => [
        'nombre' => 'Mallorca',
        'provincia' => 'Illes Balears',
        'latitud' => [39.2, 40.0],
        'longitud' => [2.3, 3.5],
    ],

    /*
     * Hábitats a partir del mapa de ocupación del suelo SIOSE 2017 (IDEIB).
     * Cada hábitat agrupa códigos de cobertura. En Mallorca las coníferas
     * son casi todas pinar de pino carrasco y las frondosas, encinar.
     */
    'habitats' => [
        'pinar' => [316],
        'encinar' => [312, 313],
        'matorral' => [300, 301, 320],
        'prados' => [290],
    ],

    /*
     * Superficie mínima (ha) del hábitat de una especie para avisar en un municipio.
     */
    'habitat_minimo' => 50,

];