<?php
require ("config.php");

//if (! isset($_POST['inscripcion']) ) {
	//header('Location: inscripcion.php');
	//exit();
//}

$tituloPagina = 'Inscripcion';

$tituloCabecera = 'Inscripcion';

$contenidoPrincipal = '';

if (isset($_SESSION["login"])) {
	$contenidoPrincipal .= <<<EOS
	<h1>¡Felicidades {$_SESSION['nombre']} ! Te has inscrito correctamente</h1>
	EOS;
} else {
	//<form action="procesarInscripcion.php" method="POST"> (va debajo de EOS)
	$contenidoPrincipal .= <<<EOS
	<h1>Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad. Si ya tienes una cuenta, inicia sesión.</h1>
	EOS;
}


require ("plantilla.php");