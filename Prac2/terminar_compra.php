<?php
include_once "funciones.php";

    terminarCompra();
$tituloPagina = "Carrito";
$tituloCabecera = "CARRITO";
$contenidoPrincipal = <<<EOS
<h2>Gracias por elegirnos para alquilar tu material deportivo!</h2>
<a href="Materiales.php" class="button is-success is-large"><i class="fa fa-check"></i>&nbsp;Volver a materiales    </a>
EOS;

include __DIR__. '/includes/plantillas/plantilla.php';