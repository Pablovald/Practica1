<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Actividad.php';


class FormularioActividadAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioActividadAdmin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
		$hoy = date('Y-m-d');
		
        $nombre = $datos['nombre'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $rutaFoto = $datos['rutaFoto'] ?? '';
        $info = $datos['info'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorDescripcion = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorRutaFoto = self::createMensajeError($errores, 'rutaFoto', 'span', array('class' => 'error'));
        $errorInfo = self::createMensajeError($errores, 'info', 'span', array('class' => 'error'));

        $html ="
        <fieldset>
            <legend>Formulario de inscripción</legend>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre completo:</label></br>
                <input class='control' type='text' name='nombre' value='$nombre' required/>$errorNombre
            </div>
            <div class='grupo-control'>
                <label>DNI:</label></br>
                <input class='control' type='text' name='descripcion' value='$descripcion' required/>$errorDescripcion
            </div>
            <div class='grupo-control'>
                <label>Correo:</label></br>
                <input class='control' type='file' name='rutaFoto' value='$rutaFoto' required/>$errorRutaFoto
            </div>
            <button type='submit' name='Aniadir'>Añadir</button>
        </fieldset>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombreActividad =$datos['actividad'] ?? null;
        $nombre =$datos['nombre'] ?? null;
        $dni =$datos['dni'] ?? null;
        $correo =$datos['correo'] ?? null;
        $fechaNac =$datos['fechaNac'] ?? null;
        $telefono =$datos['telefono'] ?? null;
        $curso =$datos['curso'] ?? null;
        $dia =$datos['dia'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($dni)){
            $result['dni'] = "El dni no puede estar vacio";
        }
        if(empty($correo)){
            $result['correo'] = "El correo no puede estar vacio";
        }
        if(empty($fechaNac)){
            $result['fechaNac'] = "La fecha de nacimiento no puede estar vacio";
        }
        if(empty($telefono)){
            $result['telefono'] = "El telefono no puede estar vacio";
        }
        if(empty($curso)){
            $result['curso'] = "El curso no puede estar vacio";
        }
        if(empty($dia)){
            $result['dia'] = "El curso no puede estar vacio";
        }


        if(count($result) === 0 && isset($_SESSION['login'])){
            Actividad::inscribirActividad($nombreActividad, $dia, $curso, $result);
        }
        else{
            $result[] = "Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad. Si ya tienes una cuenta, inicia sesión.";
        }
        return $result;
    }
}