<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = infoAlojamiento($tituloPagina, $tituloCabecera);
$contenidoPrincipal.= Alojamiento::sacarFoto($_GET['alojamiento']);

$form = new FormularioAlojamiento();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;

	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
            $contenidoPrincipal .= <<<EOS
            <div class='submit'>
            <a href='EditorAdminAlojamiento.php?alojamiento=$_GET[alojamiento]'>
                <button type='submit'>Actualizar Alojamiento</button>
            </a>
            
        </div>
        EOS;
        }

    }


if(isset($_GET["estado"])){
    
    $estado = htmlspecialchars($_GET["estado"]);
    $nombreAlojamiento = htmlspecialchars($_GET['alojamiento']);
    if($estado == "InscritoCorrectamente"){
        $solicitud_diaini = htmlspecialchars($_GET['diaini']);
        $solicitud_diafin = htmlspecialchars($_GET['diafin']);
        $contenidoPrincipal .= <<<EOS
        <h1>Inscrito correctamente en $nombreAlojamiento entre los dias $solicitud_diaini y  $solicitud_diafin</h1>
    EOS;
    }
    else if($estado == "NoPlazas"){
        $dias = htmlspecialchars($_GET['dia']);
        $contenidoPrincipal .= <<<EOS
        <h1>$nombreAlojamiento no quedan plazas suficientes : $dias  </h1>
        EOS;
    }
    else if($estado == "faltaLogin"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad!</h1>
        <h1>Si ya tienes una cuenta, inicia sesión.</h1>
    EOS;
    }
    else if($estado == 'error'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar el alojamiento: '$nombreAlojamiento'!<h1>
        EOS;
    }
    else if($estado == 'actualizado'){
        $contenidoPrincipal .= <<<EOS
        <h1>El alojamiento $nombreAlojamiento se actualizo correctamente!<h1>
        EOS;
    }
    
}

include __DIR__.'/includes/plantillas/plantilla.php';