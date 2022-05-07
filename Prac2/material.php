<?php
require_once __DIR__. '/includes/config.php';

$contenidoPrincipal = es\fdi\ucm\aw\Material::infoMaterial($tituloPagina, $tituloCabecera);
$form = new es\fdi\ucm\aw\FormularioValoracion();
$formularioComentario = $form->gestiona();
$comentarios=es\fdi\ucm\aw\Valoracion::mostrarTodos($tituloPagina);

if(isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
    $contenidoPrincipal .= <<<EOS
    <div class='submit'>
        <a href='ActualizarMaterialAdmin.php?material=$_GET[material]'>
            <button type='submit'>Actualizar Material</button>
        </a>
    </div>
    EOS;
}

include __DIR__. '/includes/plantillas/plantillaEntrada.php';
