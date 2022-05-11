<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/GeneraVistas.php';
$tituloCabecera="ELIMINAR COMENTARIO";
$tituloPagina = 'Eliminar Comentario';
//Mensaje relacionado con edicion de comentarios
if(isset($_SESSION['login']) && $_SESSION['login']){
    if(isset($_POST['eliminar'])){
        $contenidoPrincipal = Comentario::borraComentario($_POST['id']);
    }
    else{
        $contenidoPrincipal = confirmarEliminarC($_POST['id']);
    }
    
}
else{
    $contenidoPrincipal="Necesitas estar logeado para poder eliminar tus comentarios.";
}
include __DIR__.'/includes/plantillas/plantilla.php';