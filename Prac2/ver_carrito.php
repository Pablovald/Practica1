<?php
require_once __DIR__. '/includes/config.php';

if(isset($_SESSION["login"])) {
    $contenidoPrincipal = es\fdi\ucm\aw\Material::obtenerProductosEnCarrito();
}

else{
    $contenidoPrincipal = "<h2>Lo sentimos, tienes que estar iniciado sesion en nuestra pagina web para poder utilizar el carrito</h2>";
}

$tituloCabecera = "CARRITO";

$tituloPagina = "Carrito";

include __DIR__.'/includes/plantillas/plantilla.php';