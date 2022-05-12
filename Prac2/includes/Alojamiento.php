<?php
namespace es\fdi\ucm\aw;

class Alojamiento
{
    private $nombre;
    private $precio;
    private $rutaFoto;
    private $descripcion;
    private $descripciondetallada;
    private $id;
    private $capacidad;
    private $fecha;
    private $IDAlojamiento_Main;

    private function __construct($nombre,$precio,$rutaFoto,$descripcion,$descripciondetallada,$capacidad,$fecha)
    {

        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->rutaFoto = $rutaFoto;
        $this->descripcion = $descripcion;
        $this->descripciondetallada = $descripciondetallada;
        $this->$capacidad=$capacidad;
        $this->$fecha=$fecha;
    }


    public static function inscribirAlojamiento($nhabitacion, $fechaini, $fechafin,$nombreAlojamiento, &$result){
        $nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : null;
        $app = Aplicacion::getSingleton();
        $dateDifference = abs(strtotime($fechafin) - strtotime($fechaini));
        $years  = floor($dateDifference / (365 * 60 * 60 * 24));
        $months = floor(($dateDifference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days   = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24) / (60 * 60 * 24));
        $conn = $app->conexionBd();
        $rs = $conn->query(sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario LIKE '%s'", $conn->real_escape_string($nombreUsuario)));
        $rs1 =$conn->query(sprintf("SELECT id FROM Alojamiento a WHERE a.nombre LIKE '%s'", $conn->real_escape_string($nombreAlojamiento)));
        if($rs&&$rs1){
            $idUsuario=$rs->fetch_assoc();
            $Alojamiento=$rs1->fetch_assoc();
            $id=$Alojamiento['id'];
            $rs2 = $conn->query(sprintf("SELECT h.capacidad , h.fecha FROM Habitaciones h WHERE h.fecha BETWEEN '$fechaini' AND '$fechafin' AND h.nombre_alojamiento LIKE '%s'", $conn->real_escape_string($nombreAlojamiento)));
            if($rs2){
                $arrayfecha=array();
                $arraycap=array();
                for($i=0;$i<$rs2->num_rows-1;$i++){
                    $act=$rs2->fetch_assoc();
                    if($act['capacidad']<$nhabitacion){
                        array_push($arrayfecha,$act['fecha']);
                        array_push($arraycap,$act['capacidad']);
                    }
                }
                if(count($arrayfecha)==0&&($rs2->num_rows-1)==$days){
                    $rs5 = $conn->query(sprintf("SELECT h.capacidad, h.fecha FROM Habitaciones h WHERE h.fecha BETWEEN '$fechaini' AND '$fechafin' AND h.nombre_alojamiento LIKE '%s'", $conn->real_escape_string($nombreAlojamiento)));
                    $j=0;
                    
                    while($j<$rs5->num_rows-1){
                        $act=$rs5->fetch_assoc();
                        $capacidad=$act['capacidad'] - $nhabitacion;
                        $rs6 = $conn->query(sprintf("UPDATE Habitaciones SET capacidad = '%d' WHERE fecha ='%s' AND idAlojamiento = '%d'",
                         $capacidad,
                         $conn->real_escape_string($act['fecha']),
                         $id));
                        $j++;
                    }
                    $usuario=$idUsuario['id'];
                    $rs3 = $conn->query(sprintf("INSERT INTO listaAlojamiento(id, idUsuario, nombreAlojamiento, fechaini, fechafin,NumeroHabitacion) VALUES('%d','%s', '%s', '%s', '%s', '%s')"
                        , $conn->insert_id
                        , $conn->real_escape_string($usuario)
                        , $conn->real_escape_string($nombreAlojamiento)
                        , $conn->real_escape_string($fechaini)
                        , $conn->real_escape_string($fechafin)
                        , $conn->real_escape_string($nhabitacion)));
                    if($rs3){
                        $rs->free();
                        $rs1->free();
                        $rs2->free();
                        $rs5->free();
                        $result = "alojamiento.php?diaini=".$fechaini."&estado=InscritoCorrectamente&alojamiento=".$nombreAlojamiento."&diafin=".$fechafin."";
                    }
                    else{
                        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                        exit();
                    }
                }
                else{
                    $diaError="";
                    for($i=0;$i<count($arrayfecha);$i++){
                        $diaError.=$arrayfecha[$i]. ": ".$arraycap[$i]." habitaciones,";
                    }
                    header("Location: alojamiento.php?dia=".$diaError."&estado=NoPlazas&alojamiento=".$nombreAlojamiento."");
                }
            }
            else{
                echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }

    public static function buscaAlojamiento($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM Alojamiento WHERE nombre = '%s'", $conn->real_escape_string($nombre));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $alojamiento = new Alojamiento($fila['nombre'], $fila['precio'], $fila['rutaFoto'], $fila['descripcion'], $fila['descripciondetallada'],null,null);  
                $alojamiento->id = $fila['id'];
                $result = $alojamiento;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    //Crea un Alojamiento
    public static function creaAlojamiento($nombre, $precio, $rutaFoto, $descripcion, $descripciondetallada){
        $alojamiento = self::buscaAlojamiento($nombre);
        if($alojamiento == false){
            $alojamiento = new Alojamiento($nombre, $precio, $rutaFoto, $descripcion, $descripciondetallada,null,null);
        }
        else{
            $alojamiento->nombre = $nombre;
            $alojamiento->precio = $precio;
            $alojamiento->rutaFoto = $rutaFoto;
            $alojamiento->descripcion = $descripcion;
            $alojamiento->descripciondetallada = $descripciondetallada;
        }
        return self::guardaAlojamiento($alojamiento);
    }

    //Lo guarda, si ya existe, lo actualiza
    public static function guardaAlojamiento($alojamiento){
        if ($alojamiento->id != null) {
            return self::actualizaAlojamiento($alojamiento);
        }
        return self::insertaAlojamiento($alojamiento);
    }
    
    //Inserta informacion de un material en la BD
    private static function insertaAlojamiento($alojamiento){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO Alojamiento(nombre, id, descripcion, rutaFoto, descripciondetallada, precio) VALUES ('%s', '%d', '%s', '%s', '%s', '%d')"
        ,$conn->real_escape_string($alojamiento->nombre)
        , $conn->insert_id
        , $conn->real_escape_string($alojamiento->descripcion)
        , $conn->real_escape_string($alojamiento->rutaFoto)
        , $conn->real_escape_string($alojamiento->descripciondetallada)
        , $conn->real_escape_string($alojamiento->precio));
        if ( $conn->query($query) ) {
            $alojamiento->id = $conn->insert_id;
            header("Location: alojamiento.php?estado=exito&alojamiento=$alojamiento->nombre");
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $alojamiento;
    }

    //Actualiza un material
    private static function actualizaAlojamiento($alojamiento){
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Alojamiento  SET  precio='%d', rutaFoto='%s', descripcion='%s', descripciondetallada='%s' WHERE id='%d'"
        , $conn->real_escape_string($alojamiento->precio)
        , $conn->real_escape_string($alojamiento->rutaFoto)
        , $conn->real_escape_string($alojamiento->descripcion)
        , $conn->real_escape_string($alojamiento->descripciondetallada)
        , $alojamiento->id);
        if ($conn->query($query)) {
            if ( $conn->affected_rows != 1) {
                header("Location: alojamiento.php?estado=error&alojamiento=".$alojamiento->nombre."");
            }
            else{
                $result = $alojamiento;
                header("Location: alojamiento.php?estado=actualizado&alojamiento=".$alojamiento->nombre."");
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    //Sacar foto asociado al Alojamiento
    public static function sacarFoto($nombreAlojamiento){

        $conn=Aplicacion::getSingleton()->conexionBd();
        $query=sprintf("SELECT rutaFoto FROM Alojamiento WHERE nombre = '%s'", $conn->real_escape_string($nombreAlojamiento));
        $rs=$conn->query($query);
        if ($rs) {
            $result="<img src='".($rs->fetch_assoc())['rutaFoto']."' alt=''>";
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    public static function actualizarInfoAlojamiento($nombre, $precio, $rutaFoto, $descripcion, $descripciondetallada){
        
       $alojamiento = self::buscaAlojamiento($nombre);
       $alojamiento->precio = $precio;
       $alojamiento->rutaFoto = $rutaFoto;
       $alojamiento->descripcion = $descripcion;
       $alojamiento->descripciondetallada = $descripciondetallada;

        return self::guardaAlojamiento($alojamiento);
    }

    public static function optionAlojamiento(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT * FROM Alojamiento"));
        if($row){
            $ret="";
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $ret.="<option>"."$act[nombre]"."</option>";
            }
            $row->free();
            return $ret;
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }
    public static function CapacidadAlojamiento($nombre, $capacidad, $fecha){
        $capacidadAlojamiento = self::buscaCapacidadAlojamiento($nombre,$fecha);
        if($capacidadAlojamiento == false){
            $capacidadAlojamiento = new Alojamiento($nombre, null, null, null, null, null, null);
            $capacidadAlojamiento->capacidad = $capacidad;
            $capacidadAlojamiento->fecha= $fecha;
        }
        else{
            $capacidadAlojamiento->nombre = $nombre;
            $capacidadAlojamiento->capacidad = $capacidad;
            $capacidadAlojamiento->fecha= $fecha;
        }
        return self::guardaCapacidadAlojamiento($capacidadAlojamiento);
    }

    public static function buscaCapacidadAlojamiento($nombre,  $fecha){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM Habitaciones WHERE nombre_alojamiento = '%s'AND fecha ='%s'"
        , $conn->real_escape_string($nombre)
        , $conn->real_escape_string($fecha));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $capacidadAlojamiento = new Alojamiento($fila['nombre_alojamiento'], null, null, null, null, $fila['capacidad'], $fila['fecha']);
                $capacidadAlojamiento->IDAlojamiento_Main = $fila['ID'];
                $result = $capacidadAlojamiento;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    private static function insertaCapacidadAlojamiento($capacidadAlojamiento){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $rs =$conn->query(sprintf("SELECT id FROM Alojamiento WHERE nombre LIKE '%s'", $conn->real_escape_string($capacidadAlojamiento->nombre)));
        $Alojamiento=$rs->fetch_assoc();
        $id=$Alojamiento['id'];
        $query=sprintf("INSERT INTO Habitaciones(fecha,capacidad, idAlojamiento, nombre_alojamiento ,ID) VALUES ('%s', '%d', '%d', '%s','%d')"
        , $conn->real_escape_string($capacidadAlojamiento->fecha)
        , $conn->real_escape_string($capacidadAlojamiento->capacidad)
        , $id
        , $conn->real_escape_string($capacidadAlojamiento->nombre)
        ,$conn->insert_id);
        if ( $conn->query($query) ) {
            $capacidadAlojamiento->IDAlojamiento_Main = $id;
            header("Location: Alojamiento_Admin.php?estadoCap=exito&nombre=".$capacidadAlojamiento->nombre."&capacidad=".$capacidadCurso->capacidad."&fecha=".$capacidadCurso->fecha."");
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
             }
        return $capacidadAlojamiento;
        }

    //Actualiza la tabla de CapacidadActividad
    private static function actualizaCapacidadAlojamiento($capacidadAlojamiento){
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Habitaciones SET nombre_alojamiento='%s', capacidad='%d', fecha='%s' WHERE ID='%d'"
        , $conn->real_escape_string($capacidadAlojamiento->nombre)
        , $conn->real_escape_string($capacidadAlojamiento->capacidad)
        , $conn->real_escape_string($capacidadAlojamiento->fecha)
        , $capacidadAlojamiento->IDAlojamiento_Main);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                header("Location: Alojamiento_Admin.php?estadoCap=errorAct&nombre=".$capacidadAlojamiento->nombre."&capacidad=".$capacidadAlojamiento->capacidad."&fecha=".$capacidadAlojamiento->fecha."");
            }
            else{
                header("Location: Alojamiento_Admin.php?estadoCap=actualizado&nombre=".$capacidadAlojamiento->nombre."&capacidad=".$capacidadAlojamiento->capacidad."&fecha=".$capacidadAlojamiento->fecha."");
                $result = $capacidadAlojamiento;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    public static function borrarAlojamiento($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=(sprintf("DELETE FROM Alojamiento WHERE nombre= '%s'",$conn->real_escape_string($nombre)));
        $query2=(sprintf("DELETE FROM Habitaciones WHERE nombre_alojamiento= '%s'",$conn->real_escape_string($nombre)));
        $query3=(sprintf("DELETE FROM listaAlojamiento WHERE nombreAlojamiento= '%s'",$conn->real_escape_string($nombre)));
        header("Location: Alojamiento_Admin.php?estadoBorrar=eliminado&nombre=".$nombre."");

    }

    public static function guardaCapacidadAlojamiento($capacidadAlojamiento){
        if ($capacidadAlojamiento->IDAlojamiento_Main != null) {
            return self::actualizaCapacidadAlojamiento($capacidadAlojamiento);
        }
        return self::insertaCapacidadAlojamiento($capacidadAlojamiento);
    }


    public function getDescripcion()
    {
        return $this->descripcion;
    }


    public function getDescripcionDetallada()
    {
        return $this->descripciondetallada;
    }


    public function getPrecio()
    {
        return $this->precio;
    }

    public function getRutaFoto()
    {
        return $this->rutaFoto;
    }

}


?>
