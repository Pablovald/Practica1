<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/Alojamiento.php';

$tituloPagina = 'Alojamientos';

$tituloCabecera = 'ALOJAMIENTOS';

$contenidoPrincipal = Alojamiento::alojamientoMain();

include __DIR__.'/includes/plantillas/plantilla.php';