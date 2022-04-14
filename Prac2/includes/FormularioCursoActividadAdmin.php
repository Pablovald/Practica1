<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Actividad.php';


class FormularioCursoActividadAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioCursoActividadAdmin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $curso = $datos['curso'] ?? '';
        $precio = $datos['precio'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorCurso = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorPrecio = self::createMensajeError($precio, 'descripcion', 'span', array('class' => 'error'));

        $html ="
        <fieldset>
            <legend>Formulario de añadir un curso asociado a una actividad</legend>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre de la Actividad:</label></br>
                <select name='nombre'>
                ".Actividad::optionActividad()."
                </select>
            </div>
            <div class='grupo-control'>
                <label>Curso:</label></br>
                <input class='control' type='text' name='curso' value='$curso' required/>$errorCurso
            </div>
            <div class='grupo-control'>
                <label>Precio:</label></br>
                <input class='control' type='number' name='precio' value='$precio' required/>$errorPrecio
            </div>
            <button type='submit' name='Aniadir'>Añadir Curso</button>
        </fieldset>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $curso = $datos['curso'] ?? null;
        $precio = $datos['precio'] ?? null;
        
        if(empty($descripcion)){
            $result['curso'] = "El curso no puede estar vacio";
        }
        if(empty($precio)){
            $result['precio'] = "El precio no puede estar vacio";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $actividad = Actividad::creaCursoActividad($nombre, $curso, $precio);
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