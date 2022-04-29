<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Perfil';
$tituloCabecera = 'PERFIL';

$usuario = es\fdi\ucm\aw\Usuario::buscaUsuario($_SESSION['nombreUsuario']);
$editar = htmlspecialchars($_GET['editar']);
$listado = es\fdi\ucm\aw\Usuario::infoUsuario($_SESSION['nombreUsuario']);
if($editar == "false"){
	$contenidoPrincipal = "
	<div class='perfil'>
		<div class='header'>
			<div class='portada'>
				<div>
					<img src='". $usuario->getRutaFoto()."' alt='img-avatar'>
				</div>
			</div>
		</div>
		<div class='body'>
			<div class='bio'>
				<h3>¡Bienvenido ". $usuario->getNombreUsuario()." a tu perfil!</h3>
				<p>Descripción detalla del usuario</p>
				<label>Nombre:<label>
				<input type='text' value='". $usuario->getNombre()."' readonly></br>
				<label>Apellido:<label>
				<input type='text' value='". $usuario->getApellido()."' readonly></br>
				<label>Correo:<label>
				<input type='text' value='". $usuario->getCorreo()."' readonly></br>
				<label>Telefono:<label>
				<input type='text' value='". $usuario->getTelefono()."' readonly></br>
				<label>Nacionalidad:<label>
				<input type='text' value='". $usuario->getNacionalidad()."' readonly></br>
				<label>Fecha de Nacimiento:<label>
				<input type='text' value='". $usuario->getFechaNac()."' readonly></br>
				<a href='Perfil.php?editar=true'>Editar</a>
				</div>
			<div class='footer'>
				$listado
			</div>
			<div class='datos'>
				<h1>Valoraciones</h1>
				<p>Valoración 1</p>
				<p>Valoracion 2</p>
			</div>
		</div>
		</div>
		</div>
		
	</div>
	";
}
else{
	$tituloPagina = 'Modificación del perfil';
	$tituloCabecera = 'MODIFICACION DEL PERFIL';
	$form = new es\fdi\ucm\aw\FormularioPerfil();
	$htmlFormIns = $form->gestiona();
	$contenidoPrincipal =$htmlFormIns;
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

