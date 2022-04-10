<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioActividad.php';
$contenidoPrincipal = Actividad::infoActividad($tituloPagina, $tituloCabecera);

$form = new FormularioActividad();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;

include __DIR__.'/includes/plantillas/plantilla.php';