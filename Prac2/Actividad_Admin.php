<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/Actividad.php';
require_once __DIR__.'/includes/FormularioActividadAdmin.php';
require_once __DIR__.'/includes/FormularioCursoActividadAdmin.php';

$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';

$cont = Actividad::listadoActividades();
$contenidoPrincipal = <<<EOS
<h2>Actividades disponibles:</h2>
<p>$cont</p>
EOS;

$form = new FormularioActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['errorActualizarActividad'])){
    $info = htmlspecialchars($_GET["errorActualizarActividad"]);
    $contenidoPrincipal .= <<<EOS
    <h1>$info</h1>
    EOS;
}
if(isset($_GET['actualizado'])){
    $info = htmlspecialchars($_GET["actualizado"]);
    $contenidoPrincipal .= <<<EOS
    <h1>$info</h1>
    EOS;
}

if(isset($_GET['añadido'])){
    $añadido = htmlspecialchars($_GET["añadido"]);
    $contenidoPrincipal .= <<<EOS
    <h1>$añadido</h1>
    EOS;
}
$contenidoPrincipal .="</br>";
$form = new FormularioCursoActividadAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['errorActualizarCurso'])){
    $info = htmlspecialchars($_GET["errorActualizarCurso"]);
    $contenidoPrincipal .= <<<EOS
    <h1>$info</h1>
    EOS;
}
if(isset($_GET['actualizadoCurso'])){
    $info = htmlspecialchars($_GET["actualizadoCurso"]);
    $contenidoPrincipal .= <<<EOS
    <h1>$info</h1>
    EOS;
}

if(isset($_GET['añadidoCurso'])){
    $añadido = htmlspecialchars($_GET["añadidoCurso"]);
    $contenidoPrincipal .= <<<EOS
    <h1>$añadido</h1>
    EOS;
}

include __DIR__.'/includes/plantillas/plantilla.php';