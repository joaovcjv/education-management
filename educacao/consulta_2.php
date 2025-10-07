<!DOCTYPE html>
<html>
	<head>
		<?php require_once("php/config/config.php"); ?>
		<?php require_once("inc/head.php"); ?>
	</head>
	<body>
		<?php require_once("inc/header.php"); ?>
		<hr>
		<h3>Verificação de coordenadas dos pontos marcados</h3>
		<div id="local_consulta_2"></div>
		<br>
	</body>
	<script src="js/comum/funcoes.js?versao=<?php echo $versao; ?>"></script>
	<script src="js/consultas/consulta_2.js?versao=<?php echo $versao; ?>"></script>
</html>