<?php

require_once __DIR__.'/includes/config.php';
$entrada = htmlspecialchars($_GET["entrada"]);
$tituloCabecera = strtoupper($tituloPagina);
$conn = $app->conexionBd();
$tablaEntrada=sprintf("SELECT * FROM entradasBlog E WHERE E.id LIKE '$entrada' ");
$row = $conn->query($tablaEntrada);
$rs=$row->fetch_assoc();
$tituloPagina=$rs[titulo];
$contenidoPrincipal="$rs[parrafo1]."".";