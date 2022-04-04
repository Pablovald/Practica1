<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Registro';

$tituloCabecera = 'Registro';

$contenidoPrincipal = <<<EOS
<div class="login">
	<div class="login-screen">
		<div class="app-title">
			<h1>Registro</h1>
		</div>
		
		<div class="login-form">
			<form action="procesarRegistro.php" method="POST">
				<div class="grupo-control">
				<input class="control" type="text" name="nombreUsuario" placeholder="Nombre usuario" />
				</div>
				<div class="grupo-control">
				<input class="control" type="text" name="nombre" placeholder="Nombre completo" />
				</div>
				<div class="grupo-control">
				<input class="control" type="password" name="password" placeholder="Contraseña" />
				</div>
				<div class="grupo-control"><input class="control" type="password" name="password2" placeholder="Verifica tu contraseña" /><br /></div>
				<div class="grupo-control">
				<button type="submit" name="registro">Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>
</form>
EOS;

include __DIR__.'/includes/plantillas/plantillaLogin.php';