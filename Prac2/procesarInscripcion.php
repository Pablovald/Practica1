<?php
require ("config.php");

//if (! isset($_POST['inscripcion']) ) {
	//header('Location: inscripcion.php');
	//exit();
//}

$tituloPagina = 'Inscripcion';

$tituloCabecera = 'Inscripcion';

$contenidoPrincipal = '';

$nombreActividad = isset($_POST['actividad']) ? $_POST['actividad'] : null;
$solicitud_dia = isset($_POST['dia']) ? $_POST['dia'] : null;
$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : null;
$cursoActividad = isset($_POST['curso']) ? $_POST['curso'] : null;

if (isset($_SESSION["login"])) {
	$conn = $app->conexionBd();
	
	$id=sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario));
	$rs = $conn->query($id);
	$tamListaAct=sprintf("SELECT * FROM ListaActividades");
	$rs1 = $conn->query($tamListaAct);
	if($rs && $tamListaAct){
		$capacidad = sprintf("SELECT * FROM ListaActividades LA WHERE LA.dia = '%s' AND LA.nombre = '%s' AND LA.curso = '%s'"
					, $conn->real_escape_string($solicitud_dia)
					, $conn->real_escape_string($nombreActividad)
					, $conn->real_escape_string($cursoActividad));
		$rs2 = $conn->query($capacidad);
		if($rs2){
			$row = $rs->fetch_assoc();
			if($rs2->num_rows < 5){
				$query=sprintf("INSERT INTO ListaActividades(nombre, ID, dia, idUsuario, curso) VALUES('%s', '%s', '%s', '%s', '%s')"
						, $conn->real_escape_string($nombreActividad)
						, $conn->real_escape_string($rs1->num_rows)
						, $conn->real_escape_string($solicitud_dia)
						, $conn->real_escape_string($row['id'])
						, $conn->real_escape_string($cursoActividad));
				$rs3 = $conn->query($query);
				if($rs3){
					echo "Inscrito correctamente";
				} else{
					echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
					exit();
				}
			} else {
				echo "$nombreActividad del $cursoActividad en el $solicitud_dia están agotados, por favor seleccione otra fecha";
			}

		} else {
			echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
			exit();
		}
	} else {
		echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
		exit();
	}
} 
else {
	$contenidoPrincipal .= <<<EOS
	<h1>Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad. Si ya tienes una cuenta, inicia sesión.</h1>
	EOS;
}


require ("plantilla.php");