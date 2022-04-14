<head>
<link rel="stylesheet" type="text/css" href="BlogView.css" />
</head>

<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/entradaBlog.php';

$contenidoPrincipal = entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);

 include __DIR__.'/includes/plantillas/plantilla.php';