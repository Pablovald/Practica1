<?php
namespace es\fdi\ucm\aw;


class FormularioCursoActividadAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioCursoActividadAdmin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $curso = $datos['curso'] ?? '';
        $precio = $datos['precio'] ?? '';
        $hora = $datos['hora'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorCurso = self::createMensajeError($errores, 'curso', 'span', array('class' => 'error'));
        $errorPrecio = self::createMensajeError($errores, 'precio', 'span', array('class' => 'error'));
        $errorHora = self::createMensajeError($errores, 'hora', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>insertar un curso</span> asociado a una actividad</legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre de la Actividad:</label>
                <select name='nombre' id='campoNombreCursoActividad'>
                ".Actividad::optionActividad()."
                </select>
            </div>
            <div class='grupo-control'>
                <label>Curso:</label>
                <input class='control' type='text' name='curso' value='$curso' id ='campoNombreCurso'required/>$errorCurso
                <p id='campoNombreCursoOK'>&#x2714;</p><p id='campoNombreCursoMal'>&#x274c;</p>
            </div>
            <div class='grupo-control'>
                <label>Hora:</label>
                <input class='control' type='number' name='hora' value='$hora' min='0' required/>$errorHora
            </div>
            <div class='grupo-control'>
                <label>Precio:</label>
                <input class='control' type='number' name='precio' value='$precio' min='1' required/>$errorPrecio
            </div>
			<div class='submit'>
            <button type='submit' name='Aniadir'>Añadir Curso</button>
			</div>
			</div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;
        $curso = $datos['curso'] ?? null;
        $precio = $datos['precio'] ?? null;
        $hora = $datos['hora'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($curso)){
            $result['curso'] = "El curso no puede estar vacio";
        }
        if(empty($precio)){
            $result['precio'] = "El precio no puede estar vacio";
        }
        if(empty($hora)){
            $result['hora'] = "La hora no puede estar vacio";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $actividad = Actividad::insertaCursoActividad($nombre, $curso, $precio, $hora);
                    if(!$actividad){
                        $result[] ='No se ha podido crear';
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