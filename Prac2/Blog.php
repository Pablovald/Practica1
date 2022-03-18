<?php

require ("config.php");

$tituloPagina = 'Blog';

$tituloCabecera = 'BLOG';
$conn = $app->conexionBd();
$tablaBlog_Main=sprintf("SELECT * FROM Blog_Main");
$rs = $conn->query($tablaBlog_Main);
$tableCont="<tr>";
$j=0;
for($i=0;$i<$rs->num_rows;$i++){
	$row=$conn->query(sprintf("SELECT * FROM Blog_Main B WHERE B.numEntrada = '$i'"));
    $contenido=$row->fetch_assoc();
	$rowCont =  "<td>
	<div align = 'center'>
	<a href="."$contenido[link]"."><img src= '$contenido[rutaFoto]' width='250' height='250'></a>
	<h4>"."$contenido[titulo]"."</h4>
	<p>"."$contenido[descripcion]"."<a href="."$contenido[link]"."> Leer más</a></p>	
	</div>
	</td>";
	if($j<3){	
		$tableCont.=$rowCont;
		$j++;
	}
	else{
		$tableCont.="</tr>";
		$tableCont.="<tr>";
		$tableCont.=$rowCont;
		$j=0;
	}
}
$contenidoPrincipal = <<<EOS
<p> En club Seawolf Deportes Naúticos os proporcionamos un blog con las noticias más extravagantes sobre deportes acuáticos </p>
<table align = "center">
	$tableCont
  </table>  

EOS;

require ("plantilla.php");
