<?php
	require_once("../../config/config.php");
	require_once("../../comum/funcoes.php");
	$conexao=conectar($dados_conexao);
	$consulta = "
				SELECT i.nome, b.nome_b, c.nome_c, e.nome_e
				FROM instituicoes_educacionais i, bairro b, cidade c, estado e
				WHERE i.Bairro_id=b.id and b.cidade_id = c.id and c.estado_id=e.id
			";
	$resultado_consulta = mysqli_query($conexao, $consulta);
	if (!$resultado_consulta) {
		sair("Consulta inválida: ".mysqli_error($conexao), $conexao);
	}
	echo "<table class='tabela_resultado'>";
	echo "<tr><th>Escola</th><th>Bairro</th><th>Cidade</th><th>Estado</th></th>";
	while ($linha = mysqli_fetch_assoc($resultado_consulta)){
		echo "<tr>";
		echo "<td>".$linha["nome"]."</td><td>".$linha["nome_b"]."</td><td>".$linha["nome_c"]."</td><td>".$linha["nome_e"]."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>