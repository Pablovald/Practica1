<?php

if(!isset($_POST["id_producto"])){
    exit("No hay id_producto");
}
es\fdi\ucm\aw\quitarProductoDelCarrito($_POST["id_producto"], $_POST["cantidad"]);
if(isset($_POST["redireccionar_carrito"])){ // para ver si nos vamos al carrito o a materiales
    header("Location: ver_carrito.php");
}
else{
    header("Location: Materiales.php");
}