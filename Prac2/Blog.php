<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';


$tituloPagina = 'Blog';
$tituloCabecera = 'BLOG';
$contenidoPrincipal = entradaBlog::blog();


include __DIR__.'/includes/plantillas/plantillaBlog.php';
