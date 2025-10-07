<?PHP
	require_once("../../../config/config.php");
	require_once("../../../comum/funcoes.php");
	$conexao=conectar($dados_conexao);
	$anexo_rgi = $_GET['anexo_rgi'];
	$outros_docs = $_GET['outros_docs'];
	$nome = $_GET['nome'];
	$Bairro_id = $_GET['Bairro_id'];
	$localizacao = $_GET['localizacao'];
	//$fgdfsd = date("Y-m-d H:i:s");
	//echo $geometria;
	$localizacao = "POINT(".$localizacao.")";
	
	$consulta = "
					INSERT INTO instituicoes_educacionais(nome, Bairro_id)
					VALUES(?,?)
				";
	$stmt = mysqli_prepare($conexao, $consulta);
	if (!$stmt){
		sair(mysqli_error($conexao), $conexao);
	}
	mysqli_stmt_bind_param($stmt, "si", $nome,$Bairro_id);
	$sucesso = mysqli_stmt_execute($stmt);
	if (!$sucesso){
		sair(mysqli_stmt_error($stmt), $conexao, $stmt);
	}
	mysqli_stmt_close($stmt);
	$instituicoes_educacionais_id = mysqli_insert_id($conexao);
	$consulta = "
					INSERT INTO imobiliario(localizacao, anexo_rgi, outros_docs, instituicoes_educacionais_id)
					VALUES(ST_GeomFromText(?, 4326), ?, ?, ?)
				";
	$stmt = mysqli_prepare($conexao, $consulta);
	if (!$stmt) {
		sair(mysqli_error($conexao), $conexao);
	}
	mysqli_stmt_bind_param($stmt, "sssi", $localizacao, $anexo_rgi, $outros_docs, $instituicoes_educacionais_id);
	$sucesso = mysqli_stmt_execute($stmt);
	if (!$sucesso){
		sair(mysqli_stmt_error($stmt), $conexao, $stmt);
	}
	
	mysqli_stmt_close($stmt);
	mysqli_close($conexao);
	exit;
?>