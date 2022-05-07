<?php
require_once __DIR__.'/includes/config.php';

$contenidoPrincipal = es\fdi\ucm\aw\Actividad::infoActividad($tituloPagina, $tituloCabecera);
$form = new es\fdi\ucm\aw\FormularioActividad();
$htmlFormIns = $form->gestiona();
$contenidoPrincipal .=$htmlFormIns;
$form2 = new es\fdi\ucm\aw\FormularioValoracion();
$formularioComentario = $form2->gestiona();
$comentarios=es\fdi\ucm\aw\Valoracion::mostrarTodos($tituloPagina);
//Mensaje relacionado con inscripcion de una actividad
if(isset($_GET["estado"])){
    $estado = htmlspecialchars($_GET["estado"]);
    if(isset($_GET["actividad"]) && isset($_GET["dia"]) && isset($_GET["curso"])){
        $nombreActividad = htmlspecialchars($_GET['actividad']);
        $cursoActividad = htmlspecialchars($_GET['curso']);
        $solicitud_dia = htmlspecialchars($_GET['dia']);
        if($estado == "InscritoCorrectamente"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡Inscrito correctamente en $nombreActividad de $cursoActividad en el dia $solicitud_dia!</h1>
        EOS;
        }
        else if($estado == "error"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡La fecha: "$solicitud_dia" no es válida o que la hora seleccionada no es válida!</h1>
        EOS;
        }
        else if($estado == "capacidadError"){
            $contenidoPrincipal .= <<<EOS
            <h1>¡Para la fecha: "$solicitud_dia" no quedan plazas!</h1>
        EOS;
        }
    }
    else if($estado == "faltaLogin"){
        $contenidoPrincipal .= <<<EOS
        <h1>¡Necesitas estar loggeado en nuestra página web para reservar alojamientos!</h1>
        <h1>Si ya tienes una cuenta, inicia sesión.</h1>
    EOS;
    }
}

if(isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
    $contenidoPrincipal .= <<<EOS
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
}


include __DIR__.'/includes/plantillas/plantillaEntrada.php';