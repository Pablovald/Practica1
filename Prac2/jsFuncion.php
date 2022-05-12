<?php
namespace es\fdi\ucm\aw;
	require_once __DIR__. '/includes/config.php';
	if(isset($_REQUEST["user"])){
		$usuario = Usuario::buscaUsuario($_REQUEST["user"]);
		if ($usuario){
			echo "existe";
		}
		else {
			echo"disponible";
		}
	}
	if(isset($_REQUEST["actividad"])){
		if(isset($_REQUEST["curso"])){
			$cursos = Actividad::buscaCursoActividad($_REQUEST["actividad"], $_REQUEST["curso"]);
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
			$cursos = Actividad::buscaActividad($_REQUEST["actividad"]);
			if($cursos == false){
				echo "disponible";
			}
			else{
				echo "existe";
			}
		}
	}
	if(isset($_REQUEST["capacidad"])){
		$cursos = Actividad::cursosDeActividadDinamico($_REQUEST["capacidad"]);
		if(!empty($cursos)){
			echo $cursos;
		}
	}
	if(isset($_REQUEST["estado"])){
		$estado = $_REQUEST["estado"];
		if(strcmp($estado, "borrarActividad") == 0){
			$result = Actividad::borrarActividad($_REQUEST["nombre"]);
			echo "$result";
		}

	}
?>