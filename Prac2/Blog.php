<?php

require ("config.php");

$tituloPagina = 'Blog';

$tituloCabecera = 'BLOG';

$contenidoPrincipal = <<<EOS
<p> Aquí está el contenido público, visible para todos los usuarios. </p>


<table>
	<tr>
		<div><td>
			<h4>La piscina más profunda del mundo.</h4>
			<a href="piscinpaProfunda.php"><img src= 'piscina.png' width='250' height='250' align = 'center'> </a>
		</td></div>
		<div><td>
			<h4>Uluwatu, una de las mejores olas de la historia.</h4>
			<a href="olaAlta.php"><img src= 'ola.jpg' width='250' height='250' align = 'center'> </a>
		</td></div>
	</tr>
	<tr>
		<div><td>
			<h4>La increíble visita a la Gran Barrera de Coral.</h4>
			<a href="barreraCoral.php"><img src= 'barreraCoral.jpg' width='250' height='250' align = 'center'> </a>
		</td></div>
		<div><td>
			<h4>Regata, tres meses a solas con el mar.</h4>
			<a href="regata.php"><img src= 'regata.jpg' width='250' height='250' align = 'center'> </a>
		</td></div>
	</tr>
	<tr>
	<div><td>
			<h4>Récord mundial nadando, 127 kilómetros en solitario.</h4>
			<a href="record.php"><img src= 'record.jpg' width='250' height='250' align = 'center'> </a>
		</td></div>
		<div><td>
			<h4>Jötunn, el kayak más extremo</h4>
			<a href="kayakExtremo.php"><img src= 'kayakExtremo.png' width='250' height='250' align = 'center'> </a>
		</td></div>
	</tr>

  </table>  
		</article>
EOS;

require ("plantilla.php");