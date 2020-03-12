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
NOPRESCRIPCION,
TIPOTEC,
CONORDEN,
TIPOIDPACIENTE,
NROIDPACIENTE,
CODMUNENT,
DIRPACIENTE,
REGIMEN,
decode(CODAMBATE,null,'NO EXISTE',CODAMBATE)CODAMBATE,
decode(DESC_CODAMBATE,null,'NO EXISTE',DESC_CODAMBATE)DESC_CODAMBATE,
decode(TIPOTEC,'P',to_date(sysdate+90, 'YYYY-MM-DD'),
decode(CODAMBATE,11,decode((select 
                            count(1) cantidad_entregas
                            from  WEBSERV_PRES_DIRECCIONADOS pd 
                            where  pd.NOPRESCRIPCION=pi.NOPRESCRIPCION
                            and pd.TIPOTEC=pi.TIPOTEC
                            and pd.CONORDEN=pi.CONORDEN),0,to_date(sysdate+15, 'YYYY-MM-DD'),
                              to_date(sysdate+30, 'YYYY-MM-DD')),
                 12,to_date(sysdate+30, 'YYYY-MM-DD'),
                 21,to_date(sysdate+30, 'YYYY-MM-DD'),
                   '11-11-1111')) FECHA_MAXIMA_DE_ENTREGA,
decode(CODSERTECAENTREGAR,null,'NO EXISTE',CODSERTECAENTREGAR)CODSERTECAENTREGAR,
decode(DESC_CODSERTECAENTREGAR,null,'NO EXISTE',DESC_CODSERTECAENTREGAR)DESC_CODSERTECAENTREGAR,
DECODE(DIR_IDDIRECCIONAMIENTO,NULL,0,DIR_IDDIRECCIONAMIENTO)DIR_IDDIRECCIONAMIENTO,
DECODE(DIR_ID,NULL,0,DIR_ID)DIR_ID
from view_webserv_pres_info_direc pi
where  NOPRESCRIPCION='" . $NoPrescripcion . "'
 and TIPOTEC='" . $TipoTec . "' 
 and CONORDEN=" . $ConTec; //'20200206186017293511';





$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);

$tipos_tecnologia_json = '[';
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {

    $fecha_maxima_de_entrega = "20" . $row['FECHA_MAXIMA_DE_ENTREGA'];
    $fecha_maxima_de_entrega = str_replace("/", "-", $fecha_maxima_de_entrega);

    //agregar los 0 faltantes del codigo DANE(Inicio)
    $CodMunEnt = $row['CODMUNENT'];
    if (strlen($CodMunEnt) == 4) {
        $CodMunEnt = '0' . $CodMunEnt;
    }
    //agregar los 0 faltantes del codigo DANE(Fin)

    $tipos_tecnologia_json = $tipos_tecnologia_json
        . '{"TIPOIDPACIENTE":"' . $row['TIPOIDPACIENTE']
        . '","NROIDPACIENTE":"' . $row['NROIDPACIENTE']
        . '","CODMUNENT":"' . $CodMunEnt
        . '","DIRPACIENTE":"' . utf8_encode($row['DIRPACIENTE'])
        . '","REGIMEN":"' . $row['REGIMEN']
        . '","CODAMBATE":"' . $row['CODAMBATE']
        . '","DESC_CODAMBATE":"' . utf8_encode($row['DESC_CODAMBATE'])
        . '","FECHA_MAXIMA_DE_ENTREGA":"' . $fecha_maxima_de_entrega
        . '","CODSERTECAENTREGAR":"' . $row['CODSERTECAENTREGAR']
        . '","DESC_CODSERTECAENTREGAR":"' .  utf8_encode($row['DESC_CODSERTECAENTREGAR'])
        . '","DIR_ID":"' .  $row['DIR_ID']
        . '","DIR_IDDIRECCIONAMIENTO":"' .  $row['DIR_IDDIRECCIONAMIENTO'] . '"},';
}
if ($tipos_tecnologia_json != '[') {
    $tipos_tecnologia_json = substr($tipos_tecnologia_json, 0, -1);
}
$tipos_tecnologia_json = $tipos_tecnologia_json . ']';
echo $tipos_tecnologia_json;
