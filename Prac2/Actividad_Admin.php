<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';

$cont = es\fdi\ucm\aw\Actividad::listadoActividades();
$contenidoPrincipal = <<<EOS
<h2>Actividades disponibles:</h2>
<p>$cont</p>
</br>
EOS;

$form = new es\fdi\ucm\aw\FormularioActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoAct'])){
    $estadoAct = htmlspecialchars($_GET["estadoAct"]);
    $nombre = htmlspecialchars($_GET["nombre"]);
    if($estadoAct == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La actividad: "$nombre" ya existe!<h1>
        EOS;
    }
    else if($estadoAct == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡La actividad: "$nombre" se insertó correctamente!<h1>
        EOS;
    }
}
$contenidoPrincipal .="</br>";

$cont = es\fdi\ucm\aw\Actividad::listadoCursos();
$contenidoPrincipal .= <<<EOS
<h2>Cursos disponibles:</h2>
<p>$cont</p>
</br>
EOS;
$form = new es\fdi\ucm\aw\FormularioCursoActividadAdmin();
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
    else if($estadoCur == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error!</h1>
        <h1>¡El curso: "$curso" asociado al actividad: "$nombre" ya existe!<h1>
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

include __DIR__.'/includes/plantillas/plantilla.php';