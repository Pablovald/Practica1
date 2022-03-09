<?php

require ("config.php");

$tituloPagina = 'Contacto';

$tituloCabecera = 'CONTACTO';

$contenidoPrincipal = <<<EOS
<h1>Contacta con nosotros</h1>

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
EOS;


require ("plantilla.php");
