var mapa_google;
var marcador_google;
var infowindow_contribuicao;
var marcadores_carregados=[];
var infowindow_dado_carregado;
var geometria_desenhada_atual;
function inicializar(){
	var latlng = new google.maps.LatLng(-22.809483124078305, -42.99914094945563);
	var options = {
		zoom: 13,
		center: latlng,
		scaleControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	mapa_google = new google.maps.Map(document.getElementById("map_canvas"), options);
	infowindow_dado_carregado = new google.maps.InfoWindow();
	carregar_imoveis();
	google.maps.event.addListener(mapa_google, "click", function(event){
		if(infowindow_dado_carregado){
			infowindow_dado_carregado.close();
		}
		if(infowindow_contribuicao){
			infowindow_contribuicao.close();
		}
		if(marcador_google){
			marcador_google.setMap(null);
		}
		if(geometria_desenhada_atual){
			geometria_desenhada_atual.setMap(null);
		}
		var html =	
		/*
		'<link rel="stylesheet" href="css/gmaps.css">'+		
					'<div id="teste">'+
						'<form action="/action_page.php">'+
							'<label for="fname">First Name</label>'+
							'<input type="text" id="fname" name="firstname" placeholder="Your name..">'+
							'<label for="lname">Last Name</label>'+
							'<input type="text" id="lname" name="lastname" placeholder="Your last name..">'+
							'<label for="country">Country</label>'+
							'<select id="country" name="country">'+
							  '<option value="australia">Australia</option>'+
							  '<option value="canada">Canada</option>'+
							  '<option value="usa">USA</option>'+
							'</select>'+			  
							'<input type="submit" value="Submit">'+
						 ' </form>'+
					 '<div>';
		*/
		
					"<b>Nome:</b> <input type='text' id='nome'><br><br>"+
					"RGI: <input type='text' id='anexo_rgi'><br><br>"+
					"Outros: <input type='text' id='outros_docs'><br><br>"+
					"Bairro_id: <input type='text' id='Bairro_id'><br><br>"+
					"<br> <input type='button' value='Salvar' onclick='salvar_dados(\"ponto\")'/> <br>";
		
		infowindow_contribuicao = new google.maps.InfoWindow({
			content: html
		});
		marcador_google = new google.maps.Marker({
			position: event.latLng,
			map: mapa_google
		});
		infowindow_contribuicao.open(mapa_google, marcador_google);
		google.maps.event.addListener(marcador_google, "click", function(){
			infowindow_contribuicao.open(mapa_google, marcador_google);
		});
	});
	var drawingManager = new google.maps.drawing.DrawingManager({
		drawingMode: google.maps.drawing.OverlayType.NULL,
		drawingControl: true,
		drawingControlOptions:{
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: ['polygon', 'polyline']
		},
		polygonOptions: {
			clickable: true,
			draggable: true,
			editable: true
		}
	});
	drawingManager.setMap(mapa_google);
	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event){
		if(geometria_desenhada_atual){
			geometria_desenhada_atual.setMap(null);
		}
		geometria_desenhada_atual=event.overlay;
		if (marcador_google){
			marcador_google.setMap(null);
		}
		var html="";
		if (event.type == 'polygon' || event.type == 'polyline'){
			var vertices = event.overlay.getPath();
			var string_pontos="";
			for (var i =0; i < vertices.getLength(); i++){
				var xy = vertices.getAt(i);
				html += '<br>'+'Coordinate '+i+':<br>'+xy.lng()+','+xy.lat();
				string_pontos += xy.lat()+" "+xy.lng()+",";
			}
			string_pontos += vertices.getAt(0).lat()+" "+vertices.getAt(0).lng();
			//analisar_dados(string_pontos);
			html +=  "<input type='button' value='Salvar' onclick='salvar_dados(\"linha_ou_poligono\", "+'"'+string_pontos+'"'+")'/>";
		}
		if(infowindow_contribuicao){
			infowindow_contribuicao.close();
		}
		infowindow_contribuicao = new google.maps.InfoWindow({
			content: html
		});
		infowindow_contribuicao.setPosition(xy);
		infowindow_contribuicao.open(mapa_google);
		google.maps.event.addListener(event.overlay, 'click', function(event){
			infowindow_contribuicao.open(mapa_google);
		});
	});
}
function carregar_imoveis(){
	limpar_marcadores();
	carregar_dados("php/aplicacao/gmaps/carrega/carrega_dados.php", function(data){
		analisar_dados(data);
		var xml = parse_xml(data);
		educacoes = xml.getElementsByTagName("ie");
		for(var i = 0; i < educacoes.length; i++){
			var anexo_rgi = educacoes[i].getElementsByTagName("anexo_rgi")[0].textContent;
			var nome = educacoes[i].getElementsByTagName("nome")[0].textContent;
			var outros_docs = educacoes[i].getElementsByTagName("outros_docs")[0].textContent;
			var Bairro_id = educacoes[i].getElementsByTagName("Bairro_id")[0].textContent;
			var point = new google.maps.LatLng(
				parseFloat(educacoes[i].getElementsByTagName("latitude")[0].textContent),
				parseFloat(educacoes[i].getElementsByTagName("longitude")[0].textContent)
			);
			var html =	"<b>Nome: </b>" + nome + "<br><br>"+
						"<b>RGI: </b>" + anexo_rgi + "<br><br>"+
						"<b>Outros documentos: </b>" + outros_docs + "<br><br>"+
						"<b>Bairro_id: </b>" + Bairro_id + "<br><br>"+
						"<div id='cidade_infowindows'><b>Cidade: </b></div><br>"+
						"<div id='estado_infowindows'><b>Estado: </b></div>";
			var marcador_imovel_carregado = new google.maps.Marker({
				position: point,
				map: mapa_google
			});
			marcadores_carregados.push(marcador_imovel_carregado);
			bind_infowindow(marcador_imovel_carregado, infowindow_dado_carregado, html);
		}
	});
}
function bind_infowindow(marcador_imovel_carregado, infowindow_dado_carregado, html){
	google.maps.event.addListener(marcador_imovel_carregado, 'click', function (){
		infowindow_dado_carregado.setContent(html);
		if (marcador_google){
			marcador_google.setMap(null);
		}
		infowindow_dado_carregado.open(mapa_google, marcador_imovel_carregado);
		google.maps.event.addListenerOnce(infowindow_dado_carregado, 'domready', function(){
			recuperar_endereco(marcador_imovel_carregado.position.lng(), marcador_imovel_carregado.position.lat());
		});
	});
	return true;
}
function salvar_dados(tipo, string_pontos=""){
	var url;
	if(tipo=="ponto"){
		var anexo_rgi = document.getElementById("anexo_rgi").value;
		var nome = document.getElementById("nome").value;
		var outros_docs = document.getElementById("outros_docs").value;
		var Bairro_id = document.getElementById("Bairro_id").value;
		var lat = marcador_google.getPosition().lat();
		var lng = marcador_google.getPosition().lng();
		var localizacao = lat+" "+lng;
		url = "php/aplicacao/gmaps/insere/insere_dados.php?anexo_rgi=" + anexo_rgi + "&localizacao=" + localizacao +
			  "&nome=" + nome + "&Bairro_id=" + Bairro_id +  "&outros_docs=" + outros_docs;
	}
	else if(string_pontos){
		var localizacao = string_pontos;
		url = "php/aplicacao/gmaps/insere/insere_dados_extra.php?localizacao=" + localizacao;
	}
	else{
		alert("Contribução não Enviada!");
		return;
	}
	carregar_dados(url, function(data){
		analisar_dados(data);
		infowindow_contribuicao.close();
		alert("Contribução Realizada com Sucesso!");
		carregar_imoveis();
	});
}
function limpar_marcadores(){
	for (let i=0; i<marcadores_carregados.length; i++){
		marcadores_carregados[i].setMap(null);
	}
}
function recuperar_endereco(lng, lat){
	var url = "https://nominatim.openstreetmap.org/reverse?format=geojson&lat="+lat+"&lon="+lng;
	analisar_dados(url);
	carregar_dados(url, function tratar_endereco_geojson(data){
		var dados_json = JSON.parse(data);
		document.getElementById("cidade_infowindows").innerHTML+=dados_json.features[0].properties.address.city;
		document.getElementById("estado_infowindows").innerHTML+=dados_json.features[0].properties.address.state;
	});
}
function recuperar_endereco_texto(){
	var texto_da_pesquisa = document.getElementById("endereco").value;
	var texto_da_pesquisa = encodeURI(texto_da_pesquisa);
	var url = "https://nominatim.openstreetmap.org/search?q="+texto_da_pesquisa+"&format=geojson";
	analisar_dados(url);
}