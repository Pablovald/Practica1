<?php

require ("config.php");

$tituloPagina = 'Kitesurf';

$tituloCabecera = 'KITESURF';

$contenidoPrincipal = <<<EOS
<h3>Información del curso kitesurf:</h3>
<p>
    Las clases tienen una duración de 2 horas, pudiendo haber cambio de horario debido a las condiciones climáticas y medioambientales provocadas por el viento y oleaje. 
	Disponemos de diferentes niveles según el conociminiento del deporte y edad de cada alumno. El profesor es el encargado de seleccionar el nivel de cada integrante, tras una 
	prueba inicial.</p>
	<h4> Horarios disponibles </h4>
    <p> Jueves y Viernes de 16:00 a 18:00 </p>
    <p> Sabado y Domingo de 11:30 a 13:30</p>
    <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos). </p>
	<h4> Precios del curso </h4>
	<p>200€ curso completo (8 horas).</p>
	<p>165€ curso medio completo (4 horas)</p>
	<p>55€ clase privada.</p>
	
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
    <option>Clase privada</option>
    </select><br>
    <br>
    <label for="dia">Selecciona el dia de la semana que prefieres para las clases</label><br>
    <select name="dia">
    <option>Jueves</option>
    <option>Viernes</option>
    <option>Sábado</option>
    <option>Domingo</option>
    <option>Otro</option>
    </select><br>
    <br>
    <input type="submit" value="Inscribirse">
</form>
EOS;

require("plantilla.php");