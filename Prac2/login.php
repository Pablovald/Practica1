<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Login';
$tituloCabecera = 'Login';

$form = new es\fdi\ucm\aw\FormularioLogin();
$htmlFormLogin = $form->gestiona();
$contenidoPrincipal = <<<EOS
<div class="login">
	<div class="login-screen">
		<div class="app-title">
			<h1>Login</h1>
		</div>
		<div class="login-form">
			$htmlFormLogin
			<p>¿No tienes cuenta?</p>
			<a href="registro.php">¡Regístrate!</a>
		</div>
	</div>
</div>
EOS;

include __DIR__.'/includes/plantillas/plantillaLogin.php';