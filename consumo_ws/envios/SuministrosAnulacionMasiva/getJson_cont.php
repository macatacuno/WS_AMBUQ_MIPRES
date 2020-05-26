<?php

/**********************Cargar funciones funciones_generales***************************/
include('../../../funciones_generales.php');

/**********************Cargarel procedimiento para la conexion de oracle**************/
include('../../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

/**********************Variables generales*******************************************/
$tipo_id = 2; //Subsidiado
$servicio_id = 15; // Se asigna el codigo del servicio AnularSuministro

/**********************Obtener los datos para armar la URL***************************/
$url_bd = obtener_datos_url("URL", $servicio_id, $conn_oracle);
$serv_nombre = obtener_datos_url("SERV_NOMBRE", $servicio_id, $conn_oracle);
$ws_nombre = obtener_datos_url("WS_NOMBRE", $servicio_id, $conn_oracle);
$Webservice = obtener_datos_url("WS_ID", $servicio_id, $conn_oracle);

/**********************Obtener el nit y el token**************************************/
$nit = obtener_datos_token_nit("NIT", $tipo_id, $conn_oracle);
$token = obtener_datos_token_nit("TOKEN", $tipo_id, $conn_oracle);
$horas_de_diferencia = obtener_datos_token_nit("HORAS_DE_DIFERENCIA", $tipo_id, $conn_oracle);
$token_temporal = obtener_datos_token_nit("TOKEN_TEMPORAL", $tipo_id, $conn_oracle);
$token_temporal = actualizar_token_temporal($horas_de_diferencia, $conn_oracle, $nit, $token, $token_temporal);

/**********************Cargar Encabezado**********************************************/

$query = "
SELECT 
IDSUMINISTRO
FROM WEBSERV_SUMINISTROS
where  IDSUMINISTRO in ()";
$st_tire = oci_parse($conn_oracle, $query);
oci_execute($st_tire, OCI_DEFAULT);


while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $url = $url_bd . "/" . $nit . '/' . $token_temporal . '/' . $row["IDSUMINISTRO"];
    $json = Webservice_get($url);
    echo "<br>url: $url<br>";
    echo "<br>json: $json<br>";
}
