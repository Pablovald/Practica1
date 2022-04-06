<?php
require_once __DIR__.'/includes/config.php';
$entrada = htmlspecialchars($_GET["entrada"]);
$conn = $app->conexionBd();
$tablaEntrada=sprintf("SELECT * FROM entradasBlog E WHERE E.id = $entrada ");
$row = $conn->query($tablaEntrada);
$rs=$row->fetch_assoc();
$tituloPagina=$rs['titulo'];
$tituloCabecera = strtoupper($tituloPagina);
$contenidoPrincipal = <<<EOS
	<h1>$rs[header1]</h1>
	<p>$rs[intro]</p>
	<img src=$rs[rutaImagen] alt="">
	<h2>$rs[header2]</h2>
	<p>$rs[parrafo]</p>
	<iframe src="https://www.youtube.com/embed/$rs[video]" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;
		encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	
EOS;
 include __DIR__.'/includes/plantillas/plantilla.php';