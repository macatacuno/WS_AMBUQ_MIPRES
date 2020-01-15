<?php
  $conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');


  //Variable Generales
$json="";
$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

$tipo_id = $_POST['tipo'];
$servicio_id = 1; // Se asigna el codigo del servicio GetReporteEntregaXFecha

//Se calcula el rango de lso periodos
$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias=$diff->days+1;
$periodo_conteo=$periodo_inicial;

if($periodo_final<$periodo_inicial){
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
}else{

  
// Incluir la libreria PHPExcel 
require_once '../../../plugins/PHPExcel/Classes/PHPExcel.php';
// Crea un nuevo objeto PHPExcel
//$objPHPExcel = new PHPExcel();

$objXLS = new PHPExcel();
// Establecer propiedades
$objXLS->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("Documento Excel de Prueba")
->setSubject("Documento Excel de Prueba")
->setDescription("Demostracion sobre como crear archivos de Excel desde
PHP.")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Pruebas de Excel");




////////////////////////////////////////////////////////Hoja 1 (Inicio)

$hoja=0;
$query = "
--Reporte general y de prescripciones
SELECT 
  ID_PRES,
  NOPRESCRIPCION,
  FPRESCRIPCION,
  HPRESCRIPCION,
  CODHABIPS,
  TIPOIDIPS,
  NROIDIPS,
  CODDANEMUNIPS,
  DIRSEDEIPS,
  TELSEDEIPS,
  TIPOIDPROF,
  NUMIDPROF,
  PNPROFS,
  SNPROFS,
  PAPROFS,
  SAPROFS,
  REGPROFS,
  TIPOIDPACIENTE,
  NROIDPACIENTE,
  PNPACIENTE,
  SNPACIENTE,
  PAPACIENTE,
  SAPACIENTE,

  DECODE(PP.CODAMBATE,null,'NO EXISTE',CODAMBATE) AS CODAMBATE,
  (select paas.descripcion from WEBSERV_REF_PRE_AMB_ATE paas where paas.codigo=pp.CODAMBATE ) AS DESC_CODAMBATE,
  
  DECODE(PP.REFAMBATE,null,'NO EXISTE',REFAMBATE) AS REFAMBATE,
  DECODE(REFAMBATE,0,'NO',1,'SI','NO EXISTE') as DESC_REFAMBATE,
  
  DECODE(PP.ENFHUERFANA,null,'NO EXISTE',ENFHUERFANA) AS ENFHUERFANA,
  DECODE(ENFHUERFANA,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANA,
  
  DECODE(CODENFHUERFANA,NULL,'NO EXISTE',CODENFHUERFANA) AS CODENFHUERFANA,
  DECODE(dbms_lob.substr( EH.DESCRIPCION, 4000, 1 ) ,NULL,'NO EXISTE') AS DESC_CODENFHUERFANA,
  
  DECODE(ENFHUERFANADX,NULL,'NO EXISTE',ENFHUERFANADX) AS ENFHUERFANADX,
  DECODE(ENFHUERFANADX,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANADX,
  
  DECODE(CODDXPPAL,NULL,'NO EXISTE',CODDXPPAL) AS CODDXPPAL,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION)) AS DESC_CODDXPPAL, 
  --DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXPPAL), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXPPAL,
  
  DECODE(CODDXREL1,NULL,'NO EXISTE',CODDXREL1) AS CODDXREL1,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION)) AS DESC_CODDXREL1,
  --DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXREL1), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXREL1,
  
  DECODE(CODDXREL2,NULL,'NO EXISTE',CODDXREL2) AS CODDXREL2,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION)) AS DESC_CODDXREL2,
  --DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXREL2), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXREL2,
  
  SOPNUTRICIONAL,
  CODEPS,
  TIPOIDMADREPACIENTE,
  NROIDMADREPACIENTE,
  TIPOTRANSC,--5 Lista(Presc-TipoTransc)
  DECODE(DBMS_LOB.SUBSTR(TT.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(TT.DESCRIPCION)) AS DESC_TIPOTRANSC,
  TIPOIDDONANTEVIVO,
  NROIDDONANTEVIVO,
  ESTPRES
FROM WEBSERV_PRES_PRES pp
LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa
ON paa.CODIGO=pp.CODAMBATE
LEFT JOIN WEBSERV_REF_PRE_ENF_UER EH ON EH.CODIGO=pp.CODENFHUERFANA
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXPPAL ON CIE_CODDXPPAL.CODIGO=PP.CODDXPPAL
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL1 ON CIE_CODDXREL1.CODIGO=PP.CODDXREL1
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL2 ON CIE_CODDXREL2.CODIGO=PP.CODDXREL2
LEFT JOIN WEBSERV_REF_PRE_TI_TR TT ON PP.TIPOTRANSC=TT.CODIGO
where  pp.REPO_SERV_ID=3 and pp.REPO_TISE_ID=2 and pp.REPO_PERIODO='01/01/2020'";

$st_tire = oci_parse($conn_oracle, $query);
oci_execute($st_tire, OCI_DEFAULT);



$objSheet = $objXLS->createSheet();
$objSheet = $objXLS->setActiveSheetIndex($hoja);
$objXLS->getActiveSheet()->setTitle("Base");// AQUI AGREGO EL NOMBRE A LA HOJA
// Agregar Informacion
$objSheet->setCellValue('A1', 'NOPRESCRIPCION');
$objSheet->setCellValue('B1', 'FPRESCRIPCION');
$objSheet->setCellValue('C1', 'HPRESCRIPCION');
$objSheet->setCellValue('D1', 'CODHABIPS');
$objSheet->setCellValue('E1', 'TIPOIDIPS');
$objSheet->setCellValue('F1', 'NROIDIPS');
$objSheet->setCellValue('G1', 'CODDANEMUNIPS');
$objSheet->setCellValue('H1', 'DIRSEDEIPS');
$objSheet->setCellValue('I1', 'TELSEDEIPS');
$objSheet->setCellValue('J1', 'TIPOIDPROF');
$objSheet->setCellValue('K1', 'NUMIDPROF');
$objSheet->setCellValue('L1', 'PNPROFS');
$objSheet->setCellValue('M1', 'SNPROFS');//SNPROFS
$objSheet->setCellValue('N1', 'PAPROFS');//PAPROFS
$objSheet->setCellValue('O1', 'SAPROFS');//SAPROFS
$objSheet->setCellValue('P1', 'REGPROFS');//REGPROFS
$objSheet->setCellValue('Q1', 'TIPOIDPACIENTE');
$objSheet->setCellValue('R1', 'NROIDPACIENTE');
$objSheet->setCellValue('S1', 'PNPACIENTE');
$objSheet->setCellValue('T1', 'SNPACIENTE');
$objSheet->setCellValue('U1', 'PAPACIENTE');
$objSheet->setCellValue('V1', 'SAPACIENTE');
$objSheet->setCellValue('W1', 'CODAMBATE');
$objSheet->setCellValue('X1', 'DESC_CODAMBATE');
$objSheet->setCellValue('Y1', 'REFAMBATE');
$objSheet->setCellValue('Z1', 'DESC_REFAMBATE');


   $i=1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i=$i+1;

    $objSheet->setCellValue('A'.$i, $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('B'.$i, $row["FPRESCRIPCION"]);
    
    $objSheet->setCellValue('W'.$i, $row["CODAMBATE"]);
    $objSheet->setCellValue('X'.$i,$row["DESC_ENFHUERFANA"]);
   /* $objSheet->setCellValue('C'.$i, $row["HPRESCRIPCION"]);
    $objSheet->setCellValue('D'.$i, $row["CODHABIPS"]);
    $objSheet->setCellValue('E'.$i, $row["TIPOIDIPS"]);
    $objSheet->setCellValue('F'.$i, $row["NROIDIPS"]);
    $objSheet->setCellValue('G'.$i, $row["CODDANEMUNIPS"]);
    $objSheet->setCellValue('H'.$i, $row["DIRSEDEIPS"]);
    $objSheet->setCellValue('I'.$i, $row["TELSEDEIPS"]);
    $objSheet->setCellValue('J'.$i, $row["TIPOIDPROF"]);
    $objSheet->setCellValue('K'.$i, $row["NUMIDPROF"]);
    $objSheet->setCellValue('L'.$i, $row["PNPROFS"]);
    $objSheet->setCellValue('M'.$i, $row["SNPROFS"]);
    $objSheet->setCellValue('N'.$i, $row["PAPROFS"]);
    $objSheet->setCellValue('O'.$i, $row["SAPROFS"]);
    $objSheet->setCellValue('P'.$i, $row["REGPROFS"]);
    $objSheet->setCellValue('Q'.$i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('R'.$i, $row["NROIDPACIENTE"]);
    //$objSheet->setCellValue('S'.$i, $row["SNPACIENTE"]);
   // $objSheet->setCellValue('T'.$i, $row["SNPACIENTE"]);
    $objSheet->setCellValue('U'.$i, $row["PAPACIENTE"]);
    $objSheet->setCellValue('V'.$i, $row["SAPACIENTE"]);
    $objSheet->setCellValue('W'.$i, $row["CODAMBATE"]);
    $objSheet->setCellValue('X'.$i,$row["DESC_CODAMBATE"]);
    $objSheet->setCellValue('Y'.$i, $row["REFAMBATE"]);
   // $objSheet->setCellValue('Z'.$i, $row["DESC_REFAMBATE"]);
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

// Renombrar Hoja
//$objPHPExcel->getActiveSheet()->setTitle('Base');

//////////////////////////////////////////////Hoja Base (Fin)

$objXLS->setActiveSheetIndex($hoja);
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
    )
  )
);
$styleArray1 = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
/*
$objXLS->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->applyFromArray($styleArray1);
unset($styleArray);
$objXLS->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
*/


/////////////////////////////////////////////Hoja 1(Fin)


////////////////////////////////////////////////////////Hoja 2 (Inicio)
$hoja=1;
$query = "SELECT 
ID_PRES,
NOPRESCRIPCION,
FPRESCRIPCION,
HPRESCRIPCION,
CODHABIPS,
TIPOIDIPS,
NROIDIPS,
CODDANEMUNIPS,
DIRSEDEIPS,
TELSEDEIPS,
TIPOIDPROF,
NUMIDPROF,
PNPROFS,
SNPROFS,
PAPROFS,
SAPROFS,
REGPROFS,
TIPOIDPACIENTE,
NROIDPACIENTE,
PNPACIENTE,
SNPACIENTE,
PAPACIENTE,
SAPACIENTE,

DECODE(PP.CODAMBATE,null,'NO EXISTE',CODAMBATE) AS CODAMBATE,
PAA.DESCRIPCION AS DESC_CODAMBATE,

DECODE(PP.REFAMBATE,null,'NO EXISTE',REFAMBATE) AS REFAMBATE,
DECODE(REFAMBATE,0,'NO',1,'SI','NO EXISTE') as DESC_REFAMBATE,

DECODE(PP.ENFHUERFANA,null,'NO EXISTE',ENFHUERFANA) AS ENFHUERFANA,
DECODE(ENFHUERFANA,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANA,

DECODE(CODENFHUERFANA,NULL,'NO EXISTE',CODENFHUERFANA) AS CODENFHUERFANA,
DECODE(dbms_lob.substr( EH.DESCRIPCION, 4000, 1 ) ,NULL,'NO EXISTE') AS DESC_CODENFHUERFANA,

DECODE(ENFHUERFANADX,NULL,'NO EXISTE',ENFHUERFANADX) AS ENFHUERFANADX,
DECODE(ENFHUERFANADX,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANADX,

DECODE(CODDXPPAL,NULL,'NO EXISTE',CODDXPPAL) AS CODDXPPAL,--4. Lista(Prec-CIE-10)
DECODE(dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION)) AS DESC_CODDXPPAL, 

DECODE(CODDXREL1,NULL,'NO EXISTE',CODDXREL1) AS CODDXREL1,--4. Lista(Prec-CIE-10)
DECODE(dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION)) AS DESC_CODDXREL1,

DECODE(CODDXREL2,NULL,'NO EXISTE',CODDXREL2) AS CODDXREL2,--4. Lista(Prec-CIE-10)
DECODE(dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION)) AS DESC_CODDXREL2,

SOPNUTRICIONAL,
CODEPS,
TIPOIDMADREPACIENTE,
NROIDMADREPACIENTE,
TIPOTRANSC,--5 Lista(Presc-TipoTransc)
DECODE(DBMS_LOB.SUBSTR(TT.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(TT.DESCRIPCION)) AS DESC_TIPOTRANSC,
TIPOIDDONANTEVIVO,
NROIDDONANTEVIVO,
ESTPRES
FROM WEBSERV_PRES_PRES pp
LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa
ON paa.CODIGO=pp.CODAMBATE
LEFT JOIN WEBSERV_REF_PRE_ENF_UER EH ON EH.CODIGO=pp.CODENFHUERFANA
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXPPAL ON CIE_CODDXPPAL.CODIGO=PP.CODDXPPAL
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL1 ON CIE_CODDXREL1.CODIGO=PP.CODDXREL1
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL2 ON CIE_CODDXREL2.CODIGO=PP.CODDXREL2
LEFT JOIN WEBSERV_REF_PRE_TI_TR TT ON PP.TIPOTRANSC=TT.CODIGO
where  pp.REPO_SERV_ID=3 and pp.REPO_TISE_ID=2 and pp.REPO_PERIODO='01/01/2020'";

$st_tire = oci_parse($conn_oracle, $query);
oci_execute($st_tire, OCI_DEFAULT);




$objSheet = $objXLS->createSheet();
$objSheet = $objXLS->setActiveSheetIndex($hoja);
$objXLS->getActiveSheet()->setTitle("Medicamentos");// AQUI AGREGO EL NOMBRE A LA HOJA
// Agregar Informacion
$objSheet->setCellValue('A1', 'N°');
$objSheet->setCellValue('B1', 'Reporte');


   $i=1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i=$i+1;

    $objSheet->setCellValue('A'.$i, $row["NOPRESCRIPCION"]);
    $objSheet->setCellValue('B'.$i, $row["FPRESCRIPCION"]);
  }
  oci_free_statement($st_tire);

  $objXLS->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);

