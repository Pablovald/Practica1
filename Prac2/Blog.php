<?php

require ("config.php");

$tituloPagina = 'Blog';

$tituloCabecera = 'BLOG';

$contenidoPrincipal = <<<EOS
<h1>Blog</h1>
<p> Aquí está el contenido público, visible para todos los usuarios. </p>
EOS;

require ("plantilla.php");