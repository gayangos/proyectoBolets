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

];
