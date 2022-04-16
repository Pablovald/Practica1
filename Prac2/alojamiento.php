<head>
<link rel="stylesheet" type="text/css" href="FormularioStyle.css" />
</head>

<?php
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = es\fdi\ucm\aw\Alojamiento::infoAlojamiento($tituloPagina, $tituloCabecera);

$form = new es\fdi\ucm\aw\FormularioAlojamiento();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;

if(isset($_GET["estado"])){
    
    $estado = htmlspecialchars($_GET["estado"]);
    if($estado == "InscritoCorrectamente"){
        $nombreAlojamiento = htmlspecialchars($_GET['alojamiento']);
        $solicitud_diaini = htmlspecialchars($_GET['diaini']);
        $solicitud_diafin = htmlspecialchars($_GET['diafin']);
        $contenidoPrincipal .= <<<EOS
        <h1>Inscrito correctamente en $nombreAlojamiento entre los dias $solicitud_diaini y  $solicitud_diafin</h1>
    EOS;
    }
    else if($estado == "NoPlazas"){
        $restante = htmlspecialchars($_GET['restante']);
        $nombreAlojamiento = htmlspecialchars($_GET['alojamiento']);
        $solicitud_dia = htmlspecialchars($_GET['dia']);
        if($restante==0){
            $contenidoPrincipal .= <<<EOS
        <h1>$nombreAlojamiento en el dia $solicitud_dia están agotados, por favor seleccione otra fecha</h1>
        EOS;
        }
        else{
            $contenidoPrincipal .= <<<EOS
        <h1>$nombreAlojamiento solo quedan $restante habitaciones en el dia $solicitud_dia  </h1>
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