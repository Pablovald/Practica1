<?php
require_once __DIR__. '/includes/config.php';

$tituloPagina = "Eliminar material";
$tituloCabecera = "Borrar materiales";

$contenidoPrincipal = <<<EOS
<p>Selecciona uno de los materiales para eliminarlo (ATENCION: se eliminaran todas
las existencias de este producto en todos los carritos y se eliminará permanentemente):</p>
<label for='cars'>Elige un producto:</label>
EOS;
$materiales = es\fdi\ucm\aw\Material::totalMateriales();
$contenidoPrincipal .= <<<EOS
<select name='producto' id='producto'>
EOS;
foreach($materiales as &$valor){
    $contenidoPrincipal .= <<<EOS
    <option value='$valor'>$valor</option>
    EOS;
}
$contenidoPrincipal .= <<<EOS
</select>
<form action='borrar_mat.php' method='post'>
    <input type="hidden" name="nombre" value="producto.select()">
        <button class="eliminar">
            <i class="fa fa-trash-o"></i>
            <span>Eliminar</span>
    </button>
</form>
EOS;

include __DIR__. '/includes/plantillas/plantilla.php';