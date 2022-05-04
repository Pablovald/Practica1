<?php
namespace es\fdi\ucm\aw;


class FormularioBorrarEntrada extends Form
{
    public function __construct() {
        parent::__construct('FormularioBorrarEntrada');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $nombre = $datos['nombre'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($nombre, 'capacidad', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>Eliminar una entrada de blog</span></legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre de la entrada:</label>
                <select name='nombre'>
                    ".entradaBlog::listadoEntrada()."
                </select>
            </div>
			<div class='submit'>
                <button type='submit' name='Aniadir'>borrar entrada</button>
			</div>
			</div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $capacidadCurso = entradaBlog::borrarEntrada($nombre);
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