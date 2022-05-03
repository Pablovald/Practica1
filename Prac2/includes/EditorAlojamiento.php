<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/subidaImagenes.php';
class EditorAlojamiento extends Form
{
    public function __construct() {
        parent::__construct('EditorAlojamiento');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {

        $nombre =$_GET['alojamiento'];
        $alojamiento = Alojamiento::buscaAlojamiento($nombre);
        $descripcion =$alojamiento->getDescripcion();
        $imagen = $alojamiento->getRutaFoto();
        $info =$alojamiento->getDescripcionDetallada();
        $precio =$alojamiento->getPrecio();

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorDescripcion = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorImagen = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorInfo = self::createMensajeError($errores, 'info', 'span', array('class' => 'error'));
        $errorPrecio = self::createMensajeError($errores, 'precio', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>actualizar un alojamiento</span></legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre:</label>
                <input class='control' type='text' name='nombre' value='$nombre' readonly/>$errorNombre
            </div>
            <div class='grupo-control'>
                <label>Descripcion:</label>
                <input class='control' type='text' name='descripcion' value='$descripcion' required/>$errorDescripcion
            </div>
            <div class='seleccion'>
                <label>Imagen: </label>
                <input class='control' type='file' name='imagen' value='$imagen' />$errorImagen
            </div>
            <div class='grupo-control'>
                <label>Informacion detallada:</label>
                <input class='control' type='text' name='info' value='$info' required/>$errorInfo
            </div>
            <div class='grupo-control'>
                <label>precio:</label>
                <input class='control' type='number' name='precio' value='$precio' required/>$errorPrecio
            </div>
			<div class='submit'>
            <button type='submit' name='Añadir Alojamiento'>actualizar</button>
			</div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();
        $nombre = $datos['nombre'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $rutaFoto = subirImagen('img/') ?? $null;
        $info = $datos['info'] ?? null;
        $precio = $datos['precio'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($descripcion)){
            $result['descripcion'] = "La descripcion no puede estar vacio";
        }
        if(empty($rutaFoto)){
            $result['imagen'] = "La imagen no puede estar vacio";
        }
        if(empty($precio)){
            $result['precio'] = "El precio no puede estar vacio";
        }
        if(empty($info)){
            $result['info'] = "La informacion detallada no puede estar vacio";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $alojamiento = Alojamiento::actualizarInfoAlojamiento($nombre,$precio, $rutaFoto,$descripcion, $info);
                }
                else{
                    $result[] = "No eres Admin";
                }
            }
            else{
                $result[] = "Logeate primero";
            }
        }
        return $result;
    }
}