<?php
set_time_limit(9999999);
ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
$NoPrescripcion = $_POST['NoPrescripcion'];

/////obtener los parametros la url(inicio)
$query = "select 
distinct(TIPOTEC) TIPOTEC,
decode(TIPOTEC,'P','PROCEDIMIENTO','M','MEDICAMENTO','N','PRODUCTOS NUTRICIONALES','S','SERVICIOS COMPEMENTARIOS','D','DISPOSITIVOS MEDICOS',TIPOTEC)DESC_TIPOTEC
from view_webserv_pres_info_direc
where  NOPRESCRIPCION='" . $NoPrescripcion . "'"; //'20200206186017293511';
$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);

$tipos_tecnologia_json = '[';
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {
    $tipos_tecnologia_json = $tipos_tecnologia_json . '{"TIPOTEC":"' . $row['TIPOTEC'] . '","DESC_TIPOTEC":"' . $row['DESC_TIPOTEC'] . '"},';
}
if ($tipos_tecnologia_json != '[') {
    $tipos_tecnologia_json = substr($tipos_tecnologia_json, 0, -1);
}
$tipos_tecnologia_json = $tipos_tecnologia_json . ']';
//$tipos_tecnologia_json='[{"TIPOTEC":"M","DESC_TIPOTEC":"MEDICAMENTO"},{"TIPOTEC":"P","DESC_TIPOTEC":"PROCEDIMIENTO"},]';
//$tipos_tecnologia_json='[{  "ID": "19039087", "IDDireccionamiento": 18448787},{  "ID": "19039087", "IDDireccionamiento": 18448787},{  "ID": "19039087", "IDDireccionamiento": 18448787} ]';
echo $tipos_tecnologia_json;

?>