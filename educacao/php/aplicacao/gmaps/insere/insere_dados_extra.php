<?PHP
	require_once("../../../config/config.php");
	require_once("../../../comum/funcoes.php");
	$localizacao = $_GET['localizacao'];
	$localizacao = "POLYGON((".$localizacao."))";
	$conexao=conectar($dados_conexao);
	
	$consulta = "
					INSERT INTO setor_area(localizacao)
					VALUES(ST_GeomFromText(?, 4326))
				";
	$stmt = mysqli_prepare($conexao, $consulta);
	if (!$stmt) {
		sair(mysqli_error($conexao), $conexao);
	}
	mysqli_stmt_bind_param($stmt, "s", $localizacao);
	$sucesso = mysqli_stmt_execute($stmt);
	if (!$sucesso){
		sair(mysqli_stmt_error($stmt), $conexao, $stmt);
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conexao);
	exit;
?>