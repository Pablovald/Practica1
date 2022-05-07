<?php
namespace es\fdi\ucm\aw;

require_once __DIR__ . '/includes/config.php';
$contenidoPrincipal = entradaBlog::procesarEntradaBlog($tituloPagina, $tituloCabecera);
$form = new FormularioComentario();
$formularioComentario = $form->gestiona();

if(isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
    $contenidoPrincipal .= <<<EOS
    <div class='submit'>
        <a href='ActualizarEntradaAdmin.php?entrada=$_GET[entrada]'>
            <button type='submit'>Actualizar Entrada</button>
        </a>
        
    </div>
    EOS;
}
$comentarios =  Comentario::mostrarTodos($tituloPagina);

include __DIR__ . '/includes/plantillas/plantillaEntrada.php';