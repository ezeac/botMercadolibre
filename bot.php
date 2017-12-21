<?php 
set_time_limit(0); 
error_reporting(0);

ob_implicit_flush(true);
ob_end_flush();
?>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
	function limpiarLista(){
		$("#mostrarDatos").html("");
	};
</script>
<style>
	.itemLista {
	    float: left;
	    padding: 10px 20px;
	    border-right: 1px solid grey;
	    height: 200px;
	    margin-top: 20px;
	    width: 180px;
	}
</style>
</head>
<body class="container">
<?php
$url = $_REQUEST['url'];
// $url = "https://motos.mercadolibre.com.ar/naked/honda/cordoba/";
$file = fopen('arch.csv', 'w+');
fwrite($file, "URL: ".$url."\n");
fwrite($file, utf8_decode("Nombre;Ubicación;Teléfono;Teléfono2;Teléfono3;Teléfono4\n"));
$offset = 1;
$reintentos = 0;
echo "<br><button class='btn-success left' style='border 1px solid'><a style='color:black; text-decoration: none' href='arch.csv' download='Base Mercadolibre.csv'>Descargar Base (obtener los datos capturados hasta el momento)</a></button>";
?>
<button class='btn-danger left' style='border: 2px solid; margin-left: 20px' onclick="limpiarLista();">Limpiar Vista</button>
<div id="mostrarDatos">
<?php
for ($i=1; $i < 10000; $i++) {

	//Se obtiene las url de los artículos
	if ($offset == 1) {
		$resultados = busquedaEnFuente($url, '#(?<=item-url=")[\S]*(?=" item-id)#');	
	} else {
		$resultados = busquedaEnFuente($url.'_Desde_'.$offset, '#(?<=item-url=")[\S]*(?=" item-id)#');
	}
	echo "Reintentando conexión a mercadolibre...<br>";
	// echo "<pre>";
	// var_dump($resultados);
	// echo "</pre>";

	if (count($resultados) != 0){
		echo "<hr><h4 style='clear:both'>INSPECCIONANDO DESDE ARTÍCULO NRO: ".$offset."</h4><hr><hr>";
		$reintentos = 0;
		$offset += count($resultados);
		for ($f=0; $f < count($resultados); $f++) { 
			$resultados2 = busquedaEnFuente($resultados[$f], '#(?<=<section class="ui-view-more vip-section-seller-info)[\s\S]*?(?=</section>)#');
			// echo "<pre>";
			// print_r($resultados2);
			// echo "</pre>";
			// die();
			if ($resultados2 != null) {	

				for ($g=0; $g < count($resultados2); $g++) {
					
					$buffer = "";
					$csv = "";

					$resultados3 = buscarEImprimirDatos($resultados2[$g], '#(?<=card-description--bold">[\s\S][\s\S][\s\S]<span>)[\s\S]*?(?=</span>)#');
					$buffer .= "<b>Nombre: </b>".$resultados3;
					$buffer .= "<br>";
					$csv .= $resultados3.";";
					
					$resultados5 = buscarEImprimirDatos($resultados2[$g], '#(?<=card-description">[\s\S][\s\S][\s\S]<span>)[\s\S]*?(?=</span>)#');
					$buffer .= "<b>Ubicación: </b><br>".$resultados5;
					$buffer .= "<br>";
					$csv .= $resultados5.";";

					$resultados4 = buscarEImprimirDatos($resultados2[$g], '#(?<=profile-info-phone-value">)[\S\s]*?(?=</span>)#');
					$buffer .= "<b>Teléfonos: </b><br>".$resultados4;
					$resultadoFiltroTel = str_replace("<br>","; ",$resultados4);
					$resultadoFiltroTel = str_replace("(","",$resultadoFiltroTel);
					$resultadoFiltroTel = str_replace(")","",$resultadoFiltroTel);
					$resultadoFiltroTel = preg_replace('/\s/', '', $resultadoFiltroTel);
					$csv .= $resultadoFiltroTel."\n";

					$buffer .= "<hr>";

					echo "<div class='itemLista'>".$buffer."</div>";

					fwrite($file, utf8_decode($csv));			
				}

			}
		}
	} else {
		if ($reintentos < 20){
			$i--;
			$reintentos++;
		} else {
			$i = 10000;
			echo "<hr>PROCESO FINALIZÓ CORRECTAMENTE<br>";
			echo "<button><a href='arch.csv' download='Base Mercadolibre Completa.csv'>Descargar Base Completa</a></button><hr>";
		}
	}
}


function busquedaEnFuente($url, $regExp){
	$html = file_get_contents($url);	
	preg_match_all($regExp, $html, $matches);
	return $matches[0];
}

function buscarEImprimirDatos($fuente, $regExp){
	$texto = "";
	preg_match_all($regExp, $fuente, $resultado);
	for ($i=0; $i < count($resultado[0]); $i++) {
		if ($texto != "") {
			$texto .= "<br>";
		}
		$texto .= $resultado[0][$i];
	}
	if ($texto == ""){
		$texto = "Amigo";
	}
	return $texto;
}

?>
</div>
</body>
</html>