<?php

require ("config.php");

$tituloPagina = 'Kayak';

$tituloCabecera = 'Kayak';

$contenidoPrincipal = <<<EOS

<table>
    <tr>
        <td>
            <h3>Información del curso de kayak:</h3>
            <p>
            Las clases tienen una duración de 2 horas, pudiendo cambiarse la fecha y horario con solicitud previa (siendo obligatorio para las clases privadas solicitar previamente la fecha y hora):</p>
            <p>Lunes y Miércoles de 16:00 a 18:30</p>
            <p>Sabado y Domingo de 12:30 a 15:00</p>
            <p> Los cursos, por lo normal, se realizarán impartiendo una única clase semanal (ampliable a 2 semanales en el caso de los cursos completos).
            </p>

            <h4> Precios del curso </h4>
	        <p>140€ curso completo (12 horas).</p>
	        <p>85 curso medio completo (6 horas).</p>
	        <p>25€ clase privada.</p>
        </td>
        <td>
            <br>
            <div align="right">
            <img src = "kayakActividad2.jpg" width="600" height="400"></div>
            </div>
        </td>
    </tr>
</table>
<div align="left">
<h2> Formulario de inscripción </h2>
<form action="procesarInscripcion.php" method="POST">
<label for="actividad">Actividad:</label>
<input type="text" id="actividad" name="actividad" value="kayak" readonly><br>
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
<label for="dia">Selecciona la fecha para las clases</label><br>
    <input type="date" id="dia" name="dia" value="2022-03-18"><br>
    <br>
<br>
<input type="submit" value="Inscribirse">
</form>
</div>

EOS;

require("plantilla.php");