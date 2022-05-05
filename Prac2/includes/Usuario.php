<?php
namespace es\fdi\ucm\aw;

class Usuario
{
    private $id;

    private $nombreUsuario;

    private $nombre;

    private $apellido;

    private $password;

    private $rol;

    private $FechaNac;

    private $Telefono;

    private $Nacionalidad;

    private $RutaFoto;

    private $correo;

    private function __construct($nombreUsuario, $nombre, $apellido, $password, $rol, $FechaNac, $Telefono, $Nacionalidad, $RutaFoto, $correo)
    {
        $this->nombreUsuario= $nombreUsuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->password = $password;
        $this->rol = $rol;
        $this->FechaNac = $FechaNac;
        $this->Telefono = $Telefono;
        $this->Nacionalidad = $Nacionalidad;
        $this->RutaFoto = $RutaFoto;
        $this->correo = $correo;
    }

    //Muestra el perfil de un usuario
    public static function perfilUsuario($nombreUsuario){
        $usuario = self::buscaUsuario($nombreUsuario);
        $listado = self::infoUsuario($nombreUsuario);
        $contenidoPrincipal = "
        <div class='perfil'>
            <div class='header'>
                <div class='portada'>
                        <img class = 'avatar' src='". $usuario->RutaFoto."' alt='Foto'>
                </div>
            </div>
            <div class='body'>
                <div class='bio'>
                <h3>¡Bienvenido ". $usuario->nombreUsuario." a tu perfil!</h3>
                <p>Descripción detalla del usuario</p>
                <div class='datos1'>
                    <li><span>Nombre: </span>". $usuario->nombre."</li>
                    <li><span>Apellido: </span>". $usuario->apellido."</li>
                    <li><span>Correo: </span>".  $usuario->correo."</li>
                </div>
                <div class='datos2'>
                    <li><span>Telefono: </span>".  $usuario->Telefono."</li>
                    <li><span>Nacionalidad: </span>".  $usuario->Nacionalidad."</li>
                    <li><span>Fecha de nacimiento: </span>".  $usuario->FechaNac."</li>
                </div>
                <div class='datos3'>
                    <a class='adatos3' href='Perfil.php?editar=true'>Editar perfil <img class='icon-datos3' src='img/editar.png'></a>
                </div>
                    </div>
                <div class='footer'>
                    $listado
                </div>
                <div class='datos'>
                    <h1>Valoraciones</h1>
                    <p>Valoración 1</p>
                    <p>Valoracion 2</p>
                </div>
            </div>
            </div>
            </div>
            
        </div>
        ";

        return $contenidoPrincipal;
    }

    //Si no es admin, muestra todas las actividades y hoteles reservados por el usuario
    //En caso contrario muestra diferentes enlaces para añadir por ejemplo nuevas actividades
    public static function infoUsuario($nombreUsuario){
        $usuario = self::buscaUsuario($nombreUsuario);
        $contenido;
        if(!$_SESSION['esAdmin']){
            $app = Aplicacion::getSingleton();
            $conn = $app->conexionBd();
            $rs = $conn->query(sprintf("SELECT * FROM ListaActividades LA WHERE LA.idUsuario = '%d'", $usuario->id));
            if($rs){
                $textoActividad = "<h1>Listado de <span>actividades</span> inscritas</h1>";
                if($rs->num_rows == 0){
                    $textoActividad .= "<p>No has inscrito en ninguna de las actividades</p>";
                }
                else{
                    for($i=0;$i<$rs->num_rows;$i++){
                        $act=$rs->fetch_assoc();
                        $textoActividad.="<p>$act[nombre]: $act[curso] en el dia $act[dia]</p>";
                    }
                }
                $rs->free();
                $contenido = $textoActividad;
                $rs = $conn->query(sprintf("SELECT * FROM listaAlojamiento LA WHERE LA.idUsuario = '%d'", $usuario->id));
                if($rs){
                    $textoAlojamiento = "<h1>Listado de <span>hoteles</span> reservados</h1>";
                    if($rs->num_rows == 0){
                        $textoAlojamiento .= "<p>No has inscrito en ningun hotel</p>";
                    }
                    else{
                        for($i=0;$i<$rs->num_rows;$i++){
                            $act=$rs->fetch_assoc();
                            $habitaciones = $act["NumeroHabitacion"];
                            if($habitaciones > 1){
                                $textoAlojamiento.="<p>$act[nombreAlojamiento]: $act[fechaini] - $act[fechafin] ($habitaciones habitaciones)</p>";
                            }
                            else{
                                $textoAlojamiento.="<p>$act[nombreAlojamiento]: $act[fechaini] - $act[fechafin] ($habitaciones habitación)</p>";
                            }
                        }
                    }
                    $rs->free();
                    $contenido .= $textoAlojamiento;
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
            $contenido = "<h1>Enlace para <span>añadir</span></h1>";
            $contenido .= "<div class='submit'>
							<a href=editor.php>
							<button type='submit' name='Entrada'>Entrada de un blog</button>
                            </a>
						</div>
						<div class='submit'>
                            <a href=Actividad_Admin.php>
							<button type='submit' name='Actividad'>Actividad</button>
                            </a>
						</div>
						<div class='submit'>
                            <a href=Material_Admin.php>
							<button type='submit' name='Material'>Material</button>
                            </a>
						</div>
							<div class='submit'>
                            <a href=Alojamiento_Admin.php>
							<button type='submit' name='Alojamiento'>Alojamiento</button>
                            </a>
						</div>
                            ";
        }
        return $contenido;
    }

    public static function login($nombreUsuario, $password)
    {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario;
        }
        return false;
    }

    public static function buscaUsuario($nombreUsuario)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf("SELECT * FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $user = new Usuario($fila['nombreUsuario'], $fila['nombre'], $fila['Apellido'], $fila['password'], $fila['rol'], $fila['FechaNac'],
                                    $fila['Telefono'], $fila['Nacionalidad'], $fila['rutaFoto'], $fila['Correo']);
                $user->id = $fila['id'];
                $result = $user;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }

