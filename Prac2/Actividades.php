<?php

require ("config.php");

$tituloPagina = 'Actividades';

$tituloCabecera = 'ACTIVIDADES';


$contenidoPrincipal = <<<EOS
<p> Clases Disponibles en SeaWolf Deportes Náuticos. </p>

<table>
	<tr>
		<div><td>
			<a href = "surf.php"><img src= 'surf.jpg' width='667' height='400' align = 'center'> </a>
		</td></div>
		<div><td>
		<h2><a href = "surf.php">SURF </a></h2>
			¿Te gustaría sentir el poder del agua bajo tus pies? El surf es un deporte adictivo, con numerosos beneficios físicos y psicológicos. 
			Apúntate y disfruta de este maravilloso deporte.
			
			<h5> Precios </h5>
				<p>200€ curso completo (8 horas).</p>
				<p>125€ curso medio completo (4 horas).</p>
				<p>40€ clase privada... <a href = "surf.php">Leer más</a></p>
		</td></div>
	</tr>
	<tr>
		<div><td>
		<h2><a href = "vela.php">VELA LIGERA</a></h2>
			La navegación es una disciplina que cualquier amante del mar esta dispuesto a aprender. La práctica de este deporte te ayudará a mejorar habilidades como el trabajo en 
			equipo y el sentido de la orientación. Apúntate e impartirás clase con los mejores profesores de esta disciplina.
			
			<h5> Precios </h5>
			<p>	180€ curso completo (12 horas).</p>
			<p>	99€ medio completo (6 horas)</p>
			<p>	40€ bautizo... <a href = "vela.php">Leer más</a></p>
		</td></div>
		<div><td>
			<a href = "vela.php"><img src= 'vela.png' width='667' height='400' align = 'center'> </a>
		</td></div>
	</tr>
	<tr>
		<div><td>
			<a href = "windsurf.php"><img src= 'windsurf.jpg' width='667' height='400' align = 'center'> </a>
		</td></div>
		<div><td>
		<h2><a href = "windsurf.php">WINDSURF</a></h2>
			Deporte recomendado a los fanáticos de los deportes acuáticos con tabla. La disciplina incluye magníficos beneficios para tu postura, así como un disfrute especial del mar.
			<h5> Precios </h5>
			<p>	150€ curso completo (8 horas).</p>
			<p>	99€ curso medio completo (4 horas) </p>
			<p>	40€ clase privada...<a href = "windsurf.php">Leer más</a></p>
		</td></div>
	</tr>
	<tr>
		<div><td>
		<h2><a href ="buceo.php">BUCEO</a></h2>
			¿Te gustaría disfrutar de las bellezas ocultas que esconde el océano? Unéte a nosotros y encaminate en el maravilloso mundo del buceo, y obtendrás magníficas experiencias
			que serás incapaz de olvidar. Todos nuestros cursos están certificados y titulados.
			<h5> Precios </h5>
			<p>	275€ curso completo avanzado(8 horas).</p>
			<p>	225€ curso completo medio(4 horas).</p>
			<p>	120€ bautizo...<a href = "buceo.php">Leer más</a></p>
		</td></div>
		<div><td>
			<a href = "buceo.php"><img src= 'buceo.jpg' width='667' height='400' align = 'center'> </a>
		</td></div>
	</tr>
	<tr>
		<div><td>
			<a href = "kayakActividad.php"><img src= 'kayakActividad.jpg' width='667' height='400' align = 'center'> </a>
		</td></div>
		<div><td>
		<h2><a href = "kayakActividad.php">KAYAK</a></h2>
			Curso dedicado a individuos intrépidos y aventureros, dispuestos a ponerse en forma. Kayak es una disciplina multiarticular muy beneficiosa para la salud y con la 
			garantía de proporcionar buenos momentos y alegrías.
			<h5> Precios </h5>
			<p>	140€ curso completo (12 horas).</p>
			<p>	85€ curso medio completo (6 horas).</p>
			<p>	25€ clase privada...<a href = "kayakActividad.php">Leer más</a></p>
		</td></div>
	</tr>
	<tr>
		<div><td>
		<h2><a href = "kitesurf.php">KITESURF</a></h2>
			¿Dispuesto a conocer el mar desde otra perspectiva? El curso incluye ración extra de adrenalina así como momentos inimaginables con el viento y las olas. 
			Se recomienda tener nociones básicas de surf o windsurf.
			<h5> Precios </h5>
			<p>	200€ curso completo (8 horas).</p>
			<p>	165€ curso medio completo (4 horas).</p>
			<p>	55€ clase privada...<a href = "kitesurf.php">Leer más</a></p>
		</td></div>
		<div><td>
			<a href = "kitesurf.php"><img src= 'kitesurf.jpg' width='667' height='400' align = 'center'> </a>
		</td></div>
	</tr>
  </table>  
EOS;

require ("plantilla.php");