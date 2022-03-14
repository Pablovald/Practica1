<?php

require ("config.php");

$tituloPagina = 'Uluwatu';

$tituloCabecera = 'Uluwatu';

$contenidoPrincipal = <<<EOS
<h1>Récords del mundo: el salto al agua más alto de la historia</h1>
<p>Lazaro Schaller, más conocido como Laso, se tiró desde un acantilado a 58,8 metros de altura en Maggia (Suiza) en el año 2015.</p></br>
<div align="center"><img src = "record.jpg" width="500" height="400"></div></br>
<p>De vez en cuando nos gusta recordar gestas que han tenido lugar antes del nacimiento de As Acción.
Si bien la sección de deportes de acción del Diario As ya lleva varios años en marcha,
antes de que estuviera informando de todo lo que ocurre en el mundo extremo y de la aventura ocurrieron muchísimas cosas.
Una de ellas, que se mantiene vigente desde entonces es el salto al agua desde tierra más alto de la historia.</p></br>
<p>El autor del récord es el clavadista suizo de ascendencia brasileña Lazaro Schaller,
más conocido como Laso. Este atleta nacido en 1988 encontró en Maggia (Suiza) la Cascata del Salto,
donde montó una plataforma desde la que saltó al agua. La peculiaridad es que esa plataforma estaba a más altura de la que se había saltado nunca.</p>
<p>Tal y como se puede ver en la recreación de la portada, el salto tenía 192.91 pies o lo que sería lo mismo, 
58,8 metros de altura. Eso significa que era incluso más alto que la Torre de Pisa, que mide 186 pies. 
Es decir, 56,69 metros. Y eso se traduce en que con el salto, 
Laso entró al agua a una velocidad que superaba las 75 millas por hora: 120 kilómetros por hora.
Como un coche por la autopista. Sin margen de error, porque un pequeño despista podía suponer su muerte o una lesión de la que no se recuperaría en la vida.</p>
<p align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/QUpiLY5SfEc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
<p>Lazaro Schaller lo hizo todo a la perfección. Saltó donde tocaba, cayó como tocaba y consiguió el récord del mundo de la modalidad. Para hacerse una idea de la gesta, en el circuito mundial de la especialidad de Cliff Diving se salta desde plataformas que no llegan nunca a los 30 metros. Es decir, dobló la altura. Eso sí, sin piruetas ni giros, claro. Otro dato que demuestra lo que consiguió es que el récord es de 2015 y seis años después sigue vigente. ¿Lo batirá alguien algún día? El tiempo lo dirá...</p></br>
EOS;

require ("plantilla.php");