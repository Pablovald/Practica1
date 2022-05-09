<?php

function mostrarSaludo() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		echo "Bienvenido, " . $_SESSION['nombre'] . ".<a href='logout.php'>(salir)</a>";
		
	} else {
		$_SESSION['location']=urlencode($_SERVER['REQUEST_URI']);//guarda la ubicacion desde donde se hace login para redirigir al usuario una vez iniciada la sesion
		echo "Usuario desconocido. <a href='login.php'>Login</a> <a href='registro.php'>Registro</a>";
	}
}
?>

<header>
<a href="home.php"><img class = "dimension" src = "img/seawolf logo.png"></a>
<?php require ('links.php'); ?>
<div class = "titulo">
	<h1><?= $tituloCabecera ?></h1>
</div>
	<div class = "saludo">
	<?php
		mostrarSaludo();
	?>
	</div>
</header>
