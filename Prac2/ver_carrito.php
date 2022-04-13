<?php
include_once "funciones.php";
$contenidoPrincipal = obtenerProductosEnCarrito();
$tituloCabecera = "CARRITO";

$tituloPagina = "Carrito";

include __DIR__.'/includes/plantillas/plantilla.php';