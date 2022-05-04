<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="includes/js/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="includes/js/ejercicio4.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="img/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="loginEstilo.css" />
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