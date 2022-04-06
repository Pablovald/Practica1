<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioLogin.php';

$tituloPagina = 'Login';

$tituloCabecera = 'Login';

$form = new FormularioLogin();
$htmlFormLogin = $form->gestiona();
$contenidoPrincipal = <<<EOS
<div class="login">
	<div class="login-screen">
		<div class="app-title">
			<h1>Login</h1>
		</div>
		<div class="login-form">
			$htmlFormLogin;
		</div>
	</div>
</div>
EOS;

include __DIR__.'/includes/plantillas/plantillaLogin.php';