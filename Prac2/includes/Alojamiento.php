<?php
//namespace es\fdi\ucm\aw;
require_once __DIR__.'/Aplicacion.php';

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
            $Cont="<h3>Información detallada del hotel "."$tituloPagina".":</h3>
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
        $rs = $conn->query(sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario)));
        $rs1 = $conn->query(sprintf("SELECT * FROM ListaActividades"));
        if($rs && $rs1){
            $idUsuario=$rs->fetch_assoc();
            $capacidad = sprintf("SELECT * FROM ListaActividades LA WHERE LA.dia = '%s' AND LA.nombre = '%s' AND LA.curso = '%s'"
                , $conn->real_escape_string($solicitud_dia)
                , $conn->real_escape_string($nombreActividad)
                , $conn->real_escape_string($cursoActividad));
            $rs2 = $conn->query($capacidad);
            if($rs2){
                if($rs2->num_rows < 5){
                    $IDActividad = $rs1->num_rows + 1;
                    $idUsuario = $usuario['id'];
                    $insertarActividad=sprintf("INSERT INTO ListaActividades(nombre, ID, dia, idUsuario, curso) VALUES('%s', '%s', '%s', '%s', '%s')"
                        , $conn->real_escape_string($nombreActividad)
                        , $conn->real_escape_string($IDActividad)
                        , $conn->real_escape_string($solicitud_dia)
                        , $conn->real_escape_string($idUsuario)
                        , $conn->real_escape_string($cursoActividad));
                    $rs3 = $conn->query($insertarActividad);
                    if($rs3){
                        $rs1->free();
                        $rs2->free();
                        $result = 'home.php';
                    }
                    else{
                        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                        exit();
                    }
                }
                else{
                    $result[] = " ".$nombreActividad." del ".$cursoActividad." en ".$solicitud_dia." están agotados, por favor seleccione otra fecha";
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
}