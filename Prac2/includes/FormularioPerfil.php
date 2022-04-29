<?php
namespace es\fdi\ucm\aw;
require_once __DIR__.'/subidaImagenes.php';

class FormularioPerfil extends Form
{
    public function __construct() {
        parent::__construct('formularioPerfil');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $usuario = Usuario::buscaUsuario($_SESSION['nombreUsuario']);
        $nombre = (isset($datos['nombre'])) ? $datos['nombre'] : $usuario->getNombreUsuario();
        $apellido = (isset($datos['apellido'])) ? $datos['apellido'] : $usuario->getApellido();
        $fechaNac = (isset($datos['fechaNac'])) ? $datos['fechaNac'] : $usuario->getFechaNac();
        $telefono = (isset($datos['telefono'])) ? $datos['telefono'] : $usuario->getTelefono();
        $nacionalidad = (isset($datos['nacionalidad'])) ? $datos['nacionalidad'] : $usuario->getNacionalidad();
        $imagen = $datos['imagen'] ?? '';
        $correo = (isset($datos['correo'])) ? $datos['correo'] : $usuario->getCorreo();

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombre = self::createMensajeError($errores, 'nombre', 'span', array('class' => 'error'));
        $errorApellido = self::createMensajeError($errores, 'apellido', 'span', array('class' => 'error'));
        $errorFechaNac = self::createMensajeError($errores, 'fechaNac', 'span', array('class' => 'error'));
        $errorNacionalidad = self::createMensajeError($errores, 'nacionalidad', 'span', array('class' => 'error'));
        $errorTelefono = self::createMensajeError($errores, 'telefono', 'span', array('class' => 'error'));
        $errorImagen = self::createMensajeError($errores, 'imagen', 'span', array('class' => 'error'));
        $errorCorreo = self::createMensajeError($errores, 'correo', 'span', array('class' => 'error'));
        
        $html ="
        <div class='content'>
            <legend>Formulario de <span>actualizar</span> el datos del Usuario</legend></br>
			<div class='formulario'>
            $htmlErroresGlobales
            <div class='grupo-control'>
                <label>Nombre:</label>
                <input class='control' type='text' name='nombre' value='$nombre' required/>$errorNombre
            </div>
            <div class='grupo-control'>
                <label>Apellido:</label>
                <input class='control' type='text' name='apellido' value='$apellido' required/>$errorApellido
            </div>
            <div class='grupo-control'>
                <label>Correo:</label>
                <input class='control' type='text' name='correo' value='$correo' required/>$errorCorreo
            </div>
            <div class='grupo-control'>
            <label>Fecha de Nacimiento:</label>
                <input class='control' type='date' name='fechaNac' value='$fechaNac' required/>$errorFechaNac
            </div>
            <div class='grupo-control'>
                <label>Teléfono:</label>
                <input class='control' type='text' name='telefono' value='$telefono' required/>$errorTelefono
            </div>
            <div class='grupo-control'>
                <label>Nacionalidad:</label>
                <input class='control' type='text' name='nacionalidad' value='$nacionalidad' required/>$errorNacionalidad
            </div>
            <div class='seleccion'>
                <label>Imagen del avatar: </label>
                <input class='control' type='file' name='imagen' value='$imagen' required/>$errorImagen
            </div>
			<div class='submit'>
            <button type='submit' name='actualizar'>Actualizar</button>
			</div>
        </div>";
        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $result = array();

        $nombre = $datos['nombre'] ?? null;
        $apellido = $datos['apellido'] ?? null;
        $fechaNac = $datos['fechaNac'] ?? null;
        $telefono = $datos['telefono'] ?? null;
        $nacionalidad = $datos['nacionalidad'] ?? null;
        $rutaFoto = subirImagen('img/') ?? null;
        $correo = $datos['correo'] ?? null;
        

        if(empty($nombre)){
            $result['nombre'] = "El nombre no puede estar vacio";
        }
        if(empty($apellido)){
            $result['apellido'] = "El apellido no puede estar vacio";
        }
        if(empty($fechaNac)){
            $result['fechaNac'] = "La fecha de nacimiento no puede estar vacio";
        }
        if(empty($telefono)){
            $result['telefono'] = "El telefono no puede estar vacio";
        }
        if(empty($nacionalidad)){
            $result['nacionalidad'] = "La nacionalidad no puede estar vacio";
        }
        if(empty($rutaFoto)){
            $result['imagen'] = "La imagen no puede estar vacio";
        }
        if(empty($correo)){
            $result['correo'] = "El correo no puede estar vacio";
        }

        if(count($result) === 0){
            if(isset($_SESSION['login'])){
                $usuario = Usuario::actualizaPerfil($_SESSION['nombreUsuario'], $nombre, $apellido, $fechaNac, $telefono, $nacionalidad, $rutaFoto, $correo);
                if(!$usuario){
                    $result[] ='No se ha podido actualizar';
                }
            }
            else{
                $result[] = "Logeate primero";
            }
        }
        return $result;
    }
}