<?php

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
set_time_limit(9999999);
ini_set('memory_limit', '-1');

//Variable Generales
$json = "";

$periodo_inicial = $_POST['periodo_inicial'];
$periodo_inicial_oracle = date("d/m/Y", strtotime($periodo_inicial)); //formato originar "y/m/d"

$periodo_final = $_POST['periodo_final'];
$periodo_final_oracle = date("d/m/Y", strtotime($periodo_final)); //formato originar "y/m/d"

$tipo_id = $_POST['tipo'];
$regimen = "";
if ($tipo_id == "1") {
  $regimen = "Cont";
} else 
if ($tipo_id == "2") {
  $regimen = "Subs";
}
$servicio_id = 1; // Se asigna el codigo del servicio GetReporteEntrega 

//Se calcula el rango de los periodos
$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias = $diff->days + 1;
$periodo_conteo = $periodo_inicial;

if ($periodo_final < $periodo_inicial) {
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
} else {


  // Incluir la libreria PHPExcel 
  require_once '../../../../plugins/PHPExcel/Classes/PHPExcel.php';
  // Crea un nuevo objeto PHPExcel
  //$objPHPExcel = new PHPExcel();

  $objXLS = new PHPExcel();
  // Establecer propiedades
  $objXLS->getProperties()
    ->setCreator("Cattivo")
    ->setLastModifiedBy("Cattivo")
    ->setTitle("Excel AMBUQ-MIPRES")
    ->setSubject("Excel AMBUQ-MIPRES")
    ->setDescription("Excel para visualización de reportes de MIPRES")
    ->setKeywords("Excel AMBUQ-MIPRES")
    ->setCategory("Excel  AMBUQ-MIPRES Prescripciones");



  /***************************************************************************************/
  /***************************************************************************************/
  /////////////////////////////Hoja 1 Datos Generales (Inicio)///////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 0;
  /*$query = "SELECT 
  DECODE(RE.REPO_SERV_ID,NULL,'NO EXISTE',RE.REPO_SERV_ID) REPO_SERV_ID,
  DECODE(RE.REPO_TIRE_ID,NULL,'NO EXISTE',RE.REPO_TIRE_ID)REPO_TIRE_ID,
    DECODE(to_char(RE.REPO_PERIODO,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(RE.REPO_PERIODO,'DD/MM/YYYY')) REPO_PERIODO,
  DECODE(RE.ID,NULL,'NO EXISTE',RE.ID)ID,
  DECODE(RE.IDREPORTEENTREGA,null,'NO EXISTE',RE.IDREPORTEENTREGA)IDREPORTEENTREGA,
  DECODE(RE.NOPRESCRIPCION,null,'NO EXISTE',RE.NOPRESCRIPCION)NOPRESCRIPCION,
  decode(RE.TIPOTEC,
    'P','PROCEDIMIENTO',
    'M','MEDICAMENTO',
    'N','PRODUCTOS NUTRICIONALES',
    'S','SERVICIOS COMPEMENTARIOS',
    'D','DISPOSITIVOS MEDICOS',RE.TIPOTEC)TIPOTEC,
  DECODE(RE.CONTEC,null,'NO EXISTE',RE.CONTEC)CONTEC,
  DECODE(RE.TIPOIDPACIENTE,null,'NO EXISTE',RE.TIPOIDPACIENTE)TIPOIDPACIENTE,
  DECODE(RE.NOIDPACIENTE,null,'NO EXISTE',RE.NOIDPACIENTE)NOIDPACIENTE,
  DECODE(RE.NOENTREGA,null,'NO EXISTE',RE.NOENTREGA)NOENTREGA,
  DECODE(RE.ESTADOENTREGA,null,'NO EXISTE',RE.ESTADOENTREGA)ESTADOENTREGA,
  DECODE(RE.CAUSANOENTREGA,null,'NO EXISTE',RE.CAUSANOENTREGA)CAUSANOENTREGA,
  DECODE(RE.VALORENTREGADO,null,'NO EXISTE',RE.VALORENTREGADO)VALORENTREGADO,
  DECODE(RE.CODTECENTREGADO,null,'NO EXISTE',RE.CODTECENTREGADO)CODTECENTREGADO,
  DECODE(RE.CANTTOTENTREGADA,null,'NO EXISTE',RE.CANTTOTENTREGADA)CANTTOTENTREGADA,
  DECODE(RE.NOLOTE,null,'NO EXISTE',RE.NOLOTE)NOLOTE,
    DECODE(to_char(re.FECENTREGA,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(re.FECENTREGA,'DD/MM/YYYY')) FECENTREGA,
   DECODE(to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) FECREPENTREGA,
  DECODE(RE.ESTREPENTREGA,null,'NO EXISTE',RE.ESTREPENTREGA)ESTREPENTREGA,
   DECODE(to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS')) FECANULACION,
  DECODE(PP.CODDXPPAL,null,'NO EXISTE',PP.CODDXPPAL)CODDXPPAL,
  DECODE(D.NOIDPROV,NULL,'NO EXISTE',D.NOIDPROV) AS ID_PROVEEDOR,
  DECODE(PROV.NOMBRE,NULL,'NO EXISTE',PROV.NOMBRE) AS NOMBRE_PROVEEDOR,
  DECODE(PR.CODSERTECAENTREGAR,null,'NO EXISTE',PR.CODSERTECAENTREGAR)COD_SERVICIO_TECNICO,
  DECODE(PR.DESC_CODSERTECAENTREGAR,null,'NO EXISTE',PR.DESC_CODSERTECAENTREGAR)DESC_SERVICIO_TECNICO,
  
   DECODE(to_char(REE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), 
          NULL,'NO EXISTE',to_char(REE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) FECREPENTREGA_CORREGIDA,
   DECODE(RE.ID,NULL,'NO EXISTE',RE.ID)ID_CORREGIDO,
   DECODE(RE.IDREPORTEENTREGA,null,'NO EXISTE',RE.IDREPORTEENTREGA)IDREPORTEENTREGA_CORREGIDA
FROM WEBSERV_REPORTE_ENTREGA re
LEFT JOIN WEBSERV_DIRECCIONAMIENTOS D ON D.ID=re.ID AND D.REPO_TIRE_ID=re.REPO_TIRE_ID
LEFT JOIN WEBSERV_PRES_LIST_PROV PROV ON PROV.NOIDPROV=D.NOIDPROV
LEFT JOIN WEBSERV_PRES_PRES PP 
  ON  TRIM(PP.NOPRESCRIPCION) =TRIM(RE.NOPRESCRIPCION)
LEFT JOIN  view_webserv_pres_info_direc PR 
  ON TRIM(PR.NOPRESCRIPCION) =TRIM(RE.NOPRESCRIPCION)
     and PR.TIPOTEC=RE.TIPOTEC 
     and PR.conorden=RE.CONTEC
LEFT JOIN  WEBSERV_REPORTE_ENTREGA ree ON 
     ree.NOPRESCRIPCION=re.NOPRESCRIPCION and ree.FECENTREGA=re.FECENTREGA
       and ree.REPO_TIRE_ID=re.REPO_TIRE_ID
       ree.IDREPORTEENTREGA<>re.IDREPORTEENTREGA  and re.FECREPENTREGA<ree.FECREPENTREGA AND 
where  RE.REPO_SERV_ID=" . $servicio_id . " and RE.REPO_TIRE_ID=" . $tipo_id . " and RE.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'"
."order by re.NOPRESCRIPCION, re.FECENTREGA,RE.FECREPENTREGA,REE.FECREPENTREGA";*/
$query = "SELECT 
DECODE(RE.REPO_SERV_ID,NULL,'NO EXISTE',RE.REPO_SERV_ID) REPO_SERV_ID,
DECODE(RE.REPO_TIRE_ID,NULL,'NO EXISTE',RE.REPO_TIRE_ID)REPO_TIRE_ID,
  DECODE(to_char(RE.REPO_PERIODO,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(RE.REPO_PERIODO,'DD/MM/YYYY')) REPO_PERIODO,
DECODE(RE.ID,NULL,'NO EXISTE',RE.ID)ID,
DECODE(RE.IDREPORTEENTREGA,null,'NO EXISTE',RE.IDREPORTEENTREGA)IDREPORTEENTREGA,
DECODE(RE.NOPRESCRIPCION,null,'NO EXISTE',RE.NOPRESCRIPCION)NOPRESCRIPCION,
decode(RE.TIPOTEC,
  'P','PROCEDIMIENTO',
  'M','MEDICAMENTO',
  'N','PRODUCTOS NUTRICIONALES',
  'S','SERVICIOS COMPEMENTARIOS',
  'D','DISPOSITIVOS MEDICOS',RE.TIPOTEC)TIPOTEC,
DECODE(RE.CONTEC,null,'NO EXISTE',RE.CONTEC)CONTEC,
DECODE(RE.TIPOIDPACIENTE,null,'NO EXISTE',RE.TIPOIDPACIENTE)TIPOIDPACIENTE,
DECODE(RE.NOIDPACIENTE,null,'NO EXISTE',RE.NOIDPACIENTE)NOIDPACIENTE,
DECODE(RE.NOENTREGA,null,'NO EXISTE',RE.NOENTREGA)NOENTREGA,
DECODE(RE.ESTADOENTREGA,null,'NO EXISTE',RE.ESTADOENTREGA)ESTADOENTREGA,
DECODE(RE.CAUSANOENTREGA,null,'NO EXISTE',RE.CAUSANOENTREGA)CAUSANOENTREGA,
DECODE(RE.VALORENTREGADO,null,'NO EXISTE',RE.VALORENTREGADO)VALORENTREGADO,
DECODE(RE.CODTECENTREGADO,null,'NO EXISTE',RE.CODTECENTREGADO)CODTECENTREGADO,
DECODE(RE.CANTTOTENTREGADA,null,'NO EXISTE',RE.CANTTOTENTREGADA)CANTTOTENTREGADA,
DECODE(RE.NOLOTE,null,'NO EXISTE',RE.NOLOTE)NOLOTE,
  DECODE(to_char(re.FECENTREGA,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(re.FECENTREGA,'DD/MM/YYYY')) FECENTREGA,
 DECODE(to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) FECREPENTREGA,
DECODE(RE.ESTREPENTREGA,null,'NO EXISTE',RE.ESTREPENTREGA)ESTREPENTREGA,
 DECODE(to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS')) FECANULACION,
DECODE(PP.CODDXPPAL,null,'NO EXISTE',PP.CODDXPPAL)CODDXPPAL,

DECODE(D.IDDIRECCIONAMIENTO,NULL,'NO EXISTE',D.IDDIRECCIONAMIENTO) AS IDDIRECCIONAMIENTO,
DECODE(D.NOIDPROV,NULL,'NO EXISTE',D.NOIDPROV) AS ID_PROVEEDOR,
DECODE(PROV.NOMBRE,NULL,'NO EXISTE',PROV.NOMBRE) AS NOMBRE_PROVEEDOR,
DECODE(PR.CODSERTECAENTREGAR,null,'NO EXISTE',PR.CODSERTECAENTREGAR)COD_SERVICIO_TECNICO,
DECODE(PR.DESC_CODSERTECAENTREGAR,null,'NO EXISTE',PR.DESC_CODSERTECAENTREGAR)DESC_SERVICIO_TECNICO,

 DECODE(to_char(REE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), 
        NULL,'NO EXISTE',to_char(REE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) FECREPENTREGA_CORREGIDA,
 DECODE(REE.ID,NULL,'NO EXISTE',REE.ID)ID_CORREGIDO,
 DECODE(REE.IDREPORTEENTREGA,null,'NO EXISTE',REE.IDREPORTEENTREGA)IDREPORTEENTREGA_CORREGIDA
FROM WEBSERV_REPORTE_ENTREGA re
RIGHT JOIN WEBSERV_DIRECCIONAMIENTOS D ON D.ID=re.ID AND D.REPO_TIRE_ID=re.REPO_TIRE_ID
LEFT JOIN WEBSERV_PRES_LIST_PROV PROV ON PROV.NOIDPROV=D.NOIDPROV
LEFT JOIN WEBSERV_PRES_PRES PP 
ON  TRIM(PP.NOPRESCRIPCION) =TRIM(RE.NOPRESCRIPCION)
LEFT JOIN  view_webserv_pres_info_direc PR 
ON TRIM(PR.NOPRESCRIPCION) =TRIM(RE.NOPRESCRIPCION)
   and PR.TIPOTEC=RE.TIPOTEC 
   and PR.conorden=RE.CONTEC
LEFT JOIN  WEBSERV_REPORTE_ENTREGA ree ON 
   ree.NOPRESCRIPCION=re.NOPRESCRIPCION and ree.FECENTREGA=re.FECENTREGA and ree.REPO_TIRE_ID=re.REPO_TIRE_ID
   AND ree.IDREPORTEENTREGA<>re.IDREPORTEENTREGA 
   and re.FECREPENTREGA<ree.FECREPENTREGA
where  RE.REPO_SERV_ID=" . $servicio_id . " and RE.REPO_TIRE_ID=" . $tipo_id . " and RE.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'"
."order by re.NOPRESCRIPCION, re.FECENTREGA,RE.FECREPENTREGA,REE.FECREPENTREGA";
$st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("Reporte de entrega "); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion
  $objSheet->setCellValue('A1', 'ID_NUEVO');
  $objSheet->setCellValue('B1', 'IDREPORTEENTREGA_NUEVO');
  $objSheet->setCellValue('C1', 'FECREPENTREGA_NUEVO');
  $objSheet->setCellValue('D1', 'ID');
  $objSheet->setCellValue('E1', 'IDREPORTEENTREGA');
  $objSheet->setCellValue('F1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('G1', 'TIPOTEC');
  $objSheet->setCellValue('H1', 'CONTEC');
  $objSheet->setCellValue('I1', 'TIPOIDPACIENTE');
  $objSheet->setCellValue('J1', 'NOIDPACIENTE');
  $objSheet->setCellValue('K1', 'NOENTREGA');
  $objSheet->setCellValue('L1', 'ESTADOENTREGA');
  $objSheet->setCellValue('M1', 'CAUSANOENTREGA');
  $objSheet->setCellValue('N1', 'VALORENTREGADO');
  $objSheet->setCellValue('O1', 'CODTECENTREGADO');
  $objSheet->setCellValue('P1', 'CANTTOTENTREGADA');
  $objSheet->setCellValue('Q1', 'NOLOTE');
  $objSheet->setCellValue('R1', 'FECENTREGA');
  $objSheet->setCellValue('S1', 'FECREPENTREGA');
  $objSheet->setCellValue('T1', 'ESTREPENTREGA');
  $objSheet->setCellValue('U1', 'FECANULACION');
  $objSheet->setCellValue('V1', '---DATOS TECNOLOGIA ENTREGADA-->');
  $objSheet->setCellValue('W1', 'CODDXPPAL');
  $objSheet->setCellValue('X1', 'ID_PROVEEDOR');
  $objSheet->setCellValue('Y1', 'NOMBRE_PROVEEDOR');
  $objSheet->setCellValue('Z1', 'COD_SERVICIO_TECNICO');
  $objSheet->setCellValue('AA1', 'DESC_SERVICIO_TECNICO');
  $objSheet->setCellValue('AB1', 'PERIODO_CONSULTADO');
  $objSheet->setCellValue('AC1', 'ESTADO_CODTECENTREGADO');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    //Validar si el codigo del medicamentoe s correcto
    $reportar = '';
    if ($row["TIPOTEC"] == 'MEDICAMENTO') {
      if (strpos($row["CODTECENTREGADO"], '-') !== false) {
        $reportar = 'REPORTAR';
      } else {
        $reportar = 'NO REPORTAR';
      }
    }

    $objSheet->setCellValue('A' . $i, $row["ID_CORREGIDO"]);
    $objSheet->setCellValue('B' . $i, $row["IDREPORTEENTREGA_CORREGIDA"]);
    $objSheet->setCellValue('C' . $i, $row["FECREPENTREGA_CORREGIDA"]);
    $objSheet->setCellValue('D' . $i, $row["ID"]);
    $objSheet->setCellValue('E' . $i, $row["IDREPORTEENTREGA"]);
    $objSheet->setCellValue('F' . $i, ' ' . $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('G' . $i, $row["TIPOTEC"]);
    $objSheet->setCellValue('H' . $i, $row["CONTEC"]);
    $objSheet->setCellValue('I' . $i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('J' . $i, $row["NOIDPACIENTE"]);
    $objSheet->setCellValue('K' . $i, $row["NOENTREGA"]);
    $objSheet->setCellValue('L' . $i, $row["ESTADOENTREGA"]);
    $objSheet->setCellValue('M' . $i, $row["CAUSANOENTREGA"]);
    $objSheet->setCellValue('N' . $i, $row["VALORENTREGADO"]);
    $objSheet->setCellValue('O' . $i, $row["CODTECENTREGADO"]);
    $objSheet->setCellValue('P' . $i, $row["CANTTOTENTREGADA"]);
    $objSheet->setCellValue('K' . $i, $row["NOLOTE"]);
    $objSheet->setCellValue('R' . $i, $row["FECENTREGA"]);
    $objSheet->setCellValue('S' . $i, $row["FECREPENTREGA"]);
    $objSheet->setCellValue('T' . $i, $row["ESTREPENTREGA"]);
    $objSheet->setCellValue('U' . $i, $row["FECANULACION"]);
    $objSheet->setCellValue('V' . $i, '------------>');
    $objSheet->setCellValue('W' . $i, $row["CODDXPPAL"]);
    $objSheet->setCellValue('X' . $i, $row["ID_PROVEEDOR"]);
    $objSheet->setCellValue('Y' . $i, $row["NOMBRE_PROVEEDOR"]);
    $objSheet->setCellValue('Z' . $i, $row["COD_SERVICIO_TECNICO"]);
    $objSheet->setCellValue('AA' . $i, $row["DESC_SERVICIO_TECNICO"]);
    $objSheet->setCellValue('AB' . $i, $row["REPO_PERIODO"]);
    $objSheet->setCellValue('AC' . $i, $reportar);
  }
  oci_free_statement($st_tire);

  $objXLS->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("U")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("V")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("W")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("X")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("Y")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("Z")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AA")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);

  /***************************************************************************************/
  /***************************************************************************************/
  /////////////////////////////Hoja 1 Datos Generales(Fin)///////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
}

//echo "<br>query: $query<br>";

 // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objXLS->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
//header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Report Entrega2  " . $regimen . " " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
