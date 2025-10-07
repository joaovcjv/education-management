<?PHP
	require_once("../../../config/config.php");
	require_once("../../../comum/funcoes.php");
	function parseToXML($htmlStr){
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}
	$conexao=conectar($dados_conexao);
	$consulta = "
				SELECT m.anexo_rgi, i.nome, m.outros_docs, i.Bairro_id, ST_LONGITUDE(m.localizacao) as longitude, ST_LATITUDE(m.localizacao) as latitude
				FROM instituicoes_educacionais i, imobiliario m
				WHERE i.id=m.instituicoes_educacionais_id 
			";
	$resultado_consulta = mysqli_query($conexao, $consulta);
	if (!$resultado_consulta){
		sair("Consulta inválida: ".mysqli_error($conexao), $conexao);
	}

	header("Content-type: text/xml");

	$xml = new DOMDocument("1.0");
	$xml->formatOutput=true;

	$ies=$xml->createElement("ies");
	$xml->appendChild($ies);

	while ($linha = mysqli_fetch_assoc($resultado_consulta)){
		$ie=$xml->createElement("ie");
		$ies->appendChild($ie);

		$anexo_rgi=$xml->createElement("anexo_rgi", $linha["anexo_rgi"]);
		$ie->appendChild($anexo_rgi);

		$nome=$xml->createElement("nome", $linha["nome"]);
		$ie->appendChild($nome);
		
		$outros_docs=$xml->createElement("outros_docs", $linha["outros_docs"]);
		$ie->appendChild($outros_docs);
		
		$bairro_id=$xml->createElement("Bairro_id", $linha["Bairro_id"]);
		$ie->appendChild($bairro_id);

		$longitude=$xml->createElement("longitude", $linha["longitude"]);
		$ie->appendChild($longitude);

		$latitude=$xml->createElement("latitude", $linha["latitude"]);
		$ie->appendChild($latitude);

	}
	echo $xml->saveXML();
?>