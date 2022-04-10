<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/Actividad.php';

$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';
$contenidoPrincipal = Actividad::actividadMain();

include __DIR__.'/includes/plantillas/plantilla.php';