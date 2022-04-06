<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Login';

$tituloCabecera = 'Login';

$contenidoPrincipal = <<<EOS
<div class="login">
	<div class="login-screen">
		<div class="app-title">
			<h1>Login</h1>
		</div>
		
		<div class="login-form">
			<form action="procesarLogin.php" method="POST">
				<div class="grupo-control">
				<input type="text" name="nombreUsuario" placeholder="Nombre usuario" />
				</div>
				<div class="grupo-control">
				<input type="password" name="password" placeholder="contraseña" />
				</div>
				<div class="grupo-control">
				<button type="submit" name="login">Login</button>
				</div>
				<div class="grupo-control">
				<a href='registro.php'>Registrate</a>
				</div>
			</form>
		</div>
	</div>
</div>
EOS;

include __DIR__.'/includes/plantillas/plantillaLogin.php';