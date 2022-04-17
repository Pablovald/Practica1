<?php
namespace es\fdi\ucm\aw;

class Alojamiento
{

    private function __construct()
    {
        
    }

   
    public static function infoAlojamiento(&$tituloPagina, &$tituloCabecera){
        $tituloPagina = htmlspecialchars($_GET["alojamiento"]);
        $tituloCabecera = strtoupper($tituloPagina);
        $contenidoPrincipal ="";
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaActividad=sprintf("SELECT * FROM Alojamiento A WHERE A.nombre LIKE '%s' "
                                , $conn->real_escape_string($tituloPagina));
        $row = $conn->query($tablaActividad);
        if($row){
            $rs=$row->fetch_assoc();
            $Cont="<h3><span>Información detallada</span> del hotel "."$tituloPagina"."</h3>
            <p>"."$rs[descripciondetallada]"."</p>";
            $contenidoPrincipal = <<<EOS
                $Cont
            EOS;
            $row->free();
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $contenidoPrincipal;
    }

    public static function inscribirAlojamiento($nhabitacion, $fechaini, $fechafin,$nombreAlojamiento, &$result){
        $nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : null;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $rs = $conn->query(sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario LIKE '%s'", $conn->real_escape_string($nombreUsuario)));
        $rs1 =$conn->query(sprintf("SELECT id FROM Alojamiento a WHERE a.nombre LIKE '%s'", $conn->real_escape_string($nombreAlojamiento)));
        if($rs&&$rs1){
            $idUsuario=$rs->fetch_assoc();
            $Alojamiento=$rs1->fetch_assoc();
            $id=$Alojamiento['id'];
            $rs2 = $conn->query(sprintf("SELECT h.capacidad , h.fecha FROM Habitaciones h WHERE h.fecha BETWEEN '$fechaini' AND '$fechafin' AND h.idAlojamiento LIKE '%d'", $id));
            if($rs2){
                $arrayfecha=array();
                $arraycap=array();
                for($i=0;i<$rs2->num_rows-1;$i++){
                    $act=$rs2->fetch_assoc();
                    if($act['capacidad']<$nhabitacion){
                        array_push($arrayfecha,$act['fecha']);
                        array_push($arraycap,$act['capacidad']);
                    }
                }
                if(count($arrayfecha)==0){
                    $rs5 = $conn->query(sprintf("SELECT h.capacidad, h.fecha FROM Habitaciones h WHERE h.fecha BETWEEN '$fechaini' AND '$fechafin' AND h.idAlojamiento LIKE '%d'", $id));
                    $j=0;
                    while($j<(($rs5->num_rows) - 1)){
                        $act=$rs5->fetch_assoc();
                        $rs6 = $conn->query(sprintf("UPDATE Habitaciones SET capacidad = '%d' WHERE fecha ='%s' AND idAlojamiento = '%d'",
                         $act['capacidad'] - $nhabitacion,
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
                        $rs = $conn->query(sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario LIKE '%s'", $conn->real_escape_string($nombreUsuario)));
                        $result = "alojamiento.php?diaini=".$fechaini."&estado=InscritoCorrectamente&alojamiento=".$nombreAlojamiento."&diafin=".$fechafin."";
                    }
                    else{
                        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                        exit();
                    }
                }
                else{
                    $diaError="";
                    foreach($arrayfecha as $x ){
                        $diaError+=$x +" ";
                    }
                    header("Location: alojamiento.php?dia=".$diaError."&estado=NoPlazas&alojamiento=".$nombreAlojamiento."&restante=".$habitacionrestante."");
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

    public static function AlojamientoMain(){
        $contenidoPrincipal = NULL;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaAlojamiento_Main=sprintf("SELECT * FROM Alojamiento");
        $rs = $conn->query($tablaAlojamiento_Main);
        $tableCont=NULL;
        if($rs)
        {
	        for($i=1;$i<=$rs->num_rows;$i++){
		        $row=$conn->query(sprintf("SELECT * FROM Alojamiento A WHERE A.id = '$i'"));
		        if($row)
		        {
                    $contenido=$row->fetch_assoc();
                    $url=rawurlencode("$contenido[nombre]");
                    $leftCont =  "<div><td>
                        <a href ="."alojamiento.php?alojamiento=".$url."><img src= '$contenido[rutaFoto]' width='667' height='400'> </a>
                            </td></div>";
                    $rightCont = "<div><td>
                    <h2><a href = "."alojamiento.php?alojamiento=".$url.">"."$contenido[nombre]"." </a></h2>
                        "."$contenido[descripcion]"."
                    <a href = "."alojamiento.php?alojamiento=".$url.">Leer más</a></p>
                    </td></div>";

                    if($i%2==0){
                        $aux=$leftCont;
                        $leftCont=$rightCont;
                        $rightCont=$aux;
                    }
                    $tableCont.="<tr>"."$leftCont"."$rightCont"."</tr>";
                    $row->free();
                }else{
                    echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                    exit();
                }
            }
            $contenidoPrincipal = <<<EOS
            <p>Alojamientos disponibles en SeaWolf Deportes Náuticos. </p>
            
            <table>$tableCont
            </table>
            EOS;
            $rs->free();
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $contenidoPrincipal;
        
    }

    public static function buscaAlojamiento($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM Alojamiento a WHERE a.nombre = '%s'", $conn->real_escape_string($nombre));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $alojamiento = new Alojamiento($fila['nombre'], $fila['precio'], $fila['rutaFoto'], $fila['descripcion'], $fila['descripciondetallada']);  
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
    public static function creaAlojamiento($nombre, $precio, $imagen, $descripcion, $desc_det){
        $alojamiento = self::buscaAlojamiento($nombre);
        if($alojamiento == false){
            $alojamiento = new Alojamiento($nombre, $precio, $imagen, $descripcion, $desc_det);
        }
        else{
            $alojamiento->nombre = $nombre;
            $alojamiento->precio = $precio;
            $alojamiento->imagen = $imagen;
            $alojamiento->descripcion = $descripcion;
            $alojamiento->desc_det = $desc_det;
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
        $query=sprintf("INSERT INTO Materiales(id, nombre, precio, imagen, descripcion, desc_det) VALUES ('%d', '%s', '%d', '%s', '%s', '%s')"
        , $conn->insert_id
        , $conn->real_escape_string($alojamiento->nombre)
        , $conn->real_escape_string($alojamiento->precio)
        , $conn->real_escape_string($alojamiento->imagen)
        , $conn->real_escape_string($alojamiento->descripcion)
        , $conn->real_escape_string($alojamiento->desc_det));
        if ( $conn->query($query) ) {
            $alojamiento->id = $conn->insert_id;
            $mensaje = "El material: $alojamiento->nombre se inserto correctamente";
            header("Location: Materiales.php?añadido=$mensaje");
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
        $query=sprintf("UPDATE Materiales M SET nombre='%s', precio='%d', imagen='%s', descripcion='%s', desc_det='%s' WHERE M.id='%d'"
        , $conn->real_escape_string($alojamiento->nombre)
        , $conn->real_escape_string($alojamiento->precio)
        , $conn->real_escape_string($alojamiento->imagen)
        , $conn->real_escape_string($alojamiento->descripcion)
        , $conn->real_escape_string($alojamiento->desc_det)
        , $alojamiento->id);
        if ($conn->query($query)) {
            if ( $conn->affected_rows != 1) {
                header("Location: Materiales.php?estadoAct=error&nombre=".$alojamiento->nombre."");
            }
            else{
                $result = $alojamiento;
                header("Location: Materiales.php?estadoAct=exito&nombre=".$alojamiento->nombre."");
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }


}
?>
