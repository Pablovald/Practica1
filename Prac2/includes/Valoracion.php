<?php

namespace es\fdi\ucm\aw;

class Valoracion extends Comentario
{
    private $nota;

    private function __construct($idUsuario, $ubicacion, $titulo, $texto, $editado, $nota)
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
            "UPDATE Comentarios E SET titulo = '%s', texto = '%s', editado = '%d', nota = '%d' WHERE E.id=%i",
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
						<p class='comen'>@$rs[nombreUsuario]</p>
					</div>
                    <div class='nota-fijo'>
                    <p>".self::mostrarEstrellasFijo($rs['nota'])."</p>   
                    </div>
				</div>
				<div class='reseñas-comentario'>
				</div>
			</div>
			<div class='comentarios-comentario'>
				<p>$rs[texto]</p>
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
}
