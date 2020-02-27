<?php

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
set_time_limit(9999999);
ini_set('memory_limit', '-1');

//Variable Generales
$json = "";

$periodo_inicial = $_POST['periodo_inicial'];
$periodo_inicial_oracle=date("d/m/Y", strtotime($periodo_inicial)); //formato originar "y/m/d"

$periodo_final = $_POST['periodo_final'];
$periodo_final_oracle=date("d/m/Y", strtotime($periodo_final)); //formato originar "y/m/d"

$tipo_id = $_POST['tipo'];
$regimen="";
if($tipo_id=="1"){
  $regimen="Cont";
}else 
if($tipo_id=="2"){
  $regimen="Subs";
}
$servicio_id = 9; // Se asigna el codigo del servicio GetReporteEntregaXFecha

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
  JP.ID_JUPR,
  JP.REPO_PERIODO,
  JP.REPO_SERV_ID,
  JP.REPO_TIRE_ID,
  JP.NOPRESCRIPCION,
  JP.FPRESCRIPCION,
  DECODE(JP.TIPOTECNOLOGIA,NULL,'NO EXISTE',JP.TIPOTECNOLOGIA)TIPOTECNOLOGIA,------
  DECODE(TT.DESCRIPCION,NULL,'NO EXISTE',TT.DESCRIPCION)DESC_TIPOTECNOLOGIA,
  DECODE(JP.CONSECUTIVO,NULL,'NO EXISTE',JP.CONSECUTIVO)CONSECUTIVO,
  DECODE(JP.ESTJM,NULL,'NO EXISTE',JP.ESTJM)ESTJM,------
  DECODE(EJ.DESCRIPCION,NULL,'NO EXISTE',EJ.DESCRIPCION)DESC_ESTJM,
  DECODE(JP.CODENTPROC,NULL,'NO EXISTE',JP.CODENTPROC)CODENTPROC,
  DECODE(JP.OBSERVACIONES,NULL,'NO EXISTE',JP.OBSERVACIONES)OBSERVACIONES,
  DECODE(JP.JUSTIFICACIONTECNICA,NULL,'NO EXISTE',JP.JUSTIFICACIONTECNICA)JUSTIFICACIONTECNICA,
  DECODE(JP.MODALIDAD,NULL,'NO EXISTE',JP.MODALIDAD)MODALIDAD,--------
  DECODE(MO.DESCRIPCION,NULL,'NO EXISTE',MO.DESCRIPCION)DESC_MODALIDAD,
  DECODE(JP.NOACTA,NULL,'NO EXISTE',JP.NOACTA)NOACTA,
  DECODE(JP.FECHAACTA,NULL,'NO EXISTE',JP.FECHAACTA)FECHAACTA,
  DECODE(JP.FPROCESO,NULL,'NO EXISTE',JP.FPROCESO)FPROCESO,
  DECODE(JP.TIPOIDPACIENTE,NULL,'NO EXISTE',JP.TIPOIDPACIENTE)TIPOIDPACIENTE,-------
  DECODE (TA.DESCRIPCION,NULL,'NO EXISTE',TA.DESCRIPCION)DESC_TIPOIDPACIENTE,
  DECODE(JP.NROIDPACIENTE,NULL,'NO EXISTE',JP.NROIDPACIENTE)NROIDPACIENTE,
  DECODE (UB.NOM_MPIO,NULL,'NO EXISTE',UB.NOM_MPIO) MUNICIPIO,
  DECODE(UB.NOM_DPTO,NULL,'NO EXISTE',UB.NOM_DPTO)DEPARTAMENTO,
  DECODE(JP.CODENTJM,NULL,'NO EXISTE',JP.CODENTJM)CODENTJM
FROM WEBSERV_JUNTA_PROFESIONAL JP 
LEFT JOIN WEBSERV_REF_JP_TI_TE  TT ON JP.TIPOTECNOLOGIA=TT.CODIGO
LEFT JOIN WEBSERV_REF_PRE_ES_JP EJ ON JP.ESTJM=EJ.CODIGO
LEFT JOIN WEBSERV_REF_JP_MODAL  MO ON JP.MODALIDAD=MO.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TD_AA TA ON JP.TIPOIDPACIENTE=TA.CODIGO 
LEFT JOIN (SELECT B.ESTADO,B.TIDPODOCUMENTO,B.DOCUMENTO, B.DEPARTAMENTO, B.MUNICIPIO,B.NOM_MPIO,B.NOM_DPTO,B.MES 
           FROM ZZZ_BDUAHISSUB@PDBLCSTBY01 B 
           WHERE B.MES IN (SELECT MAX(MES) 
                           FROM ZZZ_BDUAHISSUB@PDBLCSTBY01)) UB ON UB.TIDPODOCUMENTO=jp.TIPOIDPACIENTE AND UB.DOCUMENTO=jp.NROIDPACIENTE
