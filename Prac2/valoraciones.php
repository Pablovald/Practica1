<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <link  rel="icon" href="img/favicon.png" type="image/png" />
  <title>Valoraciones </title>
</head>

<body>
<?php require 'links.php'; ?>
<! meter el logo >
<! aqui iria tambien el otro indice>
<h1> Usuario </h1> <! igual aqui poner el nombre propio del usuario>
<h2> Actividades realizadas </h2>
<! Aqui vendria una lista con sus distintas actividades realizadas, y haciendo click deberia llevarle a su pagina propia>
<select name ="Actividades"> 
    <option value="0">Selecciona una actividad</option>
    <! aqui las opciones serian las distintas actividades realizadas, que igual las podemos coger de la base de datos>
</select>
<! algo igual para poner de 0 a 5 estrellas? o algo para indicar la satisfaccion>
<fieldset>
    Indicanos tu opinion acerca de esta actividad!<br/>
    <textarea name="valoracion" rows="6" cols="80" required></textarea> <br/>
    <form name="fichero" action="procesa_fichero.php" method="post"
        enctype="multipart/form-data">
        <!<input type=“hidden” name=“MAX_FILE_SIZE” value=“30000” />
        Imagen: <input type="file" name="archivo" />
        <input type="submit" value="Enviar">
    </form>
</fieldset>

<! algo para poder añadir imagenes o videos?>
<button type="submit"> Enviar valoracion</button>
<button type="reset"> Borrar valoracion</button>
</body>

</html>