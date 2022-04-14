<head>
<link rel="stylesheet" type="text/css" href="materialEstilo.css" />
</head>
<?php

require_once __DIR__.'/config.php';
require_once __DIR__. '/Aplicacion.php';
include_once 'funciones.php';

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
        $conn = $app->conexionBd();
        $tablaMaterial=sprintf("SELECT * FROM Materiales M WHERE M.nombre LIKE '%s' "
                                , $conn->real_escape_string($tituloPagina));
        $row = $conn->query($tablaMaterial);
        if($row){
            $rs=$row->fetch_assoc();
            $Cont="
            <div class = 'fotoMaterial'>
                <img src= $rs[imagen]>
            </div>
            <div class='contenidoMaterial'>
            <div class = 'tituloInfo'>
                Descripción detallada del producto: <br/>
            </div>
            <p>"."$rs[desc_det]</p><br/>
            <p>"." <precio>Precio del producto: </precio>"." $rs[precio] "." €</p>
            <link rel='stylesheet' href='material.css'>
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
            <label for='cantidad'>Cantidad de unidades a comprar:
            </label>
            ";
            if (productoYaEstaEnCarrito($rs['id'])) {
                $Cont .= "
                <form action='eliminar_del_carrito.php' method='post'>
                    <input type='hidden' name='id_producto' value='$rs[id] '>
                    <span class='button is-success'>
                        <i class='fa fa-check'></i>&nbsp;En el carrito
                    </span>
                    <button class='button is-danger'>
                        <i class='fa fa-trash-o'></i>&nbsp;Quitar
                    </button>
                </form>";
             } else { 
                 $Cont .= "
                <form action='agregar_al_carrito.php' method='post'>
                    <input type='hidden' name='id_producto' value='$rs[id] '>
                    <button class='button is-primary'>
                        <i class='fa fa-cart-plus'></i>&nbsp;Agregar al carrito
                    </button>
                </form>";
            } 
            $Cont .= "</div>";
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