<head>
<link rel="stylesheet" type="text/css" href="BlogView.css" />
</head>

<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/entradaBlog.php';

$tituloPagina = 'Blog';
$tituloCabecera = 'BLOG';
$contenidoPrincipal = entradaBlog::blog();


include __DIR__.'/includes/plantillas/plantillaBlog.php';
