

<?php

function mostrarEditor() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		echo "
		<h3>Bienvenido " . $_SESSION['nombre'] . ". Publica tu entrada aquí </h3>
		<div class='alinear2'>
		<div class='boton-login'>
					<a class = 'login' href='editor.php'>Publicación</a>
				</div>
		</div>";
		
	} else {
		echo "<h3> Usuario desconocido, no estás logeado </h3>
			<div class='alinear'>
				<div class='boton-login'>
					<a class = 'login' href='login.php'>Login</a>
				</div>
			</div>";
	}
}
?>


<aside>
<h4> Si tienes alguna noticia interesante ¡CUENTANOSLA!</h4> </br>
<p>Si estás registrado en nuestra página logeate para escribir una entrada interesante</p></br>
	<?php
		mostrarEditor();
	?>	
<h4>Por aquí puedes ver fotos de algunas de las noticias más EXTRAVAGANTES</h4></br></br>
<div class = "slider">
	<ul>
		<li><img src = "img/piscina.png"></li>
		<li><img src = "img/record.jpg"></li>
		<li><img src = "img/kayak1.png"></li>
		<li><img src = "img/barreraCoral.jpg"></li>
	</ul>
</div>
<p> Si has tenido algún problema para añadir la publicación, contacta con nosotros por aquí </p>
<div class="contacto">
	<div class="boton-login">
			<a class = "login" href='contacto.php'>Contacto</a>
	</div>
</div>

</aside>