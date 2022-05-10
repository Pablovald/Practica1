<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__ . '/includes/config.php';
$contenidoPrincipal = generaEntradaIndividual($tituloPagina,$tituloCabecera);
$formularioComentario="";
if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
        $contenidoPrincipal .="
        <div class='submit'>
            <a href='ActualizarEntradaAdmin.php?entrada=".htmlspecialchars($_GET['entrada'])."'>
                <button type='submit'>Actualizar Entrada</button>
            </a>
        </div>";
    }
    $form = new FormularioComentario();
    $formularioComentario = $form->gestiona();
}
$comentarios =  Comentario::mostrarTodos($tituloPagina);

include __DIR__ . '/includes/plantillas/plantillaEntrada.php';