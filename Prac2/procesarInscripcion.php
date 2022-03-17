<?php
require ("config.php");

//if (! isset($_POST['inscripcion']) ) {
	//header('Location: inscripcion.php');
	//exit();
//}

$tituloPagina = 'Inscripcion';

$tituloCabecera = 'Inscripcion';

$contenidoPrincipal = '';

$capacidad = isset($_POST['capacidad']);
$solicitud_dia = isset($_POST['dia']) ? $_POST['dia'] : null;
echo "$solicitud_dia";
if (isset($_SESSION["login"])) {
	if(!empty($solicitud_dia)){
		if($capacidad > 0){
			$contenidoPrincipal .= <<<EOS
			<h1>¡Felicidades {$_SESSION['nombre']} ! Te has inscrito correctamente</h1>
			EOS;
		}
		else{
			$contenidoPrincipal .= <<<EOS
			<h1>Lo sentimos no quedan plazas disponibles para el dia seleccionado</h1>
			EOS;
		}
	}
	else{
	$contenidoPrincipal .= <<<EOS
	<h1>¡Lo sentimos no disponemos de actividad para el dia deseado.</h1>
	EOS;
	}
} else {
	//<form action="procesarInscripcion.php" method="POST"> (va debajo de EOS)
	$contenidoPrincipal .= <<<EOS
	<h1>Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad. Si ya tienes una cuenta, inicia sesión.</h1>
	EOS;
}


require ("plantilla.php");