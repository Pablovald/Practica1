<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Usuario.php';

class FormularioAlojamiento extends Form
{
    public function __construct() {
        parent::__construct('formularioAlojamiento');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
		$hoy = date('Y-m-d');
		
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nombre'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);

        $html = <<<EOS
            <fieldset>
                $htmlErroresGlobales
                <div class="grupo-control">
                    <input class="control" type="number" name="adultos" value="0" min="0"/>
                </div>
                <div class="grupo-control">
                    <input class="control" type="date" name="fechaIni" value="$hoy" min="$hoy"/>
                </div>
                <div class="grupo-control">
                    <input class="control" type="date" name="fechaFin" value="$hoy" min="$hoy"/>
                </div>
                <div class="grupo-control"><button type="submit" name="registro">Registrar</button></div>
            </fieldset>
        EOS;
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {
        $result = array();
        
        $nombreUsuario = $datos['nombreUsuario'] ?? null;
        
        if ( empty($nombreUsuario) || mb_strlen($nombreUsuario) < 5 ) {
            $result['nombreUsuario'] = "El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.";
        }
        
        $nombre = $datos['nombre'] ?? null;
        if ( empty($nombre) || mb_strlen($nombre) < 5 ) {
            $result['nombre'] = "El nombre tiene que tener una longitud de al menos 5 caracteres.";
        }
        
        $password = $datos['password'] ?? null;
        if ( empty($password) || mb_strlen($password) < 5 ) {
            $result['password'] = "El password tiene que tener una longitud de al menos 5 caracteres.";
        }
        $password2 = $datos['password2'] ?? null;
        if ( empty($password2) || strcmp($password, $password2) !== 0 ) {
            $result['password2'] = "Los passwords deben coincidir";
        }
        
        if (count($result) === 0) {
            $user = Usuario::crea($nombreUsuario, $nombre, $password, 'user');
            if ( ! $user ) {
                $result[] = "El usuario ya existe";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $nombre;
				$_SESSION['nombreUsuario'] = $nombreUsuario;
                $result = 'home.php';
            }
        }
        return $result;
    }
}