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
  SELECT /*D.IDDIRECCIONAMIENTO,count(re.IDREPORTEENTREGA) cantidad*/
  /*----Direccionamientos----Direccionamientos----Direccionamientos----Direccionamientos---*/
  '---Direccionamiento-------' DIRECCIONAMIENTOS,
  /*DECODE(D.REPO_SERV_ID,NULL,'NO EXISTE',D.REPO_SERV_ID) REPO_SERV_ID,*/
  DECODE(D.REPO_TIRE_ID,1,'CONTRIBUTIVO',2,'SUBSIDIADO',NULL,'NO EXISTE',D.REPO_TIRE_ID)regimen,
  /*DECODE(to_char(D.REPO_PERIODO,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(D.REPO_PERIODO,'DD/MM/YYYY')) REPO_PERIODO,*/
  DECODE(D.ID,NULL,'NO EXISTE',D.ID) AS DIR_ID,
  DECODE(D.IDDIRECCIONAMIENTO,NULL,'NO EXISTE',D.IDDIRECCIONAMIENTO) AS DIR_IDDIRECCIONAMIENTO,
  DECODE(D.NOPRESCRIPCION,NULL,'NO EXISTE',D.NOPRESCRIPCION) AS NOPRESCRIPCION,
  DECODE(D.TIPOTEC,NULL,'NO EXISTE',D.TIPOTEC) AS TIPOTEC,
  DECODE(D.CONTEC,NULL,'NO EXISTE',D.CONTEC) AS CONTEC,
  DECODE(D.TIPOIDPACIENTE,NULL,'NO EXISTE',D.TIPOIDPACIENTE) AS TIPOIDPACIENTE,
  DECODE(D.NOIDPACIENTE,NULL,'NO EXISTE',D.NOIDPACIENTE) AS NOIDPACIENTE,
  DECODE(D.NOENTREGA,NULL,'NO EXISTE',D.NOENTREGA) AS NOENTREGA,
  DECODE(D.NOSUBENTREGA,NULL,'NO EXISTE',D.NOSUBENTREGA) AS NOSUBENTREGA,
  DECODE(D.TIPOIDPROV,NULL,'NO EXISTE',D.TIPOIDPROV) AS TIPOIDPROV,
  DECODE(D.NOIDPROV,NULL,'NO EXISTE',D.NOIDPROV) AS NOIDPROV,
  DECODE(PROV.NOMBRE,NULL,'NO EXISTE',PROV.NOMBRE) AS NOMBRE_PROVEEDOR,
  DECODE(D.CODMUNENT,NULL,'NO EXISTE',D.CODMUNENT) AS CODMUNENT,
  DECODE(D.FECMAXENT,NULL,'NO EXISTE',D.FECMAXENT) AS FECMAXENT,
  DECODE(D.CANTTOTAENTREGAR,NULL,'NO EXISTE',D.CANTTOTAENTREGAR) AS CANTTOTAENTREGAR,
  DECODE(D.DIRPACIENTE,NULL,'NO EXISTE',D.DIRPACIENTE) AS DIRPACIENTE,
  DECODE(D.CODSERTECAENTREGAR,NULL,'NO EXISTE',D.CODSERTECAENTREGAR) AS CODSERTECAENTREGAR,
  DECODE(CODTEC_DIR.CODIGO,null,'NO EXISTE',CODTEC_DIR.CODIGO)CODSERTECAENTREGAR_VALIDADO,
  DECODE(CODTEC_DIR.DESCRIPCION,null,'NO EXISTE',CODTEC_DIR.DESCRIPCION)D_CODSERTECAENTREGAR_VALIDADO,
  DECODE(D.NOIDEPS,null,'NO EXISTE',D.NOIDEPS)NOIDEPS,
  DECODE(D.CODEPS,null,'NO EXISTE',D.CODEPS)CODEPS,
  DECODE(D.FECDIRECCIONAMIENTO,null,'NO EXISTE',D.FECDIRECCIONAMIENTO)FECDIRECCIONAMIENTO,
  DECODE(D.ESTDIRECCIONAMIENTO,null,'NO EXISTE',D.ESTDIRECCIONAMIENTO)ESTDIRECCIONAMIENTO,
  DECODE(D.FECANULACION,null,'NO EXISTE',D.FECANULACION)FECANULACION,
  
  /*----------------------REPORTE DE ENTREGA------REPORTE DE ENTREGA------REPORTE DE ENTREGA------REPORTE DE ENTREGA---- */
  '---Reportes de entrega-------' REPORTE_DE_ENTREGA,
  /*--DECODE(RE.REPO_SERV_ID,NULL,'NO EXISTE',RE.REPO_SERV_ID) RE_REPO_SERV_ID,*/
  DECODE(RE.REPO_TIRE_ID,1,'CONTRIBUTIVO',2,'SUBSIDIADO',NULL,'NO EXISTE',RE.REPO_TIRE_ID)regimen,
  /*--DECODE(to_char(RE.REPO_PERIODO,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(RE.REPO_PERIODO,'DD/MM/YYYY')) RE_REPO_PERIODO,*/
  DECODE(RE.ID,NULL,'NO EXISTE',RE.ID)RE_ID,
  DECODE(RE.IDREPORTEENTREGA,null,'NO EXISTE',RE.IDREPORTEENTREGA)RE_IDREPORTEENTREGA,
  DECODE(RE.NOPRESCRIPCION,null,'NO EXISTE',RE.NOPRESCRIPCION)RE_NOPRESCRIPCION,
  decode(RE.TIPOTEC,
    'P','PROCEDIMIENTO',
    'M','MEDICAMENTO',
    'N','PRODUCTOS NUTRICIONALES',
    'S','SERVICIOS COMPEMENTARIOS',
    'D','DISPOSITIVOS MEDICOS',null,'NO EXISTE',RE.TIPOTEC)RE_TIPOTEC,
  DECODE(RE.CONTEC,null,'NO EXISTE',RE.CONTEC)RE_CONTEC,
  DECODE(RE.TIPOIDPACIENTE,null,'NO EXISTE',RE.TIPOIDPACIENTE)RE_TIPOIDPACIENTE,
  DECODE(RE.NOIDPACIENTE,null,'NO EXISTE',RE.NOIDPACIENTE)RE_NOIDPACIENTE,
  DECODE(RE.NOENTREGA,null,'NO EXISTE',RE.NOENTREGA)RE_NOENTREGA,
  DECODE(RE.ESTADOENTREGA,null,'NO EXISTE',RE.ESTADOENTREGA)RE_ESTADOENTREGA,
  DECODE(RE.CAUSANOENTREGA,null,'NO EXISTE',RE.CAUSANOENTREGA)RE_CAUSANOENTREGA,
  DECODE(RE.VALORENTREGADO,null,'NO EXISTE',RE.VALORENTREGADO)RE_VALORENTREGADO,
  
  DECODE(RE.CODTECENTREGADO,null,'NO EXISTE',RE.CODTECENTREGADO)RE_CODTECENTREGADO,
  DECODE(CODTEC_REP.CODIGO,null,'NO EXISTE',CODTEC_REP.CODIGO)RE_CODTECENTREGADO_VALIDADO,
  DECODE(CODTEC_REP.DESCRIPCION,null,'NO EXISTE',CODTEC_REP.DESCRIPCION)RE_D_CODTECENTREGADO_VALIDADO,
  
  DECODE(RE.CANTTOTENTREGADA,null,'NO EXISTE',RE.CANTTOTENTREGADA)RE_CANTTOTENTREGADA,
  DECODE(RE.NOLOTE,null,'NO EXISTE',RE.NOLOTE)RE_NOLOTE,
  DECODE(to_char(re.FECENTREGA,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(re.FECENTREGA,'DD/MM/YYYY')) RE_FECENTREGA,
  DECODE(to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) RE_FECREPENTREGA,
  DECODE(RE.ESTREPENTREGA,null,'NO EXISTE',RE.ESTREPENTREGA)RE_ESTREPENTREGA,
  DECODE(to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS')) RE_FECANULACION/*,
  DECODE(to_char(REE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), 
         NULL,'NO EXISTE',to_char(REE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) FECREPENTREGA_NUEVA,
  DECODE(REE.ID,NULL,'NO EXISTE',REE.ID)ID_NUEVA,
  DECODE(REE.IDREPORTEENTREGA,null,'NO EXISTE',REE.IDREPORTEENTREGA)IDREPORTEENTREGA_NUEVA*/
  /*--------Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----Tablas----*/
  FROM WEBSERV_DIRECCIONAMIENTOS D 
  LEFT JOIN WEBSERV_PRES_LIST_PROV PROV ON PROV.NOIDPROV=D.NOIDPROV
  LEFT JOIN  VIEW_WEBSERV_PRES_CODI_TEC CODTEC_DIR 
  ON CODTEC_DIR.TIPOTEC=D.TIPOTEC and CODTEC_DIR.CODIGO=D.CODSERTECAENTREGAR
  LEFT JOIN WEBSERV_REPORTE_ENTREGA re ON D.ID=re.ID AND D.REPO_TIRE_ID=re.REPO_TIRE_ID
  LEFT JOIN  VIEW_WEBSERV_PRES_CODI_TEC CODTEC_REP
  ON CODTEC_REP.TIPOTEC=re.TIPOTEC and CODTEC_REP.CODIGO=re.CODTECENTREGADO
where  D.REPO_SERV_ID=" . $servicio_id . " and D.REPO_TIRE_ID=" . $tipo_id . " and D.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle
    . "'  order by D.FECDIRECCIONAMIENTO,re.NOPRESCRIPCION,re.FECENTREGA,RE.FECREPENTREGA/*,REE.FECREPENTREGA*/";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("Direccionamientos"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'DIRECCIONAMIENTOS');
  $objSheet->setCellValue('B1', 'DIR_ID');
  $objSheet->setCellValue('C1', 'DIR_IDDIRECCIONAMIENTO');
  $objSheet->setCellValue('D1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('E1', 'TIPOTEC');
  $objSheet->setCellValue('F1', 'CONTEC');
  $objSheet->setCellValue('G1', 'TIPOIDPACIENTE');
  $objSheet->setCellValue('H1', 'NOIDPACIENTE');
  $objSheet->setCellValue('I1', 'NOENTREGA');
  $objSheet->setCellValue('J1', 'NOSUBENTREGA');
  $objSheet->setCellValue('K1', 'TIPOIDPROV');
  $objSheet->setCellValue('L1', 'NOIDPROV');
  $objSheet->setCellValue('M1', 'NOMBRE_PROVEEDOR');
  $objSheet->setCellValue('N1', 'CODMUNENT');
  $objSheet->setCellValue('O1', 'FECMAXENT');
  $objSheet->setCellValue('P1', 'CANTTOTAENTREGAR');
  $objSheet->setCellValue('Q1', 'DIRPACIENTE');
  $objSheet->setCellValue('R1', 'CODSERTECAENTREGAR');
  $objSheet->setCellValue('S1', 'CODSERTECAENTREGAR_VALIDADO');
  $objSheet->setCellValue('T1', 'D_CODSERTECAENTREGAR_VALIDADO');
  $objSheet->setCellValue('U1', 'NOIDEPS');
  $objSheet->setCellValue('V1', 'CODEPS');
  $objSheet->setCellValue('W1', 'FECDIRECCIONAMIENTO');
  $objSheet->setCellValue('X1', 'ESTDIRECCIONAMIENTO');
  $objSheet->setCellValue('Y1', 'FECANULACION');

  $objSheet->setCellValue('Z1', 'REPORTE_DE_ENTREGA');
  $objSheet->setCellValue('AA1', 'RE_ID');
  $objSheet->setCellValue('AB1', 'RE_IDREPORTEENTREGA');
  $objSheet->setCellValue('AC1', 'RE_NOPRESCRIPCION');
  $objSheet->setCellValue('AD1', 'RE_TIPOTEC');
  $objSheet->setCellValue('AE1', 'RE_CONTEC');
  $objSheet->setCellValue('AF1', 'RE_TIPOIDPACIENTE');
  $objSheet->setCellValue('AG1', 'RE_NOIDPACIENTE');
  $objSheet->setCellValue('AH1', 'RE_NOENTREGA');
  $objSheet->setCellValue('AI1', 'RE_ESTADOENTREGA');
  $objSheet->setCellValue('AJ1', 'RE_CAUSANOENTREGA');
  $objSheet->setCellValue('AK1', 'RE_VALORENTREGADO');
  $objSheet->setCellValue('AL1', 'RE_CODTECENTREGADO');
  $objSheet->setCellValue('AM1', 'RE_CODTECENTREGADO_VALIDADO');
  $objSheet->setCellValue('AN1', 'RE_D_CODTECENTREGADO_VALIDADO');
  $objSheet->setCellValue('AO1', 'RE_CANTTOTENTREGADA');
  $objSheet->setCellValue('AP1', 'RE_NOLOTE');
  $objSheet->setCellValue('AQ1', 'RE_FECENTREGA');
  $objSheet->setCellValue('AR1', 'RE_FECREPENTREGA');
  $objSheet->setCellValue('AS1', 'RE_ESTREPENTREGA');
  $objSheet->setCellValue('AT1', 'RE_FECANULACION');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $objSheet->setCellValue('A' . $i, "----DIRECCIONAMIENTOS---");
    $objSheet->setCellValue('B' . $i, $row["DIR_ID"]);
    $objSheet->setCellValue('C' . $i, $row["DIR_IDDIRECCIONAMIENTO"]);
    $objSheet->setCellValue('D' . $i, ' ' . $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('E' . $i, $row["TIPOTEC"]);
    $objSheet->setCellValue('F' . $i, $row["CONTEC"]);
    $objSheet->setCellValue('G' . $i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('H' . $i, $row["NOIDPACIENTE"]);
    $objSheet->setCellValue('Y' . $i, $row["NOENTREGA"]);
    $objSheet->setCellValue('J' . $i, $row["NOSUBENTREGA"]);
    $objSheet->setCellValue('K' . $i, $row["TIPOIDPROV"]);
    $objSheet->setCellValue('L' . $i, $row["NOIDPROV"]);
    $objSheet->setCellValue('M' . $i, $row["NOMBRE_PROVEEDOR"]);
    $objSheet->setCellValue('N' . $i, $row["CODMUNENT"]);
    $objSheet->setCellValue('O' . $i, $row["FECMAXENT"]);
    $objSheet->setCellValue('P' . $i, $row["CANTTOTAENTREGAR"]);
    $DIRPACIENTE = utf8_encode($row["DIRPACIENTE"]);
    $objSheet->setCellValue('Q' . $i, $DIRPACIENTE);
    $CODSERTECAENTREGAR = utf8_encode($row["CODSERTECAENTREGAR"]);
    $objSheet->setCellValue('R' . $i, $CODSERTECAENTREGAR);
    $CODSERTECAENTREGAR_VALIDADO = utf8_encode($row["CODSERTECAENTREGAR_VALIDADO"]);
    $objSheet->setCellValue('S' . $i, $CODSERTECAENTREGAR_VALIDADO);
    $D_CODSERTECAENTREGAR_VALIDADO = utf8_encode($row["D_CODSERTECAENTREGAR_VALIDADO"]);
    $objSheet->setCellValue('T' . $i, $D_CODSERTECAENTREGAR_VALIDADO);
    $objSheet->setCellValue('U' . $i, $row["NOIDEPS"]);
    $objSheet->setCellValue('V' . $i, $row["CODEPS"]);
    $objSheet->setCellValue('W' . $i, $row["FECDIRECCIONAMIENTO"]);
    $objSheet->setCellValue('X' . $i, $row["ESTDIRECCIONAMIENTO"]);
    $objSheet->setCellValue('Y' . $i, $row["FECANULACION"]);

    $objSheet->setCellValue('Z1' . $i, '-----REPORTE_DE_ENTREGA-----');
    $objSheet->setCellValue('AA' . $i, $row["RE_ID"]);
    $objSheet->setCellValue('AB' . $i, $row["RE_IDREPORTEENTREGA"]);
    $objSheet->setCellValue('AC' . $i, ' ' . $row["RE_NOPRESCRIPCION"]);
    $objSheet->setCellValue('AD' . $i, $row["RE_TIPOTEC"]);
    $objSheet->setCellValue('AE' . $i, $row["RE_CONTEC"]);
    $objSheet->setCellValue('AF' . $i, $row["RE_TIPOIDPACIENTE"]);
    $objSheet->setCellValue('AG' . $i, $row["RE_NOIDPACIENTE"]);
    $objSheet->setCellValue('AH' . $i, $row["RE_NOENTREGA"]);
    $objSheet->setCellValue('AI' . $i, $row["RE_ESTADOENTREGA"]);
    $objSheet->setCellValue('AJ' . $i, $row["RE_CAUSANOENTREGA"]);
    $objSheet->setCellValue('AK' . $i, $row["RE_VALORENTREGADO"]);
    $objSheet->setCellValue('AL' . $i, $row["RE_CODTECENTREGADO"]);
    $objSheet->setCellValue('AM' . $i, $row["RE_CODTECENTREGADO_VALIDADO"]);
    $RE_D_CODTECENTREGADO_VALIDADO = utf8_encode($row["RE_D_CODTECENTREGADO_VALIDADO"]);
    $objSheet->setCellValue('AN' . $i, $RE_D_CODTECENTREGADO_VALIDADO);
    $objSheet->setCellValue('AO' . $i, $row["RE_CANTTOTENTREGADA"]);
    $objSheet->setCellValue('AP' . $i, $row["RE_NOLOTE"]);
    $objSheet->setCellValue('AQ' . $i, $row["RE_FECENTREGA"]);
    $objSheet->setCellValue('AR' . $i, $row["RE_FECREPENTREGA"]);
    $objSheet->setCellValue('AS' . $i, $row["RE_ESTREPENTREGA"]);
    $objSheet->setCellValue('AT' . $i, $row["RE_FECANULACION"]);
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
  $objXLS->getActiveSheet()->getColumnDimension("U")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("V")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("W")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("X")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("Y")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("Z")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AA")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AD")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AE")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AF")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AG")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AH")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AI")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AJ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AK")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AL")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AM")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AN")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AO")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AP")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AQ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AR")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AS")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AT")->setAutoSize(true);

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
header("Content-Disposition: attachment;filename=direc " . $regimen . " " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
