<?php
namespace es\fdi\ucm\aw;

class Material
{
    private $nombre;

    private $precio;

    private $imagen;

    private $descripcion;

    private $desc_det;

    private $id;

    private function __construct($nombre, $precio, $rutaFoto, $descripcion, $desc_det)
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->imagen = $rutaFoto;
        $this->descripcion = $descripcion;
        $this->desc_det = $desc_det;
    }

    public static function infoMaterial(&$tituloPagina, &$tituloCabecera){
        $tituloPagina = htmlspecialchars($_GET["material"]);
        $tituloCabecera = strtoupper($tituloPagina);
        $contenidoPrincipal ="";

        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaMaterial=sprintf("SELECT * FROM Materiales M WHERE M.nombre LIKE '%s' "
                                , $conn->real_escape_string($tituloPagina));
        $row = $conn->query($tablaMaterial);
        if($row){
            $rs=$row->fetch_assoc();
            $Cont="
            <div class = 'fotoMaterial'>
                <img src= $rs[imagen]>
            </div>
            <div class='contenidoMaterial'>
            <div class = 'tituloInfo'>
                Descripción detallada del producto: <br/>
            </div>
            <p>"."$rs[desc_det]</p><br/>
            <p>"." <precio>Precio del producto: </precio>"." $rs[precio] "." €</p>
            <link rel='stylesheet' href='css/material.css'>
            ";

                 $Cont .= "
                <form action='agregar_al_carrito.php' method='post'>
                    <input type='hidden' name='id_producto' value= '$rs[id]'>
                    <label>Selecciona una cantidad:</label>
                    <input type ='number' name='cantidad' min='1' value= 'number.select()'>
                    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
                    <button class='carrito'>
                        <span2>Añadir</span2>
                        <i class='fa fa-shopping-basket' aria-hidden='true'></i>
                    </button>
                </form>";

            $Cont .= "</div>";
            $contenidoPrincipal = <<<EOS
                $Cont
            EOS;
            $row->free();
        }

        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	        exit();
        }

        return $contenidoPrincipal;
    }

    public static function materialMain(&$tituloPagina, &$tituloCabecera){
        $contenidoPrincipal = NULL;
        $tituloPagina = "Materiales";
        $tituloCabecera = "MATERIALES";
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaMaterial_Main=sprintf("SELECT * FROM Materiales");
        $rs =$conn->query($tablaMaterial_Main);
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
        return $contenidoPrincipal;
    }

     //Busca un material
     public static function buscaMaterial($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM Materiales M WHERE M.nombre = '%s'", $conn->real_escape_string($nombre));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $material = new Material($fila['nombre'], $fila['precio'], $fila['imagen'], $fila['descripcion'], $fila['desc_det']);  
                $material->id = $fila['id'];
                $result = $material;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    //Crea un material
    public static function creaMaterial($nombre, $precio, $imagen, $descripcion, $desc_det){
        $material = self::buscaMaterial($nombre);
        if($material == false){
            $material = new Material($nombre, $precio, $imagen, $descripcion, $desc_det);
        }
        else{
            $material->nombre = $nombre;
            $material->precio = $precio;
            $material->imagen = $imagen;
            $material->descripcion = $descripcion;
            $material->desc_det = $desc_det;
        }
        return self::guardaMaterial($material);
    }

    //Lo guarda, si ya existe, lo actualiza
    public static function guardaMaterial($material){
        if ($material->id != null) {
            return self::actualizaMaterial($material);
        }
        return self::insertaMaterial($material);
    }
    
    //Inserta informacion de un material en la BD
    private static function insertaMaterial($material){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO Materiales(id, nombre, precio, imagen, descripcion, desc_det) VALUES ('%d', '%s', '%d', '%s', '%s', '%s')"
        , $conn->insert_id
        , $conn->real_escape_string($material->nombre)
        , $conn->real_escape_string($material->precio)
        , $conn->real_escape_string($material->imagen)
        , $conn->real_escape_string($material->descripcion)
        , $conn->real_escape_string($material->desc_det));
        if ( $conn->query($query) ) {
            $material->id = $conn->insert_id;
            $mensaje = "El material: $material->nombre se inserto correctamente";
            header("Location: Materiales.php?añadido=$mensaje");
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $material;
    }

    //Actualiza un material
    private static function actualizaMaterial($material){
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Materiales M SET nombre='%s', precio='%d', imagen='%s', descripcion='%s', desc_det='%s' WHERE M.id='%d'"
        , $conn->real_escape_string($material->nombre)
        , $conn->real_escape_string($material->precio)
        , $conn->real_escape_string($material->imagen)
        , $conn->real_escape_string($material->descripcion)
        , $conn->real_escape_string($material->desc_det)
        , $material->id);
        if ($conn->query($query)) {
            if ( $conn->affected_rows != 1) {
                header("Location: Materiales.php?estadoAct=error&nombre=".$material->nombre."");
            }
            else{
                $result = $material;
                header("Location: Materiales.php?estadoAct=exito&nombre=".$material->nombre."");
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    public static function  obtenerUsuario(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $nombreUsuario = $_SESSION['nombreUsuario'];
        $auxUsuario = $conn->query(sprintf("SELECT id FROM Usuarios
            WHERE Usuarios.nombre = '$nombreUsuario'"));
    
        $idUsuario = $auxUsuario->fetch_assoc();
    
        $auxUsuario->free();
        return $idUsuario;
    }
    
    public static function  obtenerProductosEnCarrito() { // obtenemos todos los productos que hay en el carro y los mostramos en la pagina
        $ContenidoPrincipal;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUsuario = self::obtenerUsuario();
    
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
                 <head>
                 <link rel='stylesheet' type="text/css" href='css/carrito.css'>
                 </head>
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
                                    <input type="number" name="cantidad" min ="1"value="cantidad.select()">
                                    <input type="hidden" name="redireccionar_carrito">
                                    <button class="eliminar">
                                        <i class="fa fa-trash-o"></i>
                                        <span>Eliminar</span>
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
            $row->free();
             }        
            
        $rs->free();
        return $ContenidoPrincipal;
    }
    
    public static function  estaEnCarrito($idProducto){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUsuario = self::obtenerUsuario();
        $enCarrito = FALSE;
        $sentencia = $conn->query(sprintf("SELECT * FROM Carrito WHERE id_usuario = '$idUsuario[id]' AND id_producto = '$idProducto'")); // seleccionamos todos los elementos del carrito del 
        // usuario actual cuyo id de producto coincida con el que seleccionamos
        if($sentencia->num_rows >= 1) // si hay al menos un elemento, esta en el carrito
            $enCarrito = TRUE;
    
        $sentencia->free();
        return $enCarrito;
    }
    
    public static function  terminarCompra(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUsuario = self::obtenerUsuario();
        $row=$conn->query(sprintf("DELETE FROM Carrito WHERE id_usuario = '$idUsuario[id]'"));
        return $row;
    }
    
    public static function  quitarProductoDelCarrito($idProducto, $cantidad) // quitamos un producto del carrito
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUsuario = self::obtenerUsuario();
        $row = $conn->query(sprintf("SELECT * FROM Carrito WHERE id_usuario = '$idUsuario[id]' AND id_producto = '$idProducto'"));
        $rs = $row->fetch_assoc();
        if($cantidad > 0) {
            if($rs['cantidad'] > $cantidad){
                $sentencia = $conn->query(sprintf("UPDATE Carrito SET cantidad=cantidad - $cantidad WHERE id_producto = '$idProducto' AND id_usuario = '$idUsuario[id]'"));
            }
            else { // si intentamos quitar más de los que tenemos, mostraremos un mensaje de error
                echo '<script>alert("No posees suficientes unidades del artículo. Por favor, selecciona una cantidad válida")</script>';
            }
        }
    
        $row->free();
        return $sentencia;
    }
    
    public static function agregarProductoAlCarrito($idProducto, $cantidad) // agregamos el producto con id idProducto al carrito para el usuario con id sesion la sesion act
    {
        // Ligar el id del producto con el usuario a través de la sesión
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $idUsuario = self::obtenerUsuario();
        $enCarrito = self::estaEnCarrito($idProducto);
        if($cantidad > 0) {
            if($enCarrito){
                $sentencia = $conn->query(sprintf("UPDATE Carrito SET cantidad=cantidad + $cantidad WHERE id_producto = '$idProducto' AND id_usuario = '$idUsuario[id]'"));
            }
            else{
                $sentencia = $conn->query(sprintf("INSERT INTO Carrito(id_usuario, id_producto, cantidad) VALUES ('$idUsuario[id]', '$idProducto', '$cantidad')"));
            }
        }
        return $sentencia;
    }

    public static function totalMateriales()
    {
        $mat = array();

        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaMaterial_Main=sprintf("SELECT * FROM Materiales");
        $rs =$conn->query($tablaMaterial_Main);
        $tableCont="<tr>";
        $j=0;
        for($i=1;$i<=$rs->num_rows;$i++){
            $row=$conn->query(sprintf("SELECT * FROM Materiales M WHERE M.id = '$i'"));
            $contenido=$row->fetch_assoc();
            array_push($mat, $contenido["nombre"]);
            $row->free();
        }

        return $mat;
    }

    public static function borrarMaterial($id_producto){
        // primero debemos eliminar el material de todas las tablas de carritos en las que aparezca
        // una vez borrado de todos los carritos, podemos proceder a eliminarlo de la tabla de materiales (haria falta también resetear el auto_increment)
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $sentencia=$conn->query(sprintf("DELETE FROM Carrito C WHERE '$id_producto' = C.id_producto")); // borramos todas las filas en las que aparece

        $sentencia2=$conn->query(sprintf("DELETE FROM Materiales M WHERE '$id_producto' = M.id"));
    }

    public static function sacaIdProducto($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT id FROM Materiales M WHERE M.nombre = '$nombre'"));
        $id_prod = $row->fetch_assoc();

        return $id_prod;
    }

}
?>
