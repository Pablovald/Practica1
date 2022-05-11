<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';
include __DIR__.'/includes/GeneraVistas.php';

$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';
$contenidoPrincipal = actividadMain();
include __DIR__.'/includes/plantillas/plantilla.php';