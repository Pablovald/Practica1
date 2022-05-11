<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = infoMaterial($tituloPagina, $tituloCabecera).
$formularioComentario="";
$comentarios=mostrarTodasValoraciones($tituloPagina);
$comentarios= Valoracion::mostrarTodos($tituloPagina);

if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
        $content =<<<EOS
            <div class='submit'>
                <a href='ActualizarMaterialAdmin.php?material=$_GET[material]'>
                    <button type='submit'>Actualizar Material</button>
                </a>
            </div>
            EOS;
        $contenidoPrincipal .= $content;
    }
    $form = new FormularioValoracion();
$formularioComentario = $form->gestiona();
}

include __DIR__. '/includes/plantillas/plantillaEntrada.php';
