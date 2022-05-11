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
        $val=$conn->query($tablaBlog);
        $tableCont="<tr>";
        $j=0;
        for($i=0;$i<$numEntradas;$i++){
        $aux=$val->fetch_assoc();
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
        $val->free();
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
//pide al usuario confirmacion antes de eliminar el comentario de la bbdd
function confirmarEliminarC($id){
    $content="Se eliminará el siguiente comentario";
    $content.=mostrarComentarioPerfil(Comentario::buscaComentarioPorId($id));
    $content.="<form action='EliminarComentario.php' method='post'>
    <input type='hidden' name='id' value='$id'>
    <input type='submit' name='eliminar' value='Eliminar'> </button>
    </form>";
    return $content;
}
function mostrarComentarioBlog($com,$user)
    {
        $edieli = "";
        if (isset($_SESSION['login']) && $_SESSION['login'] ) {
            $edieli = "";
            if(Valoracion::permisoEdicion($com->getId())){
                $edieli.=" <form action='EdicionComentario.php' method='post'>
                <input type='hidden' name='id' value='".$com->getId()."'>
                <button class='boton-link' type='submit'>Editar</button>
                </form>";
            }
            if(Valoracion::permisoEliminar($com->getId())){
                $edieli.="<form action='EliminarComentario.php' method='post'>
                <input type='hidden' name='id' value='".$com->getId()."'>
                <button class='boton-link' type='submit'>Eliminar</button>
                </form>";
            }
        }
        $comentarios = "
		<div class='contenedor'>
        <div class='caja-comentario'>
			<div class='caja-top-comentario'>
				<div class='perfil-comentario'>
					<div class='foto-comentario'>
						<img class='foto-comentario-img' src=".$user->getRutaFoto().">
					</div>
					<div class='nombre-user-cometario'>
						<h1>".$com->getTitulo()."</h1>
                        ".generaEditado($com->getEditado())."
						<p class='comen'>@".$user->getNombreUsuario()."</p>
					</div>
				</div>
				<div class='reseñas-comentario'>
				</div>
			</div>
			<div class='comentarios-comentario'>
				<p>".$com->getTexto()."</p>" . $edieli .
            "</div>
        </div>
		</div>
        ";
        return $comentarios;
    }
//genera el html para mostrar los comentarios en el perfil
function mostrarComentarioPerfil($com)
{
    $comentarios ="
    <div>
        <p>Comentado en el artículo ".$com->getUbicacion()."</p>
        <p>".$com->getTitulo()."</p>
        <p>".$com->getTexto()."</p>
    </div>";
    return $comentarios;
}

//genera el tag de si un comentario/valoracion está editado o no
function generaEditado($editado){
    $text="";
    if($editado){
        $text="<em>(Editado)</em>";
    }
    return $text;
}
function mostrarValoracion($val,$user)
    {
        $edieli = "";
        if (isset($_SESSION['login']) && $_SESSION['login'] ) {
            $edieli = "";
            if(Valoracion::permisoEdicion($val->getId())){
                $edieli.=" <form action='EdicionValoracion.php' method='post'>
                <input type='hidden' name='id' value='".$val->getId()."'>
                <button class='boton-link' type='submit'>Editar</button>
                </form>";
            }
            if(Valoracion::permisoEliminar($val->getId())){
                $edieli.="<form action='EliminarValoracion.php' method='post'>
                <input type='hidden' name='id' value='".$val->getId()."'>
                <button class='boton-link' type='submit'>Eliminar</button>
                </form>";
            }
        }
        $comentarios = "
		<div class='contenedor'>
        <div class='caja-comentario'>
			<div class='caja-top-comentario'>
				<div class='perfil-comentario'>
					<div class='foto-comentario'>
						<img class='foto-comentario-img' src=".$user->getRutaFoto().">
					</div>
					<div class='nombre-user-cometario'>
						<h1>".$val->getTitulo()."</h1>
                        ".generaEditado($val->getEditado())."
						<p class='comen'>@".$user->getNombreUsuario()."</p>
					</div>
                    <div class='nota-fijo'>
                    ".mostrarEstrellasFijo($val->getNota())."   
                    </div>
				</div>
				<div class='reseñas-comentario'>
				</div>
			</div>
			<div class='comentarios-comentario'>
				<p>".$val->getTexto()."</p>".$edieli."
			</div>
        </div>
		</div>";
        return $comentarios;
    }
//genera el html para ver las valoraciones de un usuario en su perfil
function mostrarValoracionPerfil($val){
    $comentarios="
    <div>
        <p>Valoración realizada en ".$val->getUbicacion()."</p>
        <p>".$val->getTitulo().mostrarEstrellasFijo($val->getNota())."</p>
        <p>".$val->getTexto()."</p>
    </div>";
    return $comentarios;
}
//genera el html para mostrar las estrellas
function mostrarEstrellasFijo($num){
    $html="
    <fieldset class='nota-valoracion'>";
    for($i=1;$i<11;$i++){
        $aux="";
        if($i<=($num*2)){
            $aux="checked = true";
        }
        if($i%2==0){
            $html.="<input type='radio' id='".($i/2)."estrellas' $aux disabled /><label class ='full' for='".($i/2)."estrellas'></label>";
        }
        else{
            $medio=strval($i/2);
            $html.="<input type='radio' id='".$medio."estrellas' $aux disabled /><label class='half' for='".$medio."estrellas'></label>";
        }
    }
    $html.="</fieldset>";
     return $html;
}
//pide al usuario confirmacion antes de eliminar la valoracion de la bbdd
function confirmarEliminarV($id){
    $content="Se eliminará la siguiente valoración";
    $content.=mostrarValoracionPerfil(Valoracion::buscaValoracionPorId($id));
    $content.="<form action='EliminarValoracion.php' method='post'>
    <input type='hidden' name='id' value='$id'>
    <input type='submit' name='eliminar' value='Eliminar'> </button>
    </form>";
    return $content;
}