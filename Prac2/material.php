<?php
require_once __DIR__. '/includes/config.php';
$tituloPagina = htmlspecialchars($_GET["material"]);

$tituloCabecera = strtoupper($tituloPagina);
$conn = $app->conexionBd();
$tablaMaterial=sprintf("SELECT * FROM Materiales M WHERE M.nombre LIKE '$tituloPagina' ");
$row = $conn->query($tablaMaterial);
$rs=$row->fetch_assoc();
$Cont="<h3> $tituloPagina </h3>
<img src= $rs[imagen] width='350' height='350'>
<p> Descripción detallada del producto: </p>
<p>"."$rs[descripcion]</p>";

$contenidoPrincipal = <<<EOS
<h3> $tituloPagina </h3>
<img src= $rs[imagen] width='250' height='250'>
<p> Descripción detallada del producto: </p>
<p>$rs[descripcion]</p>
EOS;
include __DIR__. '/includes/plantillas/plantilla.php';
