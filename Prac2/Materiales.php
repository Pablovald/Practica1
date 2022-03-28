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
    $rowCount = "<td>
    <div align = 'center'>
    <img src= $contenido[imagen] width='250' height='250'>
    <h4>"."$contenido[nombre]"."</h4>
    <p>"."$contenido[descripcion]"."</p>
    <p> Cantidad: "."$contenido[cantidad]"." unidades </p>
    <p> Precio: "."$contenido[precio]"." € <p>
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
<table align = "center">
    $tableCont
</table>
EOS;

include __DIR__. '/includes/plantillas/plantilla.php';