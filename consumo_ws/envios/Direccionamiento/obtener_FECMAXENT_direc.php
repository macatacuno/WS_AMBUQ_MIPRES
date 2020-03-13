<?php
set_time_limit(9999999);
ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
$NoPrescripcion = $_POST['NoPrescripcion'];
$TipoTec = $_POST['TipoTec'];
$ConTec = $_POST['ConTec'];

/////obtener los parametros la url(inicio)
$query = " 
select
CANTIDAD_ENTREGAS,
decode(pi.TIPOTEC,'P',to_date(FECMAXENT_ULTIMA_ENTREGA+90, 'YYYY-MM-DD'),
                decode(CODAMBATE,11,decode((select 
                            count(1) cantidad_entregas
                            from  WEBSERV_PRES_DIRECCIONADOS pd 
                            where  pd.NOPRESCRIPCION=pi.NOPRESCRIPCION
                            and pd.TIPOTEC=pi.TIPOTEC
                            and pd.CONORDEN=pi.CONORDEN),0,to_date(FECMAXENT_ULTIMA_ENTREGA+15, 'YYYY-MM-DD'),
                              to_date(FECMAXENT_ULTIMA_ENTREGA+30, 'YYYY-MM-DD')),
                 12,to_date(FECMAXENT_ULTIMA_ENTREGA+30, 'YYYY-MM-DD'),
                 21,to_date(FECMAXENT_ULTIMA_ENTREGA+30, 'YYYY-MM-DD'),
                   '11-11-1111'))  FECMAXENT
from view_webserv_pres_info_direc pi
left join WEBSERV_PRES_DIRECCIONADOS pd on pd.NOPRESCRIPCION=pi.NOPRESCRIPCION and pi.TIPOTEC=pd.TIPOTEC and pi.CONORDEN=pd.CONORDEN
left join (select pd.NOPRESCRIPCION,pd.TIPOTEC,pd.CONORDEN, 
count(NOENTREGA) CANTIDAD_ENTREGAS,
max(FECMAXENT) FECMAXENT_ULTIMA_ENTREGA
from WEBSERV_PRES_DIRECCIONADOS pd
GROUP by pd.NOPRESCRIPCION,pd.TIPOTEC,pd.CONORDEN) pm on pd.NOPRESCRIPCION=pm.NOPRESCRIPCION and pd.TIPOTEC=pm.TIPOTEC 
and pd.CONORDEN=pm.CONORDEN
where  pd.NOPRESCRIPCION='" . $NoPrescripcion . "' 
and pd.TIPOTEC='" . $TipoTec . "' 
and pd.CONORDEN=" . $ConTec.
"and rownum<=1
order by FECMAXENT desc";




$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);


$datos_direc_json = '[';
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {

    $fecha_maxima_de_entrega = "20" . $row['FECMAXENT'];
    $fecha_maxima_de_entrega = str_replace("/", "-", $fecha_maxima_de_entrega);

    $datos_direc_json = $datos_direc_json
    . '{"CANTIDAD_ENTREGAS":"' . $row['CANTIDAD_ENTREGAS']
    . '","FECMAXENT":"' . $fecha_maxima_de_entrega . '"},';
}
if ($datos_direc_json != '[') {
    $datos_direc_json = substr($datos_direc_json, 0, -1);
}
$datos_direc_json = $datos_direc_json . ']';
echo $datos_direc_json;
