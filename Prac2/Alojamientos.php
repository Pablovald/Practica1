<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Alojamientos';

$tituloCabecera = 'ALOJAMIENTOS';

$conn = $app->conexionBd();
$tablaAlojamiento_Main=sprintf("SELECT * FROM Alojamiento");
$rs = $conn->query($tablaAlojamiento_Main);
$tableCont=NULL;
for($i=1;$i<=$rs->num_rows;$i++){
	$row=$conn->query(sprintf("SELECT * FROM Alojamiento A WHERE A.id = '$i'"));
    $contenido=$row->fetch_assoc();
	$url=rawurlencode("$contenido[nombre]");
	$leftCont =  "<div><td>
			<a href ="."alojamiento.php?alojamiento=".$url."><img src= '$contenido[rutaFoto]' width='667' height='400'> </a>
		</td></div>";
	$rightCont = "<div><td>
		<h2><a href = "."alojamiento.php?alojamiento=".$url.">"."$contenido[nombre]"." </a></h2>
			"."$contenido[descripcion]"."
		<a href = "."alojamiento.php?alojamiento=".$url.">Leer más</a></p>
		</td></div>";

	if($i%2==0){
		$aux=$leftCont;
		$leftCont=$rightCont;
		$rightCont=$aux;
		}
	$tableCont.="<tr>"."$leftCont"."$rightCont"."</tr>";
}
$contenidoPrincipal = <<<EOS
<p>Alojamientos disponibles en SeaWolf Deportes Náuticos. </p>

<table>$tableCont
  </table>
EOS;

include __DIR__.'/includes/plantillas/plantilla.php';