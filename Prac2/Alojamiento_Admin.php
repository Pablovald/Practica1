<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = <<<EOS
<h2>Formulario para insertar un nuevo alojamiento:</h2>
EOS;
$tituloCabecera="FORMULARIO ADMIN";
$tituloPagina = 'Formulario admin';

$form = new FormularioAlojamientoAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estado'])){
    $estado = htmlspecialchars($_GET['estado']);
    $nombre = htmlspecialchars($_GET['alojamiento']);
    if($estado == 'error'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar el alojamiento: '$nombre'!<h1>
        EOS;
    }
    else if($estado == 'exito'){
        $contenidoPrincipal .= <<<EOS
        <h1>El alojamiento $nombre se inserto correctamente!<h1>
        EOS;
    }
    else if($estado == 'actualizado'){
        $contenidoPrincipal .= <<<EOS
        <h1>El alojamiento $nombre se actualizo correctamente!<h1>
        EOS;
    }
}

$cont = listadoCapacidad();
$contenidoPrincipal .= <<<EOS
<h2>Plazas disponibles:</h2>
<p>$cont</p>
</br>
EOS;
$form = new FormularioCapacidadAlojamientoAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoCap'])){
    $estadoCap = htmlspecialchars($_GET["estadoCap"]);
    $nombre = htmlspecialchars($_GET["nombre"]);
    $capacidad = htmlspecialchars($_GET["capacidad"]);
    $fecha = htmlspecialchars($_GET["fecha"]);
    if($estadoCap == "exito"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Se insertaron "$capacidad" asociado a la alojamiento "$nombre" para el día "$fecha" !<h1>
        EOS;
    }
    else if($estadoCap == "actualizado"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El alojamiento: "$nombre" en el día "$fecha" ahora tiene "$capacidad" plazas!<h1>
        EOS;
    }
    else if($estadoCap == "errorAct"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar la capacidad asociado al alojamiento: "$nombre"!<h1>
        EOS;
    }
    else if($estadoCap == "error"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al actualizar la capacidad asociado al alojamiento: "$nombre"!<h1>
        EOS;
    }
}

$contenidoPrincipal .= <<<EOS
<h2>Formulario para borrar un alojamiento:</h2>
EOS;
$form = new FormularioBorrarAlojamiento();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoBorrar'])){
    $estado = htmlspecialchars($_GET['estadoBorrar']);
    $nombre = htmlspecialchars($_GET['nombre']);
    if($estado == 'error'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al eliminar el alojamiento: '$nombre'!<h1>
        EOS;
    }
    else if($estado == 'eliminado'){
        $contenidoPrincipal .= <<<EOS
        <h1>El alojamiento $nombre se elimino correctamente!<h1>
        EOS;
    }
}


include __DIR__. '/includes/plantillas/plantilla.php';