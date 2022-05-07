<?php

//include __DIR__ .'/includes/Comentarios.php';

require_once __DIR__ . '/includes/config.php';
$contenidoPrincipal = es\fdi\ucm\aw\entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);
$form = new es\fdi\ucm\aw\FormularioComentario();
$formularioComentario = $form->gestiona();
$comentarios=es\fdi\ucm\aw\Comentario::mostrarTodos($tituloPagina);

include __DIR__ . '/includes/plantillas/plantillaEntrada.php';