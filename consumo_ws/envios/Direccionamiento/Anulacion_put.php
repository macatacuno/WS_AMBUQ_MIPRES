<?php
include('../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

include('../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////

//parametros del formulario
$dir_id_direccionamiento = $_POST['dir_id_direccionamiento'];
$dir_id = $_POST['dir_id'];
$tipo_id = $_POST['tipo'];
$TipoTec = $_POST['TipoTec'];

$servicio_id = 11; // Servicio de direccionamiento

//Parametros de la api
$nit = "";
$url_bd = "";
$token = "";
$token_temporal = "";
$horas_de_diferencia = "";
$Webservice = "";
$serv_nombre = "";
$url_api_generar_token = "";

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
$query = "SELECT TOKEN,NIT,
decode(TOKEN_TEMPORAL,null,'vacio',TOKEN_TEMPORAL) TOKEN_TEMPORAL, 
decode(round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2),null,-1,
       round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2))as HORAS_DE_DIFERENCIA 
FROM WEBSERV_TIPOREPORTES WHERE TIRE_ID=" . $tipo_id;
$st_tire = oci_parse($conn_oracle, $query);
oci_execute($st_tire, OCI_DEFAULT);
while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
	// Usar nombres de columna en mayúsculas para los índices del array asociativo
	$nit = $row["NIT"];
	$token = $row["TOKEN"];
	$token_temporal = $row["TOKEN_TEMPORAL"];
	$horas_de_diferencia = $row["HORAS_DE_DIFERENCIA"];
	// $row[1];
}
oci_free_statement($st_tire);
//////obtener el nit y el token(fin)


//Esta función actualiza el token solo si han pasado más de 20 horas desde la última vez que se actualizó
$token_temporal = actualizar_token_temporal($horas_de_diferencia, $conn_oracle, $nit, $token, $token_temporal);

$url = $url_bd . "/" . $nit . '/' . $token_temporal . '/' . $dir_id_direccionamiento;
//$url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularDireccionamiento/818000140/U9ReshqfOKJHH_Syry2ckV9D1tsQWDmR6gQ5r3LM1Zk%3D/20994074";
$json_direc = '';

$response = direccionar_put($url, $json_direc);
/*$response = direccionar_put($url, '{
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
echo "Response: " . $response;

//echo " <br> " . $json_direc;

if ($TipoTec == 'M') {
	$nombre_tabla = 'WEBSERV_PRES_MEDI';
} else if ($TipoTec == 'P') {
	$nombre_tabla = 'WEBSERV_PRES_PROC';
} else if ($TipoTec == 'N') {
	$nombre_tabla = 'WEBSERV_PRES_PROD_NUTR';
} else if ($TipoTec == 'S') {
	$nombre_tabla = 'WEBSERV_PRES_SERV_COMP';
} else if ($TipoTec == 'D') {
	$nombre_tabla = 'WEBSERV_PRES_DISP';
}


if (strpos($response, 'Exitosa') !== false) {

	////Actualizar tabla con el token temporal (Inicio)
	$sql_exc = "UPDATE " . $nombre_tabla . " 
        SET  DIR_IDDIRECCIONAMIENTO = '', DIR_ID = '' WHERE  DIR_IDDIRECCIONAMIENTO = " .
		$dir_id_direccionamiento . " and DIR_ID = " . $dir_id;
	$st_direc = oci_parse($conn_oracle, $sql_exc);
	$result = oci_execute($st_direc);
	oci_free_statement($st_direc);
	if ($result) {
		//echo  "<br>Actualización Correcta ";
	} else {
		//echo  "<br>Actualización Incorrecta ";
	}
	/////Actualizar tabla con el token temporal  (Fin)

	

	////Eliminar el direccionamiento (Inicio)
	$sql_exc = "UPDATE " . $nombre_tabla . " 
        SET  DIR_IDDIRECCIONAMIENTO = '', DIR_ID = '' WHERE  DIR_IDDIRECCIONAMIENTO = " .
		$dir_id_direccionamiento . " and DIR_ID = " . $dir_id;


		$sql_exc = "DELETE
		FROM WEBSERV_PRES_DIRECCIONADOS
		WHERE DIR_ID                = $dir_id
		AND DIR_IDDIRECCIONAMIENTO = $dir_id_direccionamiento";

	$st_direc = oci_parse($conn_oracle, $sql_exc);
	$result = oci_execute($st_direc);
	oci_free_statement($st_direc);
	if ($result) {
		//echo  "<br>Actualización Correcta ";
	} else {
		//echo  "<br>Actualización Incorrecta ";
	}
	/////Eliminar el direccionamiento  (Fin)


};

oci_close($conn_oracle);
