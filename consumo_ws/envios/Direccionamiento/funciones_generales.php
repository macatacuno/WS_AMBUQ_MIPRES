<?php
//Esta función actualiza el token solo si han pasado más de 20 horas desde la última vez que se actualizó
 function actualizar_token_temporal($horas_de_diferencia,$conn_oracle,$nit,$token,$token_temporal){

// Validar si es necesario actualizar el token(Inicio).
// cuando la variable $horas_de_diferencia es -1 significa que no hay una fecha definida.

if ($horas_de_diferencia == -1 || $horas_de_diferencia > 15) {

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
	$token_temporal = file_get_contents($url_token);
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


?>