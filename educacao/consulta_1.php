<!DOCTYPE html>
<html>
	<head>
		<?php require_once("php/config/config.php"); ?>
		<?php require_once("inc/head.php"); ?>
	</head>		
	<body>
		<?php require_once("inc/header.php"); ?>
		<hr>
		<h3>Verificação do bairro, estado e município das instituições educacionais</h3>
		<div id="local_consulta_1"></div>
		<br>
	</body>
	<script src="js/comum/funcoes.js?versao=<?php echo $versao; ?>"></script>
	<script src="js/consultas/consulta_1.js?versao=<?php echo $versao; ?>"></script>
</html>