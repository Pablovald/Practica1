<?php

require ("config.php");
require_once 'formularioInscripcion.php';
$tituloPagina = htmlspecialchars($_GET["actividad"]);

$tituloCabecera = strtoupper($tituloPagina);
$conn = $app->conexionBd();
$tablaActividad=sprintf("SELECT * FROM Actividades A WHERE A.nombre LIKE '$tituloPagina' ");
$row = $conn->query($tablaActividad);
$rs=$row->fetch_assoc();
$Cont="<h3>Información del curso de "."$rs[nombre]".":</h3>
<p>"."$rs[info]"."</p>
<h4> Horarios disponibles </h4>
<p> Lunes a Viernes de 16:00 a 18:00 </p>
<p> Sabado y Domingo de 11:30 a 13:30</p>
<p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
<h4> Precios del curso </h4>";
$row=$conn->query(sprintf("SELECT P.nombre,P.precio FROM PreciosActividades P JOIN Actividades A ON A.id_actividad=P.id_actividad WHERE A.nombre LIKE '$tituloPagina'"));
for($i=0;$i<$row->num_rows;$i++){
$act=$row->fetch_assoc();
$Cont.="<p>"."$act[nombre]".": "."$act[precio]"." €</p>";
}
$htmlFormIns=buildFormularioInscripcion($tituloPagina,$app);
$contenidoPrincipal = <<<EOS
	$Cont
    $htmlFormIns
EOS;
require("plantilla.php");