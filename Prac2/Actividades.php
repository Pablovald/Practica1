<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Actividades';
$tituloCabecera = 'ACTIVIDADES';

$conn = $app->conexionBd();
$tablaActividad_Main=sprintf("SELECT * FROM Actividad_Main");
$rs =$conn->query($tablaActividad_Main);
$tableCont=NULL;
if($rs)
{
	for($i=1;$i<=$rs->num_rows;$i++){
		$row=$conn->query(sprintf("SELECT * FROM Actividad_Main A WHERE A.id = '%s'"
							, $conn->real_escape_string($i)));
		if($row)
		{
			$contenido=$row->fetch_assoc();
			$url=rawurlencode("$contenido[Nombre]");
			$leftCont =  "<div><td>
					<a href ="."actividad.php?actividad=".$url."><img src= '$contenido[rutaFoto]' width='667' height='400'> </a>
				</td></div>";
			$rightCont = "<div><td>
				<h2><a href = "."actividad.php?actividad=".$url.">"."$contenido[Nombre]"." </a></h2>
					"."$contenido[Descripcion]"."
				<a href = "."actividad.php?actividad=".$url.">Leer más</a></p>
				</td></div>";
			if($i%2==0){
				$aux=$leftCont;
				$leftCont=$rightCont;
				$rightCont=$aux;
			}
			$tableCont.="<tr>"."$leftCont"."$rightCont"."</tr>";
			$row->free();
		}else{
			echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
			exit();
		}
	}
	$contenidoPrincipal = <<<EOS
	<p> Clases Disponibles en SeaWolf Deportes Náuticos. </p>
	<table>$tableCont
		</table>
	EOS;
	$rs->free();
}
else{
	echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	exit();
}


include __DIR__.'/includes/plantillas/plantilla.php';