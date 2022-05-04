<?php
namespace es\fdi\ucm\aw;


class FormularioCapacidadActividadAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioCapacidadActividadAdmin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $nombre = $datos['nombre'] ?? '';
        $curso = $datos['curso'] ?? '';
        $fecha = $datos['fecha'] ?? '';
        $capacidad = $datos['capacidad'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($nombre, 'capacidad', 'span', array('class' => 'error'));
        $errorCurso = self::createMensajeError($nombre, 'curso', 'span', array('class' => 'error'));
        $errorCapacidad = self::createMensajeError($errores, 'capacidad', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>añadir/actualizar plazas para un curso</span></legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre de la Actividad:</label>
                <select name='nombre' id='campoActividad'>
                ".Actividad::optionActividad()."
                </select>
            </div>
            <div class='grupo-control'>
                <label>Curso:</label>
                <select name='curso' id='campoCurso'>
                ".Actividad::cursosDeActividadUno()."
                </select>
            </div>
            <div class='grupo-control'>
                <label>Fecha:</label>
                <input class='control' type='date' name='fecha' value='' required/>
            </div>
            <div class='grupo-control'>
                <label>Plazas:</label>
                <input class='control' type='number' name='capacidad' value='$capacidad' min='1' required/>$errorCapacidad 
            </div>
			<div class='submit'>
                <button type='submit' name='Aniadir'>Añadir/Actualizar plazas</button>
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
        $fecha = $datos['fecha'] ?? null;
        $capacidad = $datos['capacidad'] ?? null;
        
        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($curso)){
            $result['curso'] = "El curso no puede estar vacio";
        }
        if(empty($fecha)){
            $result['fecha'] = "La fecha no puede estar vacia";
        }
        if(empty($capacidad)){
            $result['capacidad'] = "La capacidad no puede estar vacia";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $capacidadCurso = Actividad::creaCapacidadCurso($nombre, $curso, $capacidad, $fecha);
                    if(!$capacidadCurso){
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