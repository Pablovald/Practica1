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
            <link rel='stylesheet' href='material.css'>
            ";

                 $Cont .= "
                <form action='agregar_al_carrito.php' method='post'>
                    <input type='hidden' name='id_producto' value= '$rs[id]'>
                    <label>Selecciona una cantidad:</label>
                    <input type ='number' name='cantidad' min='1' value= 'number.select()'>
                    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
                    <button class='carrito'>
                        <span>Añadir</span>
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

}
?>
