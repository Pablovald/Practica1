<?php
/*
Este script de apoyo sirve para generar html para las vistas de dsintintas secciones de la web
*/
namespace es\fdi\ucm\aw;
//genera el html del blog
function generaBlog()
{
    $app=Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaBlog=sprintf("SELECT * FROM entradasBlog");
        $numEntradas = $conn->query($tablaBlog)->num_rows;
        $rs=$conn->query($tablaBlog);
        $tableCont="<tr>";
        $j=0;
        for($i=0;$i<$numEntradas;$i++){
        $aux=$rs->fetch_assoc();
        $entrada=entradaBlog::getEntradaPorId($aux['id']);
        $intro = explode(' ', $entrada->getIntro(), 16);
        $intro[15] = "...";
        $rowCont =  "<td>
            <div class = 'blog-contenedor'>
                <div class = 'blog-box'>
                    <div class = 'blog-img'>
                    <a href=" . "procesarEntradaBlog.php?entrada=" . $entrada->getId() . "><img src='".$entrada->getImagen()."'></a>
                    </div>
                    <div class = 'blog-text'>
                    <h4>" . $entrada->getTitulo(). "</h4>
            <p>" . implode(' ', $intro) . "<a href=" . "procesarEntradaBlog.php?entrada=" . $entrada->getId() . "> Leer más</a></p>
                    </div>
                </div>
            </div>
            </td>";
            if($j<3){	
                $tableCont.=$rowCont;
                $j++;
            }
            else{
                $tableCont.="</tr>";
                $tableCont.="<tr>";
                $tableCont.=$rowCont;
                $j=1;
            }
        }
        $rs->free();
        return $tableCont;
}
//genera el html para ver una entrada individual
function generaEntradaIndividual(&$tituloPagina, &$tituloCabecera){
    $id = htmlspecialchars($_GET['entrada']);
    $entrada=entradaBlog::getEntradaPorId($id);
    $tituloPagina=$entrada->getTitulo();
        $tituloCabecera = strtoupper($tituloPagina);
        $contenidoPrincipal ="
            <div class='info-blog'>
                <h3>".$entrada->getHeader1()."</h3>
                <p>".$entrada->getIntro()."</p>
                <img class='entr-img' src=".$entrada->getImagen()." alt=''> </br> </br>
                <h3>".$entrada->getHeader2()."</h3>
                <p>".$entrada->getParrafo()."</p>
                <iframe class='iframe' src='https://www.youtube.com/embed/".$entrada->getVideo()."' frameborder='0'></iframe>
            </div>";
        return $contenidoPrincipal;
}
//genera la lista de entradas que se pueden borrar
function generaListadoEntrada(){
    $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $row=$conn->query(sprintf("SELECT * FROM entradasBlog"));
        if($row){
            $ret="";
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $ret.="<option>"."$act[titulo]"."</option>";
            }
            $row->free();
        }else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }  
        return $ret;
}