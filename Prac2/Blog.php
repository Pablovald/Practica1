<?php

require ("config.php");

$tituloPagina = 'Blog';

$tituloCabecera = 'BLOG';

$contenidoPrincipal = <<<EOS
<h1>Blog</h1>
<p> Aquí está el contenido público, visible para todos los usuarios. </p>


<table>
	<tr>
		<div><td>
			<h4>La piscina más profunda del mundo.</h4>
			<a href="piscinpaProfunda.php"><img src= 'piscina.png' width='200' height='200' align = 'center'> </a>
		</td></div>
		<div><td>
			<h4>Uluwatu, una de las mejores olas de la historia.</h4>
			<a href="olaAlta.php"><img src= 'ola.jpg' width='200' height='200' align = 'center'> </a>
		</td></div>
	</tr>
	<tr>
		<td>
			<div>Imagen Material3<h4>Entrada3.</h4>
			<p> Breve descripcion material3</p>
		</td>
		<td>
			<div>Imagen Material4<h4>Entrada4.</h4>
			<p> Breve descripcion material4</p>
		</td>
	</tr>
	<tr>
		<td>
			<div>Imagen Material5<h4>Entrada5.</h4>
			<p> Breve descripcion material5</p>
		</td>
		<td>
			<div>Imagen Material6<h4>Entrada6.</h4>
			<p> Breve descripcion material6</p>
		</td>
	</tr>

  </table>  
		</article>
EOS;

require ("plantilla.php");