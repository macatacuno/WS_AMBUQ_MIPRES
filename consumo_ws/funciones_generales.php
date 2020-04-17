<?php
//Esta función actualiza el token solo si han pasado más de 20 horas desde la última vez que se actualizó
function actualizar_token_temporal($horas_de_diferencia, $conn_oracle, $nit, $token, $token_temporal)
{

	// Validar si es necesario actualizar el token(Inicio).
	// cuando la variable $horas_de_diferencia es -1 significa que no hay una fecha definida.
	if ($horas_de_diferencia == -1 || $horas_de_diferencia > 10 || $token_temporal == 'vacio') {

		//Generar token temporal(inicio)
		$query = "SELECT URL FROM WEBSERV_SERVICIOS where NOMBRE='GenerarToken'";
		$st_token_temp = oci_parse($conn_oracle, $query);
		oci_execute($st_token_temp, OCI_DEFAULT);
		while (($row = oci_fetch_array($st_token_temp, OCI_BOTH)) != false) {
			$url_api_generar_token = $row["URL"];
			// $row[1];
		}
		oci_free_statement($st_token_temp);

		$url_token = $url_api_generar_token . "/" . $nit . "/" . $token;
		$token_temporal = Webservice_get($url_token);
		//$token_temporal = file_get_contents($url_token);
		$token_temporal = str_replace("\"", '', $token_temporal);

		//echo "<br>token: " . $token . "<br>";
		//echo "<br>token_temporal: " . $token_temporal . "<br>";
		//Generar token temporal(fin)

		////Actualizar tabla con el token temporal (Inicio)
		$sql_exc = "UPDATE WEBSERV_TIPOREPORTES
                SET TOKEN_TEMPORAL    = '" . $token_temporal . "',
                    FECHA_TOKEN_TEMPORAL=sysdate
                WHERE TOKEN = '" . $token . "'";
		$st_pr_nu = oci_parse($conn_oracle, $sql_exc);
		$result = oci_execute($st_pr_nu);
		oci_free_statement($st_pr_nu);
		if ($result) {
			//echo  "<br>Actualización Correcta ";
		} else {
			//echo  "<br>Actualización Incorrecta ";
		}
		/////Actualizar tabla con el token temporal  (Fin)
	}
	// Validar si es necesario actualizar el token(Fin).
	return  $token_temporal;
}

function direccionar_put($url, $parametro)
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


function Webservice_get($url)
{
	//$url= "";
	//$parametro="";
	$conexion = curl_init();
	curl_setopt($conexion, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($conexion, CURLOPT_URL, $url);
	curl_setopt($conexion, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($conexion, CURLOPT_USERAGENT, 'Your application name');

	$respuesta = curl_exec($conexion);
	//if($respuesta===false){}
	return $respuesta;
	curl_close($conexion);
}



function obtener_datos_url($campo_oracle_alias, $servicio_id, $conn_oracle)
{
	$dato_a_retornar = "";
	//////obtener los parametros la url(Inicio)
	if ($campo_oracle_alias == "URL") {
		$campo_oracle_exacto = "S.URL";
	} else if ($campo_oracle_alias == "SERV_NOMBRE") {
		$campo_oracle_exacto = "S.NOMBRE as SERV_NOMBRE";
	} else if ($campo_oracle_alias == "WS_NOMBRE") {
		$campo_oracle_exacto = "WS.NOMBRE as WS_NOMBRE";
	} else if ($campo_oracle_alias == "WS_ID") {
		$campo_oracle_exacto = "TS.WS_ID";
	}

	$query = "select 
" . $campo_oracle_exacto . " from WEBSERV_SERVICIOS S 
JOIN WEBSERV_TIPOSERVICIOS TS ON S.TISE_ID=TS.TISE_ID 
JOIN WEBSERV_WEBSERVICES WS ON WS.WS_ID=TS.WS_ID
WHERE S.SERV_ID=" . $servicio_id;
	$st_serv = oci_parse($conn_oracle, $query);
	oci_execute($st_serv, OCI_DEFAULT);
	while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {
		$dato_a_retornar = $row[$campo_oracle_alias];
	}
	oci_free_statement($st_serv);
	return $dato_a_retornar;
};



function obtener_datos_token_nit($campo_oracle_alias, $tipo_id, $conn_oracle)
{
	$dato_a_retornar = "";
	//////obtener los parametros la url(Inicio)
	if ($campo_oracle_alias == "NIT") {
		$campo_oracle_exacto = "NIT";
	} else if ($campo_oracle_alias == "TOKEN") {
		$campo_oracle_exacto = "TOKEN";
	} else if ($campo_oracle_alias == "TOKEN_TEMPORAL") {
		$campo_oracle_exacto = "decode(TOKEN_TEMPORAL,null,'vacio',TOKEN_TEMPORAL) TOKEN_TEMPORAL";
	} else if ($campo_oracle_alias == "HORAS_DE_DIFERENCIA") {
		$campo_oracle_exacto = "decode(round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2),null,-1,
		round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2))as HORAS_DE_DIFERENCIA";
	};

	//$query = "select " . $campo_oracle_exacto . " from WEBSERV_TIPOREPORTES WHERE TIRE_ID=" . $tipo_id;
	$query = "SELECT $campo_oracle_exacto FROM WEBSERV_TIPOREPORTES WHERE TIRE_ID=$tipo_id";
	$st_serv = oci_parse($conn_oracle, $query);
	oci_execute($st_serv, OCI_DEFAULT);
	while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {
		$dato_a_retornar = $row[$campo_oracle_alias];
	}
	oci_free_statement($st_serv);
	return $dato_a_retornar;
}



