<?php
include_once 'includes/funciones.php';
if(!isset($_POST["id_producto"])){
    exit("No hay id_producto");
}

agregarProductoAlCarrito($_POST["id_producto"], $_POST["cantidad"]);
header("Location: Materiales.php");