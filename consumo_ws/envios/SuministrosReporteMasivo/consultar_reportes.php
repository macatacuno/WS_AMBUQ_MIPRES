<?php

$conn_oracle = oci_connect('RPARRA', 'Rparra2019', '10.244.19.75:1521/ambuqPRD');
set_time_limit(9999999);
ini_set('memory_limit', '-1');

//Variable Generales
$json = "";

$estado = $_POST['estado'];

/***************************************************************************************/
/***************************************************************************************/
/////////////////////////////Hoja 1 Datos Generales (Inicio)///////////////////////////
/***************************************************************************************/
/***************************************************************************************/
if ($estado != '' && $estado != null) {

  $query = "
  SELECT --count(*) CANTIDAD
  --  Datos suministro
  DECODE(RE.ID,NULL,'.',RE.ID) ID,
  '.' UltEntrega,
  '.' EntregaCompleta,
  DECODE(RE.CAUSANOENTREGA,NULL,'.',RE.CAUSANOENTREGA) CAUSANOENTREGA,
  DECODE(RE.NOPRESCRIPCION,NULL,'.',RE.NOPRESCRIPCION ) NOPRESCRIPCIONASOCIADA,
  DECODE(RE.CONTEC,NULL,'.',RE.CONTEC ) CONTECASOCIADA,
  DECODE(RE.CANTTOTENTREGADA,NULL,'.',RE.CANTTOTENTREGADA ) CANTTOTENTREGADA,
  DECODE(RE.NOLOTE,NULL,'.',RE.NOLOTE) NOLOTE,
  DECODE(RE.VALORENTREGADO,NULL,'.',RE.VALORENTREGADO ) VALORENTREGADO
  
  /*--------Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----*/
  FROM OASIS4.WEBSERV_DIRECCIONAMIENTOS D 
  /*LEFT JOIN OASIS4.VIEW_WEBSERV_PRES_INFO_DIREC PI ON D.NOPRESCRIPCION=PI.NOPRESCRIPCION AND D.TIPOTEC= PI.TIPOTEC AND D.CONTEC=PI.CONORDEN */
  LEFT JOIN OASIS4.WEBSERV_PRES_LIST_PROV PROV ON PROV.NOIDPROV=D.NOIDPROV
  LEFT JOIN  OASIS4.VIEW_WEBSERV_PRES_CODI_TEC CODTEC_DIR 
  ON CODTEC_DIR.TIPOTEC=D.TIPOTEC and CODTEC_DIR.CODIGO=D.CODSERTECAENTREGAR
  left JOIN OASIS4.WEBSERV_REPORTE_ENTREGA RE on  D.ID=re.id and D.REPO_TIRE_ID=RE.REPO_TIRE_ID AND  
            RE.FECANULACION IS NULL
  LEFT JOIN  OASIS4.VIEW_WEBSERV_PRES_CODI_TEC CODTEC_REP
  ON CODTEC_REP.TIPOTEC=re.TIPOTEC and CODTEC_REP.CODIGO=re.CODTECENTREGADO
  LEFT JOIN OASIS4.WEBSERV_SUMINISTROS S ON S.ID=RE.ID AND S.REPO_TIRE_ID=RE.REPO_TIRE_ID AND S.FECANULACION IS NULL 
  where D.FECANULACION is null 
  AND D.IDDIRECCIONAMIENTO is not null-- Esta validacion es para que no se muestren las prescripciones que no han sido direccionadas
  AND S.IDSUMINISTRO IS NULL";

  if ($estado == '1') {
    $query = $query . " and rownum<=10";
  } else if ($estado == '2') {
    $query = $query . " and rownum<=20";
  } else if ($estado == '3') {
    $query = $query . " and rownum<=30";
  } else {
    $query = $query . " and rownum<=5";
  }

  $query = $query . " order by d.TIPOTEC,d.CONtec,d.NOENTREGA";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);

  $tabla = '<table id="tablaSumi" class="table display">
  <thead>
      <tr>
          <th>heck</th>
          <th>ID</th>
          <th>ULTENTREGA</th>
          <th>ENTREGACOMPLETA</th>
          <th>CAUSANOENTREGA</th>
          <th>NOPRESCRIPCIONASOCIADA</th>
          <th>CONTECASOCIADA</th>
          <th>CANTTOTENTREGADA</th>
          <th>NOLOTE</th>
          <th>VALORENTREGADO</th>
      </tr>
  </thead>
  
  <tbody>';
  $i = 1;
  $FECANULACION  = '';
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $ID = $row['ID'];
    $ULTENTREGA = $row['ULTENTREGA'];
    $ENTREGACOMPLETA = $row['ENTREGACOMPLETA'];
    $CAUSANOENTREGA = $row['CAUSANOENTREGA'];
    $NOPRESCRIPCIONASOCIADA = $row['NOPRESCRIPCIONASOCIADA'];
    $CONTECASOCIADA = $row['CONTECASOCIADA'];
    $CANTTOTENTREGADA = $row['CANTTOTENTREGADA'];
    $NOLOTE = $row['NOLOTE'];
    $VALORENTREGADO = $row['VALORENTREGADO'];

    if ($row['ID'] == '.') {
      $ID = '';
    }
    if ($row['ULTENTREGA'] == '.') {
      $ULTENTREGA = '';
    }
    if ($row['ENTREGACOMPLETA'] == '.') {
      $ENTREGACOMPLETA = '';
    }
    if ($row['CAUSANOENTREGA'] == '.') {
      $CAUSANOENTREGA = '';
    }
    if ($row['NOPRESCRIPCIONASOCIADA'] == '.') {
      $NOPRESCRIPCIONASOCIADA = '';
    }
    if ($row['CONTECASOCIADA'] == '.') {
      $CONTECASOCIADA = '';
    }
    if ($row['CANTTOTENTREGADA'] == '.') {
      $CANTTOTENTREGADA = '';
    }
    if ($row['NOLOTE'] == '.') {
      $NOLOTE = '';
    }
    if ($row['VALORENTREGADO'] == '.') {
      $VALORENTREGADO = '';
    }
    //No muestra si la hora es am o PM

    $tabla = $tabla . "
      <tr>
            <td>" . $ID . "</td>
            <td>" . $ID . "</td>
            <td>" . $ULTENTREGA . "</td>
            <td>" . $ENTREGACOMPLETA . "</td>
            <td>" . $CAUSANOENTREGA . "</td>
            <td>" . $NOPRESCRIPCIONASOCIADA . "</td>
            <td>" . $CONTECASOCIADA . "</td>
            <td>" . $CANTTOTENTREGADA . "</td>
            <td>" . $NOLOTE . "</td>
            <td>" . $VALORENTREGADO . "</td>
      </tr>
  ";
  }
  $tabla = $tabla . '</tbody> </table>';
  echo $tabla;
} else {
}
/***************************************************************************************/
/***************************************************************************************/
/////////////////////////////Hoja 1 Datos Generales(Fin)///////////////////////////////
/***************************************************************************************/
/***************************************************************************************/
