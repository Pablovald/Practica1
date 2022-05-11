<nav>
<ul>
	<li><a href=home.php>Home</a></li>
    <li><a href=Blog.php>Blog</a>
	<?php
		
		if(isset($_SESSION["login"])){
			if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] || es\fdi\ucm\aw\Usuario::permisoEliminar(es\fdi\ucm\aw\Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']))){
				$contenido = <<<EOF
				<ul class="submenu">
				<li class='link'><a href="Blog_Admin.php">Eliminar entradas</a></li>
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
			<li><a href="BorrarMaterialAdmin.php">Eliminar un material</a></li>
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
			<li><a href="Alojamiento_Admin.php">Añadir/borrar 
			alojamiento
			 y capacidad</li>
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
			<li><a href=perfil.php?editar=false>Perfil</a></li>
			EOF;
			echo $contenido;
		}
	?>
</ul>
</nav>
