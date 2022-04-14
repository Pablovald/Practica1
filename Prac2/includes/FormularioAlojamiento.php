<?php
require_once __DIR__.'/Form.php';
require_once __DIR__.'/Alojamiento.php';

class FormularioAlojamiento extends Form
{
    public function __construct() {
        parent::__construct('formularioAlojamiento');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
		$hoy = date('2022-04-01');
		$tomorrow = date('2022-04-02');
        $semana = date('2022-04-06');
		$semana2 = date('2022-04-07');
        $nhabitaciones = $datos['nhabitaciones'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);

        $html = "<div class='content'>
                $htmlErroresGlobales
				<div class='formulario'>
                <div class='grupo-control'>
                    <label>Numero de habitaciones:</label></br>
                    <input class='control' type='number' name='nhabitaciones' value='1' min='1'/>
                </div>
                <div class='grupo-control'>
                    <label>Fecha inicio:</label></br>
                    <input class='control' type='date' name='fechaIni' value='$hoy' min='$hoy' max='$semana'/>
                </div>
                <div class='grupo-control'>
                    <label>Fecha fin:</label></br>
                    <input class='control' type='date' name='fechaFin' value='$tomorrow' min='$tomorrow' max='$semana2'/>
                </div>
                <div class='submit'><button type='submit' name='Reservar'>Reservar</button></div>
				</div>
            </div>";
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {
        $result = array();
        $nhabitacion =$datos['nhabitaciones'] ?? null;
        $fechaini =$datos['fechaIni'] ?? null;
        $fechafin =$datos['fechaFin'] ?? null;
        $nombreAlojamiento = $_GET["alojamiento"];

        if($fechaini>=$fechafin){
            $result['fechaIni'] = "La fecha de inicio no puede superar que la fecha de fin";
        }
        if(count($result) == 0){
            if(isset($_SESSION['login'])){
                Alojamiento::inscribirAlojamiento($nhabitacion, $fechaini, $fechafin,$nombreAlojamiento, $result);
            }
            else{
                header("Location: actividad.php");
                $result[] = "Necesitas estar registrado en nuestra página web para reservar alojamientos. Si ya tienes una cuenta, inicia sesión.";
            }
        }
        return $result;
    }
}