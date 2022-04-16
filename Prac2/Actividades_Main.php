<head>
<link rel="stylesheet" type="text/css" href="FormularioStyle.css" />
</head>
<?php

require_once __DIR__.'/includes/config.php';


$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';
$contenidoPrincipal = es\fdi\ucm\aw\Actividad::actividadMain();
include __DIR__.'/includes/plantillas/plantilla.php';