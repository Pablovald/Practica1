<?php
namespace es\fdi\ucm\aw;

class FormularioAlojamiento extends Form
{
    public function __construct() {
        parent::__construct('formularioAlojamiento');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $hoy = date('Y-m-d');
        $tomorrow = date('Y-m-d',time()+84600);
        $nhabitaciones = $datos['nhabitaciones'] ?? '';
        $alojamiento = $_GET["alojamiento"] ?? '';
        $precio=Alojamiento::precioHabitacion($alojamiento);
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);

        $html = "<div class='content'>
                $htmlErroresGlobales
				<div class='formulario'>
                <div class='grupo-control'>
                    <label>Nombre Hotel:</label></br>
                    <input class='control' type='text' name='alojamiento' value='$alojamiento' readonly/>
                </div>
                <div class='grupo-control'>
                    <label>Numero de habitaciones:</label></br>
                    <input class='control' type='number' name='nhabitaciones' value='1' min='1'/>
                </div>
                <div class='grupo-control'>
                <label>precio por habitacion:</label></br>
                <input class='control' type='text' name='precio' value='$precio' readonly/>
            </div>
                <div class='grupo-control'>
                    <label>Fecha inicio:</label></br>
                    <input class='control' type='date' name='fechaIni' value='$hoy' min='$hoy'/>
                </div>
                
                <div class='grupo-control'>
                    <label>Fecha fin:</label></br>
                    <input class='control' type='date' name='fechaFin' value='$tomorrow' min='$tomorrow'/>
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
        $alojamiento = $datos["alojamiento"];

        if($fechaini>=$fechafin){
            $result['fechaIni'] = "La fecha de inicio no puede superar que la fecha de fin";
        }
        if(count($result) == 0){

            if(isset($_SESSION['login'])){
                Alojamiento::inscribirAlojamiento($nhabitacion, $fechaini, $fechafin, $alojamiento, $result);
            }
            else{
                header("Location: alojamiento.php?&alojamiento=".$alojamiento."&estado=faltaLogin");
            }
        }
        return $result;
    }
}