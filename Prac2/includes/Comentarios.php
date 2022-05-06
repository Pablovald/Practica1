<?php
namespace es\fdi\ucm\aw;

class Comentario{
    private $id;
    private $ubicacion;
    private $idUsuario;
    private $titulo;
    private $texto;
    private $editado;

    private function __construct($idUsuario,$ubicacion,$titulo,$texto,$editado)
    {
        $this->idUsuario= $idUsuario;
        $this->ubicacion= $ubicacion;
        $this->titulo= $titulo;
        $this->texto= $texto;
        $this->editado=$editado;
    }
    
    public static function crea($idUsuario,$ubicacion,$titulo,$texto,$editado){
        $comentario = new Comentario($idUsuario,$ubicacion,$titulo,$texto,$editado);
        return self::guarda($comentario);
    }
    public static function guarda($comentario){
        if ($comentario->id !== null) {
            return self::actualiza($comentario);
        }
        return self::inserta($comentario);
    }
    private static function inserta($comentario){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("INSERT INTO Comentarios(idUsuario,ubicacion,titulo,texto,editado) VALUES ('%s', '%s', '%s','%s', '%d')"
        , $conn->real_escape_string($comentario->idUsuario)
        , $conn->real_escape_string($comentario->ubicacion)
        , $conn->real_escape_string($comentario->titulo)
        , $conn->real_escape_string($comentario->texto)
        , $conn->real_escape_string($comentario->editado));
        if ( $conn->query($query) ) {
            $comentario->id = $conn->insert_id;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $comentario;
    }
    private static function actualiza($comentario){
        $actualizado=false;
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query=sprintf("UPDATE Comentarios E SET titulo = '%s', texto = '%s', editado = '%d' WHERE E.id=%i"
        , $conn->real_escape_string($comentario->titulo)
        , $conn->real_escape_string($comentario->texto)
        , $conn->real_escape_string($comentario->editado)
        , $comentario->id);
    if ( $conn->query($query) ) {
        if ( $conn->affected_rows != 1) {
            echo "No se ha podido actualizar el comentario: " . $comentario->id;
        }
        else{
            $actualizado = $comentario;
        }
    } else {
        echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
    }
    
    return $actualizado;

    }
    public static function mostrarComentario($rs){
        $comentarios=<<<EOS
        <div>
            <img src=$rs[rutaFoto]>
            <p>$rs[titulo]</p>
            <p>$rs[nombreUsuario]</p>
            <p>$rs[texto]</p>
        </div>
        EOS;
        return $comentarios;
    }
    public static function mostrarTodos($ubicacion,&$comentarios){
        $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaComentarios=sprintf("SELECT C.*,U.nombreUsuario,U.rutaFoto FROM Comentarios C RIGHT JOIN Usuarios U ON U.id = C.idUsuario WHERE C.ubicacion = '$ubicacion' ");
        $row=$conn->query($tablaComentarios);
        $numCom=$row->num_rows;
        for($i=0;$i<$numCom;$i++){
            $rs=$row->fetch_assoc();
            $comentarios.=Comentario::mostrarComentario($rs);
        }

        $row->free();
    }
}