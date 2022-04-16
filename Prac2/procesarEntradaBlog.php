<head>
<link rel="stylesheet" type="text/css" href="BlogView.css" />
</head>

<?php
require_once __DIR__.'/includes/config.php';


$contenidoPrincipal = es\fdi\ucm\aw\entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);

 include __DIR__.'/includes/plantillas/plantilla.php';