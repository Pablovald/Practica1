<head>
<link rel="stylesheet" type="text/css" href="FormularioStyle.css" />
</head>

<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina="Editor";
$tituloCabecera="Editor";
$form=new es\fdi\ucm\aw\FormularioBlog();
$contenidoPrincipal=$form->gestiona();
include __DIR__.'/includes/plantillas/plantilla.php';