<?php

set_time_limit(9999999);
//ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');


///////Declaracion de Variables Generales(Inicio)/////////
$peri_error = "";
$peri_error_conteo = 0;
$periodos_cargados = "";
$periodos_cargados_conteo = 0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "subsidiado";
$tipo_id = 2;
$servicio_id = 9; // Se asigna el codigo del servicio Prescripcion
//Parametros de la api
$nit = "";
$url_bd = "";
$token = "";
$Webservice = "";
$serv_nombre = "";
$url_api_generar_token = "";
$json = "";
$count_report = 0;
///////Declaracion de Variables Generales(Fin)/////////

$id_pres = ""; //llave primaria de la tabla WEBSERV_PRES_PRES
$id_medi = ""; //llave primaria de la tabla WEBSERV_PRES_MEDI
$dato_vec[] = "";

/////obtener los parametros la url(inicio)
$query = "select S.URL,S.NOMBRE AS SERV_NOMBRE,TS.WS_ID from WEBSERV_SERVICIOS S JOIN WEBSERV_TIPOSERVICIOS TS ON S.TISE_ID=TS.TISE_ID WHERE S.SERV_ID=" . $servicio_id;
$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {
	// Usar nombres de columna en mayúsculas para los índices del array asociativo
	$url_bd = $row["URL"];
	$serv_nombre = $row["SERV_NOMBRE"];
	$Webservice = $row["WS_ID"];
	// $row[1];
}
oci_free_statement($st_serv);


//////obtener los parametros la url(fin)

//////obtener el nit y el token(inicio)

$query = "select NIT,TOKEN from WEBSERV_TIPOREPORTES WHERE TIRE_ID=" . $tipo_id;
$st_tire = oci_parse($conn_oracle, $query);
oci_execute($st_tire, OCI_DEFAULT);
while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
	// Usar nombres de columna en mayúsculas para los índices del array asociativo
	$nit = $row["NIT"];
	$token = $row["TOKEN"];
	// $row[1];
}
oci_free_statement($st_tire);


//////obtener el nit y el token(fin)


/*
$periodo_inicial ="17-01-01"; 
$periodo_final =(string)date("y-m-d",strtotime(date('y-m-d')."- 1 day")); 
*/
//19-10-29  ---YY/mm/dd
$periodo_inicial = (string) date("y-m-d", strtotime(date('y-m-d') . "- 0 day")); // "04-01-01";
$periodo_final = (string) date("y-m-d", strtotime(date('y-m-d') . "- 0 day"));

//$periodo_final =   (string)date('y-m-d');


$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias = $diff->days + 1;
echo "///////////////////////////Json por periodos////////////////////////////////////////////";
echo "<br> Servicio cargado: WSPRESCRIPCION-> " . $serv_nombre . "-> " . $tipo_get . "<br>";
echo ' dia(s) consultado(s): ' . $cant_dias;

echo "<br>";
$periodo_conteo = $periodo_inicial;
echo "Periodo consultado: 20" . $periodo_inicial . " - 20" . $periodo_final;
echo "<br>";

for ($i_Principal = 0; $i_Principal <= $cant_dias - 1; $i_Principal++) {


	$periodo_conteo = date("y-m-d", strtotime($periodo_inicial . "+ " . $i_Principal . " day"));


	//Codico para validar si existe el registro antes de insertarlo


	$periodo_conteo_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato originar "y/m/d"
	$query_exist = "select count(1) CANTIDAD from WEBSERV_REPORTES_JSON where SERV_ID=" . $servicio_id . " and TIRE_ID=" . $tipo_id . " and PERIODO='" . $periodo_conteo_oracle . "'";
	//echo "<br> query_exist: ".$query_exist."<br>";
	$st_exist = oci_parse($conn_oracle, $query_exist);
	$resultado = oci_execute($st_exist, OCI_DEFAULT);
	if ($resultado) {
		$cantidad_registros = 0;
		while (($row = oci_fetch_array($st_exist, OCI_BOTH)) != false) {
			$cantidad_registros = $row["CANTIDAD"];
			// echo "<br> cantidad_registros:".$cantidad_registros."<br>";
		}
		oci_free_statement($st_exist);
		if ($cantidad_registros == 0) {

			//$url = $url_bd . "/" . $nit . '/' . $token . '/' . "20" . $periodo_conteo;
			$url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Direccionamiento/818000140/U9ReshqfOKJHH_Syry2ckaYLaSaBwT2pqpeQTMmXcqA=";

			/////////////////////////////////////////////////////////////////////////////////////
			//$response = httpRequest($url, 80, "GET", "/Database",array("direccionamiento" =>"3434", "nit" =>"345345", "token" =>"3454")); 
			/*$response = request_put($url, '{
				"NoPrescripcion": "20200117143016858113",
				"TipoTec": "N",
				"ConTec": 1,
				"TipoIDPaciente": "CC",
				"NoIDPaciente": "1143240102",
				"NoEntrega": 1,
				"NoSubEntrega": 0,
				"TipoIDProv": "NI",
				"NoIDProv": "900843971",
				"CodMunEnt": "08001",
				"FecMaxEnt": "2020-02-17",
				"CantTotAEntregar": "60",
				"DirPaciente": "BARRANQUILLA",
				"CodSerTecAEntregar": "RSiA16I178915"
			  }');*/

			///////////////////////////////////////////////////////////////////////////////



		//	echo "<br> Response: " . $response . "<br>";

			//$json = (string) file_get_contents($url);
			//echo "<br> json: " . $json . "<br>";
		} else {
			//echo "<br>El registro ya exsiste";
		}
	}
}


//Segunda prueba
function request_put($url, $parametro)
{
	//$url= "";
	//$parametro="";
	$conexion = curl_init();
	curl_setopt($conexion, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($conexion, CURLOPT_URL, $url);
	curl_setopt($conexion, CURLOPT_HTTPGET, false);
	curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-length: ' . strlen($parametro)));
	curl_setopt($conexion, CURLOPT_POSTFIELDS, $parametro);
	curl_setopt($conexion, CURLOPT_HEADER, false);
	curl_setopt($conexion, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
	curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($conexion, CURLOPT_USERPWD, "usuario:pass");

	$respuesta = curl_exec($conexion);
	//if($respuesta===false){}
	return $respuesta;
	curl_close($conexion);
}

