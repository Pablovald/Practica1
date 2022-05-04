<?php

require_once __DIR__.'/includes/config.php';

$tituloCabecera="ACTUALIZAR CURSO";
$tituloPagina = 'Actualizar Curso';

$form = new es\fdi\ucm\aw\FormularioActualizarCursoActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal =$htmlFormIns;
//Mensaje relacionado con actualizar curso
if(isset($_GET['estadoCur'])){
    $estadoCur = htmlspecialchars($_GET["estadoCur"]);
    $nombre = htmlspecialchars($_GET["actividad"]);
    $curso = htmlspecialchars($_GET["curso"]);
    if($estadoCur == "errorAct"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar el curso: "$curso" asociado al actividad: "$nombre"!<h1>
        EOS;
    }
    else if($estadoCur == "actualizado"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El curso: "$curso" asociado al actividad: "$nombre" se actualizó correctamente!<h1>
        EOS;
    }
}
include __DIR__.'/includes/plantillas/plantilla.php';