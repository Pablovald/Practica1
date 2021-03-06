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
            header("Location: Materiales.php?a??adido=$mensaje");
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
        $ContenidoPrincipal="";
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
                                Todav??a no hay productos
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
                            <td>$contenido[precio] ???</td>
                            <td>$contenido[cantidad]</td>
                            <td>
                                <form action="eliminar_del_carrito.php" method="post">
                                    <input type="hidden" name="id_producto" value="$contenido[id]">
                                    <input type="number" name="cantidad" min ="1"value="">
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
                                    $total ???
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
            else if($rs['cantidad'] == $cantidad){ // lo eliminamos del carrito
                $sentencia = $conn->query(sprintf("DELETE FROM Carrito WHERE id_producto = '$idProducto' AND id_usuario = '$idUsuario[id]'"));
            }
            else { // si intentamos quitar m??s de los que tenemos, no eliminamos

            }
        }
    
        $row->free();
        return $sentencia;
    }
    
    public static function agregarProductoAlCarrito($idProducto, $cantidad) // agregamos el producto con id idProducto al carrito para el usuario con id sesion la sesion act
    {
        // Ligar el id del producto con el usuario a trav??s de la sesi??n
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
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT * FROM Materiales"));
        if($row){
            $ret="";
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $ret.="<option>"."$act[nombre]"."</option>";
            }
            $row->free();
            return $ret;
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }

    public static function borrarMaterial($id_producto, $nombre){
        // primero debemos eliminar el material de todas las tablas de carritos en las que aparezca
        // una vez borrado de todos los carritos, podemos proceder a eliminarlo de la tabla de materiales (haria falta tambi??n resetear el auto_increment)
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query1 = (sprintf("DELETE FROM Carrito WHERE '$id_producto' = id_producto"));
        $query2 = (sprintf("DELETE FROM Materiales WHERE '$id_producto' = id"));
        $conn->query($query1); // eliminamos el producto de todos los carritos si estuviera

        if($conn->query($query2)){
            if($conn->affected_rows != 1){
                header("Location: BorrarMaterialAdmin.php?estado=error&nombre=".$nombre."");
            }
            else{
                header("Location: BorrarMaterialAdmin.php?estado=eliminado&nombre=".$nombre."");

            }
        }

        else{
            echo "Error al borrar de la BD: (" . $conn->errno . ") " . utf8_encode($conn->errno);
        }

    }

    public static function sacaIdProducto($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT id FROM Materiales M WHERE M.nombre = '$nombre'"));
        $id_prod = $row->fetch_assoc();
        $row->free();

        return $id_prod['id'];
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($Nombre)
    {
        $this->nombre = $Nombre;

        return $this;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($Precio){
        $this->precio = $Precio;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($Descripcion)
    {
        $this->descripcion = $Descripcion;

        return $this;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($Imagen)
    {
        $this->imagen = $Imagen;

        return $this;
    }

    public function getDesc_det()
    {
        return $this->desc_det;
    }

    public function setDesc_det($Desc_det)
    {
        $this->desc_det = $Desc_det;
        
        return $this;
    }

}
?>
