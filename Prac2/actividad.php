<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = infoActividad($tituloPagina, $tituloCabecera);
$form = new FormularioActividad();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
$formularioComentario="";
//Mensaje relacionado con inscripcion de una actividad
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
        else if($estado == "error"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡La fecha: "$solicitud_dia" no es válida o que la hora seleccionada no es válida!</h1>
        EOS;
        }
        else if($estado == "capacidadError"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡Para la fecha: "$solicitud_dia" no quedan plazas!</h1>
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

if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
        $contenidoPrincipal .= mostrarFuncionesAdmin();
    }
    $form2 = new FormularioValoracion();
    $formularioComentario = $form2->gestiona();
}
$comentarios= mostrarTodasValoraciones($tituloPagina);
include __DIR__.'/includes/plantillas/plantillaEntrada.php';