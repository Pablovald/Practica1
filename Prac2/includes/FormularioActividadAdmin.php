<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Actividad.php';
require_once __DIR__.'/subidaImagenes.php';

class FormularioActividadAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioActividadAdmin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $nombre = $datos['nombre'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $imagen = $datos['imagen'] ?? '';
        $info = $datos['info'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorDescripcion = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorImagen = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorInfo = self::createMensajeError($errores, 'info', 'span', array('class' => 'error'));

        $html ="
        <fieldset>
            <legend>Formulario de añadir una actividad</legend>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre:</label></br>
                <input class='control' type='text' name='nombre' value='$nombre' required/>$errorNombre
            </div>
            <div class='grupo-control'>
                <label>Descripcion:</label></br>
                <input class='control' type='text' name='descripcion' value='$descripcion' required/>$errorDescripcion
            </div>
            <div class='grupo-control'>
                <label>Imagen:</label></br>
                <input class='control' type='file' name='imagen' value='$imagen' required/>$errorImagen
            </div>
            <div class='grupo-control'>
                <label>Informacion detallada:</label></br>
                <input class='control' type='text' name='info' value='$info' required/>$errorInfo
            </div>
            <button type='submit' name='Aniadir Actividad'>Añadir</button>
        </fieldset>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $rutaFoto = subirImagen('img/') ?? null;
        $info = $datos['info'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($descripcion)){
            $result['descripcion'] = "La descripcion no puede estar vacio";
        }
        if(empty($rutaFoto)){
            $result['imagen'] = "La imagen no puede estar vacio";
        }
        if(empty($info)){
            $result['info'] = "La informacion detallada no puede estar vacio";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $actividad = Actividad::creaActividad($nombre, $descripcion, $rutaFoto, $info);
                    if(!$actividad){
                        $result[] ='No se ha podido crear la actividad';
                    }
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