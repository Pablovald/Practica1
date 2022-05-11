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

//Muestra todas las actividades con su nombre, imagen y breve descripcion. Basicamente la informacion del contenido principal de Actividades_Main.php
function actividadMain(){
    $contenidoPrincipal = NULL;
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaActividad_Main=sprintf("SELECT * FROM Actividades");
    $rs =$conn->query($tablaActividad_Main);
    $tableCont=NULL;
    if($rs)
    {
        for($i=1;$i<=$rs->num_rows;$i++){
            $fila = $rs->fetch_assoc();
            $row=$conn->query(sprintf("SELECT * FROM Actividades A WHERE A.id = $fila[ID]"));
            if($row)
            {
                $contenido=$row->fetch_assoc();
                $url=rawurlencode("$contenido[Nombre]");
                $leftCont =  "<div><td>
                    <a href ="."actividad.php?actividad=".$url."><img class='img-pag-prin' src= '$contenido[rutaFoto]'> </a>
                    </td></div>";
                $rightCont = "<div><td>
                    <h2><a href = "."actividad.php?actividad=".$url.">"."$contenido[Nombre]"." </a></h2>
                    "."$contenido[Descripcion]"."
                    <a href = "."actividad.php?actividad=".$url.">Leer más</a></p>
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
        <p> Clases Disponibles en SeaWolf Deportes Náuticos. </p>
        <table>$tableCont
            </table>
        EOS;
        $rs->free();
    }
    else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $contenidoPrincipal;
}

    //Muestra el horario y el precio de una actividad
function infoActividad(&$tituloPagina, &$tituloCabecera){
    $tituloPagina = htmlspecialchars($_GET["actividad"]);
    $tituloCabecera = strtoupper($tituloPagina);
    $contenidoPrincipal ="";
    
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $tablaActividad=sprintf("SELECT * FROM Actividades A WHERE A.nombre LIKE '%s' "
                    , $conn->real_escape_string($tituloPagina));
    $row = $conn->query($tablaActividad);
    if($row){
        $rs=$row->fetch_assoc();
        $Cont="<h3><span>Información</span> del curso de "."$tituloPagina"."</h3>
        <p>"."$rs[info]"."</p>
        <h3> <span>Horarios </span>disponibles </h3>
        <p> Lunes a Viernes de 16:00 a 18:00 </p>
        <p> Sabado y Domingo de 11:30 a 13:30</p>
        <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
        <h3> <span>Precios</span> del curso </h3>";
        
        $row=$conn->query(sprintf("SELECT C.nombre_curso, C.precio, C.horas FROM CursosActividades C WHERE C.nombre_actividad LIKE '%s'"
                            , $conn->real_escape_string($tituloPagina)));
        if($row)
        {
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                if($act['horas'] == 0){
                    $Cont.="<p>".$act['nombre_curso'].": ".$act['precio']." €</p>";
                }
                else{
                    $Cont.="<p>".$act['nombre_curso']." (".$act['horas']." horas): ".$act['precio']." €</p>";
                }
            }
            $contenidoPrincipal = <<<EOS
                $Cont
                <h3><span>Fechas</span> de clases disponibles</h3>
            EOS;
            $row->free();
            $contenidoPrincipal .= mostrarFechas($tituloPagina);
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
    }
    else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $contenidoPrincipal;
}

    //Muestra las fechas disponibles para inscribirse en un curso de una actividad
function mostrarFechas($tituloPagina){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();

    $Cont=NULL;
    $array1= array();
    $fila = $conn->query(sprintf("SELECT C.Fecha, C.Curso, CUA.Horas FROM CapacidadActividad C JOIN CursosActividades CUA ON C.Curso = CUA.nombre_curso AND C.Nombre = CUA.nombre_actividad WHERE C.Nombre LIKE '%s' AND C.Capacidad > 0"
        , $conn->real_escape_string($tituloPagina)));
    if($fila)
    {
        $clave = "";
        for($i=0;$i<$fila->num_rows;$i++){
            $aux=$fila->fetch_assoc();
            if($aux['Horas'] == 0){
                $clave = $aux['Curso'];
            }
            else{
                $clave = $aux['Curso'];
                $clave .= " (".$aux['Horas']." horas)";
            }
            if(!isset($array1[$clave])){
                $array1[$clave] = array($aux['Fecha']);
            }
            else{
                array_push($array1[$clave], $aux['Fecha']);
            }
        }
        foreach($array1 as $key => $value){
            $Cont.="<p>"."$key".": ".$value['0']."";
            $i =0;
            foreach($value as $aux){
                if($i != 0){
                    $Cont.=", "."$aux"."";
                }
                $i++;
            }
            $Cont.= "</p>";
        }
        if(empty($array1)){
            $Cont = "<p>No hay fechas disponibles.</p>";
        }
        $fila->free();
    }
    else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $Cont;
}

//Botones de admin para actualizar y borrar actividades.
function mostrarFuncionesAdmin(){
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
function listadoCursos(){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();

    $Cont=NULL;
    $array= array();

    $row=$conn->query(sprintf("SELECT C.nombre_actividad, C.nombre_curso, C.precio, C.horas FROM CursosActividades C"));
    if($row){
        for($i=0;$i<$row->num_rows;$i++){
            $aux=$row->fetch_assoc();
            if($aux['horas'] == 0){
                $valor = "".$aux['nombre_curso']." ";
            }
            else{
                $valor = "".$aux['nombre_curso']."(".$aux['horas']." horas) ";
            }
            $valor .= "(".$aux['precio']."€)";
            if(!isset($array[$aux['nombre_actividad']])){
                $array[$aux['nombre_actividad']] = array($valor);
            }
            else{
                array_push($array[$aux['nombre_actividad']], $valor);
            }
        }
        foreach($array as $key => $value){
            $Cont.="<p>"."$key".": ".$value['0']."";
            $i=0;
            foreach($value as $aux){
                if($i != 0){
                    $Cont.=", "."$aux"."";
                }
                $i++;
            }
            $Cont.= "</p>";
        }
        if(empty($array)){
            $Cont = "<p>No existe ningun curso en la BD. Por favor inserte una</p>";
        }
        $row->free();
    }else{
        echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $Cont;
}

//Listado de plazas disponibles de una actividad en la BD
function listadoPlazas($nombre){
    $app = Aplicacion::getSingleton();
    $conn = $app->conexionBd();

    $Cont=NULL;
    
    $row=$conn->query(sprintf("SELECT * FROM CapacidadActividad WHERE Nombre='%s'"  
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
            for($i=0;$i<$row->num_rows;$i++){
                $fila = $row->fetch_assoc();
                $Cont .="<tr>
                            <td>$fila[Nombre]<td>
                            <td>$fila[Curso]<td>
                            <td>$fila[Fecha]<td>
                            <td>$fila[Capacidad]<td>
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

//Cursos de una actividad para <select> por defecto
function cursosDeActividad($nombre, &$hora, &$precio){
    $app=Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $row=$conn->query(sprintf("SELECT nombre_curso, horas, precio FROM CursosActividades WHERE nombre_actividad = '%s'"
    , $conn->real_escape_string($nombre)));
    $cont;
    if($row){
        $cont = "";
        for($i=1; $i<=$row->num_rows; $i++){
            $fila = $row->fetch_assoc();
            $cont .= "<option>"."$fila[nombre_curso]"."</option>";
            if($i == 1){
                $hora=$fila['horas'];
                $precio=$fila['precio'];
            }
        }
        $row->free();
    }
    else{
        echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $cont;
}

//Cursos de una actividad para <select>
function cursosDeActividadDinamico($nombre){
    $app=Aplicacion::getSingleton();
    $conn = $app->conexionBd();
    $row=$conn->query(sprintf("SELECT nombre_curso FROM CursosActividades WHERE nombre_actividad = '%s'"
    , $conn->real_escape_string($nombre)));
    $cont="";
    if($row){
        $cont = "";
        for($i=1; $i<=$row->num_rows; $i++){
            $fila = $row->fetch_assoc();
            $cont .= "<option>"."$fila[nombre_curso]"."</option>";
        }
        $row->free();
    }
    else{
        echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
        exit();
    }
    return $cont;
}