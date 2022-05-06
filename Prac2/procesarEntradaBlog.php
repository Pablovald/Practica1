<?php

include __DIR__ .'/includes/Comentarios.php';

require_once __DIR__ . '/includes/config.php';
$contenidoPrincipal = es\fdi\ucm\aw\entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);
$form = new es\fdi\ucm\aw\FormularioComentario();
$formularioComentario = $form->gestiona();
es\fdi\ucm\aw\Comentario::mostrarTodos($tituloPagina,$comentarios);

include __DIR__ . '/includes/plantillas/plantillaEntrada.php';