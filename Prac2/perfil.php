<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Perfil';
$tituloCabecera = 'PERFIL';

$editar = htmlspecialchars($_GET['editar']);
if($editar == "false"){
	$contenidoPrincipal = Usuario::perfilUsuario($_SESSION['nombreUsuario']);
	
}
else{
	$tituloPagina = 'Modificación del perfil';
	$tituloCabecera = 'MODIFICACION DEL PERFIL';
	$form = new FormularioPerfil();
	$htmlFormIns = $form->gestiona();
	$contenidoPrincipal = $htmlFormIns;
	if(isset($_GET['estado'])){
		$estado = htmlspecialchars($_GET["estado"]);
		if($estado = "exito"){
			$contenidoPrincipal .= <<<EOS
			<h1>¡Actualizado correctamente!<h1>
			EOS;
		}
		else if($estado = "error"){
			$contenidoPrincipal .= <<<EOS
			<h1>¡No se ha podido actualizar tu perfil!<h1>
			EOS;
		}

	}
}

include __DIR__.'/includes/plantillas/plantilla.php';

