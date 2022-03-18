<?php

require ("config.php");

$tituloPagina = 'Blog';

$tituloCabecera = 'BLOG';

$contenidoPrincipal = <<<EOS
<p> En club Seawolf Deportes Naúticos os proporcionamos un blog con las noticias más extravagantes sobre deportes acuáticos </p>


<table align = "center">
	<tr>
		<td>
			<div align = "center">
				<a href="piscinpaProfunda.php"><img src= 'img/piscina.png' width='250' height='250'></a>
				<h4>La piscina más profunda del mundo está en Dubai.</h4>
				<p>Unos buceadores se sumergen en Deep<br>
				Dive, la piscina más profunda del mundo,<br>
				en Dubai... <a href="piscinpaProfunda.php"> Leer más</a></p>
				
			</div>
		</td>
		<td>
			<div align = "center">
				<a href="olaAlta.php"><img src= 'img/ola.jpg' width='250' height='250'></a>
				<h4>Uluwatu, una de las mejores olas de la historia.</h4>
				<p>Uluwatu es una combinación de los <br>
				innumerables sueños y fantasías que han <br>
				pasado por la mente... <a href="olaAlta.php">Leer más</a></p>
				
			</div>
		</td>
		<td>
			<div align = "center">
				<a href="barreraCoral.php"><img src= 'img/barreraCoral.jpg' width='250' height='250'></a>
				<h4>La increíble visita a la Gran Barrera de Coral.</h4>
				<p>El mayor arrecife de coral del mundo <br>
				se encuentra frente a la costa de <br>
				Queensland... <a href="barreraCoral.php">Leer más</a></p>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div align = "center">
				<a href="regata.php"><img src= 'img/regata.jpg' width='250' height='250'></a>
				<h4>Regata, tres meses a solas con el mar.</h4>
				<p>La Vendée Globe es una regata que <br>
				consiste en dar la vuelta al mundo a vela<br>
				en solitario... <a href="regata.php"> Leer más</a></p>
			</div>
		</td>
		<td>
			<div align = "center">
				<a href="record.php"><img src= 'img/record.jpg' width='250' height='250'></a>
				<h4>Récord mundial, el salto mas álto de la historia.</h4>
				<p>Con una altura de 58.8 metros de altura, </br> 
				Lazaro Schaller logró </br> obtener el recórd
				del salto mas alto de la historia...<a href="record.php">Leer más</a></p>
				
			</div>
		</td>
		<td>
			<div align = "center">
				<a href="kayakExtremo.php"><img src= 'img/kayakExtremo.png' width='250' height='250'></a>
				<h4>Jötunn, el kayak más extremo</h4>
				<p>Jötunn es un documental en el que se<br>
				ha intentado enseñar una aventura de <br>
				exploración... <a href="kayakExtremo.php">Leer más</a></p>
				
			</div>
		</td>
	</tr>
  </table>  

EOS;

require ("plantilla.php");
