<?php
require_once __DIR__. '/includes/config.php';

$contenidoPrincipal = <<<EOS
<h2>Formulario para insertar un nuevo alojamiento:</h2>
EOS;
$tituloCabecera="FORMULARIO ADMIN";
$tituloPagina = 'Formulario admin';

$form = new es\fdi\ucm\aw\FormularioAlojamientoAdmin();
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

include __DIR__. '/includes/plantillas/plantilla.php';