<?php

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


?>