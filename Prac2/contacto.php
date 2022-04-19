<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Contacto';

$tituloCabecera = 'CONTACTO';

$contenidoPrincipal = <<<EOS
<h1>Contacta con nosotros</h1>

<form action="mailto:seawolfdeportesnauticos@gmail.com" method="POST" enctype="text/plain"
    name="Contacto">
    <div class='content'>
      <legend>Formulario de <span>contacto</span></legend></br>
	  <div class='formulario'>
		<div class='grupo-control'>
			<label>Nombre: </label>
			<input type="text" name="Nombre" value="" required >
		</div>
		<div class='grupo-control'>
			<label>Email: </label> 
			<input type="text" name="Correo" value="" required>
		</div>
		<div class= 'textbox'>
			<label>Escriba aquí su consulta: </label>
			<textarea name="Consulta" rows="4" cols="50" required></textarea>
		</div>
		<div class='seleccion'>
			<label>Motivo de la consulta: </label>
			<input type="radio" name="Motivo" value="Evaluacion" checked> Evaluación
			<input type="radio" name="Motivo" value="Sugerencias" /> Sugerencias
			<input type="radio" name="Motivo" value="Criticas" /> Críticas 
		</div>
		<div class = 'privacidad'>
			<input name="Termino de condicion" type="checkbox" value="check" required> 
			<label>Verificar nuestros términos y condiciones del servicio </label>
		</div>
		<div class='submit'>
			<button type="submit"> Enviar formulario</button>
		</div>
		<div class='submit'>
			<button type="reset"> Borrar formulario </button>
		</div>
		</div>
    </div>
	
  </form>
EOS;


include __DIR__.'/includes/plantillas/plantilla.php';
