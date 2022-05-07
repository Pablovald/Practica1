<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="includes/js/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="includes/js/ejercicio4.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="img/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="css/BlogView.css" />
	<link rel="stylesheet" type="text/css" href="css/Vista.css" />
	<link rel="stylesheet" type="text/css" href="css/VistaEstilos.css" />

	
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
			<div>
				<?=$formularioComentario?>
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