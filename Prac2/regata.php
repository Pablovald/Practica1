<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'La regata más dura del mundo: tres meses a solas en el mar';

$tituloCabecera = 'La regata más dura del mundo: tres meses a solas en el mar';

$contenidoPrincipal = <<<EOS
<h1>Vendée Globe, una regata de larga distancia.</h1>
<p> La Vendée Globe es una regata que consiste en dar la vuelta al mundo a vela en solitario, sin escalas y sin asistencia. 
Tiene su origen en la mítica Sunday Times Globe Race, que se celebró sólo una vez en 1968. La Vendée Globe comenzó a disputarse en 1989, actualmente es una de las 
regatas más importantes de la navegación en solitario y la competición más exigente de vela transoceánica individual. La dureza de esta regata la hace accesible tan solo 
a los navegantes más preparados, que se enfrentan a condiciones de extrema dureza durante más de tres meses de navegación que pondrá a prueba la 
resistencia de barcos y tripulantes. Se celebra cada cuatro años y la salida y la llegada tienen lugar en Vendée, en el puerto de Les Sables-d'Olonne (Francia).</p></br>
<div align="center"><img src = "img/regata2.jpeg" width="560" height="400"></div></br>
<p>Los 29 regatistas tienen que recorrer 24.437 millas naúticas (45.257 kilómetros); las 380 primeras millas son vitales para no perder el ritmo de la regata. 
Los participantes tienen que doblar tres cabos legendarios: el cabo de Buena Esperanza en Sudáfrica, cabo Leeuwin en el sur de Australia y el cabo de Hornos en 
la punta de América del Sur.</p></br>
<div align="center"><img src = "img/regata3.jpg" width="560" height="400"></div></br>
<h2>IMOCA 60, el barco</h2>
<p>IMOCA es una clase internacional de embarcaciones a vela fundada en 1991 y reconocida por la Federación Internacional de Vela (ISAF) desde 1998. 
Esta clase permite cualquier diseño que cumpla los requisitos de eslora, estabilidad y seguridad exigidos por la clase. Fundamentalmente se trata de una clase 
de monocascos de altas prestaciones diseñados para regatas en solitario.
La clase se creó tras la primera regata Vendée Globe para poder regular y normalizar las reglas de construcción de los veleros monocasco de 60 pies (18,28 m). 
Actualmente es la clase elegida en competiciones tan importantes como la Vendée Globe o la Barcelona World Race.</p>
<div align="center"><img src = "img/regata4.jpg" width="560" height="400"></div></br>
<p> A día de hoy el español Didac Costa participa en esta sorprente competición, obteniendo el puesto 27 en la ultima edición celebrada.</p>
<div align = "center">
<iframe width="560" height="315" src="https://www.youtube.com/embed/7VvPv79YRfs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
EOS;

include __DIR__.'/includes/plantillas/plantilla.php';