<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = '';

$tituloCabecera = 'Jötunn, el kayak extremo';

$contenidoPrincipal = <<<EOS
<h1></h1>
<p></p></br>
<div align="center"><img src = "" width="600" height="400"></div></br>
<h2></h2>
<p></p>
<p></p>
<div align="center"></div>
<div align="center">
<p> Trailer del documental</br>
<iframe width="600" height="400" src="https://www.eitb.eus/es/get/multimedia/screen/id/8510057/tipo/videos/deportes/" frameborder="0" allow="accelerometer; autoplay; 
    clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
EOS;

include __DIR__.'/includes/plantillas/plantilla.php';