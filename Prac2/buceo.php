<?php

require ("config.php");

$tituloPagina = 'Surf';

$tituloCabecera = 'SURF';

$contenidoPrincipal = <<<EOS
<h3>Información del curso surf:</h3>
<p>
    Las clases tienen una duración de 2 horas, pudiendo haber cambio de horario debido a las condiciones climáticas y medioambientales. Es obligatorio proporcionar al profesor 
	un informe médico antes del inicio del curso. Todos los cursos disponen de la titulación oficial según el Marco Europeo.</p>
	<h4> Horarios disponibles </h4>
    <p> Martes y Miercoles de 16:00 a 18:00 </p>
    <p> Sabado y Domingo de 9:30 a 11:30</p>
    <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
	<h4> Precios del curso </h4>
	<p>275€ curso completo avanzado(8 horas).</p>
	<p>225€ curso completo medio(4 horas).</p>
	<p>120€ bautizo</p>

<h2> Formulario de inscripción </h2>
<form>
    <label for="nombre">Nombre completo:</label><br>
    <input type="text" id="nombre" name="nombre"><br>
    <label for="dni">DNI:</label><br>
    <input type="text" id="dni" name="dni"><br>
    <label for="correo">Correo electrónico:</label><br>
    <input type="text" id="correo" name="correo"><br>
    <label for="fechaNac">Fecha de nacimiento(dd/mm/aa):</label><br>
    <input type="text" id="fechaNac" name="fechaNac"><br>
    <label for="telefono">Telefono (alumno o tutor legal):</label><br>
    <input type="text" id="telefono" name="telefono"><br>

    <p>¿El alumno sabe nadar?</p>
    <input type="radio" id="si" name="nadar" value="Si">
    <label for="si">Si</label><br>
    <input type="radio" id="no" name="nadar" value="No">
    <label for="no">No</label><br>
    <br>
    <label for="curso">Selecciona el tipo de curso al que quieres inscribirte:</label><br>
    <select name="curso">
    <option>Curso completo</option>
    <option>Medio curso</option>
    <option>Bautizo</option>
    </select><br>
    <br>
    <label for="dia">Selecciona la fecha para las clases</label><br>
    <input type="date" id="dia" name="dia"><br>
    <br>
    <br>
    <input type="submit" value="Inscribirse">
</form>
EOS;

require("plantilla.php");