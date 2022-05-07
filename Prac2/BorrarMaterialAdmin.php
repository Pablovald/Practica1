<?php

require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = <<<EOS
<h2>Formulario para eliminar un material:</h2>
EOS;
$tituloCabecera="ELIMINAR MATERIAL";
$tituloPagina = 'Eliminar Material';

$form = new es\fdi\ucm\aw\FormularioBorrarProducto();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .= $htmlFormIns;
if(isset($_GET['estado'])){
    $estado = htmlspecialchars($_GET['estado']);
    $nombre = htmlspecialchars($_GET['nombre']);
    if($estado == 'error'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al eliminar el material: '$nombre'!<h1>
        EOS;
    }
    else if($estado == 'eliminado'){
        $contenidoPrincipal .= <<<EOS
        <h1>El material $nombre se eliminó correctamente!<h1>
        EOS;
    }
}

include __DIR__. '/includes/plantillas/plantilla.php';