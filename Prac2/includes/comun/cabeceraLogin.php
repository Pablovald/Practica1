<?php
function mostrarSaludo() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		echo "Bienvenido, " . $_SESSION['nombre'] . ".<a href='logout.php'>(salir)</a>";
		
	} else {
		echo "Usuario desconocido. <a href='login.php'>Login</a> <a href='registro.php'>Registro</a>";
	}
}
?>

<img class = "dimension" src = "img/seawolf logo.png">
<div class = "saludo">
<?php
	mostrarSaludo();
?>
</div>


