<?php
	require_once("../../config/config.php");
	require_once("../../comum/funcoes.php");
	$conexao=conectar($dados_conexao);
	$consulta = "
				SELECT i.nome, a.nivel_acessibilidade
				FROM instituicoes_educacionais i, arquitetura a
				WHERE NOT nivel_acessibilidade BETWEEN 3 AND 5 and i.id=a.instituicoes_educacionais_id
			";
	$resultado_consulta = mysqli_query($conexao, $consulta);
	if (!$resultado_consulta){
		sair("Consulta inválida: ".mysqli_error($conexao), $conexao);
	}
	echo "<table class='tabela_resultado'>";
	echo "<tr><th>Escola</th><th>Nível de Acessibilidade</th></th>";
	while ($linha = mysqli_fetch_assoc($resultado_consulta)){
		echo "<tr>";
		echo "<td>".$linha["nome"]."</td><td>".$linha["nivel_acessibilidade"]."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>