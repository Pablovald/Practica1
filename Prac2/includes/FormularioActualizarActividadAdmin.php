<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/subidaImagenes.php';

class FormularioActualizarActividadAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioActualizarActividadAdmin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {

        $nombre = $_GET['actividad'];
        $actividad = Actividad::buscaActividad($nombre);
        $descripcion = $actividad->getDescripcion();
        $imagen = $actividad->getrutaFoto();
        $info = $actividad->getInfo();

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorDescripcion = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorImagen = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorInfo = self::createMensajeError($errores, 'info', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>añadir una actividad</span></legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre:</label>
                <input class='control' type='text' name='nombre' value='$nombre' readonly/>$errorNombre
            </div>
            <div class='grupo-control'>
                <label>Descripcion:</label>
                <textarea name='descripcion' required>$descripcion$errorDescripcion</textarea>
            </div>
            <div class='seleccion'>
                <label>Imagen: </label>
                <input class='control' type='file' name='imagen' value='$imagen' required/>$errorImagen
            </div>
            <div class='grupo-control'>
                <label>Información detallada:</label>
                <textarea name='info' required>$info$errorInfo</textarea>
            </div>
			<div class='submit'>
            <button type='submit' name='Añadir Actividad'>Actualizar</button>
			</div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;
        $rutaFoto = subirImagen('img/') ?? null;
        $info = $_POST['info'] ?? null;

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