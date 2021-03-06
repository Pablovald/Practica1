<?php
namespace es\fdi\ucm\aw;
include __DIR__.'/includes/GeneraVistas.php';
require_once __DIR__.'/includes/config.php';


$cont = materialMain($tituloPagina, $tituloCabecera);
$contenidoPrincipal = <<<EOS
<p>$cont</p>
EOS;

$form = new FormularioMaterialAdmin();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
if(isset($_GET['estadoMat'])){
    $estadoMat = htmlspecialchars($_GET['estadoMat']);
    $nombre = htmlspecialchars($_GET['nombre']);
    if($estadoMat == 'error'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Error al crear el material: '$nombre'!<h1>
        EOS;
    }
    else if($estadoMat == 'exito'){
        $contenidoPrincipal .= <<<EOS
        <h1>¡El material: '$nombre' se creó correctamente!<h1>
        EOS;
    }
}

include __DIR__. '/includes/plantillas/plantilla.php';