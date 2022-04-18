<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Perfil';

$tituloCabecera = 'PERFIL';

$contenidoPrincipal = "
	<div class='perfil'>
		<div class='header'>
			<div class='portada'>
				<div class='avatar'>
					<img src='' alt='img-avatar'>
				</div>
			</div>
		</div>
		<div class='body'>
			<div class='bio'>
				<h3> Nombre de usuario </h3>
				<p> Descripción detalla del usuario con sus nombres y apellidos </p>
				</div>
			<div class='footer'>
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
include __DIR__.'/includes/plantillas/plantilla.php';

