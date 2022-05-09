<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';

$tituloCabecera="ELIMINAR VALORACION";
$tituloPagina = 'Eliminar valoracion';
//Mensaje relacionado con edicion de comentarios
if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_POST['eliminar'])){
        $contenidoPrincipal = Valoracion::borraValoracion($_POST['id']);
    }
    else{
        $contenidoPrincipal = Valoracion::confirmarEliminar($_POST['id']);
    }
    
}
else{
    $contenidoPrincipal="Necesitas estar logeado para poder eliminar tus valoraciones.";
}
include __DIR__.'/includes/plantillas/plantilla.php';