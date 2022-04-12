<?php
require_once __DIR__. '/includes/config.php';
require_once __DIR__. '/includes/Material.php';
$contenidoPrincipal = Material::infoMaterial($tituloPagina, $tituloCabecera);

include __DIR__. '/includes/plantillas/plantilla.php';