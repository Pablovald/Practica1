<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';

$tituloPagina="Editor";
$tituloCabecera="Editor";
$form=new FormularioBlog();
$contenidoPrincipal=$form->gestiona();
include __DIR__.'/includes/plantillas/plantilla.php';