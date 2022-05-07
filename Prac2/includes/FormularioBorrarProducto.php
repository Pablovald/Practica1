<?php
namespace es\fdi\ucm\aw;

class FormularioBorrarProducto extends Form
{
    public function __construct(){
        parent::__construct('FormularioBorrarProducto');
    }

    protected function generaCamposFormulario($datos, $errores = array()){
        $nombre = $datos['nombre'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($nombre, 'capacidad', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>Eliminar un material</span></legens></br>
            <div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre del material:</label>
                <select name='nombre'>
                    ".Material::totalMateriales()."
                </select>
            </div>
            <div class='submit'>
                <button type='submit' name='Aniadir'>Eliminar material</button>
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
                    $id_prod = Material::sacaIdProducto($nombre);
                    $eliminado = Material::borrarMaterial($id_prod, $nombre);
                    if(!$eliminado){
                        $result[] = "No se ha podido eliminar";
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