<?php

$conn_oracle = oci_connect('RPARRA', 'Rparra2019', '10.244.19.75:1521/ambuqPRD');
set_time_limit(9999999);
ini_set('memory_limit', '-1');

//Variable Generales
$json = "";

$NoPrescripcion = $_POST['NoPrescripcion'];
$NoIDPaciente = $_POST['NoIDPaciente'];

/***************************************************************************************/
/***************************************************************************************/
/////////////////////////////Hoja 1 Datos Generales (Inicio)///////////////////////////
/***************************************************************************************/
/***************************************************************************************/
if (($NoPrescripcion != '' && $NoPrescripcion != null) || ($NoIDPaciente != '' && $NoIDPaciente != null)) {

  $query = "
  select 
  decode(d.ID,null,'.',d.ID)ID,
  decode(d.IDDIRECCIONAMIENTO,null,'.',d.IDDIRECCIONAMIENTO)IDDIRECCIONAMIENTO,
  decode(DESC_CODSERTECAENTREGAR,null,'.',DESC_CODSERTECAENTREGAR)DESC_CODSERTECAENTREGAR,
  d.NOIDPROV || ' - ' || NOMBRE PROVEEDOR,
  decode(ESTDIRECCIONAMIENTO,
         1,'1-Activo',
         2,'2-Procesado',
         0,'0-Anulado',ESTDIRECCIONAMIENTO)ESTDIRECCIONAMIENTO,
  decode(FECDIRECCIONAMIENTO,null,'.',FECDIRECCIONAMIENTO)FECDIRECCIONAMIENTO,
  decode(d.TIPOTEC,'P','PROCEDIMIENTO',
                 'M','MEDICAMENTO',
                 'N','PRODUCTOS NUTRICIONALES',
                 'S','SERVICIOS COMPEMENTARIOS',
                 'D','DISPOSITIVOS MEDICOS',
                 null,'.',d.TIPOTEC)DESC_TIPOTEC,
  decode(d.CONTEC,null,'.',d.CONTEC)CONTEC,
  decode(d.NOENTREGA,null,'.',d.NOENTREGA)NOENTREGA,
   decode(FECANULACION,null,'.',FECANULACION)FECANULACION,
  decode(d.NOPRESCRIPCION,null,'.',d.NOPRESCRIPCION)NOPRESCRIPCION,
  decode(d.TIPOIDPACIENTE,null,'.',d.TIPOIDPACIENTE)TIPOIDPACIENTE,
  decode(d.NOIDPACIENTE,null,'.',d.NOIDPACIENTE)NOIDPACIENTE
  --decode(d.CODEPS,null,'.',d.CODEPS)CODEPS
  FROM OASIS4.WEBSERV_DIRECCIONAMIENTOS d
  left JOIN OASIS4.VIEW_WEBSERV_pres_info_direc pi 
  ON trim(pi.NOPRESCRIPCION)=trim(d.NOPRESCRIPCION)  and trim(pi.NROIDPACIENTE)=trim(d.NOIDPACIENTE) 
     and pi.CONORDEN=d.CONTEC and pi.TIPOTEC=d.TIPOTEC
  left JOIN OASIS4.WEBSERV_PRES_LIST_PROV pr on pr.NOIDPROV=d.NOIDPROV
  where 1=1";

  if ($NoPrescripcion != '' && $NoPrescripcion != null) {
    $query = $query . " and trim(d.NOPRESCRIPCION)='$NoPrescripcion'";
  };
  if ($NoIDPaciente != '' && $NoIDPaciente != null) {
    $query = $query . " and trim(d.NOIDPACIENTE) = '$NoIDPaciente'";
  }

  $query = $query . " order by d.TIPOTEC,d.CONtec,d.NOENTREGA";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);

  $tabla = '<table class="table display AllDataTables">
  <thead>
      <tr>
          <th>id</th>
          <th>iddireccionamiento</th>
          <th>Descripcion_del_Servicio_Tecnico_a_entregar</th>
          <th>Nit_del_Proveedor</th>
          <th>Estado del Direccionamiento</th>
          <th>Tipo de tecnología</th>
          <th>Conteo de tecnología</th>
          <th>Número de Entrega</th>
          <th>Fecha de Direccionamiento</th>
          <th>Fecha de Anulacion</th>
          <th>Número de prescripción</th>
          <th>Tipo de documento</th>
          <th>Número de Documento</th>
      </tr>
  </thead>
  
  <tbody>';
  $i = 1;
  $FECANULACION  = '';
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $ID = $row['ID'];
    $IDDIRECCIONAMIENTO = $row['IDDIRECCIONAMIENTO'];
    $DESC_CODSERTECAENTREGAR = $row['DESC_CODSERTECAENTREGAR'];
    $PROVEEDOR = $row['PROVEEDOR'];
    $ESTDIRECCIONAMIENTO = $row['ESTDIRECCIONAMIENTO'];
    $DESC_TIPOTEC = $row['DESC_TIPOTEC'];
    $CONTEC = $row['CONTEC'];
    $NOENTREGA = $row['NOENTREGA'];
    $FECDIRECCIONAMIENTO  = str_replace(',000000', '', $row['FECDIRECCIONAMIENTO']);
    $FECANULACION  = str_replace(',000000', '', $row['FECANULACION']);
    $NOPRESCRIPCION = $row['NOPRESCRIPCION'];
    $TIPOIDPACIENTE = $row['TIPOIDPACIENTE'];
    $NOIDPACIENTE = $row['NOIDPACIENTE'];

    if ($row['ID'] == '.') {
      $ID = '';
    }
    if ($row['IDDIRECCIONAMIENTO'] == '.') {
      $IDDIRECCIONAMIENTO = '';
    }
    if ($row['DESC_CODSERTECAENTREGAR'] == '.') {
      $DESC_CODSERTECAENTREGAR = '';
    }
    if ($row['PROVEEDOR'] == '.') {
      $PROVEEDOR = '';
    }
    if ($row['ESTDIRECCIONAMIENTO'] == '.') {
      $ESTDIRECCIONAMIENTO = '';
    }
    if ($row['DESC_TIPOTEC'] == '.') {
      $DESC_TIPOTEC = '';
    }
    if ($row['CONTEC'] == '.') {
      $CONTEC = '';
    }
    if ($row['NOENTREGA'] == '.') {
      $NOENTREGA = '';
    }
    if ($row['FECDIRECCIONAMIENTO'] == '.') {
      $FECDIRECCIONAMIENTO = '';
    }
    if ($row['FECANULACION'] == '.') {
      $FECANULACION = '';
    }
    if ($row['NOPRESCRIPCION'] == '.') {
      $NOPRESCRIPCION = '';
    }
    if ($row['TIPOIDPACIENTE'] == '.') {
      $TIPOIDPACIENTE = '';
    }
    if ($row['NOIDPACIENTE'] == '.') {
      $NOIDPACIENTE = '';
    }
    //No muestra si la hora es am o PM

    $tabla = $tabla . "
      <tr>
            <td>" . $ID . "</td>
            <td>" . $IDDIRECCIONAMIENTO . "</td>
            <td>" . $DESC_CODSERTECAENTREGAR . "</td>
            <td>" . $PROVEEDOR . "</td>
            <td>" . $ESTDIRECCIONAMIENTO . "</td>
            <td>" . $DESC_TIPOTEC . "</td>
            <td>" . $CONTEC . "</td>
            <td>" . $NOENTREGA . "</td>
            <td>" . $FECDIRECCIONAMIENTO . "</td>
            <td>" . $FECANULACION . "</td>
            <td>" . $NOPRESCRIPCION . "</td>
            <td>" . $TIPOIDPACIENTE . "</td>
            <td>" . $NOIDPACIENTE . "</td>
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
