<?php
namespace es\fdi\ucm\aw;

class Actividad
{
    private $IDActividad_Main;

    private $Nombre;

    private $Descripcion;

    private $RutaFoto;

    private $Info;

    private $Curso;

    private $Precio;

    private $Capacidad;

    private $Fecha;

    private $Horas;

    private function __construct($nombre, $descripcion, $rutaFoto, $info, $curso, $precio, $capacidad, $fecha, $Horas)
    {
        $this->Nombre = $nombre;
        $this->Descripcion = $descripcion;
        $this->RutaFoto = $rutaFoto;
        $this->Info = $info;
        $this->Curso = $curso;
        $this->Precio = $precio;
        $this->Capacidad = $capacidad;
        $this->Fecha = $fecha;
        $this->Horas = $Horas;
    }

    //Muestra todas las actividades del BD
    public static function listadoActividades(){
        $contenido=NULL;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();

        $rs = $conn->query("SELECT Nombre FROM Actividades");
        if($rs){
            $row = $rs->fetch_assoc();
            $contenido = "Listado de actividades: ";
            $contenido .= $row['Nombre'];
            for($i=1; $i<$rs->num_rows;$i++){
                $row = $rs->fetch_assoc();
                $contenido.=", ".$row['Nombre']."";
            }
            $contenido.= ".";
            $rs->free();
        }
        else{
	        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	        exit();
        }

        return $contenido;
    }

