<?php
require_once __DIR__.'/includes/Aplicacion.php';
require_once __DIR__.'/includes/config.php';

function obtenerUsuario(){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $nombreUsuario = $_SESSION['nombreUsuario'];
    $auxUsuario = $conn->query(sprintf("SELECT id FROM Usuarios
        WHERE Usuarios.nombre = '$nombreUsuario'"));

    $idUsuario = $auxUsuario->fetch_assoc();

    return $idUsuario;
}

function obtenerProductosEnCarrito() { // obtenemos todos los productos que hay en el carro y los mostramos en la pagina
    $ContenidoPrincipal;
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idUsuario = obtenerUsuario();

    $tablaCarrito = sprintf("SELECT * FROM Carrito
        WHERE Carrito.id_usuario = '$idUsuario[id]'");
     $rs = $conn->query($tablaCarrito);

    if($rs->num_rows === 0) {
        $ContenidoPrincipal = <<< EOS
            <section class='hero is-info'>
                <div class='hero-body'>
                    <div class='container'>
                        <h1 class='title'>
                            Todavía no hay productos
                        </h1>
                        <h2 class='subtitle'>
                            Visita la tienda para agregar productos a tu carrito
                        </h2>
                        <a href='Materiales.php' class='button is-warning'>Ver materiales</a>
                    </div>
                </div>
            </section>
            EOS;
    }
         else {
            $total = 0;
             $ContenidoPrincipal = <<< EOS
            <div class='columns'>
                <div class='column'>
                    <h2 class='is-size-2'>Mi carrito</h2>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Imagen</th>
                                <th>Precio</th>
                                <th>Unidades</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
            <tbody>
            EOS;
            

                $row=$conn->query(sprintf(("SELECT Materiales.id, Materiales.nombre, Materiales.imagen, Materiales.precio, Carrito.cantidad
                FROM Materiales
                INNER JOIN Carrito
                ON Materiales.id = Carrito.id_producto
                WHERE Carrito.id_usuario = $idUsuario[id]")));
                if($row)  
                for($i = 1; $i <= $row->num_rows; $i++) {
                    $contenido=$row->fetch_assoc();
                    $total += $contenido['precio'] * $contenido['cantidad'];
                    $ContenidoPrincipal.= <<<EOS
                    <tr>
                        <td>$contenido[nombre]</td>
                        <td><img src = '$contenido[imagen]' width='250' height='250'</td>
                        <td>$contenido[precio] €</td>
                        <td>$contenido[cantidad]</td>
                        <td>
                            <form action="eliminar_del_carrito.php" method="post">
                                <input type="hidden" name="id_producto" value="$contenido[id]">
                                <input type="hidden" name="redireccionar_carrito">
                                <button class="button is-danger">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                        </tbody>
                    EOS;
                }
            $ContenidoPrincipal .= <<<EOS
                    <tfoot>
                        <tr>
                            <td colspan="2" class="is-size-4 has-text-right"><strong>Total</strong></td>
                            <td colspan="2" class="is-size-4">
                                $total €
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <a href="terminar_compra.php" class="button is-success is-large"><i class="fa fa-check"></i>&nbsp;Terminar compra</a>
            </div>
        </div>
        EOS;
         }        
        
    
    return $ContenidoPrincipal;
}

function estaEnCarrito($idProducto){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idUsuario = obtenerUsuario();
    $enCarrito = FALSE;
    $sentencia = $conn->query(sprintf("SELECT * FROM Carrito WHERE id_usuario = '$idUsuario[id]' AND id_producto = '$idProducto'")); // seleccionamos todos los elementos del carrito del 
    // usuario actual cuyo id de producto coincida con el que seleccionamos
    //$rs = $sentencia->fetch_assoc();
    if($sentencia->num_rows >= 1) // si hay al menos un elemento, esta en el carrito
        $enCarrito = TRUE;

    return $enCarrito;
}

function terminarCompra(){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idUsuario = obtenerUsuario();
    $row=$conn->query(sprintf("DELETE FROM Carrito WHERE id_usuario = '$idUsuario[id]'"));
    return $row;
}

function quitarProductoDelCarrito($idProducto) // quitamos un producto del carrito
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idUsuario = obtenerUsuario();
    $row = $conn->query(sprintf("SELECT * FROM Carrito WHERE id_usuario = '$idUsuario[id]' AND id_producto = '$idProducto'"));
    $rs = $row->fetch_assoc();
    if($rs['cantidad'] > 1){
        $sentencia = $conn->query(sprintf("UPDATE Carrito SET cantidad=cantidad - 1 WHERE id_producto = '$idProducto' AND id_usuario = '$idUsuario[id]'"));
    }
    else {
        $sentencia = $conn->query(sprintf("DELETE FROM Carrito WHERE id_usuario = '$idUsuario[id]' AND id_producto = $idProducto"));
    }
    //return $sentencia->execute([$idUsuario['id'], $idProducto]);
    return $sentencia;
}

function agregarProductoAlCarrito($idProducto) // agregamos el producto con id idProducto al carrito para el usuario con id sesion la sesion act
{
    // Ligar el id del producto con el usuario a través de la sesión
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idUsuario = obtenerUsuario();
    $enCarrito = estaEnCarrito($idProducto);
    if($enCarrito){
        $sentencia = $conn->query(sprintf("UPDATE Carrito SET cantidad=cantidad + 1 WHERE id_producto = '$idProducto' AND id_usuario = '$idUsuario[id]'"));
    }
    else{
        $sentencia = $conn->query(sprintf("INSERT INTO Carrito(id_usuario, id_producto, cantidad) VALUES ('$idUsuario[id]', '$idProducto', '1')"));
    }
    return $sentencia;
}