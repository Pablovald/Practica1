<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="img/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="home1.css" />
    <title><?= $tituloPagina ?></title>

</head>
<body>
<div id="contenedor">
	
	<?php require("includes/comun/cabecera.php"); ?>

	<main>
		<article>
		<div class = "textPrincipal">
			<?= $contenidoPrincipal ?>
		</div>
		</article>
	</main>
	<br/> <br/>
<?php
	require("includes/comun/pie.php");
?>
</div>
</body>
</html>