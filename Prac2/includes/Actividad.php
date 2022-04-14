<head>
<link rel="stylesheet" type="text/css" href="FormularioStyle.css" />
</head>

<?php
//namespace es\fdi\ucm\aw;
require_once __DIR__.'/Aplicacion.php';

class Actividad
{
    private $IDActividad_Main;

    private $Nombre;

    private $Descripcion;

    private $RutaFoto;

    private $Info;

    private $Curso;

    private $Precio;

    private function __construct($nombre, $descripcion, $rutaFoto, $info, $curso, $precio)
    {
        $this->Nombre = $nombre;
        $this->Descripcion = $descripcion;
        $this->RutaFoto = $rutaFoto;
        $this->Info = $info;
        $this->Curso = $curso;
        $this->Precio = $precio;
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
            
	        $row=$conn->query(sprintf("SELECT C.nombre_curso,C.precio FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'"
								, $conn->real_escape_string($tituloPagina)));
	        if($row)
	        {
	        	for($i=0;$i<$row->num_rows;$i++){
		    	    $act=$row->fetch_assoc();
		    	    $Cont.="<p>"."$act[nombre_curso]".": "."$act[precio]"." €</p>";
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
        $fila = $conn->query(sprintf("SELECT C.Fecha, C.Curso FROM CapacidadActividad C WHERE C.Nombre LIKE '%s' AND C.Capacidad > 0"
            , $conn->real_escape_string($tituloPagina)));
        if($fila)
        {
            for($i=0;$i<$fila->num_rows;$i++){
                $aux=$fila->fetch_assoc();
                if(!isset($array1[$aux['Curso']])){
                    $array1[$aux['Curso']] = array($aux['Fecha']);
                }
                else{
                    array_push($array1[$aux['Curso']], $aux['Fecha']);
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

    //Un usuario se inscribe en un curso de una actividad, puede ocurrir dos cosas: 1.La fecha no es valido 2.Se inscribe correctamente
    public static function inscribirActividad($nombreActividad, $solicitud_dia, $cursoActividad, &$result){
        $nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : null;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $rs1 = $conn->query(sprintf("SELECT C.Capacidad, C.ID FROM CapacidadActividad C WHERE C.Nombre='%s' AND C.Curso='%s' AND C.Fecha ='%s'"
                                    , $conn->real_escape_string($nombreActividad)
                                    , $conn->real_escape_string($cursoActividad)
                                    , $conn->real_escape_string($solicitud_dia)));
        if($rs1){
            if($rs1->num_rows > 0){
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
                        $filaCapacidadActividad = $rs1->fetch_assoc();
                        $rs = $conn->query(sprintf("UPDATE CapacidadActividad C SET Capacidad = '%d' WHERE C.ID = '%d'"
                                                    , $filaCapacidadActividad['Capacidad'] - 1
                                                    , $filaCapacidadActividad['ID']));
                        $rs1->free();                        
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
                header("Location: actividad.php?curso=".$cursoActividad."&dia=".$solicitud_dia."&estado=fechaError&actividad=".$nombreActividad."");
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
                $row=$conn->query(sprintf("SELECT * FROM Actividades A WHERE A.id = '$i'"));
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
                $actividad = new Actividad( $fila['Nombre'], $fila['Descripcion'], $fila['rutaFoto'], $fila['info'], null, null);
                $actividad->Id = $fila['ID'];
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
            $actividad = new Actividad($nombre, $descripcion, $rutaFoto, $info, null, null);
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
        if ($actividad->Id != null) {
            return self::actualizaActividad($actividad);
        }
        return self::insertaActividad($actividad);
    }
    
    //Inserta informacion de una actividad en la BD
    private static function insertaActividad($actividad){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO Actividades(ID, Nombre, Descripcion, rutaFoto, info) VALUES ('%d', '%s', '%s', '%s', '%s')"
        , $conn->insert_id
        , $conn->real_escape_string($actividad->Nombre)
        , $conn->real_escape_string($actividad->Descripcion)
        , $conn->real_escape_string($actividad->RutaFoto)
        , $conn->real_escape_string($actividad->Info));
        if ( $conn->query($query) ) {
            $actividad->id = $conn->insert_id;
            $mensaje = "La actividad: $actividad->Nombre se inserto correctamente";
            header("Location: Actividad_Admin.php?añadido=$mensaje");
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
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
        , $actividad->Id);
        if ($conn->query($query)) {
            if ( $conn->affected_rows != 1) {
                header("Location: Actividad_Admin.php?estadoAct=error&nombre=".$actividad->Nombre."");
            }
            else{
                $result = $actividad;
                header("Location: Actividad_Admin.php?estadoAct=exito&nombre=".$actividad->Nombre."");
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
                $cursoActividad = new Actividad( $fila['nombre_actividad'], null, null, null, $fila['nombre_curso'], $fila['precio']);
                $cursoActividad->Id = $fila['id_curso'];
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
    public static function creaCursoActividad($nombre, $curso, $precio){
        $cursoActividad = self::buscaCursoActividad($nombre, $curso);
        if($cursoActividad == false){
            $cursoActividad = new Actividad($nombre, null, null, null, $curso, $precio);
        }
        else{
            $cursoActividad->Nombre = $nombre;
            $cursoActividad->Curso = $curso;
            $cursoActividad->Precio = $precio;
        }
        return self::guardaCursoActividad($cursoActividad);
    }

    //Guarda dicho curso, si ya existe, lo actualiza
    public static function guardaCursoActividad($cursoActividad){
        if ($cursoActividad->Id != null) {
            return self::actualizaCursoActividad($cursoActividad);
        }
        return self::insertaCursoActividad($cursoActividad);
    }

    //Inserta informacion de una actividad en la BD
    private static function insertaCursoActividad($cursoActividad){
        $Id = self::buscaActividad($cursoActividad->Nombre);
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO CursosActividades(id_actividad, nombre_actividad, nombre_curso, precio, id_curso) VALUES ('%d', '%s', '%s', '%s', '%d')"
        , $Id->Id
        , $conn->real_escape_string($cursoActividad->Nombre)
        , $conn->real_escape_string($cursoActividad->Curso)
        , $conn->real_escape_string($cursoActividad->Precio)
        , $conn->insert_id);
        if ( $conn->query($query) ) {
            $cursoActividad->id = $conn->insert_id;
            header("Location: Actividad_Admin.php?estadoCur=exito&nombre=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $cursoActividad;
    }

    //Actualiza una actividad
    private static function actualizaCursoActividad($cursoActividad){
        $Id = self::buscaActividad($cursoActividad->Nombre);
        $result=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE  CursosActividades C SET id_actividad='%d', nombre_actividad='%s', nombre_curso='%s', precio='%s' WHERE C.id_curso='%d'"
        , $Id->Id
        , $conn->real_escape_string($cursoActividad->Nombre)
        , $conn->real_escape_string($cursoActividad->Curso)
        , $conn->real_escape_string($cursoActividad->Precio)
        , $cursoActividad->Id);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                header("Location: Actividad_Admin.php?estadoCur=errorAct&nombre=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
            }
            else{
                header("Location: Actividad_Admin.php?estadoCur=actualizado&nombre=".$cursoActividad->Nombre."&curso=".$cursoActividad->Curso."");
                $result = $cursoActividad;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        return $result;
    }

    //Saca los cursos de una actividad 
    public static function cursoActividad($nombreActividad){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT C.nombre_curso FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'", 
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

    //Para el <select> del FormularioActividad
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
}