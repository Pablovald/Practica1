<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset = "UTF-8">
  <link  rel="icon"   href="img/favicon.png" type="image/png" />
  <title>Actividades</title>
</head>
<body>
<?php require("links.php")?>
	<h1 align="center">Titulo del actividad</h1>
	<p align="center">Descripción del actividad<p>
	<table>
		<tr>
			<td>
				<form action="mailto:seawolfdeportesnauticos@gmail.com" method="POST" enctype="text/plain" name="Inscripción">
					<fieldset>
					<legend>Formulario de inscripción</legend>
					Nombre: <input type="text" name="Nombre" value="" required ><br/>
					Email: <input type="text" name="Correo" value="" required><br/>
					</fieldset>
					<input name="Termino de condicion" type="checkbox" value="check" required> Marque esta casilla para verificar que ha leído nuestros términos y condiciones del servicio <br/><br/>
					<button type="submit"> Enviar formulario</button>
					<button type="reset"> Borrar formulario </button>
				</form>
			</td>
			<td>
				<label for="start">Fecha de comienzo:</label>
				<input type="date" id="inicio" name="fecha de comienzo" value="2022-03-01" min="2022-03-01" max="20-12-31"><br/>
				<label for="start">Fecha de finalización:</label>
				<input type="date" id="fin" name="fecha de finalizacion" value="2022-03-01" min="2022-03-01" max="20-12-31">
			</td>
		</tr>
	</table>
</body>
</html>