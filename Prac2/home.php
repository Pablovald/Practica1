<?php

require ("config.php");

$tituloPagina = 'HOME';

$tituloCabecera = 'HOME';

$contenidoPrincipal = <<<EOS
			<p>
                ¿Alguna vez has querido empezar a realizar un deporte acuático pero nunca te has atrevido a ello o no conoces donde practicarlo ni aprenderlo?
                En Seawolf Deportes Náuticos te abrimos las puertas a nuestras exclusivas instalaciones. Tanto si quieres aprender sobre una gran variedad de deportes acuáticos,
                como si simplemente quieres alquilar equipo para practicar surf, kayak, remo, etc, con tus amigos o en solitario, nuestros profesionales se pondrán a tu completa disposición. Contamos
                con un amplio equipo de profesores formado por deportistas federados y profesionales, así como entusiastas de distintos deportes. Contáctanos para informarte
                acerca de los distintos cursos, reservas de actividades y horarios, o visita directamente nuestras instalaciones, y resolveremos todas tus dudas.
                ¡Esperamos verte pronto!
            </p>
EOS;


require ("plantilla.php");