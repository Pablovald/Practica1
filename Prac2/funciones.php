<?php
require_once __DIR__.'/includes/Aplicacion.php';
require_once __DIR__.'/includes/config.php';

function obtenerProductosEnCarrito() { // obtenemos todos los productos que hay en el carro y los mostramos en la pagina
    $ContenidoPrincipal;
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idSesion = session_id();
    $tablaCarrito = sprintf("SELECT * FROM Carrito");
     $rs = $conn->query($tablaCarrito);

    if(!$rs) {
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
                                <th>Quitar</th>
                            </tr>
                        </thead>
            <tbody>
            EOS;
            for($i = 1; $i <=$rs->num_rows; $i++)  {
                $row=$conn->query(sprintf(("SELECT Materiales.id, Materiales.nombre, Materiales.imagen, Materiales.precio
                FROM Materiales
                INNER JOIN Carrito
                ON Materiales.id = Carrito.id_producto
                WHERE Carrito.id_producto = '$i'")));
                if($row)  
                $contenido=$row->fetch_assoc();
                $total += $contenido['precio'];
                $ContenidoPrincipal.= <<<EOS
                <tr>
                    <td>$contenido[nombre]</td>
                    <td><img src = '$contenido[imagen]' width='250' height='250'</td>
                    <td>$contenido[precio]</td>
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
                                $total
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

function productoYaEstaEnCarrito($idProducto) // para un id concreto, recorremos todos los ids de productos en el carro para ver si está ahi
{
    $ids = obtenerIdsDeProductosEnCarrito();
    if($ids = NULL)
        return false;
    foreach ($ids as $id) {
        if ($id == $idProducto) return true;
    }
    return false;
}

function obtenerIdsDeProductosEnCarrito() // obtenemos los ids de todos los productos en el carrito
{
    //necesito sacar mediante fetch_assoc() un array con los distintos ids de los productos del carrito cuyo id de sesion sea igual al actual
    $resultado = array();
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idSesion = session_id();
    $totalIds = sprintf("SELECT * FROM Materiales"); // para sacar el tamaño total de la tabla
    $rs = $conn->query($totalIds);
    for($i = 1; $i <= $rs->num_rows; $i++) {
        $row = $conn->query(sprintf("SELECT id_producto FROM Carrito 
        WHERE id_sesion = '$idSesion' AND id_producto = '$i'"));
        $contenido = $row->fetch_assoc();
        array_push($resultado, $contenido['id_producto']);
    }

    return $resultado;
}

function quitarProductoDelCarrito($idProducto) // quitamos un producto del carrito
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idSesion = session_id();
    $sentencia = $conn->prepare("DELETE FROM Carrito WHERE id_sesion = ? AND id_producto = ?");
    return $sentencia->execute([$idSesion, $idProducto]);
}

function agregarProductoAlCarrito($idProducto) // agregamos el producto con id idProducto al carrito para el usuario con id sesion la sesion act
{
    // Ligar el id del producto con el usuario a través de la sesión
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $idSesion = session_id();
    $sentencia = $conn->query(sprintf("INSERT INTO Carrito(id_sesion, id_producto) VALUES ($idSesion, $idProducto"));
    return $sentencia->fetch_assoc();
}