<?php

require_once __DIR__.'/includes/config.php';


$tituloPagina = 'Seawolf Deportes Náuticos';

$tituloCabecera = 'Seawolf Deportes Náuticos';

$contenidoPrincipal = <<<EOS
			<p>
                ¿Alguna vez has querido empezar a realizar un deporte acuático pero nunca te has atrevido a ello o no conoces donde 
				practicarlo ni aprenderlo? En Seawolf Deportes Náuticos te abrimos las puertas a nuestras exclusivas instalaciones. 
				Tanto si quieres aprender sobre una gran variedad de deportes acuáticos, como si simplemente quieres alquilar equipo 
				para practicar surf, kayak, remo, etc, con tus amigos o en solitario, nuestros profesionales se pondrán a tu completa 
				disposición. Contamos con un amplio equipo de profesores formado por deportistas federados y profesionales, así como 
				entusiastas de distintos deportes. Contáctanos para informarte acerca de los distintos cursos, reservas de actividades 
				y horarios, o visita directamente nuestras instalaciones, y resolveremos todas tus dudas. ¡Esperamos verte pronto!
            </p>
			
			<h4> ¡HAZTE SOCIO! Y TE REGALAREMOS UNA MOCHILA MULTIUSOS. </h4>
			<p>
				-> Por la contratación de un curso completo en un pago único, te regalaremos una mochila completa con un kit multiusos.
			</p>
			<div class = "Navegacion">
			<div class = "NavTitulo"> <h4> NAVEGA POR NUESTRO CONTENIDO </h4> </div>
			<a href=Blog.php><img src= "img/blogNav.png"></a>
			<a href=Actividades_Main.php><img src= "img/actNav.png"></a>
			<a href=Materiales.php><img src= "img/matNav.png"></a>
			<a href=Alojamientos_Main.php><img src= "img/alojNav.png"></a>
			Disponemos de ofertas en nuestros cursos impartidos este verano. ¡¡¡Pásate por ACTIVIDADES y apúntate al curso que más
			te convenga!!!
			</div>
			
EOS;


include __DIR__.'/includes/plantillas/plantilla.php';
?>