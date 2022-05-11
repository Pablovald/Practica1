<?php
/*
Este script de apoyo sirve para generar html para las vistas de dsintintas secciones de la web
*/

namespace es\fdi\ucm\aw;
//genera el html del blog
function generaBlog()
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaBlog = sprintf("SELECT * FROM entradasBlog");
    $numEntradas = $conn->query($tablaBlog)->num_rows;
    $val = $conn->query($tablaBlog);
    $tableCont = "<tr>";
    $j = 0;
    for ($i = 0; $i < $numEntradas; $i++) {
        $aux = $val->fetch_assoc();
        $entrada = entradaBlog::getEntradaPorId($aux['id']);
        $intro = explode(' ', $entrada->getIntro(), 16);
        $intro[15] = "...";
        $rowCont =  "<td>
            <div class = 'blog-contenedor'>
                <div class = 'blog-box'>
                    <div class = 'blog-img'>
                    <a href=" . "procesarEntradaBlog.php?entrada=" . $entrada->getId() . "><img src='" . $entrada->getImagen() . "' alt=''></a>
                    </div>
                    <div class = 'blog-text'>
                    <h4>" . $entrada->getTitulo() . "</h4>
            <p>" . implode(' ', $intro) . "<a href=" . "procesarEntradaBlog.php?entrada=" . $entrada->getId() . "> Leer más</a></p>
            <p>Autor: ".Usuario::buscaUsuarioPorId($entrada->getIdAutor())->getNombreUsuario()."</p>
                    </div>
                </div>
            </div>
            </td>";
        if ($j < 3) {
            $tableCont .= $rowCont;
            $j++;
        } else {
            $tableCont .= "</tr>";
            $tableCont .= "<tr>";
            $tableCont .= $rowCont;
            $j = 1;
        }
    }
    $val->free();
    return $tableCont;
}
//genera el html para ver una entrada individual
function generaEntradaIndividual(&$tituloPagina, &$tituloCabecera)
{
    $id = htmlspecialchars($_GET['entrada']);
    $entrada = entradaBlog::getEntradaPorId($id);
    $tituloPagina = $entrada->getTitulo();
    $tituloCabecera = strtoupper($tituloPagina);
    $contenidoPrincipal = "
            <div class='info-blog'>
                <h3>" . $entrada->getHeader1() . "</h3>
                <p class='autor'> escrito por: <em>".Usuario::buscaUsuarioPorId($entrada->getIdAutor())->getNombreUsuario()."</em></p>
                <p>" . $entrada->getIntro() . "</p>
                <img class='entr-img' src=" . $entrada->getImagen() . " alt=''> </br> </br>
                <h3>" . $entrada->getHeader2() . "</h3>
                <p>" . $entrada->getParrafo() . "</p>
                <iframe class='iframe' src='https://www.youtube.com/embed/" . $entrada->getVideo() . "' frameborder='0'></iframe>
            </div>";
    return $contenidoPrincipal;
}
//genera la lista de todas las entradas que se pueden borrar por el admin
function generaListadoEntrada($id)
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    if ($id==0)$listaEntradas= sprintf("SELECT * FROM entradasBlog");
    else $listaEntradas= sprintf("SELECT * FROM entradasBlog WHERE idAutor=$id");
    $row = $conn->query($listaEntradas);
    if ($row) {
        $ret = "";
        for ($i = 0; $i < $row->num_rows; $i++) {
            $act = $row->fetch_assoc();
            $ret .= "<option>" . "$act[titulo]" . "</option>";
        }
        $row->free();
    } else {
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $ret;
}
//pide al usuario confirmacion antes de eliminar el comentario de la bbdd
function confirmarEliminarC($id)
{
    $content = "Se eliminará el siguiente comentario";
    $content .= mostrarComentarioPerfil(Comentario::buscaComentarioPorId($id));
    $content .= "<p>Si estás seguro de querer eliminar este comentario, pincha el botón</p>
    <form action='EliminarComentario.php' method='post'>
    <input type='hidden' name='id' value='$id'>
    <input type='submit' name='eliminar' value='Eliminar'> </button>
    </form>";
    return $content;
}
function mostrarComentarioBlog($com, $user)
{
    $edieli = "";
    if (isset($_SESSION['login']) && $_SESSION['login']) {
        $edieli = "";
        if (Comentario::permisoEdicion($com->getId())) {
            $edieli .= " <form action='EdicionComentario.php' method='post'>
                <input type='hidden' name='id' value='" . $com->getId() . "'>
                <button class='boton-link' type='submit'>Editar</button>
                </form>";
        }
        if (Comentario::permisoEliminar($com->getId())) {
            $edieli .= "<form action='EliminarComentario.php' method='post'>
                <input type='hidden' name='id' value='" . $com->getId() . "'>
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
						<img class='foto-comentario-img' src=" . $user->getRutaFoto() . "alt=''>
					</div>
					<div class='nombre-user-cometario'>
						<h1>" . $com->getTitulo() . "</h1>
                        " . generaEditado($com->getEditado()) . "
						<p class='comen'>@" . $user->getNombreUsuario() . "</p>
					</div>
				</div>
				<div class='reseñas-comentario'>
				</div>
			</div>
			<div class='comentarios-comentario'>
				<p>" . $com->getTexto() . "</p>" . $edieli .
        "</div>
        </div>
		</div>
        ";
    return $comentarios;
}
//genera el html para mostrar los comentarios en el perfil
function mostrarComentarioPerfil($com)
{
    $comentarios = "
    <div>
        <p>Comentado en el artículo " . $com->getUbicacion() . "</p>
        <p>" . $com->getTitulo() . "</p>
        <p>" . $com->getTexto() . "</p>
    </div>";
    return $comentarios;
}
//genera todos los comentarios en un articulo
function mostrarTodos($ubicacion)
{
    $comentarios = "";
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaComentarios = sprintf("SELECT C.*,U.nombreUsuario,U.rutaFoto FROM Comentarios C RIGHT JOIN Usuarios U ON U.id = C.idUsuario WHERE C.ubicacion = '$ubicacion' ");
    $row = $conn->query($tablaComentarios);
    $numCom = $row->num_rows;
    if ($numCom == 0) {
        $comentarios = "<div class='contenedor'>
            <div class='caja-comentario'>
            <div class='caja-top-comentario'>
            <h1> Aún no hay comentarios publicados.¡Sé el primero en compartir tu opinión!</h1>
            </div>
            </div>
            </div>";
    }
    for ($i = 0; $i < $numCom; $i++) {
        $rs = $row->fetch_assoc();
        $user = new Usuario($rs['nombreUsuario'], null, null, null, null, null, null, null, $rs['rutaFoto'], null);
        $comentarios .= mostrarComentarioBlog(Comentario::buscaComentarioPorId($rs['id']), $user);
    }
    $row->free();
    return $comentarios;
}
//muestra todos los comentarios en el perfil
function mostrarTodosPerfil($idUsuario)
{
    $comentarios = "";
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaComentarios = sprintf("SELECT C.* FROM Comentarios C JOIN Usuarios U ON C.idUsuario = U.id WHERE $idUsuario = C.idUsuario ");
    $row = $conn->query($tablaComentarios);
    $numCom = $row->num_rows;
    if ($numCom == 0) {
        $comentarios = "<p>Aún no has realizado ningun comentario</p>";
    }
    for ($i = 0; $i < $numCom; $i++) {
        $rs = $row->fetch_assoc();
        $com = new Comentario($rs['idUsuario'], $rs['ubicacion'], $rs['titulo'], $rs['texto'], $rs['editado']);
        $comentarios .= mostrarComentarioPerfil($com);
    }

    $row->free();
    return $comentarios;
}
//genera el tag de si un comentario/valoracion está editado o no
function generaEditado($editado)
{
    $text = "";
    if ($editado) {
        $text = "<em>(Editado)</em>";
    }
    return $text;
}
function mostrarValoracion($val, $user)
{
    $edieli = "";
    if (isset($_SESSION['login']) && $_SESSION['login']) {
        $edieli = "";
        if (Valoracion::permisoEdicion($val->getId())) {
            $edieli .= " <form action='EdicionValoracion.php' method='post'>
                <input type='hidden' name='id' value='" . $val->getId() . "'>
                <button class='boton-link' type='submit'>Editar</button>
                </form>";
        }
        if (Valoracion::permisoEliminar($val->getId())) {
            $edieli .= "<form action='EliminarValoracion.php' method='post'>
                <input type='hidden' name='id' value='" . $val->getId() . "'>
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
						<img class='foto-comentario-img' src=" . $user->getRutaFoto() . " alt=''>
					</div>
					<div class='nombre-user-cometario'>
						<h1>" . $val->getTitulo() . "</h1>
                        " . generaEditado($val->getEditado()) . "
						<p class='comen'>@" . $user->getNombreUsuario() . "</p>
					</div>
                    <div class='nota-fijo'>
                    " . mostrarEstrellasFijo($val->getNota()) . "   
                    </div>
				</div>
				<div class='reseñas-comentario'>
				</div>
			</div>
			<div class='comentarios-comentario'>
				<p>" . $val->getTexto() . "</p>" . $edieli . "
			</div>
        </div>
		</div>";
    return $comentarios;
}
//genera el html para ver las valoraciones de un usuario en su perfil
function mostrarValoracionPerfil($val)
{
    $comentarios = "
        <div class='valora'>
            <p>Valoración realizada en " . $val->getUbicacion() . "</p>
            <p>" . $val->getTitulo() . mostrarEstrellasFijo($val->getNota()) . "</p>
            <p>" . $val->getTexto() . "</p>
        </div>";
    return $comentarios;
}
//genera el html para todas las valoraciones del perfil
function mostrarTodasValoracionesPerfil($idUsuario)
{
    $valoraciones = "";
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaValoraciones = sprintf("SELECT V.*FROM Valoraciones V JOIN Usuarios U ON V.idUsuario = U.id WHERE $idUsuario = V.idUsuario");
    $row = $conn->query($tablaValoraciones);
    $numVal = $row->num_rows;
    if ($numVal == 0) {
        $valoraciones = "<p>Aún no has realizado ninguna valoración</p>";
    }
    for ($i = 0; $i < $numVal; $i++) {
        $rs = $row->fetch_assoc();
        $val = new Valoracion($rs['idUsuario'], $rs['ubicacion'], $rs['titulo'], $rs['texto'], $rs['editado'], $rs['nota']);
        $valoraciones .= mostrarValoracionPerfil($val);
    }
    $row->free();
    return $valoraciones;
}
//genera el html para mostrar las estrellas
function mostrarEstrellasFijo($num)
{
    $html = "
    <fieldset class='nota-valoracion'>";
    for ($i = 1; $i < 11; $i++) {
        $aux = "";
        if ($i <= ($num * 2)) {
            $aux = "checked = true";
        }
        if ($i % 2 == 0) {
            $html .= "<input type='radio' id='" . ($i / 2) . "estrellas' $aux disabled /><label class ='full' for='" . ($i / 2) . "estrellas'></label>";
        } else {
            $medio = strval($i / 2);
            $html .= "<input type='radio' id='" . $medio . "estrellas' $aux disabled /><label class='half' for='" . $medio . "estrellas'></label>";
        }
    }
    $html .= "</fieldset>";
    return $html;
}
//pide al usuario confirmacion antes de eliminar la valoracion de la bbdd
function confirmarEliminarV($id)
{
    $content = "Se eliminará la siguiente valoración";
    $content .= mostrarValoracionPerfil(Valoracion::buscaValoracionPorId($id));
    $content .= "<p>Si estás seguro de querer eliminar esta valoración, pincha el botón</p>
    <form action='EliminarValoracion.php' method='post'>
    <input type='hidden' name='id' value='$id'>
    <input type='submit' name='eliminar' value='Eliminar'> </button>
    </form>";
    return $content;
}
//muestra todas las valoraciones en materiales/actividades
function mostrarTodasValoraciones($ubicacion)
    {
        $comentarios = "";
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $tablaValoraciones = sprintf("SELECT V.*,U.nombreUsuario,U.rutaFoto FROM Valoraciones V RIGHT JOIN Usuarios U ON U.id = V.idUsuario WHERE V.ubicacion = '$ubicacion' ");
        $row = $conn->query($tablaValoraciones);
        $numCom = $row->num_rows;
        if ($numCom == 0) {
            $comentarios = "<div class='contenedor'>
                <div class='caja-comentario'>
                <div class='caja-top-comentario'>
                <div class='sinComent'>
                <h1> Aún no hay valoraciones publicadas.¡Sé el primero en compartir tu opinión!</h1>
                </div>
                </div>
                </div>
                </div>";
        }
        for ($i = 0; $i < $numCom; $i++) {
            $rs = $row->fetch_assoc();
            $user=new Usuario($rs['nombreUsuario'],null,null,null,null,null,null,null,$rs['rutaFoto'],null);
            $comentarios .= mostrarValoracion(Valoracion::buscaValoracionPorId($rs['id']),$user);
        }
        $row->free();
        return $comentarios;
    }
//Muestra todas las actividades con su nombre, imagen y breve descripcion. Basicamente la informacion del contenido principal de Actividades_Main.php
function actividadMain()
{
    $contenidoPrincipal = NULL;
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaActividad_Main = sprintf("SELECT * FROM Actividades");
    $rs = $conn->query($tablaActividad_Main);
    $tableCont = NULL;
    if ($rs) {
        for ($i = 1; $i <= $rs->num_rows; $i++) {
            $fila = $rs->fetch_assoc();
            $row = $conn->query(sprintf("SELECT * FROM Actividades A WHERE A.id = $fila[ID]"));
            if ($row) {
                $contenido = $row->fetch_assoc();
                $url = rawurlencode("$contenido[Nombre]");
                $leftCont =  "<div><td>
                    <a href =" . "actividad.php?actividad=" . $url . "><img class='img-pag-prin' src= '$contenido[rutaFoto]' alt=''> </a>
                    </td></div>";
                $rightCont = "<div><td>
                    <h2><a href = " . "actividad.php?actividad=" . $url . ">" . "$contenido[Nombre]" . " </a></h2>
                    " . "$contenido[Descripcion]" . "
                    <a href = " . "actividad.php?actividad=" . $url . ">Leer más</a></p>
                    </td></div>";
                if ($i % 2 == 0) {
                    $aux = $leftCont;
                    $leftCont = $rightCont;
                    $rightCont = $aux;
                }
                $tableCont .= "<tr>" . "$leftCont" . "$rightCont" . "</tr>";
                $row->free();
            } else {
                echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        $contenidoPrincipal = <<<EOS
        <p> Clases Disponibles en SeaWolf Deportes Náuticos. </p>
        <table>$tableCont
            </table>
        EOS;
        $rs->free();
    } else {
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $contenidoPrincipal;
}

//Muestra el horario y el precio de una actividad
function infoActividad(&$tituloPagina, &$tituloCabecera)
{
    $tituloPagina = htmlspecialchars($_GET["actividad"]);
    $tituloCabecera = strtoupper($tituloPagina);
    $contenidoPrincipal = "";

    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaActividad = sprintf(
        "SELECT * FROM Actividades A WHERE A.nombre LIKE '%s' ",
        $conn->real_escape_string($tituloPagina)
    );
    $row = $conn->query($tablaActividad);
    if ($row) {
        $rs = $row->fetch_assoc();
        $Cont = "<h3><span>Información</span> del curso de " . "$tituloPagina" . "</h3>
        <p>" . "$rs[info]" . "</p>
        <h3> <span>Horarios </span>disponibles </h3>
        <p> Lunes a Viernes de 16:00 a 18:00 </p>
        <p> Sabado y Domingo de 11:30 a 13:30</p>
        <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
        <h3> <span>Precios</span> del curso </h3>";

        $row = $conn->query(sprintf(
            "SELECT C.nombre_curso, C.precio, C.horas FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'",
            $conn->real_escape_string($tituloPagina)
        ));
        if ($row) {
            for ($i = 0; $i < $row->num_rows; $i++) {
                $act = $row->fetch_assoc();
                if ($act['horas'] == 0) {
                    $Cont .= "<p>" . $act['nombre_curso'] . ": " . $act['precio'] . " €</p>";
                } else {
                    $Cont .= "<p>" . $act['nombre_curso'] . " (" . $act['horas'] . " horas): " . $act['precio'] . " €</p>";
                }
            }
            $contenidoPrincipal = <<<EOS
                $Cont
                <h3><span>Fechas</span> de clases disponibles</h3>
            EOS;
            $row->free();
            $contenidoPrincipal .= mostrarFechas($tituloPagina);
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    } else {
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $contenidoPrincipal;
}

//Muestra las fechas disponibles para inscribirse en un curso de una actividad
function mostrarFechas($tituloPagina)
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();

    $Cont = NULL;
    $array1 = array();
    $fila = $conn->query(sprintf(
        "SELECT C.Fecha, C.Curso, CUA.Horas FROM CapacidadActividad C JOIN CursosActividades CUA ON C.Curso = CUA.nombre_curso AND C.Nombre = CUA.nombre_actividad WHERE C.Nombre LIKE '%s' AND C.Capacidad > 0",
        $conn->real_escape_string($tituloPagina)
    ));
    if ($fila) {
        $clave = "";
        for ($i = 0; $i < $fila->num_rows; $i++) {
            $aux = $fila->fetch_assoc();
            if ($aux['Horas'] == 0) {
                $clave = $aux['Curso'];
            } else {
                $clave = $aux['Curso'];
                $clave .= " (" . $aux['Horas'] . " horas)";
            }
            if (!isset($array1[$clave])) {
                $array1[$clave] = array($aux['Fecha']);
            } else {
                array_push($array1[$clave], $aux['Fecha']);
            }
        }
        foreach ($array1 as $key => $value) {
            $Cont .= "<p>" . "$key" . ": " . $value['0'] . "";
            $i = 0;
            foreach ($value as $aux) {
                if ($i != 0) {
                    $Cont .= ", " . "$aux" . "";
                }
                $i++;
            }
            $Cont .= "</p>";
        }
        if (empty($array1)) {
            $Cont = "<p>No hay fechas disponibles.</p>";
        }
        $fila->free();
    } else {
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $Cont;
}

//Botones de admin para actualizar y borrar actividades.
function mostrarFuncionesAdmin()
{
    $content = <<<EOS
    <div class='submit'>
        <a href='ActualizarActividadAdmin.php?actividad=$_GET[actividad]'>
            <button type='submit'>Actualizar Actividad</button>
        </a>
        
    </div>
    <div class='submit'>
        <a href='ActualizarCursoAdmin.php?actividad=$_GET[actividad]'>
            <button type='submit'>Actualizar Cursos</button>
        </a>
    </div>
        <div class='submit'>
        <a href='Actualizar_InsertarCapacidadAdmin.php?actividad=$_GET[actividad]'>
        <button type='submit'>Actualizar/Insertar Capacidad</button>
        </a>
    </div>
    </div>
        <div class='submit'>
        <a href='EliminarCursoAdmin.php?actividad=$_GET[actividad]'>
        <button type='submit'>Eliminar Curso</button>
        </a>
    </div>
    </div>
        <div class='submit'>
        <button id='borrarActividad'>Borrar Actividad</button>
    </div>
    EOS;
    return $content;
}

//Listado de cursos disponibles en la BD
function listadoCursos()
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();

    $Cont = NULL;
    $array = array();

    $row=$conn->query(sprintf("SELECT C.nombre_actividad, C.nombre_curso, C.precio, C.horas FROM CursosActividades C ORDER BY C.nombre_actividad, C.nombre_curso"));
    if($row){
        for($i=0;$i<$row->num_rows;$i++){
            $aux=$row->fetch_assoc();
            if($aux['horas'] == 0){
                $valor = "".$aux['nombre_curso']." ";
            }
            $valor .= "(" . $aux['precio'] . "€)";
            if (!isset($array[$aux['nombre_actividad']])) {
                $array[$aux['nombre_actividad']] = array($valor);
            } else {
                array_push($array[$aux['nombre_actividad']], $valor);
            }
        }
        foreach ($array as $key => $value) {
            $Cont .= "<p>" . "$key" . ": " . $value['0'] . "";
            $i = 0;
            foreach ($value as $aux) {
                if ($i != 0) {
                    $Cont .= ", " . "$aux" . "";
                }
                $i++;
            }
            $Cont .= "</p>";
        }
        if (empty($array)) {
            $Cont = "<p>No existe ningun curso en la BD. Por favor inserte una</p>";
        }
        $row->free();
    } else {
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $Cont;
}

//Listado de plazas disponibles de una actividad en la BD
function listadoPlazas($nombre)
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();

    $Cont = NULL;

    $row = $conn->query(sprintf(
        "SELECT * FROM CapacidadActividad WHERE Nombre='%s'",
        $conn->real_escape_string($nombre)
    ));
    $row=$conn->query(sprintf("SELECT * FROM CapacidadActividad WHERE Nombre='%s' ORDER BY Fecha"  
    , $conn->real_escape_string($nombre)));
    if($row){
        if($row->num_rows > 0){
            $Cont = "<table >
                        <tr>
                        <th colspan='2'>Nombre</th>
                        <th colspan='2'>Curso</th>
                        <th colspan='2'>Fecha</th>
                        <th colspan='2'>Plazas</th>
                        </tr>";
            for ($i = 0; $i < $row->num_rows; $i++) {
                $fila = $row->fetch_assoc();
                $Cont .= "<tr>
                            <td>$fila[Nombre]<td>
                            <td>$fila[Curso]<td>
                            <td>$fila[Fecha]<td>
                            <td>$fila[Capacidad]<td>
                            </tr>
                ";
            }
            $Cont .= "</table>";
        } else {
            $Cont = "<p>No existe hay plazas disponibles en la BD. Por favor inserte una</p>";
        }
        $row->free();
    } else {
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $Cont;
}

//Cursos de una actividad para <select> por defecto
function cursosDeActividad($nombre, &$hora, &$precio)
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $row = $conn->query(sprintf(
        "SELECT nombre_curso, horas, precio FROM CursosActividades WHERE nombre_actividad = '%s'",
        $conn->real_escape_string($nombre)
    ));
    $cont = "";
    if ($row) {
        $cont = "";
        for ($i = 1; $i <= $row->num_rows; $i++) {
            $fila = $row->fetch_assoc();
            $cont .= "<option>" . "$fila[nombre_curso]" . "</option>";
            if ($i == 1) {
                $hora = $fila['horas'];
                $precio = $fila['precio'];
            }
        }
        $row->free();
    } else {
        echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $cont;
}

//Cursos de una actividad para <select>
function cursosDeActividadDinamico($nombre)
{
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $row = $conn->query(sprintf(
        "SELECT nombre_curso FROM CursosActividades WHERE nombre_actividad = '%s'",
        $conn->real_escape_string($nombre)
    ));
    $cont = "";
    if ($row) {
        $cont = "";
        for ($i = 1; $i <= $row->num_rows; $i++) {
            $fila = $row->fetch_assoc();
            $cont .= "<option>" . "$fila[nombre_curso]" . "</option>";
        }
        $row->free();
    } else {
        echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $cont;
}
function mostrarEntradaPerfil($ent)
{
    $comentarios = "
        <div class='entrada'>
           <a href ='procesarEntradaBlog.php?entrada=".$ent->getId()."'><h1>" . $ent->getTitulo() . "</h1></a>
            <img src='" . $ent->getImagen() . "' alt=''>
        </div>";
    return $comentarios;
}
function mostrarEntradasPerfil($idUsuario){
    $entradas = "";
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaValoraciones = sprintf("SELECT V.*FROM entradasBlog V JOIN Usuarios U ON V.idAutor = U.id WHERE $idUsuario = V.idAutor");
    $row = $conn->query($tablaValoraciones);
    $numVal = $row->num_rows;
    if ($numVal == 0) {
        $entradas = "<p>Aún no has escrito ninguna entrada</p>";
    }
    for ($i = 0; $i < $numVal; $i++) {
        $rs = $row->fetch_assoc();
        $val = new entradaBlog($rs['titulo'],null,null,null,null,$rs['rutaImagen'],null,$rs['idAutor']);
        $val->setId($rs['id']);
        $entradas .= mostrarEntradaPerfil($val);
    }
    $row->free();
    return $entradas;
}
//Muestra el perfil de un usuario
function perfilUsuario($nombreUsuario)
{
    $usuario = Usuario::buscaUsuario($nombreUsuario);
    $listado = infoUsuario($nombreUsuario);
    $idU = Usuario::buscaIdDelUsuario($_SESSION['nombreUsuario']);
    $entradas= mostrarEntradasPerfil($idU);
    $comentarios = mostrarTodosPerfil($idU);
    $valoraciones = mostrarTodasValoracionesPerfil($idU);
    $contenidoPrincipal = "
    <div class='perfil'>
        <div class='header'>
            <div class='portada'>
                    <img class = 'avatar' src='" . $usuario->getRutaFoto() . "' alt='Foto'>
            </div>
        </div>
        <div class='body'>
            <div class='bio'>
            <h3>¡Bienvenido a tu perfil " . $usuario->getNombreUsuario() . "!</h3>
            <p>Descripción detalla del usuario</p>
            <div class='datos1'>
                <li><span>Nombre: </span>" . $usuario->getNombre() . "</li>
                <li><span>Apellido: </span>" . $usuario->getApellido() . "</li>
                <li><span>Correo: </span>" .  $usuario->getCorreo() . "</li>
            </div>
            <div class='datos2'>
                <li><span>Telefono: </span>" .  $usuario->getTelefono() . "</li>
                <li><span>Nacionalidad: </span>" .  $usuario->getNacionalidad() . "</li>
                <li><span>Fecha de nacimiento: </span>" .  $usuario->getFechaNac() . "</li>
            </div>
            <div class='datos3'>
                <a class='adatos3' href='Perfil.php?editar=true'>Editar perfil <img class='icon-datos3' src='img/editar.png' alt=''></a>
            </div>
                </div>
            <div class='footer'>
                $listado
            </div>
            <div class='footer2'>
                <h1>Entradas</h1>" .
        $entradas . "
            </div>
            <div class='footer2'>
                <h1>Comentarios</h1>" .
        $comentarios . "
            </div>
            <div class='footer2'>
                <h1>Valoraciones</h1>" .
        $valoraciones . "
            </div>
        </div>
        </div>
        </div>
        
    </div>
    ";

    return $contenidoPrincipal;
}

//Si no es admin, muestra todas las actividades y hoteles reservados por el usuario
//En caso contrario muestra diferentes enlaces para añadir por ejemplo nuevas actividades
function infoUsuario($nombreUsuario)
{
    $usuario = Usuario::buscaUsuario($nombreUsuario);
    $contenido = "";
    if (!$_SESSION['esAdmin']) {
        $app = Aplicacion::getSingleton();
        $conn = $app->conexionBd();
        $rs = $conn->query(sprintf("SELECT * FROM ListaActividades LA WHERE LA.idUsuario = '%d'", $usuario->getId()));
        if ($rs) {
            $textoActividad = "<h1>Listado de <span>actividades</span> inscritas</h1>";
            if ($rs->num_rows == 0) {
                $textoActividad .= "<p>No te has inscrito en ninguna de las actividades</p>";
            } else {
                for ($i = 0; $i < $rs->num_rows; $i++) {
                    $act = $rs->fetch_assoc();
                    $textoActividad .= "<p>$act[nombre]: $act[curso] en el dia $act[dia]</p>";
                }
            }
            $rs->free();
            $contenido = $textoActividad;
            $rs = $conn->query(sprintf("SELECT * FROM listaAlojamiento LA WHERE LA.idUsuario = '%d'", $usuario->getId()));
            if ($rs) {
                $textoAlojamiento = "<h1>Listado de <span>hoteles</span> reservados</h1>";
                if ($rs->num_rows == 0) {
                    $textoAlojamiento .= "<p>No te has inscrito en ningún hotel</p>";
                } else {
                    for ($i = 0; $i < $rs->num_rows; $i++) {
                        $act = $rs->fetch_assoc();
                        $habitaciones = $act["NumeroHabitacion"];
                        if ($habitaciones > 1) {
                            $textoAlojamiento .= "<p>$act[nombreAlojamiento]: $act[fechaini] - $act[fechafin] ($habitaciones habitaciones)</p>";
                        } else {
                            $textoAlojamiento .= "<p>$act[nombreAlojamiento]: $act[fechaini] - $act[fechafin] ($habitaciones habitación)</p>";
                        }
                    }
                }
                $rs->free();
                $contenido .= $textoAlojamiento;
            } else {
                echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    } else {
        $contenido = "<h1>Enlaces para  las <span>funcionalidades de admin</span></h1>";
        $contenido .= "<div class='submit'>
                        <a href=editor.php>
                        <button type='submit' name='Entrada'>Añadir Entrada Blog</button>
                        </a>
                    </div>
                    <div class='submit'>
                        <a href=Actividad_Admin.php>
                        <button type='submit' name='Actividades'>Añadir Actividad</button>
                        </a>
                    </div>
                    <div class='submit'>
                        <a href=Material_Admin.php>
                        <button type='submit' name='Material'>Añadir Material</button>
                        </a>
                    </div>
                        <div class='submit'>
                        <a href=Alojamiento_Admin.php>
                        <button type='submit' name='Alojamientos'>Añadir Alojamiento</button>
                        </a>
                    </div>
                        ";
    }
    return $contenido;
}

//Diferentes paises para seleccionar la nacionalidad
function nacionalidad($usuario)
{
    $result = "";
    $paises = array("Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda", "Arabia Saudita", "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladés", "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia", "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", "Bután", "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China", "Chipre", "Ciudad del Vaticano", "Colombia", "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "España", "Estados Unidos", "Estonia", "Etiopía", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabón", "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana", "Guinea", "Guinea ecuatorial", "Guinea-Bisáu", "Haití", "Honduras", "Hungría", "India", "Indonesia", "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón", "Israel", "Italia", "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati", "Kuwait", "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos", "Mauricio", "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", "Mozambique", "Namibia", "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega", "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", "Palaos", "Palestina", "Panamá", "Papúa Nueva Guinea", "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido", "República Centroafricana", "República Checa", "República de Macedonia", "República del Congo", "República Democrática del Congo", "República Dominicana", "República Sudafricana", "Ruanda", "Rumanía", "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas", "Santa Lucía", "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", "Singapur", "Siria", "Somalia", "Sri Lanka", "Suazilandia", "Sudán", "Sudán del Sur", "Suecia", "Suiza", "Surinam", "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago", "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda", "Uruguay", "Uzbekistán", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Yibuti", "Zambia", "Zimbabue");
    foreach ($paises as $i) {
        if (strcmp($i, $usuario->getNacionalidad()) == 0) {
            $result .= "<option selected='selected'>" . $i . "</option>";
        } else {
            $result .= "<option>" . $i . "</option>";
        }
    }
    return $result;
}

//Informacion de un material
function infoMaterial(&$tituloPagina, &$tituloCabecera){
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
            <img src= $rs[imagen] alt=''>
        </div>
        <div class='contenidoMaterial'>
        <div class = 'tituloInfo'>
            Descripción detallada del producto: <br/>
        </div>
        <p>"."$rs[desc_det]</p><br/>
        <div class='nota-valoracion'>".mostrarEstrellasFijo(Valoracion::notaMedia($tituloPagina))."
        </div>
        <p>"." <precio>Precio del producto: </precio>"." $rs[precio] "." €</p>
        <link rel='stylesheet' href='css/material.css'>
        ";

            $Cont .= "
            <form action='agregar_al_carrito.php' method='post'>
                <input type='hidden' name='id_producto' value= '$rs[id]'>
                <label>Selecciona una cantidad:</label>
                <input type ='number' name='cantidad' min='1' value= ''>
                <button class='carrito'>
                    <span2>Añadir</span2>
                    <i class='fa fa-shopping-basket' aria-hidden='true'></i>
                </button>
            </form>";

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

//Mostrar todos los materiales
function materialMain(&$tituloPagina, &$tituloCabecera){
    $contenidoPrincipal = NULL;
    $tituloPagina = "Materiales";
    $tituloCabecera = "MATERIALES";
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaMaterial_Main=sprintf("SELECT * FROM Materiales");
    $rs =$conn->query($tablaMaterial_Main);
    $tableCont="<tr>";
    $j=0;
    for($i=1;$i<=$rs->num_rows;$i++){
        $fila=$rs->fetch_assoc();
        $row=$conn->query(sprintf("SELECT * FROM Materiales M WHERE M.id = '$fila[id]'"));
        $contenido =$row->fetch_assoc();
        $url=rawurlencode("$contenido[nombre]");
        $rowCount = "<td>
        <div class = 'contenido'>
            <div class = 'card'>
                <a href ="."material.php?material=".$url."><img src= $contenido[imagen] alt=''> </a>
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
    return $contenidoPrincipal;
}

//Informacion de un Alojamiento
function infoAlojamiento(&$tituloPagina, &$tituloCabecera){
    $tituloPagina = htmlspecialchars($_GET["alojamiento"]);
    $tituloCabecera = strtoupper($tituloPagina);
    $contenidoPrincipal ="";
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaActividad=sprintf("SELECT * FROM Alojamiento A WHERE A.nombre LIKE '%s' "
                            , $conn->real_escape_string($tituloPagina));
    $row = $conn->query($tablaActividad);
    if($row){
        $rs=$row->fetch_assoc();
        $Cont="<h3><span>Información detallada</span> del hotel "."$tituloPagina"."</h3>
        <p>"."$rs[descripciondetallada]"."</p>";
        $contenidoPrincipal = <<<EOS
            $Cont
        EOS;
        $row->free();
    }else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $contenidoPrincipal;
}

//Muestra todos los Alojamientos
function AlojamientoMain(){
    $contenidoPrincipal = NULL;
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaAlojamiento_Main=sprintf("SELECT * FROM Alojamiento");
    $rs = $conn->query($tablaAlojamiento_Main);
    $tableCont=NULL;
    if($rs)
    {
        for($i=1;$i<=$rs->num_rows;$i++){
            $id=$rs->fetch_assoc();
            $row=$conn->query(sprintf("SELECT * FROM Alojamiento A WHERE A.id = '$id[id]'"));
            if($row)
            {
                $contenido=$row->fetch_assoc();
                $url=rawurlencode("$contenido[nombre]");
                $leftCont =  "<div><td>
                    <a href ="."alojamiento.php?alojamiento=".$url."><img class='img-pag-prin' src= '$contenido[rutaFoto]' alt=''> </a>
                        </td></div>";
                $rightCont = "<div><td>
                <h2><a href = "."alojamiento.php?alojamiento=".$url.">"."$contenido[nombre]"." </a></h2>
                    "."$contenido[descripcion]"."
                <a href = "."alojamiento.php?alojamiento=".$url.">Leer más</a></p>
                </td></div>";

                if($i%2==0){
                    $aux=$leftCont;
                    $leftCont=$rightCont;
                    $rightCont=$aux;
                }
                $tableCont.="<tr>"."$leftCont"."$rightCont"."</tr>";
                $row->free();
            }else{
                echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        $contenidoPrincipal = <<<EOS
        <p>Alojamientos disponibles en SeaWolf Deportes Náuticos. </p>
        
        <table>$tableCont
        </table>
        EOS;
        $rs->free();
    }else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $contenidoPrincipal;
    
}

//Muestra todas las plazas disponibles
function listadoCapacidad(){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $Cont=NULL;
    $row=$conn->query(sprintf("SELECT * FROM Habitaciones ORDER BY nombre_alojamiento,fecha "));
    if($row){
        if($row->num_rows > 0){
            $Cont = "<table >
                        <tr>
                        <th colspan='2'>Nombre</th>
                        <th colspan='2'>Fecha</th>
                        <th colspan='2'>Plazas</th>
                        </tr>";
            for($i=0;$i<$row->num_rows;$i++){
                $fila = $row->fetch_assoc();
                $Cont .="<tr>
                            <td>$fila[nombre_alojamiento]<td>
                            <td>$fila[fecha]<td>
                            <td>$fila[capacidad]<td>
                            </tr>
                ";
            }
            $Cont .= "</table>";
        }
        else{
            $Cont = "<p>No existe hay plazas disponibles en la BD. Por favor inserte una</p>";
        }
        $row->free();
    }else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $Cont;
}
