<?php
set_time_limit(9999999);
ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('RPARRA', 'Rparra2019', '10.244.19.75:1521/ambuqPRD');
//$NoPrescripcion = $_POST['NoPrescripcion'];
//$TipoTec = $_POST['TipoTec'];

/////obtener los parametros la url(inicio)
$query = "SELECT NOIDPROV, NOMBRE FROM OASIS4.WEBSERV_PRES_LIST_PROV";
$st = oci_parse($conn_oracle, $query);
oci_execute($st, OCI_DEFAULT);

$json = '[';
while (($row = oci_fetch_array($st, OCI_BOTH)) != false) {
    $json = $json .
        '{"NOIDPROV":"' . $row['NOIDPROV'] .
        '","NOMBRE":"' .utf8_encode($row['NOMBRE']) . '"},';
}
if ($json != '[') {
    $json = substr($json, 0, -1);
}
$json = $json . ']';
echo $json;

?>