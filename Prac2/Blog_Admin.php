<?php
require_once __DIR__. '/includes/config.php';

$contenidoPrincipal = <<<EOS
<h2>Formulario para borrar una entrada:</h2>
EOS;
$tituloCabecera="BORRAR ENTRADA";
$tituloPagina = 'Borrar entrada';

$form = new es\fdi\ucm\aw\FormularioBorrarEntrada();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estado'])){
    $estado = htmlspecialchars($_GET['estado']);
    $nombre = htmlspecialchars($_GET['nombre']);
    if($estado == 'error'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al eliminar la entrada: '$nombre'!<h1>
        EOS;
    }
    else if($estado == 'eliminado'){
        $contenidoPrincipal .= <<<EOS
        <h1>La entrada $nombre se elimino correctamente!<h1>
        EOS;
    }
}


include __DIR__. '/includes/plantillas/plantilla.php';