<?php
set_time_limit(9999999);
ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
$NoPrescripcion = $_POST['NoPrescripcion'];
$TipoTec = $_POST['TipoTec'];
$ConTec = $_POST['ConTec'];
$NoEntrega = $_POST['NoEntrega'];

/////obtener los parametros la url(inicio)
$query = " 
select 
pi.NOPRESCRIPCION,
pi.TIPOTEC,
pi.CONORDEN,
pd.NOENTREGA,
to_date(pd.FECMAXENT, 'YYYY-MM-DD') FECMAXENT,
DECODE(pd.DIR_IDDIRECCIONAMIENTO,NULL,0,pd.DIR_IDDIRECCIONAMIENTO)DIR_IDDIRECCIONAMIENTO,
DECODE(pd.DIR_ID,NULL,0,pd.DIR_ID)DIR_ID
from view_webserv_pres_info_direc pi
left join WEBSERV_PRES_DIRECCIONADOS pd on pd.NOPRESCRIPCION=pi.NOPRESCRIPCION and pi.TIPOTEC=pd.TIPOTEC and pi.CONORDEN=pd.CONORDEN
where  pd.NOPRESCRIPCION='" . $NoPrescripcion . "' 
and pd.TIPOTEC='" . $TipoTec . "' 
and pd.CONORDEN=" . $ConTec . " 
and pd.NOENTREGA=" . $NoEntrega;




$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);

$datos_direc_json = '[';
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {

    $fecha_maxima_de_entrega = "20" . $row['FECMAXENT'];
    $fecha_maxima_de_entrega = str_replace("/", "-", $fecha_maxima_de_entrega);

    $datos_direc_json = $datos_direc_json
        . '{"NOPRESCRIPCION":"' . $row['NOPRESCRIPCION']
        . '","TIPOTEC":"' . $row['TIPOTEC']
        . '","CONORDEN":"' . $row['CONORDEN']
        . '","NOENTREGA":"' . $row['NOENTREGA']
        . '","FECMAXENT":"' . $fecha_maxima_de_entrega
        . '","DIR_ID":"' .  $row['DIR_ID']
        . '","DIR_IDDIRECCIONAMIENTO":"' .  $row['DIR_IDDIRECCIONAMIENTO'] . '"},';
}
if ($datos_direc_json != '[') {
    $datos_direc_json = substr($datos_direc_json, 0, -1);
}
$datos_direc_json = $datos_direc_json . ']';
echo $datos_direc_json;
