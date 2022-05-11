<?php

namespace es\fdi\ucm\aw;
require_once __DIR__.'/GeneraVistas.php';
class Comentario
{
    protected $id;
    protected $ubicacion;
    protected $idUsuario;
    protected $titulo;
    protected $texto;
    protected $editado;

    public function __construct($idUsuario, $ubicacion, $titulo, $texto, $editado)
    {
        $this->idUsuario = $idUsuario;
        $this->ubicacion = $ubicacion;
        $this->titulo = $titulo;
        $this->texto = $texto;
        $this->editado = $editado;
    }

    public static function crea($idUsuario, $ubicacion, $titulo, $texto, $editado)
    {
        $comentario = new Comentario($idUsuario, $ubicacion, $titulo, $texto, $editado);
        return self::guarda($comentario);
    }
    public static function guarda($comentario)
    {
        if ($comentario->id !== null) {
            return self::actualiza($comentario);
        }
        return self::inserta($comentario);
    }
    private static function inserta($comentario)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf(
            "INSERT INTO Comentarios(idUsuario,ubicacion,titulo,texto,editado) VALUES ('%s', '%s', '%s','%s', '%d')",
            $conn->real_escape_string($comentario->idUsuario),
            $conn->real_escape_string($comentario->ubicacion),
            $conn->real_escape_string($comentario->titulo),
            $conn->real_escape_string($comentario->texto),
            $conn->real_escape_string($comentario->editado)
        );
        if ($conn->query($query)) {
            $comentario->id = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $comentario;
    }
    private static function actualiza($comentario)
    {
        $actualizado = false;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf(
            "UPDATE Comentarios E SET titulo = '%s', texto = '%s', editado = '%d' WHERE E.id=%d",
            $conn->real_escape_string($comentario->titulo),
            $conn->real_escape_string($comentario->texto),
            $conn->real_escape_string($comentario->editado),
            $comentario->id
        );
        if ($conn->query($query)) {
            if ($conn->affected_rows != 1) {
                echo "No se ha podido actualizar el comentario: " . $comentario->id;
            } else {
                $actualizado = $comentario;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }

        return $actualizado;
    }

    public static function mostrarTodos($ubicacion)
    {
        $comentarios = "";
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaComentarios = sprintf("SELECT C.*,U.nombreUsuario,U.rutaFoto FROM Comentarios C RIGHT JOIN Usuarios U ON U.id = C.idUsuario WHERE C.ubicacion = '$ubicacion' ");
        $row = $conn->query($tablaComentarios);
        $numCom = $row->num_rows;
        for ($i = 0; $i < $numCom; $i++) {
            $rs = $row->fetch_assoc();
            $user=new Usuario($rs['nombreUsuario'],null,null,null,null,null,null,null,$rs['rutaFoto'],null);
            $comentarios .= mostrarComentarioBlog(Comentario::buscaComentarioPorId($rs['id']),$user);
        }

        $row->free();
        return $comentarios;
    }
   
    public static function mostrarTodosPerfil($idUsuario)
    {
        $comentarios = "";
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaComentarios = sprintf("SELECT C.* FROM Comentarios C JOIN Usuarios U ON C.idUsuario = U.id WHERE $idUsuario = C.idUsuario ");
        $row = $conn->query($tablaComentarios);
        $numCom = $row->num_rows;
        for ($i = 0; $i < $numCom; $i++) {
            $rs = $row->fetch_assoc();
            $com=new Comentario($rs['idUsuario'],$rs['ubicacion'],$rs['titulo'],$rs['texto'],$rs['editado']);
            $comentarios .= mostrarComentarioPerfil($com);
        }

        $row->free();
        return $comentarios;
    }
    public static function buscaComentarioPorId($id)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaComentarios = sprintf("SELECT C.* FROM Comentarios C WHERE $id = C.id ");
        $row = $conn->query($tablaComentarios);
        $rs = $row->fetch_assoc();
        $com=new Comentario($rs['idUsuario'],$rs['ubicacion'],$rs['titulo'],$rs['texto'],$rs['editado']);
        $com->id = $id;
        $row->free();
        return $com;
    }
    public static function permisoEdicion($id)
    {
        $permiso = false;
        $coment=self::buscaComentarioPorId($id);
        if (Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']) == $coment->getIdUsuario()) {
            $permiso = true;
        }
        return $permiso;
    }
    public static function permisoEliminar($id)
    {
        $permiso = false;
        $coment=Comentario::buscaComentarioPorId($id);
        if (Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']) == $coment->getIdUsuario() 
        || Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario'])==0 ) {
            $permiso = true;
        }
        return $permiso;
    }
    public static function borraComentario($id){
        if(isset($_POST['Eliminar'])){}
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = (sprintf("DELETE FROM Comentarios C WHERE $id = C.id"));
        if($conn->query($query)){
            $ret="Comentario eliminada con éxito.";
        }else {
            echo "Error al eliminar de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $ret;
    }
    //getters
    /**
     * Get the value of ubicacion
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Get the value of titulo
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Get the value of texto
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Get the value of editado
     */
    public function getEditado()
    {
        return $this->editado;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}
