<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="includes/js/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="includes/js/ejercicio4.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link  rel="icon" href="img/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="css/Vista.css" />
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

	
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