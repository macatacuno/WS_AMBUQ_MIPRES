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
$servicio_id = 13; // Se asigna el codigo del servicio GetDirecionamiento

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
ID,
IDDIRECCIONAMIENTO,
NOPRESCRIPCION,
DECODE(TIPOTEC, NULL,'NO EXISTE',TIPOTEC) TIPOTEC,
DECODE(CONTEC, NULL,'NO EXISTE',CONTEC) CONTEC,
DECODE(TIPOIDPACIENTE, NULL,'NO EXISTE',TIPOIDPACIENTE) TIPOIDPACIENTE,
DECODE(NOIDPACIENTE, NULL,'NO EXISTE',NOIDPACIENTE) NOIDPACIENTE,
DECODE(NOENTREGA, NULL,'NO EXISTE',NOENTREGA) NOENTREGA,
DECODE(NOSUBENTREGA, NULL,'NO EXISTE',NOSUBENTREGA) NOSUBENTREGA,
DECODE(TIPOIDPROV, NULL,'NO EXISTE',TIPOIDPROV) TIPOIDPROV,
DECODE(NOIDPROV, NULL,'NO EXISTE',NOIDPROV) NOIDPROV,
DECODE(CODMUNENT, NULL,'NO EXISTE',CODMUNENT) CODMUNENT,
DECODE(to_char(FECMAXENT,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(FECMAXENT,'DD/MM/YYYY')) FECMAXENT,
DECODE(CANTTOTAENTREGAR, NULL,'NO EXISTE',CANTTOTAENTREGAR) CANTTOTAENTREGAR,
DECODE(DIRPACIENTE, NULL,'NO EXISTE',DIRPACIENTE) DIRPACIENTE,
DECODE(CODSERTECAENTREGAR, NULL,'NO EXISTE',CODSERTECAENTREGAR) CODSERTECAENTREGAR,
DECODE(NOIDEPS, NULL,'NO EXISTE',NOIDEPS) NOIDEPS,
DECODE(CODEPS, NULL,'NO EXISTE',CODEPS) CODEPS,
DECODE(to_char(FECDIRECCIONAMIENTO,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(FECDIRECCIONAMIENTO,'DD/MM/YYYY HH24:MI:SS')) FECDIRECCIONAMIENTO,
DECODE(ESTDIRECCIONAMIENTO, NULL,'NO EXISTE',ESTDIRECCIONAMIENTO) ESTDIRECCIONAMIENTO,
DECODE(to_char(FECANULACION,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(FECANULACION,'DD/MM/YYYY HH24:MI:SS')) FECANULACION,
to_char(REPO_PERIODO,'DD/MM/YYYY') REPO_PERIODO
FROM WEBSERV_DIRECCIONAMIENTOS 
where  REPO_SERV_ID=" . $servicio_id . " and REPO_TIRE_ID=" . $tipo_id . " and REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("Direccionamientos"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'ID');
  $objSheet->setCellValue('B1', 'IDDIRECCIONAMIENTO');
  $objSheet->setCellValue('C1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('D1', 'TIPOTEC');
  $objSheet->setCellValue('E1', 'CONTEC');
  $objSheet->setCellValue('F1', 'TIPOIDPACIENTE');
  $objSheet->setCellValue('G1', 'NOIDPACIENTE');
  $objSheet->setCellValue('H1', 'NOENTREGA');
  $objSheet->setCellValue('I1', 'NOSUBENTREGA');
  $objSheet->setCellValue('J1', 'TIPOIDPROV');
  $objSheet->setCellValue('K1', 'NOIDPROV');
  $objSheet->setCellValue('L1', 'CODMUNENT');
  $objSheet->setCellValue('M1', 'FECMAXENT');
  $objSheet->setCellValue('N1', 'CANTTOTAENTREGAR');
  $objSheet->setCellValue('O1', 'DIRPACIENTE');
  $objSheet->setCellValue('P1', 'CODSERTECAENTREGAR');
  $objSheet->setCellValue('Q1', 'NOIDEPS');
  $objSheet->setCellValue('R1', 'CODEPS');
  $objSheet->setCellValue('S1', 'FECDIRECCIONAMIENTO');
  $objSheet->setCellValue('T1', 'ESTDIRECCIONAMIENTO');
  $objSheet->setCellValue('U1', 'FECANULACION');
  $objSheet->setCellValue('V1', 'PERIODO_CONSULTADO');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $objSheet->setCellValue('A' . $i, $row["ID"]);
    $objSheet->setCellValue('B' . $i, $row["IDDIRECCIONAMIENTO"]);
    $objSheet->setCellValue('C' . $i, ' ' . $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('D' . $i, $row["TIPOTEC"]);
    $objSheet->setCellValue('E' . $i, $row["CONTEC"]);
    $objSheet->setCellValue('F' . $i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('G' . $i, $row["NOIDPACIENTE"]);
    $objSheet->setCellValue('H' . $i, $row["NOENTREGA"]);
    $objSheet->setCellValue('I' . $i, $row["NOSUBENTREGA"]);
    $objSheet->setCellValue('J' . $i, $row["TIPOIDPROV"]);
    $objSheet->setCellValue('K' . $i, $row["NOIDPROV"]);
    $objSheet->setCellValue('L' . $i, $row["CODMUNENT"]);
    $objSheet->setCellValue('M' . $i, $row["FECMAXENT"]);
    $objSheet->setCellValue('N' . $i, $row["CANTTOTAENTREGAR"]);
    $DIRPACIENTE = utf8_encode($row["DIRPACIENTE"]);
    $objSheet->setCellValue('O' . $i, $DIRPACIENTE);
    $CODSERTECAENTREGAR = utf8_encode($row["CODSERTECAENTREGAR"]);
    $objSheet->setCellValue('P' . $i, $CODSERTECAENTREGAR);
    $objSheet->setCellValue('Q' . $i, $row["NOIDEPS"]);
    $objSheet->setCellValue('R' . $i, $row["CODEPS"]);
    $objSheet->setCellValue('S' . $i, $row["FECDIRECCIONAMIENTO"]);
    $objSheet->setCellValue('T' . $i, $row["ESTDIRECCIONAMIENTO"]);
    $objSheet->setCellValue('U' . $i, $row["FECANULACION"]);
    $objSheet->setCellValue('V' . $i, $row["REPO_PERIODO"]);
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
header("Content-Disposition: attachment;filename=direc " . $regimen . " " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
