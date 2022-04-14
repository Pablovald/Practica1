<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularioBlog.php';
$tituloPagina="Editor";
$tituloCabecera="Editor";
$form=new FormularioBlog();
$contenidoPrincipal=$form->gestiona();
include __DIR__.'/includes/plantillas/plantilla.php';