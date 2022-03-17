<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="favicon.png" type="image/png" />
    <title><?= $tituloPagina ?></title>
</head>
<body>
<div id="contenedor">
<?php
	require("links.php");
	require("cabecera.php");
?>
	<main>
		<article>
			<?= $contenidoPrincipal ?>
		</article>
	</main>
	<br/> <br/>
<?php
	require("pie.php");
?>
</div>
</body>
</html>