<?php

include __DIR__ .'/includes/Comentarios.php';

require_once __DIR__ . '/includes/config.php';
$contenidoPrincipal = es\fdi\ucm\aw\entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);
$comentarios = es\fdi\ucm\aw\Comentario::mostrarComentario($tituloPagina);
include __DIR__ . '/includes/plantillas/plantillaEntrada.php';