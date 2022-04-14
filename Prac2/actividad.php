<head>
	<link rel="stylesheet" type="text/css" href="FormulariosEstilo.css" />
</head>

<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioActividad.php';
require_once __DIR__.'/includes/Actividad.php';

$contenidoPrincipal = Actividad::infoActividad($tituloPagina, $tituloCabecera);
$form = new FormularioActividad();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET["estado"])){
    $estado = htmlspecialchars($_GET["estado"]);
    if(isset($_GET["actividad"]) && isset($_GET["dia"]) && isset($_GET["curso"])){
        $nombreActividad = htmlspecialchars($_GET['actividad']);
        $cursoActividad = htmlspecialchars($_GET['curso']);
        $solicitud_dia = htmlspecialchars($_GET['dia']);
        if($estado == "InscritoCorrectamente"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡Inscrito correctamente en $nombreActividad de $cursoActividad en el dia $solicitud_dia!</h1>
        EOS;
        }
        else if($estado == "NoPlazas"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡$nombreActividad de $cursoActividad en el dia $solicitud_dia están agotados, por favor seleccione otra fecha!</h1>
        EOS;
        }
    }
    else if($estado == "faltaLogin"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad!</h1>
        <h1>Si ya tienes una cuenta, inicia sesión.</h1>
    EOS;
    }
}

include __DIR__.'/includes/plantillas/plantilla.php';