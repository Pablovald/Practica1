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
    $nombreAlojamiento = htmlspecialchars($_GET['alojamiento']);
    $solicitud_dia = htmlspecialchars($_GET['dia']);
    if($estado == "InscritoCorrectamente"){
        $contenidoPrincipal .= <<<EOS
        <h1>Inscrito correctamente en $nombreAlojamiento en el dia $solicitud_dia</h1>
    EOS;
    }
    else if($estado == "NoPlazas"){
        $contenidoPrincipal .= <<<EOS
        <h1>$nombreAlojamiento en el dia $solicitud_dia están agotados, por favor seleccione otra fecha</h1>
    EOS;
    }
}

include __DIR__.'/includes/plantillas/plantilla.php';