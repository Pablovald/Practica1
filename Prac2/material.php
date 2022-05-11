<?php
namespace es\fdi\ucm\aw;

require_once __DIR__. '/includes/config.php';

$contenidoPrincipal = Material::infoMaterial($tituloPagina, $tituloCabecera).
$formularioComentario="";
$comentarios=mostrarTodasValoraciones($tituloPagina);

if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
        $contenidoPrincipal .= Material::mostrarFuncionesAdmin();
    }
    $form = new FormularioValoracion();
$formularioComentario = $form->gestiona();
}

include __DIR__. '/includes/plantillas/plantillaEntrada.php';
