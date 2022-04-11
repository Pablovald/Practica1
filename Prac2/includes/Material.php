<?php

require_once __DIR__. '/Aplicacion.php';

class Material
{
    private function __construct()
    {

    }

    public static function infoMaterial(&$tituloPagina, &$tituloCabecera){
        $tituloPagina = htmlspecialchars($_GET["material"]);
        $tituloCabecera = strtoupper($tituloPagina);
        $contenidoPrincipal ="";

        $app = Aplicacion::getSingleton();
        $conn = $app->conextionBd();
        $tablaMaterial=sprintf("SELECT * FROM Materiales M WHERE M.nombre LIKE '%s' "
                                , $conn->real_escape_string($tituloPagina));
        $row = $conn->query($tablaMaterial);
        if($row){
            $rs=$row->fetch_assoc();
            $Cont="
            <div id='imagen'>
                <h3> $tituloPagina </h3>
                <img src= $rs[imagen] width='350' height='350'>
            </div>
            <div id='contenido'>
            <p> Descripción detallada del producto: </p><br/>
            <p>"."$rs[desc_det]</p><br/>
            <p>"." Precio del producto: "." $rs[precio] "." €</p>
            <link rel='stylesheet' href='material.css'>
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
            <button class='carrito'>
                <span>Carrito</span>
                <i class='fa fa-shopping-basket' aria-hidden='true'></i>
            </button>
            </div>";
            $contenidoPrincipal = <<<EOS
                $Cont
            EOS;
            $row->free();
        }

        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
	        exit();
        }

        return $contenidoPrincipal;
    }

    public static function materialMain(){
        $contenidoPrincipal = NULL;
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaMaterial_Main=sprintf("SELECT * FROM Materiales");
        $rs =$conn->query($tablaMaterial_Main);
        $tableCont="<tr>";
        $j=0;
        if($rs){
            for($i=1;$i<=$rs->num_rows;$i++){
                $row=$conn->query(sprintf("SELECT * FROM Materiales M WHERE M.id = '$i'"));
                $contenido=$row->fetch_assoc();
                $url=rawurlencode("$contenido[nombre]");
                $rowCount = "<td>
                <div class = 'contenido'>
                    <div class = 'card'>
                        <a href ="."material.php?material=".$url."><img src= $contenido[imagen]> </a>
                    </div>
                    <div class = 'informacion'>
                        <h4>"."$contenido[nombre]"."</h4>
                        <p class = 'descripcion'>"."$contenido[descripcion]"." </p>
                    </div>
                    <div class = 'precio'>
                        <div class = 'box-precio'>
                            <p> Precio: "."$contenido[precio]"." €/hora <p>
                        </div>
                    </div>
                </div>
                </td>";
                if($j<3){	
                    $tableCont.=$rowCount;
                    $j++;
                }
                else{
                    $tableCont.="</tr>";
                    $tableCont.="<tr>";
                    $tableCont.=$rowCount;
                    $j=0;
                }
            }
            $contenidoPrincipal = <<<EOS
            <p> Materiales disponibles para alquilar. </p>
            <div class='alinear'>
                $tableCont
            </div>
            
            EOS;
            $rs->free();
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $contenidoPrincipal;
    }

}