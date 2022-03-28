<?php
function mostrarSaludo() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		echo "Bienvenido, " . $_SESSION['nombre'] . ".<a href='logout.php'>(salir)</a>";
		
	} else {
		echo "Usuario desconocido. <a href='login.php'>Login</a> <a href='registro.php'>Registro</a>";
	}
}
?>

<img src = "img/favicon.png" width="70" height="70" align="left">
<div align = "center">
	<h1><?= $tituloCabecera ?></h1>
	<div align = "right">
	<?php
		mostrarSaludo();
	?>
	</div>
</div>
