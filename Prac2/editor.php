<head>
<link rel="stylesheet" type="text/css" href="FormulariosEstilo.css" />
</head>

<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/formularioBlog.php';
$tituloPagina="Editor";
$tituloCabecera="Editor";
$form=new FormularioBlog();
$contenidoPrincipal=$form->gestiona();
include __DIR__.'/includes/plantillas/plantilla.php';