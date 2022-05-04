<?php

require_once __DIR__.'/includes/config.php';

$tituloCabecera="ACTUALIZAR/INSERTAR CAPACIDAD";
$tituloPagina = 'Actualizar/Insertar Capacidad';
$cont = es\fdi\ucm\aw\Actividad::listadoPlazas($_GET['actividad']);
$contenidoPrincipal = <<<EOS
<h2>Plazas disponibles:</h2>
<p>$cont</p>
</br>
EOS;


$form = new es\fdi\ucm\aw\FormularioCapacidadActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
//Mensaje relacionado con actualizar curso
if(isset($_GET['estadoCap'])){
    $estadoCap = htmlspecialchars($_GET["estadoCap"]);
    $nombre = htmlspecialchars($_GET["actividad"]);
    $curso = htmlspecialchars($_GET["curso"]);
    $capacidad = htmlspecialchars($_GET["capacidad"]);
    $fecha = htmlspecialchars($_GET["fecha"]);
    if($estadoCap == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Se insertaron "$capacidad" plazas del curso "$curso" asociado a la actividad "$nombre" para el día "$fecha" !<h1>
        EOS;
    }
    else if($estadoCap == "actualizado"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El curso: "$curso" asociado al actividad: "$nombre" en el día "$fecha" ahora tiene "$capacidad" plazas!<h1>
        EOS;
    }
    else if($estadoCap == "errorAct"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar el curso: "$curso" asociado al actividad: "$nombre"!<h1>
        EOS;
    }
}
include __DIR__.'/includes/plantillas/plantilla.php';