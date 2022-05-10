<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';
$tituloPagina = 'Blog';
$tituloCabecera = 'BLOG';
$contenidoPrincipal = "
<div class='cabecera'>
    <p> En club Seawolf Deportes Naúticos os proporcionamos un blog con las noticias más extravagantes sobre deportes acuáticos </p>
</div>
<table align = 'center'>".
generaBlog().
"</table>";


include __DIR__.'/includes/plantillas/plantillaBlog.php';
