<nav>
<ul>
	<li><a href=home.php>Home</a></li>
    <li><a href=Blog.php>Blog</a></li>
    <li><a href=Actividades_Main.php>Actividades</a>
	<?php
		
		if(isset($_SESSION["login"])){
			if($_SESSION['esAdmin']){
				$contenido = <<<EOF
				<ul class="submenu">
				<li><a href="Actividad_Admin.php">Añadir clases</a></li>
				</ul></li>
			EOF;

			echo $contenido;
			}
		}

		?></li>
	<li><a href=Materiales.php>Materiales</a></li>
	<li><a href=Alojamientos_Main.php>Alojamientos</a></li>
	<li><a href=Carrito.php>Carrito</a></li>
</ul>
</nav>
