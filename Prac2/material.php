<?php
require_once __DIR__. '/includes/config.php';

$contenidoPrincipal = es\fdi\ucm\aw\Material::infoMaterial($tituloPagina, $tituloCabecera);
$form = new es\fdi\ucm\aw\FormularioValoracion();
$formularioComentario = $form->gestiona();
$comentarios=es\fdi\ucm\aw\Valoracion::mostrarTodos($tituloPagina);

include __DIR__. '/includes/plantillas/plantillaEntrada.php';