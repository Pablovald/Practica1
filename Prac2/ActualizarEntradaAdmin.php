<?php

require_once __DIR__.'/includes/config.php';

$tituloCabecera="ACTUALIZAR ENTRADA";
$tituloPagina = 'Actualizar Entrada';

$form = new es\fdi\ucm\aw\FormularioActualizarEntrada();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal =$htmlFormIns;
//Mensaje relacionado con actualizar entrada
if(isset($_GET['estadoAct'])){
    $estadoAct = htmlspecialchars($_GET["estadoAct"]);
    $nombre = htmlspecialchars($_GET["entrada"]);
    if($estadoAct == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La entrada: "$nombre" no existe!<h1>
        EOS;
    }
    else if($estadoAct == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La entrada: "$nombre" se actualizó correctamente!<h1>
        EOS;
    }
}
include __DIR__.'/includes/plantillas/plantilla.php';