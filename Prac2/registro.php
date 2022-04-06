<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioRegistro.php';

$tituloPagina = 'Registro';

$tituloCabecera = 'Registro';

$form = new FormularioRegistro();
$htmlFormRegistro = $form->gestiona();
$contenidoPrincipal = <<<EOS
<div class="login">
	<div class="login-screen">
		<div class="app-title">
			<h1>Registro</h1>
		</div>
		<div class="login-form">
			$htmlFormRegistro
		</div>
	</div>
</div>
EOS;

include __DIR__.'/includes/plantillas/plantillaLogin.php';