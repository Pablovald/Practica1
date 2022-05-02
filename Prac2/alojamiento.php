<?php
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = es\fdi\ucm\aw\Alojamiento::infoAlojamiento($tituloPagina, $tituloCabecera);
$contenidoPrincipal.=es\fdi\ucm\aw\Alojamiento::sacarFoto($_GET['alojamiento']);

$form = new es\fdi\ucm\aw\FormularioAlojamiento();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;

	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
            echo "
            <div class='submit'>
                        <a class = 'login' href='/practica1/Prac2/EditorAdminAlojamiento.php?alojamiento=$_GET[alojamiento]'>Editar</a>
                    </div>
            </div>";
        }
    }
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
        $nombreAlojamiento = htmlspecialchars($_GET['alojamiento']);
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
}

include __DIR__.'/includes/plantillas/plantilla.php';