<?php

require_once __DIR__.'/includes/config.php';

$tituloCabecera="ACTUALIZAR ACTIVIDAD";
$tituloPagina = 'Actualizar Actividad';

$form = new es\fdi\ucm\aw\FormularioActualizarActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal =$htmlFormIns;
//Mensaje relacionado con actualizar actividad
if(isset($_GET['estadoAct'])){
    $estadoAct = htmlspecialchars($_GET["estadoAct"]);
    $nombre = htmlspecialchars($_GET["actividad"]);
    if($estadoAct == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La actividad: "$nombre" no existe!<h1>
        EOS;
    }
    else if($estadoAct == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La actividad: "$nombre" se actualizó correctamente!<h1>
        EOS;
    }
}
include __DIR__.'/includes/plantillas/plantilla.php';