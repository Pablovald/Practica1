<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="img/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="Login.css" />
    <title><?= $tituloPagina ?></title>
</head>
<body>
<div id="contenedor">
	<img class = "dimension" src = "img/seawolf logo.png">;
<?php
	require("includes/comun/links.php");

?>
	<main>
		<article>
			<?= $contenidoPrincipal ?>
		</article>
	</main>
	<br/> <br/>
</div>
</body>
</html>