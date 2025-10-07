<?php
	require_once("../../config/config.php");
	require_once("../../comum/funcoes.php");
	$conexao=conectar($dados_conexao);
	$consulta = "
				SELECT i.nome, i.Bairro_id
				FROM instituicoes_educacionais i
				ORDER BY i.Bairro_id
			";
	$resultado_consulta = mysqli_query($conexao, $consulta);
	if (!$resultado_consulta){
		sair("Consulta inválida: ".mysqli_error($conexao), $conexao);
	}
	echo "<table class='tabela_resultado'>";
	echo "<tr><th>Escola</th><th>ID Bairro</th></th>";
	while ($linha = mysqli_fetch_assoc($resultado_consulta)){
		echo "<tr>";
		echo "<td>".$linha["nome"]."</td><td>".$linha["Bairro_id"]."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>