<?php

if(!isset($_POST["id_producto"])){
    exit("No hay id_producto");
}

es\fdi\ucm\aw\agregarProductoAlCarrito($_POST["id_producto"], $_POST["cantidad"]);
header("Location: Materiales.php");