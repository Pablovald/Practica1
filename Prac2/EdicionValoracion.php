<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';

$tituloCabecera="EDITAR VALORACION";
$tituloPagina = 'Editar valoracion';
//Mensaje relacionado con edicion de comentarios
if(isset($_SESSION['login']) && $_SESSION['login']){
    $form = new FormularioEdicionValoracion();
    $htmlFormIns = $form->gestiona();
    $contenidoPrincipal =$htmlFormIns;
}
else{
    $contenidoPrincipal="Necesitas estar logeado para poder editar tus valoraciones.";
}
include __DIR__.'/includes/plantillas/plantilla.php';