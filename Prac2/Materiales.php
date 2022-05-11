<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';

$cont = materialMain($tituloPagina, $tituloCabecera);
$contenidoPrincipal = <<<EOS
<p>$cont</p>
EOS;

$form = 


include __DIR__. '/includes/plantillas/plantilla.php';