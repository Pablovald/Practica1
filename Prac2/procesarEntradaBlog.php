<?php
namespace es\fdi\ucm\aw;

require_once __DIR__ . '/includes/config.php';
$contenidoPrincipal = entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);
$formularioComentario="";
if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
        $contenidoPrincipal .= entradaBlog::mostrarActualizar();
    }
    $form = new FormularioComentario();
    $formularioComentario = $form->gestiona();
}
$comentarios =  Comentario::mostrarTodos($tituloPagina);

include __DIR__ . '/includes/plantillas/plantillaEntrada.php';