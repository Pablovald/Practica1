<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Usuario.php';

class FormularioLogin extends Form
{
    public function __construct() {
        parent::__construct('formLogin');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombreUsuario =$datos['nombreUsuario'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorNombreUsuario = self::createMensajeError($errores, 'nombreUsuario', 'span', array('class' => 'error'));
        $errorPassword = self::createMensajeError($errores, 'password', 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <p><input type="text" name="nombreUsuario" placeholder="Nombre de usuario"value="$nombreUsuario"/>$errorNombreUsuario</p>
        <p><input type="password" name="password" placeholder="Contraseña" />$errorPassword</p>
        <button type="submit" name="login">Entrar</button>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {
        $result = array();
        
        $nombreUsuario =$datos['nombreUsuario'] ?? null;
        $nombre = $datos['nombre'] ?? null;
                
        if ( empty($nombreUsuario) ) {
            $result['nombreUsuario'] = "El nombre de usuario no puede estar vacío";
        }
        
        $password = $datos['password'] ?? null;
        if ( empty($password) ) {
            $result['password'] = "El password no puede estar vacío.";
        }
        
        if (count($result) === 0) {
            $usuario = Usuario::login($nombreUsuario, $password);
            if ( ! $usuario ) {
                // No se da pistas a un posible atacante
                $result[] = "El usuario o el password no coinciden";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $usuario->getNombre();
				$_SESSION['nombreUsuario'] = $usuario->getNombreUsuario();
                $_SESSION['esAdmin'] = strcmp($usuario->getRol(), 'admin') == 0 ? true : false;
                $result = 'home.php';
            }
        }
        return $result;
    }
}