function armar_encabezado($periodo_inicial, $periodo_final, $ws_nombre, $serv_nombre, $tipo_get)
{

	$date1 = new DateTime($periodo_inicial);
	$date2 = new DateTime($periodo_final);
	$diff = $date1->diff($date2);
	$cant_dias = $diff->days + 1;
	echo "///////////////////////////Json por periodos////////////////////////////////////////////";
	echo "<br> Servicio cargado: " . utf8_encode($ws_nombre) . "->$serv_nombre->$tipo_get <br>";
	echo "dia(s) consultado(s): $cant_dias";

	echo "<br>";
	echo "Periodo consultado: 20" . $periodo_inicial . " - 20" . $periodo_final;
	echo "<br>";
	return $cant_dias;
}


function validar_que_el_periodo_exista($conn_oracle, $periodo_conteo, $servicio_id, $tipo_id)
{
	//Codico para validar si existe el registro antes de insertarlo
	$periodo_conteo_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato originar "y/m/d"
	$query_exist = "select count(1) CANTIDAD from WEBSERV_REPORTES_JSON where SERV_ID=" . $servicio_id . " and TIRE_ID=" . $tipo_id . " and PERIODO='" . $periodo_conteo_oracle . "'";
	//echo "<br> query_exist: ".$query_exist."<br>";
	$st_exist = oci_parse($conn_oracle, $query_exist);
	$periodo_existe = oci_execute($st_exist, OCI_DEFAULT);

	if ($periodo_existe) {
		$cantidad_registros = 0;
		while (($row = oci_fetch_array($st_exist, OCI_BOTH)) != false) {
			$cantidad_registros = $row["CANTIDAD"];
			// echo "<br> cantidad_registros:".$cantidad_registros."<br>";
		}
		oci_free_statement($st_exist);
		if ($cantidad_registros > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false; //El meos uno significa que el periodo no existe  
	}
}


function insertar_log_de_error($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, $serv_nombre, $tipo_get, $periodo_conteo)
{
	$sql_log_err = "INSERT INTO webserv_log_errores (serv_id, tire_id,periodo, nombre, descripcion) 
	VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "', 'WSPRESCRIPCION: Error al consumir el WebService','No se cargó " . $serv_nombre . " " . $tipo_get . " 20" . $periodo_conteo . "')";
	echo $sql_log_err;
	$st_log_err = oci_parse($conn_oracle, $sql_log_err);
	$result = oci_execute($st_log_err);
	oci_free_statement($st_log_err);
	if ($result) {
		echo "<br>Se insertó el log de error";
	}
}

function insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, $json, $serv_nombre, $tipo_get, $periodo_conteo)
{
	$sql_exc = "INSERT INTO webserv_reportes_json ( serv_id, tire_id,periodo, json) VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "','$json')"; //no se inserta el json porque provoca error al insertar el registro
	echo $sql_exc;
	$st = oci_parse($conn_oracle, $sql_exc);
	$result = oci_execute($st);
	oci_free_statement($st);

	/////Eliminar el el registro de la tabla del log de errores en caso de que exista
	if ($result) {
		$sql_log_err = "delete from webserv_log_errores where serv_id=" . $servicio_id . " and tire_id=" . $tipo_id . " and  periodo = '" . $fecha_oracle . "'";
		$st_log_err = oci_parse($conn_oracle, $sql_log_err);
		$result_log = oci_execute($st_log_err);
		oci_free_statement($st_log_err);
		if ($result_log) {
			return "OK";
		} else {
			return "Error al borrar el log";
		}
	} else {
		$sql_log_err = "INSERT INTO webserv_log_errores (serv_id, tire_id,periodo, nombre, descripcion) 
		 VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "', 'WSPRESCRIPCION: Error al insertar el registro','No se cargó " . $serv_nombre . " " . $tipo_get . " 20" . $periodo_conteo . "')";
		echo $sql_log_err;
		$st_log_err = oci_parse($conn_oracle, $sql_log_err);
		$result = oci_execute($st_log_err);
		oci_free_statement($st_log_err);
		if ($result) {
		}
		return "Error al insertar el json en la tabla webserv_reportes_json";
	}
}

function formatear_json_general($json)
{
	/*Nota 1:Al remplazar los valores se debe hacer con comillas dobles, 
ya que con commillas simples la funcion str_replace no encuentra los datos buscados*/
	$json = str_replace("\\\"", "", $json);
	$json = str_replace("\n", "", $json); //quitar \n
	$json = str_replace("\t", "", $json); //quitar \t
	return $json;
}
