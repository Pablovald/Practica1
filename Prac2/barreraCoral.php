<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'BarreraCoral';

$tituloCabecera = 'La increíble visita a la Gran Barrera de Coral';

$contenidoPrincipal = <<<EOS
<h1>Gran barrera de coral, Cairns</h1>
<p>Una de las razones por las que viajamos a Australia fue, precisamente, visitar la Gran Barrera de Coral.
 El mayor arrecife de coral del mundo se encuentra frente a la costa de Queensland, en el llamado mar del Coral,
 y es el lugar soñado para los amantes de la vida submarina. Cuando uno se imagina lo que va a encontrar en un lugar así,
 se le pasan por la cabeza peces de colores, tortugas, tiburones, anémonas y almejas gigantes. Y efectivamente, es así.
 Pero también hemos podido comprobar que se ha convertido, por desgracia, en un lugar masificado por las hordas de turistas.
En el post de hoy vamos a contaros nuestra experiencia en este lugar mágico, mostrándoos las luces y las sombras de uno de los
 lugares más fascinantes del planeta. Hicimos dos visitas al arrecife desde dos ciudades diferentes: Cairns y Port Douglas. 
 Hoy vamos a contaros nuestra experiencia desde la ciudad de Cairns.</p></br>
<div align="center"><img src = "img/barreraCoral.jpg" width="500" height="400"></div></br>
<h2>Hacer snorkel en la Gran Barrera de Coral</h2>
<p>Durante nuestra jornada en la Gran Barrera de Coral visitamos tres atolones diferentes en la parte exterior del arrecife,
con paradas de una hora en cada uno, aproximadamente. La empresa con la que fuimos visita habitualmente los siguientes emplazamientos:
Thetford, Flynn, Milln o Pellowe, dependiendo de las condiciones del día. Cada uno de los lugares visitados es diferente, por lo que 
pudimos ver infinidad de fauna marina: almejas gigantes del tamaño de personas, peces payaso escondidos en las anémonas, coloridos peces loro,
pepinos de mar enormes, e incluso un tiburón escondido en el fondo. Algún afortunado se encontró también con una tortuga, aunque nosotros no 
la vimos. La temperatura del agua era muy agradable, aunque de vez en cuando cruzábamos una corriente fría que nos hacía escapar hacia zonas
más templadas.
Entre inmersiones tuvimos también el bufé de la comida, aunque luego nos arrepentimos un poco de haber comido tanto, porque meterse de nuevo 
en el agua y hacer esfuerzo físico con el estómago lleno no fue muy buena idea. Tras la última inmersión, un café para entrar de nuevo en calor,
 y unos trozos de tarta para animar la despedida.

Tras el viaje de regreso a Cairns, llegamos a puerto sobre las 16:30, por lo que tuvimos tiempo de pasear por Esplanade, que estaba cerca,
antes de que anocheciera. Allí acabamos la jornada fotografiando pelícanos y otras aves, además de la infinidad de mariposas que revoloteaban
sobre el paseo. Cairns es una ciudad con un ambiente juvenil, lleno de tiendas, restaurantes de comida asiática y peluquerías de estilo 
alternativo. Existen muchos albergues de mochileros con precios razonables, además de gran variedad de lugares para reservar excursiones,
y eso se nota en el tipo de turista que visita la ciudad, dejando un ambiente alegre y desenfadado.</p></br>

<p align="center"><iframe width="560" height="315" src= title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;
 encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
EOS;

include __DIR__.'/includes/plantillas/plantilla.php';