<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/Actividad.php';
require_once __DIR__.'/includes/FormularioActividadAdmin.php';
require_once __DIR__.'/includes/FormularioCursoActividadAdmin.php';
require_once __DIR__.'/includes/FormularioCapacidadActividadAdmin.php';

$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';

$cont = Actividad::listadoActividades();
$contenidoPrincipal = <<<EOS
<h2>Actividades disponibles:</h2>
<p>$cont</p>
</br>
EOS;

$form = new FormularioActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoAct'])){
    $estadoAct = htmlspecialchars($_GET["estadoAct"]);
    $nombre = htmlspecialchars($_GET["nombre"]);
    if($estadoAct == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar la actividad: "$nombre"!<h1>
        EOS;
    }
    else if($estadoAct == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La actividad: "$nombre" se insertó correctamente!<h1>
        EOS;
    }
    else if($estadoAct == "actualizado"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La actividad: "$nombre" se actualizó correctamente!<h1>
        EOS;
    }
}
$contenidoPrincipal .="</br>";

$cont =Actividad::listadoCursos();
$contenidoPrincipal .= <<<EOS
<h2>Cursos disponibles:</h2>
<p>$cont</p>
</br>
EOS;
$form = new FormularioCursoActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoCur'])){
    $estadoCur = htmlspecialchars($_GET["estadoCur"]);
    $nombre = htmlspecialchars($_GET["nombre"]);
    $curso = htmlspecialchars($_GET["curso"]);
    if($estadoCur == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El curso: "$curso" asociado al actividad: "$nombre" se insertó correctamente!<h1>
        EOS;
    }
    else if($estadoCur == "actualizado"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El curso: "$curso" asociado al actividad: "$nombre" se actualizó correctamente!<h1>
        EOS;
    }
    else if($estadoCur == "errorAct"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar el curso: "$curso" asociado al actividad: "$nombre"!<h1>
        EOS;
    }
}
$contenidoPrincipal .="</br>";

$cont =Actividad::listadoPlazas();
$contenidoPrincipal .= <<<EOS
<h2>Plazas disponibles:</h2>
<p>$cont</p>
</br>
EOS;
$form = new FormularioCapacidadActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoCap'])){
    $estadoCap = htmlspecialchars($_GET["estadoCap"]);
    $nombre = htmlspecialchars($_GET["nombre"]);
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
    else if($estadoCap == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error el curso: "$curso" asociado al actividad: "$nombre" no existe en la BD!<h1>
        EOS;
    }
}

include __DIR__.'/includes/plantillas/plantilla.php';