    public static function crea($nombreUsuario, $nombre, $password, $rol)
    {
        $user = self::buscaUsuario($nombreUsuario);
        if ($user) {
            return false;
        }
        $user = new Usuario($nombreUsuario, $nombre, null, self::hashPassword($password), $rol, null, null, null, null, null);
        return self::guarda($user);
    }

    //Actualiza los datos del Usuaroo
    public static function actualizaPerfil($nombreUsuario, $nombre, $apellido, $FechaNac, $Telefono, $Nacionalidad, $RutaFoto, $correo)
    {
        $result=false;
        $usuario = self::buscaUsuario($nombreUsuario);
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Usuarios U SET nombre = '%s', apellido='%s', FechaNac='%s', Telefono='%s', Nacionalidad='%s', RutaFoto='%s', correo='%s' WHERE U.id='%d'"
            , $conn->real_escape_string($nombre)
            , $conn->real_escape_string($apellido)
            , $conn->real_escape_string($FechaNac)
            , $conn->real_escape_string($Telefono)
            , $conn->real_escape_string($Nacionalidad)
            , $conn->real_escape_string($RutaFoto)
            , $conn->real_escape_string($correo)
            , $usuario->id);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                header("Location: Perfil.php?editar=true&estado=error");
            }
            else{
                $result = $usuario;
                header("Location: Perfil.php?editar=true&estado=exito");
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        
        return $result;
    }

    
    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function guarda($usuario)
    {
        if ($usuario->id != null) {
            return self::actualiza($usuario);
        }
        return self::inserta($usuario);
    }

    private static function inserta($usuario)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO Usuarios(nombreUsuario, nombre, password, rol) VALUES('%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->rol));
        if ( $conn->query($query) ) {
            $usuario->id = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $usuario;
    }
    
    private static function actualiza($usuario)
    {
        $result = false;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Usuarios U SET nombreUsuario = '%s', nombre='%s', password='%s', rol='%s' WHERE U.id='%d'"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->rol)
            , $usuario->id);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                echo "No se ha podido actualizar el usuario: " . $usuario->id;
            }
            else{
                $result = $usuario;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }
        
        return $result;
    }
   
    private static function borra($usuario)
    {
        return self::borraPorId($usuario->id);
    }
    
    private static function borraPorId($idUsuario)
    {
        if (!$idUsuario) {
            return false;
        } 

        $app = Aplicacion::getInstancia();
        $conn = $app->conexionBd();
        $query = sprintf("DELETE FROM Usuarios U WHERE U.id = %d", $idUsuario);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getFechaNac()
    {
        return $this->FechaNac;
    }

    public function getTelefono()
    {
        return $this->Telefono;
    }

    public function getNacionalidad()
    {
        return $this->Nacionalidad;
    }

    public function getRutaFoto()
    {
        $result = $this->RutaFoto;
        if(!isset($result)){
            $result = "";
        }
        return $this->RutaFoto;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function compruebaPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }
    
    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}