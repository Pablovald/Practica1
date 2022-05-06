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
		if(isset($_REQUEST["curso"])){
			$cursos = es\fdi\ucm\aw\Actividad::buscaCursoActividad($_REQUEST["actividad"], $_REQUEST["curso"]);
			if(isset($_REQUEST["usuario"])){
				if(strcmp($_REQUEST["usuario"], "admin") == 0){
					if($cursos == false){
						echo "disponible";
					}
					else{
						echo "existe";
					}
				}
			}
			else{
				if(!empty($cursos)){
					$horas = $cursos->getHoras();
					$precio = $cursos->getPrecio();
					echo "".$horas." ".$precio."";
				}
			}
		}
		else{
			$cursos = es\fdi\ucm\aw\Actividad::buscaActividad($_REQUEST["actividad"]);
			if($cursos == false){
				echo "disponible";
			}
			else{
				echo "existe";
			}
		}
	}
	if(isset($_REQUEST["capacidad"])){
		$cursos = es\fdi\ucm\aw\Actividad::cursosDeActividadDinamico($_REQUEST["capacidad"]);
		if(!empty($cursos)){
			echo $cursos;
		}
	}
	if(isset($_REQUEST["estado"])){
		$estado = $_REQUEST["estado"];
		if(strcmp($estado, "borrarActividad") == 0){
			$result = es\fdi\ucm\aw\Actividad::borrarActividad($_REQUEST["nombre"]);
			echo "$result";
		}

	}
?>