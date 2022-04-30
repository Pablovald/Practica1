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
					<img class = 'avatar' src='". $usuario->getRutaFoto()."' alt='Foto'>
			</div>
		</div>
		<div class='body'>
			<div class='bio'>
			<h3>¡Bienvenido ". $usuario->getNombreUsuario()." a tu perfil!</h3>
			<p>Descripción detalla del usuario</p>
			<div class='datos1'>
				<li><span>Nombre: </span>". $usuario->getNombre()."</li>
				<li><span>Apellido: </span>". $usuario->getApellido()."</li>
				<li><span>Correo: </span>".  $usuario->getCorreo()."</li>
			</div>
			<div class='datos2'>
				<li><span>Telefono: </span>".  $usuario->getTelefono()."</li>
				<li><span>Nacionalidad: </span>".  $usuario->getNacionalidad()."</li>
				<li><span>Fecha de nacimiento: </span>".  $usuario->getFechaNac()."</li>
			</div>
			<div class='datos3'>
				<a class='adatos3' href='Perfil.php?editar=true'>Editar perfil <img class='icon-datos3' src='img/editar.png'></a>
			</div>
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

