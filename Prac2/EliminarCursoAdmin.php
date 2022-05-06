<?php

require_once __DIR__.'/includes/config.php';

$tituloCabecera="ELIMINAR CURSO";
$tituloPagina = 'Eliminar Curso';

$form = new es\fdi\ucm\aw\FormularioEliminarCursoActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal =$htmlFormIns;
//Mensaje relacionado con actualizar curso
if(isset($_GET['estadoElim'])){
    $estadoCur = htmlspecialchars($_GET["estadoElim"]);
    $nombre = htmlspecialchars($_GET["actividad"]);
    $curso = htmlspecialchars($_GET["curso"]);
    if($estadoCur == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El curso: "$curso" asociado al actividad: "$nombre" fue eliminada!<h1>
        EOS;
    }
}
include __DIR__.'/includes/plantillas/plantilla.php';