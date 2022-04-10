<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Actividad.php';


class FormularioActividad extends Form
{
    public function __construct() {
        parent::__construct('formularioActividad');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
		$hoy = date('Y-m-d');
		
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $dni = $datos['dni'] ?? '';
        $correo = $datos['correo'] ?? '';
        $fechaNac = $datos['fechaNac'] ?? '';
        $telefono = $datos['telefono'] ?? '';
        $nombreActividad = $_GET["actividad"];

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombreUsuario = self::createMensajeError($errores, 'nombreUsuario', 'span', array('class' => 'error'));
        $errorDni = self::createMensajeError($errores, 'dni', 'span', array('class' => 'error'));
        $errorCorreo = self::createMensajeError($errores, 'correo', 'span', array('class' => 'error'));
        $errorFechaNac = self::createMensajeError($errores, 'fechaNac', 'span', array('class' => 'error'));
        $errorTelefono = self::createMensajeError($errores, 'telefono', 'span', array('class' => 'error'));
        $html ="
        <fieldset>
            <legend>Formulario de inscripción</legend>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Actividad:</label></br>
                <input class='control' type='text' name='actividad' value='$nombreActividad' readonly/>
            </div>
            <div class='grupo-control'>
                <label>Nombre completo:</label></br>
                <input class='control' type='text' name='nombre' value='$nombreUsuario' required/>$errorNombreUsuario
            </div>
            <div class='grupo-control'>
                <label>DNI:</label></br>
                <input class='control' type='text' name='dni' value='$dni' required/>$errorDni
            </div>
            <div class='grupo-control'>
                <label>Correo:</label></br>
                <input class='control' type='email' name='correo' value='$correo' required/>$errorCorreo
            </div>
            <div class='grupo-control'>
                <label>Fecha de Nacimiento:</label></br>
                <input class='control' type='date' name='fechaNac' value='$fechaNac' required/>$errorFechaNac
            </div>
            <div class='grupo-control'>
                <label>Telefono:</label></br>
                <input class='control' type='text' name='telefono' value='$telefono' required/>$errorTelefono
            </div>
            <div class='grupo-control'>
                <label>¿El alumno sabe nadar?</label></br>
                <input class='control' type='radio' name='nadarSi' value='Si'/>
                <label>Si</label>
                <input class='control' type='radio' name='nadarNo' value='No'/>
                <label>No</label>
            </div>
            <label for='curso'>Selecciona el tipo de curso al que quieres inscribirte:</label><br>
            <select name='curso'>';
            ".self::cursoActividad()."
            </select><br>
            <div class='grupo-control'>
                <label>Selecciona la fecha para las clases:</label></br>
                <input class='control' type='date' name='dia' value='$hoy' required/>
            </div>
            <button type='submit' name='login'>Entrar</button>
        </fieldset>";
        return $html;
    }
    
    private function cursoActividad(){
        return Actividad::cursoActividad($_GET["actividad"]);
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


        if(isset($_SESSION['login'])){
            Actividad::inscribirActividad($nombreActividad, $dia, $curso, $result);
        }
        else{
            $result[] = "Necesitas estar registrado en nuestra página web para inscribirte en alguna actividad. Si ya tienes una cuenta, inicia sesión.";
        }
        return $result;
    }
}