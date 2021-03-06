<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <link  rel="icon" href="img/favicon.png" type="image/png" />
  <title>Página de contacto </title>
</head>

<body>
<?php require 'links.php'; ?>
  <h1>Contacta con nosotros</h1>
  <p>Rellena el siguiente formulario para contactar con el equipo de SeaWolf</p>
    <form action="mailto:seawolfdeportesnauticos@gmail.com" method="POST" enctype="text/plain"
    name="Contacto">
    <fieldset>
      <legend>Formulario de Contacto</legend>
      Nombre: <input type="text" name="Nombre" value="" required ><br/>
      Email: <input type="text" name="Correo" value="" required><br/>
      Motivo de la consulta: <br />
      <input type="radio" name="Motivo" value="Evaluacion" checked> Evaluación<br/>
      <input type="radio" name="Motivo" value="Sugerencias" /> Sugerencias<br/>
      <input type="radio" name="Motivo" value="Criticas" /> Críticas <br/>
      Escriba aquí su consulta:<br/>
      <textarea name="Consulta" rows="4" cols="50" required></textarea> <br/>
    </fieldset>
    <input name="Termino de condicion" type="checkbox" value="check" required> Marque esta casilla para verificar
    que ha leído nuestros términos y condiciones del servicio <br/><br/>

    <button type="submit"> Enviar formulario</button>
    <button type="reset"> Borrar formulario </button>
  </form>
</body>

</html>