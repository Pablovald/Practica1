
<?php
require_once __DIR__. '/includes/config.php';

$contenidoPrincipal = es\fdi\ucm\aw\Material::materialMain($tituloPagina, $tituloCabecera);


include __DIR__. '/includes/plantillas/plantilla.php';