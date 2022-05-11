

<?php

function mostrarEditor() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true) && 
	\es\fdi\ucm\aw\Usuario::permisoPublicacion(\es\fdi\ucm\aw\Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']))) {
		echo "
		<h3>Bienvenido " . $_SESSION['nombre'] . ". Publica tu entrada aquí </h3>
		<div class='alinear2'>
		<div class='boton-login'>
				<a class = 'login' href='AniadirEntrada.php'>Publicación</a>
				</div>
		</div>";
	} 
}
?>


<aside>
<h4> ¿Eres profesor y tienes alguna noticia interesante? ¡CUENTANOSLA!</h4> </br>
<p>Inicia sesión para escribir una entrada interesante</p></br>
<?php
		mostrarEditor();
	?>
<div class='alinear'>
				<div class='boton-login'>
					<a class = 'login' href='login.php'>Login</a>
				</div>
</div>
<div>
<p> Si has tenido algún problema para añadir la publicación, contacta con nosotros por aquí </p>
</div>
<div class="contacto">
	<div class="boton-login">
			<a class = "login" href='contacto.php'>Contacto</a>
	</div>
</div>

</aside>