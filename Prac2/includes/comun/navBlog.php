

<?php

function mostrarEditor() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		if(\es\fdi\ucm\aw\Usuario::permisoPublicacion(\es\fdi\ucm\aw\Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']))){
		echo "
		<h3>Bienvenido " . $_SESSION['nombre'] . ". Publica tu entrada aquí </h3>
		<div class='alinear2'>
		<div class='boton-login'>
				<a class = 'login' href='AniadirEntrada.php'>Publicación</a>
				</div>
		</div>";
	}
	
}
	else {
		echo "<h4> ¿Eres profesor y tienes alguna noticia interesante? ¡CUENTANOSLA!</h4> </br>
		<p>Inicia sesión para escribir una entrada interesante</p></br>";
		echo "<div class='alinear2'>
		<div class='boton-login'>
			<a class = 'login' href='login.php'>Login</a>
		</div>
        </div>";
		
	}
}
?>


<aside>
<?php
		mostrarEditor();
		?>
<div>
<p> Si has tenido algún problema para añadir la publicación, contacta con nosotros por aquí </p>
</div>
<div class="contacto">
	<div class="boton-login">
			<a class = "login" href='contacto.php'>Contacto</a>
	</div>
</div>

</aside>