<?php
	require_once __DIR__. '/includes/config.php';
	if(isset($_REQUEST["user"])){
		$usuario = es\fdi\ucm\aw\Usuario::buscaUsuario($_REQUEST["user"]);
		if ($usuario){
			echo "existe";
		}
		else {
			echo"disponible";
		}
	}
	if(isset($_REQUEST["actividad"])){
		$cursos = es\fdi\ucm\aw\Actividad::buscaCursoActividad($_REQUEST["actividad"], $_REQUEST["curso"]);
		if(!empty($cursos)){
			$horas = $cursos->getHoras();
			$precio = $cursos->getPrecio();
			echo "".$horas." ".$precio."";
		}
	}
	if(isset($_REQUEST["capacidad"])){
		$cursos = es\fdi\ucm\aw\Actividad::cursosDeActividadDinamico($_REQUEST["capacidad"]);
		if(!empty($cursos)){
			echo $cursos;
		}
	}
	
?>