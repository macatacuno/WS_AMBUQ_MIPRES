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
  $query = "SELECT 
    DECODE(RE.REPO_SERV_ID,NULL,'NO EXISTE',RE.REPO_SERV_ID) REPO_SERV_ID,
  DECODE(RE.REPO_TIRE_ID,NULL,'NO EXISTE',RE.REPO_TIRE_ID)REPO_TIRE_ID,
  DECODE(D.REPO_TIRE_ID,NULL,'NO EXISTE',D.REPO_TIRE_ID)DIREC_TIRE_ID,
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
  DECODE(RE.ESTADOENTREGA,null,'NO EXISTE',RE.ESTADOENTREGA)ESTADOENTREGA,
  DECODE(RE.CAUSANOENTREGA,null,'NO EXISTE',RE.CAUSANOENTREGA)CAUSANOENTREGA,
  DECODE(RE.VALORENTREGADO,null,'NO EXISTE',RE.VALORENTREGADO)VALORENTREGADO,
  DECODE(RE.CODTECENTREGADO,null,'NO EXISTE',RE.CODTECENTREGADO)CODTECENTREGADO,
  DECODE(CODTEC_REP.CODIGO,NULL,'NO EXISTE',CODTEC_REP.CODIGO) CODSERTECAENTREGAR_VALI,
  DECODE(CODTEC_REP.DESCRIPCION,NULL,'NO EXISTE',CODTEC_REP.DESCRIPCION) D_CODSERTECAENTREGAR_VALI,
  DECODE(RE.NOENTREGA,null,'NO EXISTE',RE.NOENTREGA)NOENTREGA,
  DECODE(DURACION_EN_MESES,NULL,'NO EXISTE',DURACION_EN_MESES) TOTAL_ENTREGAS,
  DECODE(CANTIDAD_TRATAMIENTO,NULL,'NO EXISTE',CANTIDAD_TRATAMIENTO||' '||DESC_DURACION_TRATAMIENTO) DURACION_TRATAMIENTO,
  CASE 
  WHEN RE.NOENTREGA<DURACION_EN_MESES THEN '0'
  WHEN RE.NOENTREGA=DURACION_EN_MESES THEN '1'
  WHEN RE.NOENTREGA>DURACION_EN_MESES THEN 'ERROR'
  ELSE 'ERROR' END ULTENTREGA,
  DECODE(RE.CANTTOTENTREGADA,null,'NO EXISTE',RE.CANTTOTENTREGADA)CANTTOTENTREGADA,
  DECODE(ENTREGA_POR_MES_ESTIMADA,NULL,'NO EXISTE',ENTREGA_POR_MES_ESTIMADA)ENTREGA_POR_MES_ESTIMADA,
  CASE 
  WHEN RE.CANTTOTENTREGADA<ENTREGA_POR_MES_ESTIMADA THEN '0'
  WHEN RE.CANTTOTENTREGADA=ENTREGA_POR_MES_ESTIMADA THEN '1'
  WHEN RE.CANTTOTENTREGADA>ENTREGA_POR_MES_ESTIMADA THEN 'ERROR'
  ELSE 'ERROR' END ENTREGACOMPLETA,
  DECODE(PR.CANTIDAD_TOTAL_FORMULADA,null,'NO EXISTE',PR.CANTIDAD_TOTAL_FORMULADA)CANTIDAD_TOTAL_FORMULADA,
  DECODE(RE.NOLOTE,null,'NO EXISTE',RE.NOLOTE)NOLOTE,
  DECODE(to_char(FECENTREGA,'DD/MM/YYYY'), NULL,'NO EXISTE',to_char(FECENTREGA,'DD/MM/YYYY')) FECENTREGA,
  DECODE(RE.FECREPENTREGA,null,'NO EXISTE',RE.FECREPENTREGA)FECREPENTREGA,
  DECODE(to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECREPENTREGA,'DD/MM/YYYY HH24:MI:SS')) FECREPENTREGA,
  DECODE(RE.ESTREPENTREGA,null,'NO EXISTE',RE.ESTREPENTREGA)ESTREPENTREGA,
  DECODE(to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS'), NULL,'NO EXISTE',to_char(RE.FECANULACION,'DD/MM/YYYY HH24:MI:SS')) FECANULACION,
  DECODE(PP.CODDXPPAL,null,'NO EXISTE',PP.CODDXPPAL)CODDXPPAL,
  DECODE(D.NOIDPROV,NULL,'NO EXISTE',D.NOIDPROV) AS ID_PROVEEDOR,
  DECODE(PROV.NOMBRE,NULL,'NO EXISTE',PROV.NOMBRE) AS NOMBRE_PROVEEDOR,
  DECODE(PR.CODSERTECAENTREGAR,null,'NO EXISTE',PR.CODSERTECAENTREGAR)COD_SERVICIO_TECNICO,
  DECODE(PR.DESC_CODSERTECAENTREGAR,null,'NO EXISTE',PR.DESC_CODSERTECAENTREGAR)DESC_SERVICIO_TECNICO
FROM WEBSERV_REPORTE_ENTREGA re
LEFT JOIN WEBSERV_DIRECCIONAMIENTOS D ON D.ID=re.ID AND D.REPO_TIRE_ID=re.REPO_TIRE_ID
LEFT JOIN WEBSERV_PRES_LIST_PROV PROV ON PROV.NOIDPROV=D.NOIDPROV
LEFT JOIN WEBSERV_PRES_PRES PP 
  ON  TRIM(PP.NOPRESCRIPCION) =TRIM(RE.NOPRESCRIPCION)
LEFT JOIN  view_webserv_pres_info_direc PR 
  ON TRIM(PR.NOPRESCRIPCION) =TRIM(RE.NOPRESCRIPCION)
     and PR.TIPOTEC=RE.TIPOTEC 
     and PR.conorden=RE.CONTEC
LEFT JOIN  VIEW_WEBSERV_PRES_CODI_TEC CODTEC_REP
ON CODTEC_REP.TIPOTEC=re.TIPOTEC and CODTEC_REP.CODIGO=re.CODTECENTREGADO
where  RE.REPO_SERV_ID=" . $servicio_id . " and RE.REPO_TIRE_ID=" . $tipo_id . " and RE.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("Reporte de entrega "); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion
  
  $objSheet->setCellValue('A1', 'ID');
  $objSheet->setCellValue('B1', 'IDREPORTEENTREGA');
  $objSheet->setCellValue('C1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('D1', 'TIPOTEC');
  $objSheet->setCellValue('E1', 'CONTEC');
  $objSheet->setCellValue('F1', 'TIPOIDPACIENTE');
  $objSheet->setCellValue('G1', 'NOIDPACIENTE');
  $objSheet->setCellValue('H1', 'ESTADOENTREGA');
  $objSheet->setCellValue('I1', 'CAUSANOENTREGA');
  $objSheet->setCellValue('J1', 'VALORENTREGADO');
  $objSheet->setCellValue('K1', 'CODTECENTREGADO');
  $objSheet->setCellValue('L1', 'CODSERTECAENTREGAR_VALI');
  $objSheet->setCellValue('M1', 'DESC_CODSERTECAENTREGAR_VALI');
  $objSheet->setCellValue('N1', 'NOENTREGA');
  $objSheet->setCellValue('O1', 'TOTAL_ENTREGAS');
  $objSheet->setCellValue('P1', 'DURACION_TRATAMIENTO');
  $objSheet->setCellValue('Q1', 'ULTENTREGA');
  $objSheet->setCellValue('R1', 'CANTTOTENTREGADA');
  $objSheet->setCellValue('S1', 'ENTREGA_POR_MES_ESTIMADA');
  $objSheet->setCellValue('T1', 'ENTREGACOMPLETA');
  $objSheet->setCellValue('U1', 'CANTIDAD_TOTAL_FORMULADA');
  $objSheet->setCellValue('V1', 'NOLOTE');
  $objSheet->setCellValue('W1', 'FECENTREGA');
  $objSheet->setCellValue('X1', 'FECREPENTREGA');
  $objSheet->setCellValue('Y1', 'ESTREPENTREGA');
  $objSheet->setCellValue('Z1', 'FECANULACION');
  $objSheet->setCellValue('AA1', '---DATOS TECNOLOGIA ENTREGADA-->');
  $objSheet->setCellValue('AB1', 'CODDXPPAL');
  $objSheet->setCellValue('AC1', 'ID_PROVEEDOR');
  $objSheet->setCellValue('AD1', 'NOMBRE_PROVEEDOR');
  $objSheet->setCellValue('AE1', 'COD_SERVICIO_TECNICO');
  $objSheet->setCellValue('AF1', 'DESC_SERVICIO_TECNICO');
  $objSheet->setCellValue('AG1', 'PERIODO_CONSULTADO');
  //$objSheet->setCellValue('AH1', 'ESTADO_CODTECENTREGADO');

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

    $objSheet->setCellValue('A' . $i, $row["ID"]);
    $objSheet->setCellValue('B' . $i, $row["IDREPORTEENTREGA"]);
    $objSheet->setCellValue('C' . $i, ' ' . $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('D' . $i, $row["TIPOTEC"]);
    $objSheet->setCellValue('E' . $i, $row["CONTEC"]);
    $objSheet->setCellValue('F' . $i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('G' . $i, $row["NOIDPACIENTE"]);
    $objSheet->setCellValue('H' . $i, $row["ESTADOENTREGA"]);
    $objSheet->setCellValue('I' . $i, $row["CAUSANOENTREGA"]);
    $objSheet->setCellValue('J' . $i, $row["VALORENTREGADO"]);
    $objSheet->setCellValue('K' . $i, $row["CODTECENTREGADO"]);
    $objSheet->setCellValue('L' . $i, $row["CODSERTECAENTREGAR_VALI"]);
    $D_CODSERTECAENTREGAR_VALI = utf8_encode($row["D_CODSERTECAENTREGAR_VALI"]);
    $objSheet->setCellValue('M' . $i, $D_CODSERTECAENTREGAR_VALI);
    $objSheet->setCellValue('N' . $i, $row["NOENTREGA"]);
    $objSheet->setCellValue('O' . $i, $row["TOTAL_ENTREGAS"]);
    $DURACION_TRATAMIENTO = utf8_encode($row["DURACION_TRATAMIENTO"]);
    $objSheet->setCellValue('P' . $i, $DURACION_TRATAMIENTO);
    $objSheet->setCellValue('Q' . $i, $row["ULTENTREGA"]);
    $objSheet->setCellValue('R' . $i, $row["CANTTOTENTREGADA"]);
    $objSheet->setCellValue('S' . $i, $row["ENTREGA_POR_MES_ESTIMADA"]);
    $objSheet->setCellValue('T' . $i, $row["ENTREGACOMPLETA"]);
    $objSheet->setCellValue('U' . $i, $row["CANTIDAD_TOTAL_FORMULADA"]);
    $objSheet->setCellValue('V' . $i, $row["NOLOTE"]);
    $objSheet->setCellValue('W' . $i, $row["FECENTREGA"]);
    $objSheet->setCellValue('X' . $i, $row["FECREPENTREGA"]);
    $objSheet->setCellValue('Y' . $i, $row["ESTREPENTREGA"]);
    $objSheet->setCellValue('Z' . $i, $row["FECANULACION"]);
    $objSheet->setCellValue('AA' . $i, '------------>');
    $objSheet->setCellValue('AB' . $i, $row["CODDXPPAL"]);
    $objSheet->setCellValue('AC' . $i, $row["ID_PROVEEDOR"]);
    $objSheet->setCellValue('AD' . $i, $row["NOMBRE_PROVEEDOR"]);
    $objSheet->setCellValue('AE' . $i, $row["COD_SERVICIO_TECNICO"]);
    $DESC_SERVICIO_TECNICO = utf8_encode($row["DESC_SERVICIO_TECNICO"]);
    $objSheet->setCellValue('AF' . $i, $DESC_SERVICIO_TECNICO);
    $objSheet->setCellValue('AG' . $i, $row["REPO_PERIODO"]);
    //$objSheet->setCellValue('AH' . $i, $reportar);
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
header("Content-Disposition: attachment;filename=Report Entrega2  " . $regimen . " " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
