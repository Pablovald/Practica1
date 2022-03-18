<?php

require ("config.php");

$tituloPagina = 'Actividades';

$tituloCabecera = 'ACTIVIDADES';

$conn = $app->conexionBd();
$tablaActividad_Main=sprintf("SELECT * FROM Actividad_Main");
$rs = $conn->query($tablaActividad_Main);
$tableCont=NULL;
for($i=1;$i<=$rs->num_rows;$i++){
	$row=$conn->query(sprintf("SELECT * FROM Actividad_Main A WHERE A.id = '$i'"));
    $contenido=$row->fetch_assoc();
	$leftCont =  "<div><td>
			<a href = "."$contenido[link]"."><img src= '$contenido[rutaFoto]' width='667' height='400'> </a>
		</td></div>";
	$rightCont = "<div><td>
		<h2><a href = "."$contenido[link]".">"."$contenido[Nombre]"." </a></h2>
			"."$contenido[Descripcion]"."
		<a href = "."$contenido[link]".">Leer más</a></p>
		</td></div>";

	if($i%2==0){
		$aux=$leftCont;
		$leftCont=$rightCont;
		$rightCont=$aux;
		}
	$tableCont.="<tr>"."$leftCont"."$rightCont"."</tr>";
}
$contenidoPrincipal = <<<EOS
<p> Clases Disponibles en SeaWolf Deportes Náuticos. </p>

<table>$tableCont
  </table>
EOS;

require ("plantilla.php");