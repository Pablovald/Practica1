<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="img/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="VistaPrincipal.css" />
	<link rel="stylesheet" type="text/css" href="BlogView.css" />
	
    <title><?= $tituloPagina ?></title>

</head>
<body>
<div id="contenedor">
	
	<?php require("includes/comun/cabecera.php");
	?>

	<main>
		<article>
		<div class = "textPrincipal">
			<?= $contenidoPrincipal ?>
		</div>
		</article>
		<article>
			<div class = "comentarios">
				<?=$comentarios ?>
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