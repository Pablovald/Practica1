<head>
<link rel="stylesheet" type="text/css" href="estyleBlog.css" />
</head>

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
	<div class = 'blog-contenedor'>
		<div class = 'blog-box'>
			<div class = 'blog-img'>
			<a href="."procesarEntradaBlog.php?entrada="."$contenido[id]"."><img src= '$contenido[rutaImagen]'></a>
			</div>
			<div class = 'blog-text'>
			<h4>"."$contenido[titulo]"."</h4>
	<p>".implode(' ',$intro)."<a href="."procesarEntradaBlog.php?entrada="."$contenido[id]"."> Leer más</a></p>
			</div>
		</div>
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
<div class='cabecera'>
	<p> En club Seawolf Deportes Naúticos os proporcionamos un blog con las noticias más extravagantes sobre deportes acuáticos </p>
</div>
<table align = "center">
	$tableCont
  </table>  

EOS;


include __DIR__.'/includes/plantillas/plantillaBlog.php';