// Renombrar Hoja
//$objPHPExcel->getActiveSheet()->setTitle('Base');

//////////////////////////////////////////////Hoja Base (Fin)

//$objXLS->setActiveSheetIndex($hoja);
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
    )
  )
);
$styleArray1 = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
/*
$objXLS->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->applyFromArray($styleArray1);
unset($styleArray);
$objXLS->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
*/


/////////////////////////////////////////////Hoja 2(Fin)



}









/*


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Listado Reportes.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel5');
$objWriter->save('php://output');
header("Location:../Administrador/ReportesTecnico.php");
*/



// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objXLS->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Reporte ".$periodo_inicial." - ".$periodo_final.".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS,
'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;




















  /*
//Variable Generales
$json="";
$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

$tipo_id = $_POST['tipo'];
$servicio_id = 1; // Se asigna el codigo del servicio GetReporteEntregaXFecha

//Se calcula el rango de lso periodos
$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias=$diff->days+1;
$periodo_conteo=$periodo_inicial;

if($periodo_final<$periodo_inicial){
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
}else{

  
// Incluir la libreria PHPExcel 
require_once '../../../plugins/PHPExcel/Classes/PHPExcel.php';
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("Documento Excel de Prueba")
->setSubject("Documento Excel de Prueba")
->setDescription("Demostracion sobre como crear archivos de Excel desde
PHP.")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Pruebas de Excel");





///////////////////////////////////Hoja Base (Inicio)
  $query = "SELECT 
  ID_PRES,
  NOPRESCRIPCION,
  FPRESCRIPCION,
  HPRESCRIPCION,
  CODHABIPS,
  TIPOIDIPS,
  NROIDIPS,
  CODDANEMUNIPS,
  DIRSEDEIPS,
  TELSEDEIPS,
  TIPOIDPROF,
  NUMIDPROF,
  PNPROFS,
  SNPROFS,
  PAPROFS,
  SAPROFS,
  REGPROFS,
  TIPOIDPACIENTE,
  NROIDPACIENTE,
  PNPACIENTE,
  SNPACIENTE,
  PAPACIENTE,
  SAPACIENTE,

  DECODE(PP.CODAMBATE,null,'NO EXISTE',CODAMBATE) AS CODAMBATE,
  PAA.DESCRIPCION AS DESC_CODAMBATE,
  
  DECODE(PP.REFAMBATE,null,'NO EXISTE',REFAMBATE) AS REFAMBATE,
  DECODE(REFAMBATE,0,'NO',1,'SI','NO EXISTE') as DESC_REFAMBATE,
  
  DECODE(PP.ENFHUERFANA,null,'NO EXISTE',ENFHUERFANA) AS ENFHUERFANA,
  DECODE(ENFHUERFANA,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANA,
  
  DECODE(CODENFHUERFANA,NULL,'NO EXISTE',CODENFHUERFANA) AS CODENFHUERFANA,
  DECODE(dbms_lob.substr( EH.DESCRIPCION, 4000, 1 ) ,NULL,'NO EXISTE') AS DESC_CODENFHUERFANA,
  
  DECODE(ENFHUERFANADX,NULL,'NO EXISTE',ENFHUERFANADX) AS ENFHUERFANADX,
  DECODE(ENFHUERFANADX,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANADX,
  
  DECODE(CODDXPPAL,NULL,'NO EXISTE',CODDXPPAL) AS CODDXPPAL,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION)) AS DESC_CODDXPPAL, 
  //DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXPPAL), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXPPAL,//
  
  DECODE(CODDXREL1,NULL,'NO EXISTE',CODDXREL1) AS CODDXREL1,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION)) AS DESC_CODDXREL1,
  //DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXREL1), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXREL1,//
  
  DECODE(CODDXREL2,NULL,'NO EXISTE',CODDXREL2) AS CODDXREL2,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION)) AS DESC_CODDXREL2,
  //DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXREL2), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXREL2,//
  
  SOPNUTRICIONAL,
  CODEPS,
  TIPOIDMADREPACIENTE,
  NROIDMADREPACIENTE,
  TIPOTRANSC,--5 Lista(Presc-TipoTransc)
  DECODE(DBMS_LOB.SUBSTR(TT.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(TT.DESCRIPCION)) AS DESC_TIPOTRANSC,
  TIPOIDDONANTEVIVO,
  NROIDDONANTEVIVO,
  ESTPRES
FROM WEBSERV_PRES_PRES pp
LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa
ON paa.CODIGO=pp.CODAMBATE
LEFT JOIN WEBSERV_REF_PRE_ENF_UER EH ON EH.CODIGO=pp.CODENFHUERFANA
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXPPAL ON CIE_CODDXPPAL.CODIGO=PP.CODDXPPAL
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL1 ON CIE_CODDXREL1.CODIGO=PP.CODDXREL1
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL2 ON CIE_CODDXREL2.CODIGO=PP.CODDXREL2
LEFT JOIN WEBSERV_REF_PRE_TI_TR TT ON PP.TIPOTRANSC=TT.CODIGO
where  pp.REPO_SERV_ID=3 and pp.REPO_TISE_ID=2 and pp.REPO_PERIODO='01/01/2020'";

$st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'ID')
->setCellValue('B1', 'IDReporteEntrega')
->setCellValue('C1', 'NoPrescripcion')
->setCellValue('D1', 'TipoTec')
->setCellValue('E1', 'ConTec')
->setCellValue('F1', 'TipoIDPaciente')
->setCellValue('G1', 'NoIDPaciente')
->setCellValue('H1', 'NoEntrega')
->setCellValue('I1', 'EstadoEntrega')
->setCellValue('J1', 'CausaNoEntrega')
->setCellValue('K1', 'ValorEntregado')
->setCellValue('L1', 'CodTecEntregado')
->setCellValue('M1', 'CantTotEntregada')
->setCellValue('N1', 'NoLote')
->setCellValue('O1', 'FecEntrega')
->setCellValue('P1', 'FecRepEntrega')
->setCellValue('Q1', 'EstRepEntrega')
->setCellValue('R1', 'FecAnulacion');

   $i=1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i=$i+1;
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i, $row["NOPRESCRIPCION"])
    ->setCellValue('B'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('C'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('D'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('E'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('F'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('G'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('H'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('I'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('J'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('K'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('L'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('M'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('N'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('O'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('P'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('Q'.$i, $row["FPRESCRIPCION"])
    ->setCellValue('R'.$i, $row["FPRESCRIPCION"]);
  }
  oci_free_statement($st_tire);


// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Base');

//////////////////////////////////////////////Hoja Base (Fin)



// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Reporte ".$periodo_inicial." - ".$periodo_final.".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,
'Excel2007');
$objWriter->save('php://output');
exit;

//echo $json; //Escribir el Json en la vista
}

*/
?>