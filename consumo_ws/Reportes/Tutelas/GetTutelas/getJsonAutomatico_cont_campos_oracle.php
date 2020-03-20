<?php
include('../../../funciones_generales.php');

include('../../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS


$tipo_get = "contributivo";
$tipo_id = 1;

$servicio_id = 3; // Se asigna el codigo del servicio Prescripcion


$url_bd=obtener_datos_url("URL",$servicio_id,$conn_oracle);
$serv_nombre=obtener_datos_url("SERV_NOMBRE",$servicio_id,$conn_oracle);
$Webservice=obtener_datos_url("WS_ID",$servicio_id,$conn_oracle);
echo $url_bd."<br>";
echo $serv_nombre."<br>";
echo $Webservice."<br>";


$nit=obtener_datos_token_nit("NIT",$tipo_id,$conn_oracle);
$token=obtener_datos_token_nit("TOKEN",$tipo_id,$conn_oracle);
echo $nit."<br>";
echo $token."<br>";


$periodo_inicial = "20-01-01";
$periodo_final = (string) date("y-m-d", strtotime(date('y-m-d') . "- 1 day"));


?>