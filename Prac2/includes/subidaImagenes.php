<?php
namespace es\fdi\ucm\aw;
function subirImagen($directorio){
$nombreArchivo=$_FILES['imagen']['name'];
$mensaje = "";
$destinoImagen = $directorio .$nombreArchivo ;
$extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
$extValidas=array('jpg','jpeg','png');
 if (!in_array($extension,$extValidas)) {
    $mensaje = "Solo se permiten archivos jpg, jpeg o png";
}
else {
    if(!move_uploaded_file($_FILES['imagen']['tmp_name'],$destinoImagen)){
         $mensaje="Error al subir el archivo, inténtalo de nuevo";
     }
    else{
          $mensaje="Archivo subido corectamente";
    }
}
return $destinoImagen;
}

?>
