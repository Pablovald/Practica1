<?php

namespace es\fdi\ucm\aw;

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
            "INSERT INTO Valoraciones(idUsuario,ubicacion,titulo,texto,editado,nota) VALUES ('%s', '%s', '%s','%s', '%d','%d')",
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
            "UPDATE Valoraciones E SET titulo = '%s', texto = '%s', editado = '%d', nota = '%d' WHERE E.id=%d",
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
    public static function mostrarValoracion($rs)
    {
        $edieli = "";
        if (isset($_SESSION['login']) && $_SESSION['login'] && self::permisoEdicion($rs['id'])) {
            $edieli = "
        <form action='EdicionValoracion.php' method='post'>
        <input type='hidden' name='id' value='$rs[id]'>
        <button class='boton-link' type='submit'>Editar</button>
        </form>
        <form action='EliminarValoracion.php' method='post'>
        <input type='hidden' name='id' value='$rs[id]'>
        <button class='boton-link' type='submit'>Eliminar</button>
        </form>";
        }
        $comentarios = "
		<div class='contenedor'>
        <div class='caja-comentario'>
			<div class='caja-top-comentario'>
				<div class='perfil-comentario'>
					<div class='foto-comentario'>
						<img class='foto-comentario-img' src=$rs[rutaFoto]>
					</div>
					<div class='nombre-user-cometario'>
						<h1>$rs[titulo]</h1>
                        ".parent::editado($rs['editado'])."
						<p class='comen'>@$rs[nombreUsuario]</p>
					</div>
                    <div class='nota-fijo'>
                    ".self::mostrarEstrellasFijo($rs['nota'])."   
                    </div>
				</div>
				<div class='reseñas-comentario'>
				</div>
			</div>
			<div class='comentarios-comentario'>
				<p>$rs[texto]</p>".$edieli."
			</div>
        </div>
		</div>
        ";
        return $comentarios;
    }
    public static function mostrarTodos($ubicacion)
    {
        $comentarios = "";
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaValoraciones = sprintf("SELECT V.*,U.nombreUsuario,U.rutaFoto FROM Valoraciones V RIGHT JOIN Usuarios U ON U.id = V.idUsuario WHERE V.ubicacion = '$ubicacion' ");
        $row = $conn->query($tablaValoraciones);
        $numCom = $row->num_rows;
        for ($i = 0; $i < $numCom; $i++) {
            $rs = $row->fetch_assoc();
            $comentarios .= Valoracion::mostrarValoracion($rs);
        }
        $row->free();
        return $comentarios;
    }
    public static function mostrarEstrellasFijo($num){
        $html="
        <fieldset class='nota-valoracion'>";
        for($i=1;$i<6;$i++){
            $aux="";
            if($i<=$num){
                $aux="checked = true";
            }
            $html.="<input type='radio' id='".$i."estrellas' $aux disabled /><label for='".$i."estrellas'></label>";
        }
        $html.="</fieldset>";
         return $html;
    }
    public static function mostrarValoracionPerfil($rs){
        $comentarios="
        <div>
            <p>Valoración realizada en $rs[ubicacion]</p>
            <p>$rs[titulo]".Valoracion::mostrarEstrellasFijo($rs['nota'])."</p>
            <p>$rs[texto]</p>
        </div>";
        return $comentarios;
    }
    public static function mostrarTodosPerfil($idUsuario){
        $valoraciones="";
        $app=Aplicacion::getSingleton();
        $conn=$app->conexionBd();
        $tablaValoraciones=sprintf("SELECT V.*FROM Valoraciones V JOIN Usuarios U ON V.idUsuario = U.id WHERE $idUsuario = V.idUsuario");
        $row=$conn->query($tablaValoraciones);
        $numVal=$row->num_rows;
        for($i=0;$i<$numVal;$i++){
            $rs=$row->fetch_assoc();
            $valoraciones.=Valoracion::mostrarValoracionPerfil($rs);
        }
        $row->free();
        return $valoraciones;
    }
    public static function buscaValoracionPorId($id)
    {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaValoraciones = sprintf("SELECT C.* FROM Valoraciones C WHERE $id = C.id ");
        $row = $conn->query($tablaValoraciones);
        $rs = $row->fetch_assoc();
        $rs['id'] = $id;
        return $rs;
    }
    public static function permisoEdicion($id)
    {
        $permiso = false;
        $valoracion=self::buscaValoracionPorId($id);
        if (Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']) == $valoracion['idUsuario']) {
            $permiso = true;
        }
        return $permiso;
    }
    public static function confirmarEliminar($id){
        $content="Se eliminará la siguiente valoración";
        $content.=self::mostrarValoracionPerfil(self::buscaValoracionPorId($id));
        $content.="<form action='EliminarValoracion.php' method='post'>
        <input type='hidden' name='id' value='$id'>
        <input type='submit' name='eliminar' value='Eliminar'> </button>
        </form>";
        return $content;
    }
    public static function borraValoracion($id){
        if(isset($_POST['Eliminar'])){}
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $query = (sprintf("DELETE FROM Valoraciones C WHERE $id = C.id"));
        if($conn->query($query)){
            $ret="Valoracion eliminada con éxito.";
        }else {
            echo "Error al eliminar de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $ret;
    }

    /**
     * Get the value of nota
     */ 
    public function getNota()
    {
        return $this->nota;
    }
}
