<?php

require ("config.php");

$tituloPagina = 'Kayak';

$tituloCabecera = 'Kayak';

$contenidoPrincipal = <<<EOS
<h3>Horarios del curso de kayak:</h3>
<p>
    Las clases tienen una duración de 2 horas, pudiendo cambiarse la fecha y horario con solicitud previa (siendo obligatorio para las clases privadas solicitar previamente la fecha y hora):</p>
    <p>Lunes y Miércoles de 16:00 a 18:30</p>
    <p>Sabado y Domingo de 12:30 a 15:00</p>
   <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos).
</p>
<<<<<<< HEAD
<h4> Precios del curso </h4>
	<p>140€ curso completo (12 horas).</p>
	<p>85 curso medio completo (6 horas).</p>
	<p>25€ clase privada.</p>
<h2> Formulario de inscripción </h2>
=======
<div align="right><img src = "kayakActividad2.jpg"></div>
>>>>>>> 0bb42b35a8796f80d87c18f59b966955c174b6ac
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
    <option>Lunes</option>
    <option>Miércoles</option>
    <option>Sábado</option>
    <option>Domingo</option>
    <option>Otro</option>
    </select><br>
    <br>
    <input type="submit" value="Inscribirse">
</form>
EOS;

require("plantilla.php");