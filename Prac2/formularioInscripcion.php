<?php




function buildFormularioInscripcion($actividad='',$app)
{
    $conn = $app->conexionBd();
    $row=$conn->query(sprintf("SELECT C.nombre_curso,C.precio FROM CursosActividades C WHERE C.nombre_actividad LIKE '$actividad'"));
    $ret = "
    <form id='formInscription' action='procesarInscripcion.php' method='POST'>
        <fieldset>
            <legend>Formulario de inscripción</legend>
            <label for='actividad'>Actividad:</label><br>
            <input type='text' id='actividad' name='actividad' value='$actividad' readonly></br>
            <label for='nombre'>Nombre completo:</label><br>
            <input type='text' id='nombre' name='nombre' required></br>
            <label for='dni'>DNI:</label>
            <br><input type='text' id='dni' name='dni' required></br>
            <label for='correo'>Correo electrónico:</label>
            <br><input type='text' id='correo' name='correo' required></br>
            <label for='fechaNac'>Fecha de nacimiento:</label>
            <br><input type='date' id='fechaNac' name='fechaNac' required></br>
            <label for='telefono'>Teléfono (alumno o tutor legal):</label>
            <br><input type='text' id='telefono' name='telefono' required></br>
            
            <p>¿El alumno sabe nadar?</p>
            <input type='radio' id='si' name='nadar' value='Si'>
            <label for='si'>Si</label><br>
            <input type='radio' id='no' name='nadar' value='No'>
            <label for='no'>No</label><br><br>
            <label for='curso'>Selecciona el tipo de curso al que quieres inscribirte:</label><br>
            <select name='curso'>";
            for($i=0;$i<$row->num_rows;$i++){
                $act=$row->fetch_assoc();
                $ret.="<option>"."$act[nombre_curso]"."</option>";
                }
            $ret.="</select><br>
            <label for='dia'>Selecciona la fecha para las clases</label><br>
            <input type='date' id='dia' name='dia' value='2022-03-18'>            
        </fieldset>
        <button type='submit'>Inscribirse</button>
    </form>";


return $ret;
}