<?php
require_once __DIR__. '/includes/config.php';
$tituloPagina = htmlspecialchars($_GET["material"]);

$tituloCabecera = strtoupper($tituloPagina);
$conn = $app->conexionBd();
$tablaMaterial=sprintf("SELECT * FROM Material M WHERE M.nombre LIKE '$tituloPagina' ");
$row = $conn->query($tablaMaterial);
$rs=$row->fetch_assoc();
$Cont="<h3> $tituloPagina </h3>
<img src= $rs[imagen] width='350' height='350'>
<p> Descripción detallada del producto: </p>
<p>"."$rs[desc_det]</p>
<p>"." Precio del producto: "." $rs[precio]</p>
<link rel='stylesheet' href='style.css'>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
<button class='carrito'>
    <span>Carrito</span>
    <i class='fa fa-shopping-basket' aria-hidden='true'></i>
</button>";

$contenidoPrincipal = <<<EOS
    $Cont


EOS;
include __DIR__. '/includes/plantillas/plantilla.php';
