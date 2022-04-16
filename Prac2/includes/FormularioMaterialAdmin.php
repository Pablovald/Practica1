<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/subidaImagenes.php';

class FormularioMaterialAdmin extends Form
{
    public function __construct() {
        parent::__construct('formularioMaterial Admin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $nombre = $datos['nombre'] ?? '';
        $precio = $datos['precio'] ?? '';
        $imagen = $datos['imagen'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $desc_det = $datos['desc_det'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorPrecio = self::createMensajeError($errores, 'precio', 'span', array('class' => 'error'));
        $errorImagen = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorDescripcion = self::createMensajeError($errores, 'descripcion', 'span', array('class' => 'error'));
        $errorDesc_det = self::createMensajeError($errores, 'desc_det', 'span', array('class' => 'error'));

        $html ="
        <div class='content'>
            <legend>Formulario de <span>añadir un material</span></legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre:</label>
                <input class='control' type='text' name='nombre' value='$nombre' required/>$errorNombre
            </div>
            <div class='grupo-control'>
            <label>Precio:</label>
            <input class='control' type='text' name='precio' value='$precio' required/>$errorPrecio
            </div>
            <div class='seleccion'>
            <label>Imagen: </label>
            <input class='control' type='file' name='imagen' value='$imagen' required/>$errorImagen
            </div>
            <div class='grupo-control'>
                <label>Descripcion:</label>
                <input class='control' type='text' name='descripcion' value='$descripcion' required/>$errorDescripcion
            </div>
            <div class='grupo-control'>
                <label>Descripción detallada:</label>
                <input class='control' type='text' name='desc_det' value='$desc_det' required/>$errorDesc_det
            </div>
			<div class='submit'>
            <button type='submit' name='Añadir Material'>Añadir</button>
			</div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;
        $precio = $datos['precio'] ?? null;
        $rutaFoto = subirImagen('img/') ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $desc_det = $datos['desc_det'] ?? null;

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($precio)){
            $result['precio'] = "El precio no puede estar vacio";
        }
        if(empty($rutaFoto)){
            $result['imagen'] = "La imagen no puede estar vacio";
        }
        if(empty($descripcion)){
            $result['descripcion'] = "La descripcion no puede estar vacio";
        }
        if(empty($desc_det)){
            $result['desc_det'] = "La descripcion detallada no puede estar vacio";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                if($_SESSION['esAdmin']){
                    $material = Material::creaMaterial($nombre, $precio, $rutaFoto, $descripcion, $desc_det);
                    if(!$material){
                        $result[] ='No se ha podido crear el material';
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