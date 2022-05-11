<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = materialMain($tituloPagina, $tituloCabecera);


include __DIR__. '/includes/plantillas/plantilla.php';