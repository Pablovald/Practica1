<?php
require_once __DIR__. '/includes/config.php';

$tituloPagina = 'Carrito';

$tituloCabecera = 'Inscripcion';

$contenidoPrincipal = '';

if(isset($_SESSION["login"])){
    echo $productos;
}

else{
    $contenidoPrincipal .= <<<EOS
    <h1>Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad. Si ya tienes una cuenta, inicia sesión.</h1>
    EOS;
}

include __DIR__. '/includes/plantillas/plantilla.php';