<?php

require_once __DIR__.'/includes/config.php';
require_once 'formularioInscripcion.php';

$tituloPagina = htmlspecialchars($_GET["alojamiento"]);
$tituloCabecera = strtoupper($tituloPagina);
$conn = $app->conexionBd();
$tablaActividad=sprintf("SELECT * FROM Alojamiento A WHERE A.nombre LIKE '%s' "
						, $conn->real_escape_string($tituloPagina));
$row = $conn->query($tablaActividad);
if($row){
	$rs=$row->fetch_assoc();
	$Cont="<h3>Información detallada del hotel "."$tituloPagina".":</h3>
	<p>"."$rs[descripciondetallada]"."</p>";
	$htmlFormIns=buildFormularioInscripcionAlojamiento();

	$contenidoPrincipal = <<<EOS
		$Cont
		$htmlFormIns
	EOS;
	$row->free();
}else{
	echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	exit();
}

include __DIR__.'/includes/plantillas/plantilla.php';