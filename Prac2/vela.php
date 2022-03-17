<?php

require ("config.php");

$tituloPagina = 'Vela';

$tituloCabecera = 'VELA';

$contenidoPrincipal = <<<EOS
<h3>Información del curso vela:</h3>
<p>
    Las clases tienen una duración de 2 horas, pudiendo haber cambio de horario debido a las condiciones climáticas y medioambientales. Disponemos de diferentes barcos para 
	los diferentes niveles de aprendizaje, el profesor decidirá al inicio de la clase en que nivel será establecido el alumno.</p>
	<h4> Horarios disponibles </h4>
    <p> Martes y Jueves de 16:00 a 18:00 </p>
    <p> Sabado y Domingo de 12:30 a 14:30</p>
    <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
	<h4> Precios del curso </h4>
	<p>180€ curso completo (12 horas).</p>
	<p>99€ medio completo (6 horas).</p>
	<p>40€ bautizo.</p>
	
<h2> Formulario de inscripción </h2>
<form action="procesarInscripcion.php">
    <label for="nombre">Nombre completo:</label><br>
    <input type="text" id="nombre" name="nombre" required><br>
    <label for="dni">DNI:</label><br>
    <input type="text" id="dni" name="dni" required><br>
    <label for="correo">Correo electrónico:</label><br>
    <input type="text" id="correo" name="correo" required><br>
    <label for="fechaNac">Fecha de nacimiento(dd/mm/aa):</label><br>
    <input type="text" id="fechaNac" name="fechaNac" required><br>
    <label for="telefono">Telefono (alumno o tutor legal):</label><br>
    <input type="text" id="telefono" name="telefono" required><br>

    <p>¿El alumno sabe nadar?</p>
    <input type="radio" id="si" name="nadar" value="Si" checked>
    <input type="radio" id="si" name="nadar" value="Si" required>
    <label for="si">Si</label><br>
    <input type="radio" id="no" name="nadar" value="No" required>
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
    <input type="date" id="dia" name="dia" value="2022-03-18"><br>
    <input type="date" id="dia" name="dia" required><br>
    <br>
    <br>
    <input type="submit" value="Inscribirse">
</form>
EOS;

require("plantilla.php");