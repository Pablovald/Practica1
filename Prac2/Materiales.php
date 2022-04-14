<head>
<link rel="stylesheet" type="text/css" href="materialEstilo.css" />
</head>

<?php

require_once __DIR__. '/includes/config.php';

$tituloPagina = 'Materiales';

$tituloCabecera = 'MATERIALES';
$conn = $app->conexionBd();
$tablaMateriales=sprintf("SELECT * FROM Materiales");
$rs = $conn->query($tablaMateriales);
$tableCont="<tr>";
$j=0;
for($i=1;$i<=$rs->num_rows;$i++){
    $row=$conn->query(sprintf("SELECT * FROM Materiales M WHERE M.id = '$i'"));
    $contenido=$row->fetch_assoc();
    $url=rawurlencode("$contenido[nombre]");
    $rowCount = "<td>
    <div class = 'contenido'>
		<div class = 'card'>
			<a href ="."material.php?material=".$url."><img src= $contenido[imagen]> </a>
		</div>
		<div class = 'informacion'>
			<h4>"."$contenido[nombre]"."</h4>
			<p class = 'descripcion'>"."$contenido[descripcion]"." </p>
		</div>
		<div class = 'precio'>
			<div class = 'box-precio'>
				<p> Precio: "."$contenido[precio]"." €/hora <p>
			</div>
		</div>
    </div>
    </td>";
    if($j<3){	
		$tableCont.=$rowCount;
		$j++;
	}
    else{
		$tableCont.="</tr>";
		$tableCont.="<tr>";
		$tableCont.=$rowCount;
		$j=0;
	}
}
$contenidoPrincipal = <<<EOS
<p> Materiales disponibles para alquilar. </p>
<div class='alinear'>
    $tableCont
</div>

EOS;

include __DIR__. '/includes/plantillas/plantilla.php';