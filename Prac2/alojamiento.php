<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioAlojamiento.php';
require_once __DIR__.'/includes/Alojamiento.php';
$contenidoPrincipal = Alojamiento::infoAlojamiento($tituloPagina, $tituloCabecera);

$form = new FormularioAlojamiento();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;

include __DIR__.'/includes/plantillas/plantilla.php';