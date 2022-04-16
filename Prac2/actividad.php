<head>
<link rel="stylesheet" type="text/css" href="FormularioStyle.css" />
</head>
<?php

require_once __DIR__.'/includes/config.php';



$contenidoPrincipal = es\fdi\ucm\aw\Actividad::infoActividad($tituloPagina, $tituloCabecera);
$form = new es\fdi\ucm\aw\FormularioActividad();
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
        else if($estado == "fechaError"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡La fecha: "$solicitud_dia" no es válida!</h1>
            <h1>¡Por favor selecciona una fecha válida!</h1>
        EOS;
        }
    }
    else if($estado == "faltaLogin"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Necesitas estar loggeado en nuestra página web para reservar alojamientos!</h1>
        <h1>Si ya tienes una cuenta, inicia sesión.</h1>
    EOS;
    }
}

include __DIR__.'/includes/plantillas/plantilla.php';