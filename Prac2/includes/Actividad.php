<?php
//namespace es\fdi\ucm\aw;
require_once __DIR__.'/Aplicacion.php';

class Actividad
{

    private function __construct()
    {
        
    }

    public static function cursoActividad($nombreActividad){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT C.nombre_curso,C.precio FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'", 
                                    $conn->real_escape_string($nombreActividad)));
        if($row){
            $ret="";
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $ret.="<option>"."$act[nombre_curso]"."</option>";
            }
            $row->free();
            return $ret;
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }

    public static function inscribirActividad($nombreActividad, $solicitud_dia, $cursoActividad, &$result){
        $nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : null;

        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $rs = $conn->query(sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario)));
        $rs1 = $conn->query(sprintf("SELECT * FROM ListaActividades"));
        if($rs && $rs1){
            $capacidad = sprintf("SELECT * FROM ListaActividades LA WHERE LA.dia = '%s' AND LA.nombre = '%s' AND LA.curso = '%s'"
                , $conn->real_escape_string($solicitud_dia)
                , $conn->real_escape_string($nombreActividad)
                , $conn->real_escape_string($cursoActividad));
            $rs2 = $conn->query($capacidad);
            if($rs2){
                if($rs2->num_rows < 5){
                    $usuario = $rs->fetch_assoc();
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

    public static function actividadMain(){
        $contenidoPrincipal = NULL;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaActividad_Main=sprintf("SELECT * FROM Actividad_Main");
        $rs =$conn->query($tablaActividad_Main);
        $tableCont=NULL;
        if($rs)
        {
            for($i=1;$i<=$rs->num_rows;$i++){
                $row=$conn->query(sprintf("SELECT * FROM Actividad_Main A WHERE A.id = '$i'"));
                if($row)
                {
                    $contenido=$row->fetch_assoc();
                    $url=rawurlencode("$contenido[Nombre]");
                    $leftCont =  "<div><td>
                        <a href ="."actividad.php?actividad=".$url."><img src= '$contenido[rutaFoto]' width='667' height='400'> </a>
                        </td></div>";
                    $rightCont = "<div><td>
                        <h2><a href = "."actividad.php?actividad=".$url.">"."$contenido[Nombre]"." </a></h2>
                        "."$contenido[Descripcion]"."
                        <a href = "."actividad.php?actividad=".$url.">Leer más</a></p>
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
            <p> Clases Disponibles en SeaWolf Deportes Náuticos. </p>
            <table>$tableCont
            	</table>
            EOS;
            $rs->free();
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $contenidoPrincipal;
    }
    
}