where  JP.REPO_SERV_ID=".$servicio_id." and JP.REPO_TIRE_ID=".$tipo_id." and JP.REPO_PERIODO BETWEEN '".$periodo_inicial_oracle."' AND '".$periodo_final_oracle."'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("Junta de Profesionales"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion
  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'REPO_PERIODO');
  $objSheet->setCellValue('C1', 'FPRESCRIPCION');
  $objSheet->setCellValue('D1', 'TIPOTECNOLOGIA');
  $objSheet->setCellValue('E1', 'DESC_TIPOTECNOLOGIA');
  $objSheet->setCellValue('F1', 'CONSECUTIVO');
  $objSheet->setCellValue('G1', 'ESTJM');
  $objSheet->setCellValue('H1', 'DESC_ESTJM');
  $objSheet->setCellValue('I1', 'CODENTPROC');
  $objSheet->setCellValue('J1', 'OBSERVACIONES');
  $objSheet->setCellValue('K1', 'JUSTIFICACIONTECNICA');
  $objSheet->setCellValue('L1', 'MODALIDAD');
  $objSheet->setCellValue('M1', 'DESC_MODALIDAD');
  $objSheet->setCellValue('N1', 'NOACTA');
  $objSheet->setCellValue('O1', 'FECHAACTA');
  $objSheet->setCellValue('P1', 'FPROCESO');
  $objSheet->setCellValue('Q1', 'TIPOIDPACIENTE'); 
  $objSheet->setCellValue('R1', 'DESC_TIPOIDPACIENTE'); 
  $objSheet->setCellValue('S1', 'NROIDPACIENTE'); 
  $objSheet->setCellValue('T1', 'MUNICIPIO'); 
  $objSheet->setCellValue('U1', 'DEPARTAMENTO'); 
  $objSheet->setCellValue('V1', 'CODENTJM'); 

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $objSheet->setCellValue('A' . $i, '="'.$row["NOPRESCRIPCION"].'"');
    $objSheet->setCellValue('B' . $i, $row["REPO_PERIODO"]);
    $objSheet->setCellValue('C' . $i, $row["FPRESCRIPCION"]);
    $objSheet->setCellValue('D' . $i, $row["TIPOTECNOLOGIA"]);

    $DESC_TIPOTECNOLOGIA= utf8_encode($row["DESC_TIPOTECNOLOGIA"]);
    $objSheet->setCellValue('E' . $i,$DESC_TIPOTECNOLOGIA );

    $objSheet->setCellValue('F' . $i, $row["CONSECUTIVO"]);

    $objSheet->setCellValue('G' . $i, $row["ESTJM"]);
    $DESC_ESTJM= utf8_encode($row["DESC_ESTJM"]);
    $objSheet->setCellValue('H' . $i, $DESC_ESTJM);

    $objSheet->setCellValue('I' . $i, '="'.$row["CODENTPROC"].'"');
    $objSheet->setCellValue('J' . $i, $row["OBSERVACIONES"]);

    $JUSTIFICACIONTECNICA= utf8_encode($row["JUSTIFICACIONTECNICA"]);
    $objSheet->setCellValue('K' . $i, $JUSTIFICACIONTECNICA);

    $objSheet->setCellValue('L' . $i, $row["MODALIDAD"]);
    $DESC_MODALIDAD= utf8_encode($row["DESC_MODALIDAD"]);
    $objSheet->setCellValue('M' . $i, $DESC_MODALIDAD);

    $objSheet->setCellValue('N' . $i, '="'.$row["NOACTA"].'"');
    $objSheet->setCellValue('O' . $i, $row["FECHAACTA"]);
    $objSheet->setCellValue('P' . $i, $row["FPROCESO"]);

    $objSheet->setCellValue('Q' . $i, $row["TIPOIDPACIENTE"]);
    $DESC_TIPOIDPACIENTE= utf8_encode($row["DESC_TIPOIDPACIENTE"]);
    $objSheet->setCellValue('R' . $i, $DESC_TIPOIDPACIENTE);

    $objSheet->setCellValue('S' . $i, '="'.$row["NROIDPACIENTE"].'"');
    $objSheet->setCellValue('T' . $i, $row["MUNICIPIO"]);
    $objSheet->setCellValue('U' . $i, $row["DEPARTAMENTO"]);
    $objSheet->setCellValue('V' . $i, '="'.$row["CODENTJM"].'"');

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

/***************************************************************************************/
/***************************************************************************************/
  /////////////////////////////Hoja 1 Datos Generales(Fin)///////////////////////////////
/***************************************************************************************/
/***************************************************************************************/
}






// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objXLS->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
//header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Reporte ".$regimen." ". $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
