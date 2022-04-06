<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Blog';

$tituloCabecera = 'BLOG';
$conn = $app->conexionBd();
$tablaBlog_Main=sprintf("SELECT * FROM entradasBlog");
$rs = $conn->query($tablaBlog_Main);
$tableCont="<tr>";
$j=0;
for($i=1;$i<=$rs->num_rows;$i++){
	$row=$conn->query(sprintf("SELECT * FROM entradasBlog B WHERE B.id = '$i'"));
    $contenido=$row->fetch_assoc();
	$intro=explode(' ',$contenido['intro'],16);
	$intro[15]="...";
	$rowCont =  "<td>
	<div align = 'center'>
	<a href="."procesarEntradaBlog.php?entrada="."$contenido[id]"."><img src= '$contenido[rutaImagen]' width='250' height='250'></a>
	<h4>"."$contenido[titulo]"."</h4>
	<p>".implode(' ',$intro)."<a href="."procesarEntradaBlog.php?entrada="."$contenido[id]"."> Leer más</a></p>	
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
		$j=1;
	}
}
$contenidoPrincipal = <<<EOS
<p> En club Seawolf Deportes Naúticos os proporcionamos un blog con las noticias más extravagantes sobre deportes acuáticos </p>
<table align = "center">
	$tableCont
  </table>  

EOS;

include __DIR__.'/includes/plantillas/plantilla.php';
