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
		$tomorrow = date('Y-m-d',time()+84600);
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nhabitaciones'] ?? '';
        $fechaini = $datos['fechaIni'] ?? '';
        $fechafin = $datos['fechaFin'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);

        $html = <<<EOS
            <fieldset>
                $htmlErroresGlobales
                <div class="grupo-control">
                    <label>Numero de habitaciones:</label></br>
                    <input class="control" type="number" name="nhabitaciones" value="1" min="1"/>
                </div>
                <div class="grupo-control">
                    <label>Fecha inicio:</label></br>
                    <input class="control" type="date" name="fechaIni" value="$hoy" min="$hoy"/>
                </div>
                <div class="grupo-control">
                    <label>Fecha fin:</label></br>
                    <input class="control" type="date" name="fechaFin" value="$tomorrow" min="$tomorrow"/>
                </div>
                <div class="grupo-control"><button type="submit" name="Reservar">Reservar</button></div>
            </fieldset>
        EOS;
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {
        $result = array();
        $nhabitacion =$datos['nhabitaciones'] ?? null;
        $fechaini =$datos['fechaIni'] ?? null;
        $fechafin =$datos['fechaFin'] ?? null;
        
        if(isset($_SESSION['login'])){
            Alojamiento::inscribirAlojamiento($nhabitacion, $fechaini, $fechafin, $result);
        }
        else{
            $result[] = "Necesitas estar registrado en nuestra página web para reservar alojamientos. Si ya tienes una cuenta, inicia sesión.";
        
        return $result;
    }
}