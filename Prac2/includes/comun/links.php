<nav>
<ul>
	<li><a href=home.php>Home</a></li>
    <li><a href=Blog.php>Blog</a>
	<?php
		
		if(isset($_SESSION["login"])){
			if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
				$contenido = <<<EOF
				<ul class="submenu">
				<li><a href="Blog_Admin.php">eliminar entradas</a></li>
				</ul></li>
			EOF;
			echo $contenido;
			}
		}

	?></li>
    <li><a href=Actividades_Main.php>Actividades</a>
	<?php
		
		if(isset($_SESSION["login"])){
			if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
				$contenido = <<<EOF
				<ul class="submenu">
				<li><a href="Actividad_Admin.php">Añadir clases</a></li>
				</ul></li>
			EOF;
			echo $contenido;
			}
		}

		?></li>
	<li><a href=Materiales.php>Materiales</a>
	
	<?php
	if(isset($_SESSION["login"])){
		if(isset($_SESSION['esAdmin'])&& $_SESSION['esAdmin']){
			$contenido = <<<EOF
			<ul class="submenu">
			<li><a href="Material_Admin.php">Añadir materiales</a></li>
			</ul></li>
			EOF;
			echo $contenido;
		}
	}
	?></li>

	<li><a href=Alojamientos_Main.php>Alojamientos</a>
	<?php
	if(isset($_SESSION["login"])){
		if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
			$contenido = <<<EOF
			<ul class="submenu">
			<li><a href="Alojamiento_Admin.php">Añadir alojamiento</a></li>
			</ul></li>
			EOF;
			echo $contenido;
		}
	}
	?></li>

	<li><a href=ver_carrito.php>Carrito</a></li>
	<?php
		if(isset($_SESSION["login"])){
			$contenido = <<<EOF
			<li><a href=Perfil.php?editar=false>Perfil</a></li>
			EOF;
			echo $contenido;
		}
	?>
</ul>
</nav>
