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
$servicio_id = 14; // Se asigna el codigo del servicio GetSuministros

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
  $query = "
  SELECT 
  REPO_PERIODO,
  ID,
  IDSUMINISTRO,
  NOPRESCRIPCION,
  decode(TIPOTEC,'P','PROCEDIMIENTO',
                 'M','MEDICAMENTO',
                 'N','PRODUCTOS NUTRICIONALES',
                 'S','SERVICIOS COMPEMENTARIOS',
                 'D','DISPOSITIVOS MEDICOS',
                 null,'NO EXISTE',TIPOTEC)TIPOTEC,
  CONTEC,
  TIPOIDPACIENTE,
  NOIDPACIENTE,
  DECODE(NOENTREGA,null,'NO EXISTE',NOENTREGA)NOENTREGA,
  DECODE(ULTENTREGA,NULL,'NO EXISTE',ULTENTREGA)ULTENTREGA,
  DECODE(ENTREGACOMPLETA,NULL,'NO EXISTE',ENTREGACOMPLETA)ENTREGACOMPLETA,
  DECODE(CAUSANOENTREGA,NULL,'NO EXISTE',CAUSANOENTREGA)CAUSANOENTREGA,
  DECODE(NOPRESCRIPCIONASOCIADA,NULL,'NO EXISTE',NOPRESCRIPCIONASOCIADA)NOPRESCRIPCIONASOCIADA,
  DECODE(CONTECASOCIADA,NULL,'NO EXISTE',CONTECASOCIADA)CONTECASOCIADA,
  DECODE(CANTTOTENTREGADA,NULL,'NO EXISTE',CANTTOTENTREGADA)CANTTOTENTREGADA,
  DECODE(NOLOTE,NULL,'NO EXISTE',NOLOTE)NOLOTE,
  DECODE(VALORENTREGADO,NULL,'NO EXISTE',VALORENTREGADO)VALORENTREGADO,
  DECODE(FECSUMINISTRO,NULL,'NO EXISTE',FECSUMINISTRO)FECSUMINISTRO,
  DECODE(ESTSUMINISTRO,NULL,'NO EXISTE',ESTSUMINISTRO)ESTSUMINISTRO,
  DECODE(FECANULACION,NULL,'NO EXISTE',FECANULACION)FECANULACION
FROM WEBSERV_SUMINISTROS 
where  REPO_PERIODO BETWEEN '$periodo_inicial_oracle' AND '$periodo_final_oracle'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("Suministros"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion


  /*
  REPO_PERIODO,
  ID,
  IDSUMINISTRO,
  NOPRESCRIPCION,
  TIPOTEC,
  CONTEC,
  TIPOIDPACIENTE,
  NOIDPACIENTE,
  NOENTREGA,
  ULTENTREGA,
  ENTREGACOMPLETA,
  CAUSANOENTREGA,
  NOPRESCRIPCIONASOCIADA,
  CONTECASOCIADA,
  CANTTOTENTREGADA,
  NOLOTE,
  VALORENTREGADO,
  FECSUMINISTRO,
  ESTSUMINISTRO,
  FECANULACION
  */
  $objSheet->setCellValue('A1', 'ID');
  $objSheet->setCellValue('B1', 'IDSUMINISTRO');
  $objSheet->setCellValue('C1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('D1', 'TIPOTEC');
  $objSheet->setCellValue('E1', 'CONTEC');
  $objSheet->setCellValue('F1', 'TIPOIDPACIENTE');
  $objSheet->setCellValue('G1', 'NOIDPACIENTE');
  $objSheet->setCellValue('H1', 'NOENTREGA');
  $objSheet->setCellValue('I1', 'ULTENTREGA');
  $objSheet->setCellValue('J1', 'ENTREGACOMPLETA');
  $objSheet->setCellValue('K1', 'CAUSANOENTREGA');
  $objSheet->setCellValue('L1', 'NOPRESCRIPCIONASOCIADA');
  $objSheet->setCellValue('M1', 'CONTECASOCIADA');
  $objSheet->setCellValue('N1', 'CANTTOTENTREGADA');
  $objSheet->setCellValue('O1', 'NOLOTE');
  $objSheet->setCellValue('P1', 'VALORENTREGADO');
  $objSheet->setCellValue('Q1', 'FECSUMINISTRO');
  $objSheet->setCellValue('R1', 'ESTSUMINISTRO');
  $objSheet->setCellValue('S1', 'FECANULACION');
  $objSheet->setCellValue('T1', 'PERIODO_CONSULTADO');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $objSheet->setCellValue('A' . $i, $row["ID"]);
    $objSheet->setCellValue('B' . $i, $row["IDSUMINISTRO"]);
    $objSheet->setCellValue('C' . $i, ' ' . $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('D' . $i, $row["TIPOTEC"]);
    $objSheet->setCellValue('E' . $i, $row["CONTEC"]);
    $objSheet->setCellValue('F' . $i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('G' . $i, $row["NOIDPACIENTE"]);
    $objSheet->setCellValue('H' . $i, $row["NOENTREGA"]);
    $objSheet->setCellValue('I' . $i, $row["ULTENTREGA"]);
    $objSheet->setCellValue('J' . $i, $row["ENTREGACOMPLETA"]);
    $objSheet->setCellValue('K' . $i, $row["CAUSANOENTREGA"]);
    $objSheet->setCellValue('L' . $i, ' ' . $row["NOPRESCRIPCIONASOCIADA"]);
    $objSheet->setCellValue('M' . $i, $row["CONTECASOCIADA"]);
    $objSheet->setCellValue('N' . $i, $row["CANTTOTENTREGADA"]);
    $objSheet->setCellValue('O' . $i, $row["NOLOTE"]);
    $objSheet->setCellValue('P' . $i, $row["VALORENTREGADO"]);
    $objSheet->setCellValue('Q' . $i, $row["FECSUMINISTRO"]);
    $objSheet->setCellValue('R' . $i, $row["ESTSUMINISTRO"]);
    $objSheet->setCellValue('S' . $i, $row["FECANULACION"]);
    $objSheet->setCellValue('T' . $i, $row["REPO_PERIODO"]);
    /*
    $DESC_TIPOTRANSC = utf8_encode($row["DESC_TIPOTRANSC"]);
    $objSheet->setCellValue('AU' . $i, $DESC_TIPOTRANSC);
*/
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

  /***************************************************************************************/
  /***************************************************************************************/
  /////////////////////////////Hoja 1 Datos Generales(Fin)///////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
}

// echo "<br>query: $query<br>";
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objXLS->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
//header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Suministros " . $regimen . " " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
