<?php
include('../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

include('../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////

//parametros del formulario
$tipo_id = $_POST['tipo'];
$NoPrescripcion = $_POST['NoPrescripcion'];
$TipoTec = $_POST['TipoTec'];
$ConTec = $_POST['ConTec'];
$TipoIDPaciente = $_POST['TipoIDPaciente'];
$NoIDPaciente = $_POST['NoIDPaciente'];
$NoEntrega = $_POST['NoEntrega'];
$NoSubEntrega = $_POST['NoSubEntrega'];
$TipoIDProv = $_POST['TipoIDProv'];
$NoIDProv = $_POST['NoIDProv'];
$CodMunEnt = $_POST['CodMunEnt'];
$FecMaxEnt = $_POST['FecMaxEnt'];
$CantTotAEntregar = $_POST['CantTotAEntregar'];
$DirPaciente = $_POST['DirPaciente'];
$CodSerTecAEntregar = $_POST['CodSerTecAEntregar'];

//agregar los 0 faltantes del codigo DANE(Inicio)
if (strlen($CodMunEnt) == 4) {
	$CodMunEnt = '0' . $CodMunEnt;
}
//agregar los 0 faltantes del codigo DANE(Fin)

$servicio_id = 10; // Servicio de direccionamiento

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


$url = $url_bd . "/" . $nit . '/' . $token_temporal;
//$url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Direccionamiento/818000140/U9ReshqfOKJHH_Syry2ckaYLaSaBwT2pqpeQTMmXcqA=";
$json_direc = '{
	"NoPrescripcion": "' . $NoPrescripcion . '",
	"TipoTec": "' . $TipoTec . '",
	"ConTec": ' . $ConTec . ',
	"TipoIDPaciente": "' . $TipoIDPaciente . '",
	"NoIDPaciente": "' . $NoIDPaciente . '",
	"NoEntrega": ' . $NoEntrega . ',
	"NoSubEntrega": ' . $NoSubEntrega . ',
	"TipoIDProv": "' . $TipoIDProv . '",
	"NoIDProv": "' . $NoIDProv . '",
	"CodMunEnt": "' . $CodMunEnt . '",
	"FecMaxEnt": "' . $FecMaxEnt . '",
	"CantTotAEntregar": "' . $CantTotAEntregar . '",
	"DirPaciente": "' . $DirPaciente . '",
	"CodSerTecAEntregar": "' . $CodSerTecAEntregar . '"
  }';
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


$id = '';
$id_direc = '';
if (strpos($response, 'Message') !== false) {
} else {
	//'[{"Id":21589220,"IdDireccionamiento":20898467}] '
	$Id_busc_ini = '"Id":'; //Parametro inicial de nusqueda
	$Id_busc_fin = ',"IdDireccionamiento"'; //parametro final de busqueda
	$cadena_Id = $response; // Cadena completa
	$posPresInicial = strpos($cadena_Id, $Id_busc_ini) + strlen($Id_busc_ini); //numero de pocicion inicial
	$posPresFinal = strpos($cadena_Id, $Id_busc_fin); //numero de pocicion final
	$Id = substr($cadena_Id, $posPresInicial, $posPresFinal - $posPresInicial); //Dato extaido de la cadena completa
	//$Id = str_replace('"', "'", $Id);

	$IdDireccionamiento_busc_ini = '"IdDireccionamiento":'; //Parametro inicial de nusqueda
	$IdDireccionamiento_busc_fin = '}]'; //parametro final de busqueda
	$cadena_IdDireccionamiento = $response; // Cadena completa
	$posPresInicial = strpos($cadena_IdDireccionamiento, $IdDireccionamiento_busc_ini) + strlen($IdDireccionamiento_busc_ini); //numero de pocicion inicial
	$posPresFinal = strpos($cadena_IdDireccionamiento, $IdDireccionamiento_busc_fin); //numero de pocicion final
	$IdDireccionamiento = substr($cadena_IdDireccionamiento, $posPresInicial, $posPresFinal - $posPresInicial); //Dato extaido de la cadena completa
	//$IdDireccionamiento = str_replace('"', "'", $IdDireccionamiento);
	$id = $Id;
	$id_direc = $IdDireccionamiento;


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



	////Actualizar tabla con el token temporal (Inicio)
	$sql_exc = "UPDATE " . $nombre_tabla . " 
 SET  DIR_IDDIRECCIONAMIENTO = " . $id_direc . ", DIR_ID = " . $id
		. " WHERE  CONORDEN = " . $ConTec
		. " AND ID_PRES in ( select id_pres from WEBSERV_PRES_PRES where NOPRESCRIPCION='"
		. $NoPrescripcion . "')";

	$st_direc = oci_parse($conn_oracle, $sql_exc);
	$result = oci_execute($st_direc);
	oci_free_statement($st_direc);
	if ($result) {
		//echo  "<br>Actualización Correcta ";
	} else {
		//echo  "<br>Actualización Incorrecta ";
	}
	/////Actualizar tabla con el token temporal  (Fin)


	
	////Actualizar tabla con el token temporal (Inicio)

	$fecha_oracle = date("d/m/Y", strtotime($FecMaxEnt)); //formato originar "y/m/d"
$sql_exc = 	"INSERT
		INTO WEBSERV_PRES_DIRECCIONADOS
		  (
			NOPRESCRIPCION,
			TIPOTEC,
			NOENTREGA,
			DIR_ID,
			DIR_IDDIRECCIONAMIENTO,
			CONORDEN,
			FECMAXENT

		  )
		  VALUES
		  (
			'$NoPrescripcion',
			'$TipoTec',
			$NoEntrega,
			$id,
			$id_direc,
			$ConTec,
			'$fecha_oracle'
		  )";

	$st_direc2 = oci_parse($conn_oracle, $sql_exc);
	$result = oci_execute($st_direc2);
	oci_free_statement($st_direc2);
	if ($result) {
		//echo  "<br>Actualización Correcta ";
	} else {
		//echo  "<br>Actualización Incorrecta ";
	}
	/////Actualizar tabla con el token temporal  (Fin)

}


oci_close($conn_oracle);
