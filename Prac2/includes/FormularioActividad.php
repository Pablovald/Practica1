<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/GeneraVistas.php';

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
        $nombreActividad = $_GET["actividad"] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombreUsuario = self::createMensajeError($errores, 'nombreUsuario', 'span', array('class' => 'error'));
        $errorDni = self::createMensajeError($errores, 'dni', 'span', array('class' => 'error'));
        $errorCorreo = self::createMensajeError($errores, 'correo', 'span', array('class' => 'error'));
        $errorFechaNac = self::createMensajeError($errores, 'fechaNac', 'span', array('class' => 'error'));
        $errorTelefono = self::createMensajeError($errores, 'telefono', 'span', array('class' => 'error'));
        $html ="
        <div class='content'>
		<legend>Formulario de <span>inscripción</span></legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Actividad:</label>
                <input class='control' type='text' name='actividad' value='$nombreActividad' id='nombreActividad'readonly/>
            </div>
            <div class='grupo-control'>
                <label>Nombre completo:</label>
                <input class='control' type='text' name='nombre' value='$nombreUsuario' required/>$errorNombreUsuario
            </div>
            <div class='grupo-control'>
                <label>DNI/NIE:</label>
                <input class='control' type='text' name='dni' value='$dni' id='campoDNI' required/>$errorDni
                <p id='DNIOK'>&#x2714;</p><p id='DNIMal'>&#x274c;</p>
            </div>
            <div class='grupo-control'>
                <label>Correo:</label>
                <input class='control' type='email' name='correo' value='$correo' id='campoEmail' required/>$errorCorreo
                <p id='correoOK'>&#x2714;</p><p id='correoMal'>&#x274c;</p>
            </div>
            <div class='grupo-control'>
                <label>Fecha de Nacimiento:</label>
                <input class='control' type='date' name='fechaNac' value='$fechaNac' max='$hoy' required/>$errorFechaNac
            </div>
            <div class='grupo-control'>
                <label>Telefono:</label>
                <input class='control' type='number' name='telefono' value='$telefono' id='campoTelefono' required/>$errorTelefono
                <p id='telefonoOK'>&#x2714;</p><p id='telefonoMal'>&#x274c;</p>
            </div>
			<div class='grupo-control'>
                <label for='curso'>Selecciona el curso:</label>
                <select name='curso'>'
                ".cursosDeActividadDinamico($nombreActividad)."
                </select>
			</div>
            <div class='grupo-control'>
                <label>Fechas clase:</label>
                <input class='control' type='date' name='dia' value='$hoy' required/>
            </div>
			<div class='seleccion'>
                <label>¿El alumno sabe nadar?</label>
                <div><input class='control' type='radio' name='nadarSi' value='Si'/>Si</div>
                <div><input class='control' type='radio' name='nadarNo' value='No'/>No</div>	
            </div>
			<div class='submit'>
            <button type='submit' name='login'>Enviar</button>
			</div>
            
			</div>
        </div>";
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
            $result['fechaNac'] = "La fecha de nacimiento no puede estar vacia";
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

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                Actividad::inscribirActividad($nombreActividad, $dia, $curso, $result);
            }
            else{
                header("Location: actividad.php?actividad=".$nombreActividad."&estado=faltaLogin");
            }
        }
        return $result;
    }
}