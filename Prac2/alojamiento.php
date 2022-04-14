<head>
	<link rel="stylesheet" type="text/css" href="FormulariosEstilo.css" />
</head>

<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioAlojamiento.php';
require_once __DIR__.'/includes/Alojamiento.php';
$contenidoPrincipal = Alojamiento::infoAlojamiento($tituloPagina, $tituloCabecera);

$form = new FormularioAlojamiento();
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