    //Muestra el horario y el precio de una actividad
    public static function infoActividad(&$tituloPagina, &$tituloCabecera){
        $tituloPagina = htmlspecialchars($_GET["actividad"]);
        $tituloCabecera = strtoupper($tituloPagina);
        $contenidoPrincipal ="";
        
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaActividad=sprintf("SELECT * FROM Actividades A WHERE A.nombre LIKE '%s' "
						, $conn->real_escape_string($tituloPagina));
        $row = $conn->query($tablaActividad);
        if($row){
	        $rs=$row->fetch_assoc();
	        $Cont="<h3><span>Información</span> del curso de "."$tituloPagina"."</h3>
	        <p>"."$rs[info]"."</p>
	        <h3> <span>Horarios </span>disponibles </h3>
	        <p> Lunes a Viernes de 16:00 a 18:00 </p>
	        <p> Sabado y Domingo de 11:30 a 13:30</p>
	        <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
	        <h3> <span>Precios</span> del curso </h3>";
            
	        $row=$conn->query(sprintf("SELECT C.nombre_curso, C.precio, C.horas FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'"
								, $conn->real_escape_string($tituloPagina)));
	        if($row)
	        {
	        	for($i=0;$i<$row->num_rows;$i++){
		    	    $act=$row->fetch_assoc();
                    if($act['horas'] == 0){
                        $Cont.="<p>".$act['nombre_curso'].": ".$act['precio']." €</p>";
                    }
                    else{
                        $Cont.="<p>".$act['nombre_curso']." (".$act['horas']." horas): ".$act['precio']." €</p>";
                    }
		        }
                $contenidoPrincipal = <<<EOS
                    $Cont
                    <h3><span>Fechas</span> de clases disponibles</h3>
                EOS;
                $row->free();
                $contenidoPrincipal .= self::mostrarFechas($tituloPagina);
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
        return $contenidoPrincipal;
    }

    //Muestra las fechas disponibles para inscribirse en un curso de una actividad
    private static function mostrarFechas($tituloPagina){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();

        $Cont=NULL;
        $array1= array();
        $fila = $conn->query(sprintf("SELECT C.Fecha, C.Curso, CUA.Horas FROM CapacidadActividad C JOIN CursosActividades CUA ON C.Curso = CUA.nombre_curso AND C.Nombre = CUA.nombre_actividad WHERE C.Nombre LIKE '%s' AND C.Capacidad > 0"
            , $conn->real_escape_string($tituloPagina)));
        if($fila)
        {
            $clave = "";
            for($i=0;$i<$fila->num_rows;$i++){
                $aux=$fila->fetch_assoc();
                if($aux['Horas'] == 0){
                    $clave = $aux['Curso'];
                }
                else{
                    $clave = $aux['Curso'];
                    $clave .= " (".$aux['Horas']." horas)";
                }
                if(!isset($array1[$clave])){
                    $array1[$clave] = array($aux['Fecha']);
                }
                else{
                    array_push($array1[$clave], $aux['Fecha']);
                }
            }
            foreach($array1 as $key => $value){
                $Cont.="<p>"."$key".": ".$value['0']."";
                $i =0;
                foreach($value as $aux){
                    if($i != 0){
                        $Cont.=", "."$aux"."";
                    }
                    $i++;
                }
                $Cont.= "</p>";
            }
            if(empty($array1)){
                $Cont = "<p>No hay fechas disponibles.</p>";
            }
            $fila->free();
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	        exit();
        }
        return $Cont;
    }

    //Un usuario se inscribe en un curso de una actividad, puede ocurrir: 1.La fecha no es valido 2.No quedan plazan 3.Inscrito correctamente
    public static function inscribirActividad($nombreActividad, $solicitud_dia, $cursoActividad, &$result, $horas){
        $nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : null;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $rs1 = $conn->query(sprintf("SELECT C.Capacidad, C.ID FROM CapacidadActividad C JOIN CursosActividades CUA ON C.Curso = CUA.nombre_curso AND C.Nombre = CUA.nombre_actividad WHERE C.Nombre='%s' AND C.Curso='%s' AND C.Fecha ='%s' AND CUA.horas ='%d'"
                                    , $conn->real_escape_string($nombreActividad)
                                    , $conn->real_escape_string($cursoActividad)
                                    , $conn->real_escape_string($solicitud_dia)
                                    , $conn->real_escape_string($horas)));
        if($rs1){
            if($rs1->num_rows > 0){
                $filaCapacidadActividad = $rs1->fetch_assoc();
                if($filaCapacidadActividad['Capacidad'] > 0){
                    $rs = $conn->query(sprintf("UPDATE CapacidadActividad C SET Capacidad = '%d' WHERE C.ID = '%d'"
                                                , $filaCapacidadActividad['Capacidad'] - 1
                                                , $filaCapacidadActividad['ID']));
                    $rs1->free();                        
                    if($rs){
                        $rs = $conn->query(sprintf("SELECT id FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario)));
                        if($rs){
                            $filaUsuario = $rs->fetch_assoc();
                            $idUsuario = $filaUsuario['id'];
                            $rs->free();
                            $rs = $conn->query(sprintf("INSERT INTO ListaActividades(nombre, ID, dia, idUsuario, curso) VALUES('%s', '%d', '%s', '%s', '%s')"
                            , $conn->real_escape_string($nombreActividad)
                            , $conn->insert_id
                            , $conn->real_escape_string($solicitud_dia)
                            , $conn->real_escape_string($idUsuario)
                            , $conn->real_escape_string($cursoActividad)));
                            if($rs){
                                $result = "actividad.php?curso=".$cursoActividad."&dia=".$solicitud_dia."&estado=InscritoCorrectamente&actividad=".$nombreActividad."";
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
                    else{
                        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                        exit();
                    }
                }
                else{
                    header("Location: actividad.php?curso=".$cursoActividad."&dia=".$solicitud_dia."&estado=capacidadError&actividad=".$nombreActividad."");
                }
            }
            else{
                header("Location: actividad.php?curso=".$cursoActividad."&dia=".$solicitud_dia."&estado=error&actividad=".$nombreActividad."");
            }
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	        exit();
        }
    }

    //Muestra todas las actividades con su nombre, imagen y breve descripcion. Basicamente la informacion del contenido principal de Actividades_Main.php
    public static function actividadMain(){
        $contenidoPrincipal = NULL;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaActividad_Main=sprintf("SELECT * FROM Actividades");
        $rs =$conn->query($tablaActividad_Main);
        $tableCont=NULL;
        if($rs)
        {
            for($i=1;$i<=$rs->num_rows;$i++){
                $fila = $rs->fetch_assoc();
                $row=$conn->query(sprintf("SELECT * FROM Actividades A WHERE A.id = $fila[ID]"));
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

    //Busca una actividad
    public static function buscaActividad($nombre){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM Actividades A WHERE A.Nombre = '%s'", $conn->real_escape_string($nombre));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $actividad = new Actividad( $fila['Nombre'], $fila['Descripcion'], $fila['rutaFoto'], $fila['info'], null, null, null, null, null);
                $actividad->IDActividad_Main = $fila['ID'];
                $result = $actividad;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    //Crea una actividad
    public static function creaActividad($nombre, $descripcion, $rutaFoto, $info){
        $actividad = self::buscaActividad($nombre);
        if($actividad == false){
            $actividad = new Actividad($nombre, $descripcion, $rutaFoto, $info, null, null, null, null, null);
        }
        else{
            $actividad->Nombre = $nombre;
            $actividad->Descripcion = $descripcion;
            $actividad->RutaFoto = $rutaFoto;
            $actividad->Info = $info;
        }
        return self::guardaActividad($actividad);
    }

    //Lo guarda, si ya existe, lo actualiza
    public static function guardaActividad($actividad){
        if ($actividad->IDActividad_Main != null) {
            return self::actualizaActividad($actividad);
        }
        return self::insertaActividad($actividad);
    }
    
    //Inserta informacion de una actividad en la BD
    public static function insertaActividad($nombre, $descripcion, $rutaFoto, $info){
        $actividad = self::buscaActividad($nombre);
        if($actividad == false){
            $actividad = new Actividad($nombre, $descripcion, $rutaFoto, $info, null, null, null, null, null);
            $app=Aplicacion::getSingleton();
            $conn = $app->conexionBd();
            $query=sprintf("INSERT INTO Actividades(ID, Nombre, Descripcion, rutaFoto, info) VALUES ('%d', '%s', '%s', '%s', '%s')"
            , $conn->insert_id
            , $conn->real_escape_string($actividad->Nombre)
            , $conn->real_escape_string($actividad->Descripcion)
            , $conn->real_escape_string($actividad->RutaFoto)
            , $conn->real_escape_string($actividad->Info));
            if ( $conn->query($query) ) {
                $actividad->IDActividad_Main = $conn->insert_id;
                header("Location: Actividad_Admin.php?estadoAct=exito&nombre=".$actividad->Nombre."");
            } else {
                echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        else{
            header("Location: Actividad_Admin.php?estadoAct=error&nombre=".$actividad->Nombre."");
        }

        return $actividad;
    }

    //Actualiza una actividad
    private static function actualizaActividad($actividad){
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Actividades A SET Nombre='%s', Descripcion='%s', rutaFoto='%s', info='%s' WHERE A.ID='%d'"
        , $conn->real_escape_string($actividad->Nombre)
        , $conn->real_escape_string($actividad->Descripcion)
        , $conn->real_escape_string($actividad->RutaFoto)
        , $conn->real_escape_string($actividad->Info)
        , $actividad->IDActividad_Main);
        if ($conn->query($query)) {
            if ( $conn->affected_rows != 1) {
                header("Location: ActualizarActividadAdmin.php?estadoAct=error&actividad=".$actividad->Nombre."");
            }
            else{
                $result = $actividad;
                header("Location: ActualizarActividadAdmin.php?estadoAct=exito&actividad=".$actividad->Nombre."");
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    //Busca una actividad
    public static function buscaCursoActividad($nombre, $curso){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM CursosActividades C WHERE C.nombre_actividad = '%s'AND C.nombre_curso ='%s'"
        , $conn->real_escape_string($nombre)
        , $conn->real_escape_string($curso));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $cursoActividad = new Actividad( $fila['nombre_actividad'], null, null, null, $fila['nombre_curso'], $fila['precio'], null, null, $fila['horas']);
                $cursoActividad->IDActividad_Main = $fila['id_curso'];
                $result = $cursoActividad;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    //Crea un curso asociado a una actividad
    public static function creaCursoActividad($nombre, $curso, $precio, $hora){
        $cursoActividad = self::buscaCursoActividad($nombre, $curso);
        if($cursoActividad == false){
            $cursoActividad = new Actividad($nombre, null, null, null, $curso, $precio, null, null, $hora);
        }
        else{
            $cursoActividad->Nombre = $nombre;
            $cursoActividad->Curso = $curso;
            $cursoActividad->Precio = $precio;
            $cursoActividad->Horas = $hora;
        }
        return self::guardaCursoActividad($cursoActividad);
    }

    //Guarda dicho curso, si ya existe, lo actualiza
    public static function guardaCursoActividad($cursoActividad){
        if ($cursoActividad->IDActividad_Main != null) {
            return self::actualizaCursoActividad($cursoActividad);
        }
        return self::insertaCursoActividad($cursoActividad);
    }

    //Inserta informacion de un curso en la BD
    public static function insertaCursoActividad($nombre, $curso, $precio, $hora){
        $cursoActividad = self::buscaCursoActividad($nombre, $curso);
        if($cursoActividad == false){
            $cursoActividad = new Actividad($nombre, null, null, null, $curso, $precio, null, null, $hora);
            $Id = self::buscaActividad($cursoActividad->Nombre);
            $app=Aplicacion::getSingleton();
            $conn = $app->conexionBd();
            $query=sprintf("INSERT INTO CursosActividades(id_actividad, nombre_actividad, nombre_curso, precio, id_curso, horas) VALUES ('%d', '%s', '%s', '%s', '%d', '%d')"
            , $Id->IDActividad_Main
            , $conn->real_escape_string($cursoActividad->Nombre)
            , $conn->real_escape_string($cursoActividad->Curso)
            , $conn->real_escape_string($cursoActividad->Precio)
            , $conn->insert_id
            , $cursoActividad->Horas);
            if ( $conn->query($query) ) {
                $cursoActividad->IDActividad_Main = $conn->insert_id;
                header("Location: Actividad_Admin.php?estadoCur=exito&nombre=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
            } else {
                echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        else{
            header("Location: Actividad_Admin.php?estadoCur=error&nombre=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
        }
        return $cursoActividad;
    }

    //Actualiza un curso
    private static function actualizaCursoActividad($cursoActividad){
        $Id = self::buscaActividad($cursoActividad->Nombre);
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE  CursosActividades C SET id_actividad='%d', nombre_actividad='%s', nombre_curso='%s', precio='%s', horas ='%d' WHERE C.id_curso='%d'"
        , $Id->IDActividad_Main
        , $conn->real_escape_string($cursoActividad->Nombre)
        , $conn->real_escape_string($cursoActividad->Curso)
        , $conn->real_escape_string($cursoActividad->Precio)
        , $cursoActividad->Horas
        , $cursoActividad->IDActividad_Main);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                header("Location: ActualizarCursoAdmin.php?estadoCur=errorAct&actividad=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
            }
            else{
                header("Location: ActualizarCursoAdmin.php?estadoCur=actualizado&actividad=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
                $result = $cursoActividad;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    //Saca los cursos de una actividad 
    public static function selectCursoHoraActividad($nombreActividad, &$curso, &$hora){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT C.nombre_curso, C.horas FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'", 
                                    $conn->real_escape_string($nombreActividad)));
        if($row){
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $curso .= "<option>".$act['nombre_curso']."</option>";
                $hora .= "<option>".$act['horas']."</option>";
            }
            $row->free();
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }

    //Para el <select> del name='nombre' del FormularioCursoActividadAdmin
    public static function optionActividad(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT * FROM Actividades"));
        if($row){
            $ret="";
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $ret.="<option>"."$act[Nombre]"."</option>";
            }
            $row->free();
            return $ret;
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }

    //Para el <select> del name='curso' del FormularioCursoActividadAdmin
    public static function optionCurso(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT DISTINCT nombre_curso FROM CursosActividades"));
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

        //Para el <select> del name='hora' del FormularioCursoActividadAdmin
        public static function optionHora(){
            $app = Aplicacion::getSingleton();
            $conn = $app->conexionBd();
            $row=$conn->query(sprintf("SELECT DISTINCT horas FROM CursosActividades"));
            if($row){
                $ret="";
                for($i=0;$i<$row->num_rows;$i++){
                    $act=$row->fetch_assoc();
                    $ret.="<option>"."$act[horas]"."</option>";
                }
                $row->free();
                return $ret;
            }else{
                echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
    
    //Listado de cursos disponibles en la BD
    public static function listadoCursos(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();

        $Cont=NULL;
        $array= array();

        $row=$conn->query(sprintf("SELECT C.nombre_actividad, C.nombre_curso, C.precio, C.horas FROM CursosActividades C"));
        if($row){
            for($i=0;$i<$row->num_rows;$i++){
                $aux=$row->fetch_assoc();
                if($aux['horas'] == 0){
                    $valor = "".$aux['nombre_curso']." ";
                }
                else{
                    $valor = "".$aux['nombre_curso']."(".$aux['horas']." horas) ";
                }
                $valor .= "(".$aux['precio']."€)";
                if(!isset($array[$aux['nombre_actividad']])){
                    $array[$aux['nombre_actividad']] = array($valor);
                }
                else{
                    array_push($array[$aux['nombre_actividad']], $valor);
                }
            }
            foreach($array as $key => $value){
                $Cont.="<p>"."$key".": ".$value['0']."";
                $i=0;
                foreach($value as $aux){
                    if($i != 0){
                        $Cont.=", "."$aux"."";
                    }
                    $i++;
                }
                $Cont.= "</p>";
            }
            if(empty($array)){
                $Cont = "<p>No existe ningun curso en la BD. Por favor inserte una</p>";
            }
            $row->free();
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $Cont;
    }

        //Listado de plazas disponibles en la BD
    public static function listadoPlazas(){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();

        $Cont=NULL;
        
        $row=$conn->query(sprintf("SELECT * FROM CapacidadActividad"));
        if($row){
            if($row->num_rows > 0){
                $Cont = "<table >
                            <tr>
                            <th colspan='2'>Nombre</th>
                            <th colspan='2'>Curso</th>
                            <th colspan='2'>Fecha</th>
                            <th colspan='2'>Plazas</th>
                            </tr>";
                for($i=0;$i<$row->num_rows;$i++){
                    $fila = $row->fetch_assoc();
                    $Cont .="<tr>
                                <td>$fila[Nombre]<td>
                                <td>$fila[Curso]<td>
                                <td>$fila[Fecha]<td>
                                <td>$fila[Capacidad]<td>
                                </tr>
                    ";
                }
                $Cont .= "</table>";
            }
            else{
                $Cont = "<p>No existe hay plazas disponibles en la BD. Por favor inserte una</p>";
            }
            $row->free();
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $Cont;
    }

    //Busca una plazas de un curso
    public static function buscaCapacidadCurso($nombre, $curso, $fecha){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM CapacidadActividad C WHERE C.Nombre = '%s'AND C.Curso ='%s' AND C.Fecha ='%s'"
        , $conn->real_escape_string($nombre)
        , $conn->real_escape_string($curso)
        , $conn->real_escape_string($fecha));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $capacidadCurso = new Actividad($fila['Nombre'], null, null, null, $fila['Curso'], null, $fila['Capacidad'], $fila['Fecha'], null);
                $capacidadCurso->IDActividad_Main = $fila['ID'];
                $result = $capacidadCurso;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    //Crea un plazas para un curso asociado a una actividad
    public static function creaCapacidadCurso($nombre, $curso, $capacidad, $fecha){
        $capacidadCurso = self::buscaCapacidadCurso($nombre, $curso, $fecha);
        if($capacidadCurso == false){
            $capacidadCurso = new Actividad($nombre, null, null, null, $curso, null, $capacidad, $fecha, null);
        }
        else{
            $capacidadCurso->Nombre = $nombre;
            $capacidadCurso->Curso = $curso;
            $capacidadCurso->Capacidad = $capacidad;
            $capacidadCurso->Fecha= $fecha;
        }
        return self::guardaCapacidadCurso($capacidadCurso);
    }

    //Guarda dicho capacidad, si ya existe, lo actualiza
    public static function guardaCapacidadCurso($capacidadCurso){
        if ($capacidadCurso->IDActividad_Main != null) {
            return self::actualizaCapacidadCurso($capacidadCurso);
        }
        return self::insertaCapacidadCurso($capacidadCurso);
    }

   //Inserta informacion de las plaszas de un curso en la BD
    public static function insertaCapacidadCurso($capacidadCurso){
        $cursoActividad = self::buscaCursoActividad($capacidadCurso->Nombre, $capacidadCurso->Curso);
        if($cursoActividad){
            $app=Aplicacion::getSingleton();
            $conn = $app->conexionBd();
            $query=sprintf("INSERT INTO CapacidadActividad(ID, Curso, Nombre, Capacidad, Fecha) VALUES ('%d', '%s', '%s', '%d', '%s')"
            , $conn->insert_id
            , $conn->real_escape_string($capacidadCurso->Curso)
            , $conn->real_escape_string($capacidadCurso->Nombre)
            , $conn->real_escape_string($capacidadCurso->Capacidad)
            , $conn->real_escape_string($capacidadCurso->Fecha));
            if ( $conn->query($query) ) {
                $capacidadCurso->IDActividad_Main = $conn->insert_id;
                header("Location: Actividad_Admin.php?estadoCap=exito&nombre=".$capacidadCurso->Nombre."&curso=".$capacidadCurso->Curso."&capacidad=".$capacidadCurso->Capacidad."&fecha=".$capacidadCurso->Fecha."");
            } else {
                echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        else{
            header("Location: Actividad_Admin.php?estadoCap=error&nombre=".$capacidadCurso->Nombre."&curso=".$capacidadCurso->Curso."&capacidad=".$capacidadCurso->Capacidad."&fecha=".$capacidadCurso->Fecha."");
        }
        return $capacidadCurso;
    }

    //Actualiza la tabla de CapacidadActividad
    private static function actualizaCapacidadCurso($capacidadCurso){
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE CapacidadActividad C SET Curso='%s', Nombre='%s', Capacidad='%d', Fecha='%s' WHERE C.ID='%d'"
        , $conn->real_escape_string($capacidadCurso->Curso)
        , $conn->real_escape_string($capacidadCurso->Nombre)
        , $conn->real_escape_string($capacidadCurso->Capacidad)
        , $conn->real_escape_string($capacidadCurso->Fecha)
        , $capacidadCurso->IDActividad_Main);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                header("Location: Actividad_Admin.php?estadoCap=errorAct&nombre=".$capacidadCurso->Nombre."&curso=".$capacidadCurso->Curso."&capacidad=".$capacidadCurso->Capacidad."&fecha=".$capacidadCurso->Fecha."");
            }
            else{
                header("Location: Actividad_Admin.php?estadoCap=actualizado&nombre=".$capacidadCurso->Nombre."&curso=".$capacidadCurso->Curso."&capacidad=".$capacidadCurso->Capacidad."&fecha=".$capacidadCurso->Fecha."");
                $result = $capacidadCurso;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    //Cursos de una actividad para <select>
    public static function cursosDeActividadUno(){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT ID FROM Actividades LIMIT 1"));
        $cont;
        if($row){   
            $aux = $row->fetch_assoc();
            $curso=$conn->query(sprintf("SELECT nombre_curso FROM CursosActividades WHERE id_actividad='%d'",
                $aux['ID']));
            if($curso){
                $cont = "";
                for($i=1; $i<=$curso->num_rows; $i++){
                    $fila = $curso->fetch_assoc();
                    $cont .= "<option>"."$fila[nombre_curso]"."</option>";
                }
                $row->free();
                $curso->free();
            }
            else{
                echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        else{
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $cont;
    }

    //Cursos de una actividad para <select>
    public static function cursosDeActividadDinamico($nombre){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT nombre_curso FROM CursosActividades WHERE nombre_actividad = '%s'"
        , $conn->real_escape_string($nombre)));
        $cont;
        if($row){
            $cont = "";
            for($i=1; $i<=$row->num_rows; $i++){
                $fila = $row->fetch_assoc();
                $cont .= "<option>"."$fila[nombre_curso]"."</option>";
            }
            $row->free();
        }
        else{
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $cont;
    }
    //Cursos de una actividad para <select> por defecto
    public static function cursosDeActividad($nombre, &$hora, &$precio){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT nombre_curso, horas, precio FROM CursosActividades WHERE nombre_actividad = '%s'"
        , $conn->real_escape_string($nombre)));
        $cont;
        if($row){
            $cont = "";
            for($i=1; $i<=$row->num_rows; $i++){
                $fila = $row->fetch_assoc();
                $cont .= "<option>"."$fila[nombre_curso]"."</option>";
                if($i == 1){
                    $hora=$fila['horas'];
                    $precio=$fila['precio'];
                }
            }
            $row->free();
        }
        else{
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $cont;
    }


    //Acontinuacion vienen los getters y los setters
    public function getId()
    {
        return $this->Id;
    }

    public function setId($id)
    {
        $this->Id = $id;

        return $this;
    }

    public function getNombre()
    {
        return $this->Nombre;
    }

    public function setNombre($Nombre)
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    public function setDescripcion($Descripcion)
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    public function getrutaFoto()
    {
        return $this->RutaFoto;
    }

    public function setrutaFoto($RutaFoto)
    {
        $this->RutaFoto = $RutaFoto;

        return $this;
    }

    public function getInfo()
    {
        return $this->Info;
    }

    public function setInfo($Info)
    {
        $this->Info = $Info;

        return $this;
    }

    public function getCurso()
    {
        return $this->Curso;
    }

    public function setCurso($Curso)
    {
        $this->Curso = $Curso;

        return $this;
    }

    public function getPrecio()
    {
        return $this->Precio;
    }

    public function setPrecio($Precio)
    {
        $this->Precio = $Precio;

        return $this;
    }

    public function getCapacidad()
    {
        return $this->Capacidad;
    }

    public function setCapacidad($Capacidad)
    {
        $this->Capacidad = $Capacidad;

        return $this;
    }

    public function getHoras(){
        return $this->Horas;
    }
}
?>
