<?php
namespace es\fdi\ucm\aw;
require_once __DIR__ .'/subidaImagenes.php';

class FormularioActualizarMaterialAdmin extends Form
{
    public function __construct(){
        parent::__construct('formularioActualizarMaterialAdmin');
    }

    protected function generaCamposFormulario($datos, $errores = array()){
        $nombre = $_GET['material'];
        $material = Material::buscaMaterial($nombre);
        $precio = (isset($datos['precio'])) ? $datos['precio'] : $material->getPrecio();
        $imagen = $material->getImagen();
        $descripcion = (isset($datos['descripcion'])) ? $datos['descripcion'] : $material->getDescripcion();
        $desc_det = (isset($datos['desc_det'])) ? $datos['desc_det'] : $material->getDesc_det();

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorPrecio = self::createMensajeError($errores, 'precio', 'span', array('class' => 'error'));
        $errorImagen = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorDescripcion = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorDesc_det = self::createMensajeError($errores, 'desc_det', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>actualizar un material</span></legend></br>
            <div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre:</label>
                <input class='control' type='text' name='nombre' value='$nombre' readonly/>$errorNombre
            </div>
            <div class='grupo-control'>
                <label>Precio:</label>
                <input class='control' type='text' name='precio' value='$precio' required/>$errorPrecio
            </div>
            <div class='grupo-control'>
                <label>Imagen:</label>
                <input class='control' type='file' name='imagen' value='$imagen' required/>$errorImagen
            </div>
            <div class='grupo-control'>
                <label>Descripcion:</label>
                <input class='control' type='text' name='descripcion' value='$descripcion' required/>$errorDescripcion
            </div>
            <div class='grupo-control'>
                <label>Descripcion detallada:</label>
                <input class='control' type='text' name='desc_det' value='$desc_det' required/>$errorDesc_det
            </div>
            <div class='submit'>
            <button type='submit' name='Actualizar Material'>Actualizar</button>
            </div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;
        $precio = $datos['precio'] ?? null;
        $imagen = subirImagen('img/') ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $desc_det = $datos['desc_det'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }

        if(empty($precio)){
            $result['precio'] = "El precio no puede estar vacio";
        }

        if(empty($imagen)){
            $result['imagen'] = "La imagen no puede estar vacia";
        }

        if(empty($descripcion)){
            $result['descripcion'] = "La descripcion no puede estar vacia";
        }

        if(empty($desc_det)){
            $result['desc_det'] = "La descripcion detallada no puede estar vacia";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $material = Material::creaMaterial($nombre, $precio, $imagen, $descripcion, $desc_det);
                    if(!$material){
                        $result[] = "No se ha podido actualizar el material";
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