<?php
require_once __DIR__. '/includes/config.php';

if(!isset($_POST["nombre"])){
    exit("No se ha seleccionado ningun producto");
}

$tituloPagina = "Eliminar material";
$tituloCabecera = "Borrar materiales";

//una vez tenemos el nombre necesitamos sacar el id del producto al que hace referencia

//POR HACER//
$nombre = $_POST["nombre"]; // me saca literalmente producto.select() en vez del valor
$id_producto = es\fdi\ucm\aw\Material::sacaIdProducto($_POST["nombre"]); // es nulo ya que no encuentra un producto con ese nombre

if($id_producto == NULL)
    $id_producto = "Es nulo";

es\fdi\ucm\aw\Material::borrarMaterial($id_producto); // borramos el material

$contenidoPrincipal = <<<EOS
<p>$nombre </p>
<p>$id_producto</p>
<h2>Producto correctamente eliminado!</h2>
<a href="Materiales.php" class="button is-success is-large"><i class="fa fa-check"></i>&nbsp;Volver a materiales    </a>
EOS;

include __DIR__. '/includes/plantillas/plantilla.php';