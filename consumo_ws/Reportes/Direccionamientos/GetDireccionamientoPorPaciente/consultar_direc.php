<?php

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
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
  d.ID,
  d.IDDIRECCIONAMIENTO,
  decode(DESC_CODSERTECAENTREGAR,null,'NO EXISTE',DESC_CODSERTECAENTREGAR)DESC_CODSERTECAENTREGAR,
  d.NOIDPROV,
  FECDIRECCIONAMIENTO,
  decode(ESTDIRECCIONAMIENTO,
         1,'Direccionado',
         2,'Pendiente',
         0,'Anulado',ESTDIRECCIONAMIENTO)ESTDIRECCIONAMIENTO,
    FECANULACION,
  pi.NOPRESCRIPCION,
  pi.TIPOIDPACIENTE,
  pi.NROIDPACIENTE,
  decode(pi.TIPOTEC,'P','PROCEDIMIENTO',
                 'M','MEDICAMENTO',
                 'N','PRODUCTOS NUTRICIONALES',
                 'S','SERVICIOS COMPEMENTARIOS',
                 'D','DISPOSITIVOS MEDICOS',pi.TIPOTEC)DESC_TIPOTEC,
  pi.CONORDEN,
  NOENTREGA,
  d.CODEPS
  from WEBSERV_DIRECCIONAMIENTOS d
  left JOIN view_webserv_pres_info_direc pi 
  ON trim(pi.NOPRESCRIPCION)=trim(d.NOPRESCRIPCION)  and trim(pi.NROIDPACIENTE)=trim(d.NOIDPACIENTE) 
     and pi.CONORDEN=d.CONTEC and pi.TIPOTEC=d.TIPOTEC
  where 1=1";

  if ($NoPrescripcion != '' && $NoPrescripcion != null) {
    $query = $query . " and trim(d.NOPRESCRIPCION)='$NoPrescripcion'";
  };
  if ($NoIDPaciente != '' && $NoIDPaciente != null) {
    $query = $query . " and trim(pi.NROIDPACIENTE) = '$NoIDPaciente'";
  }

  $query = $query . " order by pi.TIPOTEC,pi.CONORDEN,NOENTREGA";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);

  $tabla = '<table class="table display AllDataTables">
  <thead>
      <tr>
          <th>id</th>
          <th>iddireccionamiento</th>
          <th>servicio a entregar</th>
          <th>Nit del Proveedor</th>
          <th>Fecha de Direccionamiento</th>
          <th>Estado del Direccionamiento</th>
          <th>Fecha de Anulacion</th>
          <th>Número de prescripción</th>
          <th>Tipo de documento</th>
          <th>Número de Documento</th>
          <th>Tipo de tecnología</th>
          <th>Conteo de tecnología</th>
          <th>Número de Entrega</th>
          <th>Codigo de la EPS</th>
      </tr>
  </thead>
  
  <tbody>';
  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $FECDIRECCIONAMIENTO  = str_replace(',000000', '', $row['FECDIRECCIONAMIENTO']);
    $FECANULACION  = str_replace(',000000', '', $row['FECANULACION']);
    //No muestra si la hora es am o PM
    
    $tabla = $tabla . "
      <tr>
            <td>" . $row['ID'] . "</td>
            <td>" . $row['IDDIRECCIONAMIENTO'] . "</td>
            <td>" . $row['DESC_CODSERTECAENTREGAR'] . "</td>
            <td>" . $row['NOIDPROV'] . "</td>
            <td>" . $FECDIRECCIONAMIENTO . "</td>
            <td>" . $row['ESTDIRECCIONAMIENTO'] . "</td>
            <td>" . $FECANULACION . "</td>
            <td>" . $row['NOPRESCRIPCION'] . "</td>
            <td>" . $row['TIPOIDPACIENTE'] . "</td>
            <td>" . $row['NROIDPACIENTE'] . "</td>
            <td>" . $row['DESC_TIPOTEC'] . "</td>
            <td>" . $row['CONORDEN'] . "</td>
            <td>" . $row['NOENTREGA'] . "</td>
            <td>" . $row['CODEPS'] . "</td>
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
