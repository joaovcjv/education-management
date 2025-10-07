<?php
	require_once("../../config/config.php");
	require_once("../../comum/funcoes.php");
	$conexao=conectar($dados_conexao);
	$consulta = "
				SELECT i.nome, ST_LONGITUDE(m.localizacao) as longitude, ST_LATITUDE(m.localizacao) as latitude
				FROM instituicoes_educacionais i, imobiliario m
				WHERE i.id=m.instituicoes_educacionais_id
			";
	$resultado_consulta = mysqli_query($conexao, $consulta);
	if (!$resultado_consulta){
		sair("Consulta inválida: ".mysqli_error($conexao), $conexao);
	}
	echo "<table class='tabela_resultado'>";
	echo "<tr><th>Escola</th><th>Longitude</th><th>Latitude</th></th>";
	while ($linha = mysqli_fetch_assoc($resultado_consulta)){
		echo "<tr>";
		echo "<td>".$linha["nome"]."</td><td>".$linha["longitude"]."</td><td>".$linha["latitude"]."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>