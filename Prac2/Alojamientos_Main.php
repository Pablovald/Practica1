<head>
<link rel="stylesheet" type="text/css" href="FormularioStyle.css" />
</head>
<?php

require_once __DIR__.'/includes/config.php';


$tituloPagina = 'Alojamientos';

$tituloCabecera = 'ALOJAMIENTOS';
$contenidoPrincipal = es\fdi\ucm\aw\Alojamiento::alojamientoMain();

include __DIR__.'/includes/plantillas/plantilla.php';