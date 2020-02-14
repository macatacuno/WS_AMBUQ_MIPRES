<?php
set_time_limit(9999999);
ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
$NoPrescripcion = $_POST['NoPrescripcion'];
$TipoTec = $_POST['TipoTec'];

/////obtener los parametros la url(inicio)
$query = "select 
NOPRESCRIPCION,
TIPOTEC,
TIPOIDPACIENTE,
NROIDPACIENTE,
CODMUNENT,
DIRPACIENTE,
max(conorden) CANTIDAD_TIPOTEC
from view_webserv_pres_info_direc
where  NOPRESCRIPCION='" . $NoPrescripcion . "'
 and TIPOTEC='" . $TipoTec . "' 
 GROUP BY NOPRESCRIPCION,TIPOTEC,TIPOIDPACIENTE,NROIDPACIENTE,CodMunEnt,DirPaciente"; //'20200206186017293511';
$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);

$tipos_tecnologia_json = '[';
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {
    $tipos_tecnologia_json = $tipos_tecnologia_json
        . '{"TIPOIDPACIENTE":"' . $row['TIPOIDPACIENTE']
        . '","NROIDPACIENTE":"' . $row['NROIDPACIENTE']
        . '","CODMUNENT":"' . $row['CODMUNENT']
        . '","DIRPACIENTE":"' . utf8_encode($row['DIRPACIENTE']) . '"},';
}
if ($tipos_tecnologia_json != '[') {
    $tipos_tecnologia_json = substr($tipos_tecnologia_json, 0, -1);
}
$tipos_tecnologia_json = $tipos_tecnologia_json . ']';
echo $tipos_tecnologia_json;
