<?php

namespace es\fdi\ucm\aw;
require_once __DIR__.'/GeneraVistas.php';
class Valoracion extends Comentario
{
    private $nota;

    public function __construct($idUsuario, $ubicacion, $titulo, $texto, $editado, $nota)
    {
        parent::__construct($idUsuario, $ubicacion, $titulo, $texto, $editado);
        $this->nota = $nota;
    }
    public static function creaV($idUsuario, $ubicacion, $titulo, $texto, $editado, $nota)
    {
        $valoracion = new Valoracion($idUsuario, $ubicacion, $titulo, $texto, $editado, $nota);
        return self::guarda($valoracion);
    }
    public static function guarda($valoracion)
    {
        if ($valoracion->id !== null) {
            return self::actualiza($valoracion);
        }
        return self::inserta($valoracion);
    }
    private static function inserta($valoracion)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf(
            "INSERT INTO Valoraciones(idUsuario,ubicacion,titulo,texto,editado,nota) VALUES ('%s', '%s', '%s','%s', '%d','%f')",
            $conn->real_escape_string($valoracion->idUsuario),
            $conn->real_escape_string($valoracion->ubicacion),
            $conn->real_escape_string($valoracion->titulo),
            $conn->real_escape_string($valoracion->texto),
            $conn->real_escape_string($valoracion->editado),
            $conn->real_escape_string($valoracion->nota)
        );
        if ($conn->query($query)) {
            $valoracion->id = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $valoracion;
    }
    private static function actualiza($valoracion)
    {
        $actualizado = false;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = sprintf(
            "UPDATE Valoraciones E SET titulo = '%s', texto = '%s', editado = '%d', nota = '%f' WHERE E.id=%d",
            $conn->real_escape_string($valoracion->titulo),
            $conn->real_escape_string($valoracion->texto),
            $conn->real_escape_string($valoracion->editado),
            $conn->real_escape_string($valoracion->nota),
            $valoracion->id
        );
        if ($conn->query($query)) {
            if ($conn->affected_rows != 1) {
                echo "No se ha podido actualizar la valoracion: " . $valoracion->id;
            } else {
                $actualizado = $valoracion;
            }
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        }

        return $actualizado;
    }
    public static function buscaValoracionPorId($id)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaValoraciones = sprintf("SELECT C.* FROM Valoraciones C WHERE $id = C.id ");
        $row = $conn->query($tablaValoraciones);
        $rs = $row->fetch_assoc();
        $val=new Valoracion($rs['idUsuario'],$rs['ubicacion'],$rs['titulo'],$rs['texto'],$rs['editado'],$rs['nota']);
        $val->id = $id;
        $row->free();
        return $val;
    }
    //verifica si el usuario logeado puede editar/eliminar las valoraciones
    public static function permisoEdicion($id)
    {
        $permiso = false;
        $valoracion=self::buscaValoracionPorId($id);
        if (Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']) == $valoracion->getIdUsuario()) {
            $permiso = true;
        }
        return $permiso;
    }
    public static function permisoEliminar($id)
    {
        $permiso = false;
        $valoracion=self::buscaValoracionPorId($id);
        if (Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']) == $valoracion->getIdUsuario() 
        || Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario'])==0 ) {
            $permiso = true;
        }
        return $permiso;
    }
    //elimina la valoracion de la bbdd
    public static function borraValoracion($id){
        if(isset($_POST['Eliminar'])){}
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = (sprintf("DELETE FROM Valoraciones  WHERE $id = id"));
        if($conn->query($query)){
            $ret="Valoracion eliminada con éxito.";
        }else {
            echo "Error al eliminar de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $ret;
    }
    //saca la media de las notas de las valoraciones de un producto
    public static function notaMedia($ubicacion){
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaValoraciones = sprintf("SELECT V.*,U.nombreUsuario,U.rutaFoto FROM Valoraciones V RIGHT JOIN Usuarios U ON U.id = V.idUsuario WHERE V.ubicacion = '$ubicacion' ");
        $row = $conn->query($tablaValoraciones);
        $numCom = $row->num_rows;
        $sum=0;
        for($i=0;$i<$numCom;$i++){
            $rs=$row->fetch_assoc();
            $sum+=$rs['nota'];
        }
        return $numCom==0?0:$sum/$numCom;
    }

    /**
     * Get the value of nota
     */ 
    public function getNota()
    {
        return $this->nota;
    }
}
