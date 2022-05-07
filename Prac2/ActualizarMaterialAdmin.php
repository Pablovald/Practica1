<?php

require_once __DIR__. '/includes/config.php';

$tituloCabecera="ACTUALIZAR MATERIAL";
$tituloPagina = "Actualizar Material";

$form = new es\fdi\ucm\aw\FormularioActualizarMaterialAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal =$htmlFormIns;
// Mensaje relacionado con actualizar material
if(isset($_GET['estadoAct'])){
    $estadoAct = htmlspecialchars($_GET["estadoAct"]);
    $nombre = htmlspecialchars($_GET["nombre"]);
    if($estadoAct == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El material: "$nombre" no existe!<h1>
        EOS;
    }
    else if($estadoAct == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El material: "$nombre" se actualizó correctamente!<h1>
        EOS;
    }
}
include __DIR__ .'/includes/plantillas/plantilla.php';