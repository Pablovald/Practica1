<?php
require_once __DIR__. '/includes/config.php';
es\fdi\ucm\aw\Material::terminarCompra();
$tituloPagina = "Carrito";
$tituloCabecera = "CARRITO";
$contenidoPrincipal = <<<EOS
<h2>Gracias por elegirnos para alquilar tu material deportivo!</h2>
<a href="Materiales.php" class="button is-success is-large"><i class="fa fa-check"></i>&nbsp;Volver a materiales    </a>
EOS;

include __DIR__. '/includes/plantillas/plantilla.php';