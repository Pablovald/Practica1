<?php

function mostrarSaludo() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		echo "Bienvenido, " . $_SESSION['nombre'] . ".<a href='logout.php'>(salir)</a>";
		
	} else {
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
