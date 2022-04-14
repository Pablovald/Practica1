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
                EOS;
		        $row->free();
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
                        $result = "actividad.php?curso=".$cursoActividad."&dia=".$solicitud_dia."&estado=InscritoCorrectamente&actividad=".$nombreActividad."";
                    }
                    else{
                        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                        exit();
                    }
                }
                else{
                    header("Location: actividad.php?curso=".$cursoActividad."&dia=".$solicitud_dia."&estado=NoPlazas&actividad=".$nombreActividad."");
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
                $error = "¡Error al actualizar la actividad:\"$actividad->Nombre\"!";
                header("Location: Actividad_Admin.php?errorActualizarActividad=$error");
            }
            else{
                $result = $actividad;
                $exito = "¡La actividad: \"$actividad->Nombre\" actualizado!";
                header("Location: Actividad_Admin.php?actualizado=$exito");
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
            $mensaje = "¡El curso: \"$cursoActividad->Curso\" asociado al actividad: \"$cursoActividad->Nombre\" se insertó correctamente!";
            header("Location: Actividad_Admin.php?añadidoCurso=$mensaje");
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
                $error = "¡Error al actualizar el curso: \"$cursoActividad->Curso\" asociado al actividad: \"$cursoActividad->Nombre\"!";
                header("Location: Actividad_Admin.php?errorActualizarCurso=$error");
            }
            else{
                $exito = "¡El curso: \"$cursoActividad->Curso\" asociado al actividad: \"$cursoActividad->Nombre\" se actualizó correctamente!";
                header("Location: Actividad_Admin.php?actualizadoCurso=$exito");
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