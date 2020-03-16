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
$servicio_id = 3; // Se asigna el codigo del servicio GetReporteEntregaXFecha

//Se calcula el rango de lso periodos
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
  ID_PRES,
  NOPRESCRIPCION,
  PP.REPO_PERIODO AS PERIODO_WEBSERVICE,
  to_char(FPRESCRIPCION,'DD/MM/YYYY') FPRESCRIPCION,
  DECODE(HPRESCRIPCION, NULL,'NO EXISTE',HPRESCRIPCION) HPRESCRIPCION,
  DECODE(CODHABIPS, NULL,'NO EXISTE',CODHABIPS) CODHABIPS,
  DECODE(TIPOIDIPS, NULL,'NO EXISTE',TIPOIDIPS) TIPOIDIPS,
  DECODE(NROIDIPS, NULL,'NO EXISTE',NROIDIPS) NROIDIPS,
  DECODE(CODDANEMUNIPS, NULL,'NO EXISTE',CODDANEMUNIPS) CODDANEMUNIPS,
  DECODE(DIRSEDEIPS, NULL,'NO EXISTE',DIRSEDEIPS) DIRSEDEIPS,
  DECODE(TELSEDEIPS, NULL,'NO EXISTE',TELSEDEIPS) TELSEDEIPS,
  DECODE(TIPOIDPROF, NULL,'NO EXISTE',TIPOIDPROF) TIPOIDPROF,
  DECODE(NUMIDPROF, NULL,'NO EXISTE',NUMIDPROF) NUMIDPROF,
  DECODE(PNPROFS, NULL,'NO EXISTE',PNPROFS) PNPROFS,
  DECODE(SNPROFS, NULL,'NO EXISTE',SNPROFS) SNPROFS,
  DECODE(PAPROFS, NULL,'NO EXISTE',PAPROFS) PAPROFS,
  DECODE(SAPROFS, NULL,'NO EXISTE',SAPROFS) SAPROFS,
  DECODE(REGPROFS, NULL,'NO EXISTE',REGPROFS) REGPROFS,
  DECODE(TIPOIDPACIENTE, NULL,'NO EXISTE',TIPOIDPACIENTE) TIPOIDPACIENTE,
  DECODE(NROIDPACIENTE, NULL,'NO EXISTE',NROIDPACIENTE) NROIDPACIENTE,
  DECODE(PNPACIENTE, NULL,'.',PNPACIENTE) PNPACIENTE,
  DECODE(SNPACIENTE, NULL,'.',SNPACIENTE)SNPACIENTE,
  DECODE(PAPACIENTE, NULL,'.',PAPACIENTE) PAPACIENTE,
  DECODE(SAPACIENTE, NULL,'.',SAPACIENTE) SAPACIENTE,
  
  DECODE (UB.NOM_MPIO,NULL,DECODE(gl.GeographicLocationName,NULL,'NO EXISTE',gl.GeographicLocationName),UB.NOM_MPIO) MUNICIPIO,
  DECODE(UB.NOM_DPTO,NULL,'NO EXISTE',UB.NOM_DPTO)DEPARTAMENTO,
  
  DECODE(PP.CODAMBATE,null,'NO EXISTE',CODAMBATE) AS CODAMBATE,
  DECODE(PAA.DESCRIPCION,NULL,'NO EXISTE',PAA.DESCRIPCION) AS DESC_CODAMBATE,
  
  DECODE(PP.REFAMBATE,null,'NO EXISTE',REFAMBATE) AS REFAMBATE,
  DECODE(REFAMBATE,0,'NO',1,'SI','NO EXISTE') as DESC_REFAMBATE,
  
  DECODE(PP.ENFHUERFANA,null,'NO EXISTE',ENFHUERFANA) AS ENFHUERFANA,
  DECODE(ENFHUERFANA,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANA,
  
  DECODE(CODENFHUERFANA,NULL,'NO EXISTE',CODENFHUERFANA) AS CODENFHUERFANA,
  DECODE( EH.DESCRIPCION,NULL,'NO EXISTE',EH.DESCRIPCION) AS DESC_CODENFHUERFANA,
  
  DECODE(ENFHUERFANADX,NULL,'NO EXISTE',ENFHUERFANADX) AS ENFHUERFANADX,
  DECODE(ENFHUERFANADX,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANADX,
  
  DECODE(CODDXPPAL,NULL,'NO EXISTE',CODDXPPAL) AS CODDXPPAL,--4. Lista(Prec-CIE-10)
  DECODE(CIE_CODDXPPAL.DESCRIPCION,NULL,'NO EXISTE',CIE_CODDXPPAL.DESCRIPCION) AS DESC_CODDXPPAL, 
  
  DECODE(CODDXREL1,NULL,'NO EXISTE',CODDXREL1) AS CODDXREL1,--4. Lista(Prec-CIE-10)
  DECODE(CIE_CODDXREL1.DESCRIPCION,NULL,'NO EXISTE',CIE_CODDXREL1.DESCRIPCION) AS DESC_CODDXREL1,
  
  DECODE(CODDXREL2,NULL,'NO EXISTE',CODDXREL2) AS CODDXREL2,--4. Lista(Prec-CIE-10)
  DECODE(CIE_CODDXREL2.DESCRIPCION,NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION)) AS DESC_CODDXREL2,
  
  DECODE(SOPNUTRICIONAL, NULL,'NO EXISTE',SOPNUTRICIONAL) SOPNUTRICIONAL,
  DECODE(CODEPS, NULL,'NO EXISTE',CODEPS) CODEPS,
  DECODE(PE.DESCRIPCION,NULL,'NO EXISTE',PE.DESCRIPCION) DESC_CODEPS,
  DECODE(TIPOIDMADREPACIENTE, NULL,'NO EXISTE',TIPOIDMADREPACIENTE) TIPOIDMADREPACIENTE,
  DECODE(NROIDMADREPACIENTE, NULL,'NO EXISTE',NROIDMADREPACIENTE) NROIDMADREPACIENTE,
  
  DECODE(TIPOTRANSC, NULL,'NO EXISTE',TIPOTRANSC) TIPOTRANSC,--5 Lista(Presc-TipoTransc)
  DECODE(TT.DESCRIPCION,NULL,'NO EXISTE',TT.DESCRIPCION) AS DESC_TIPOTRANSC,
  
  DECODE(TIPOIDDONANTEVIVO, NULL,'NO EXISTE',TIPOIDDONANTEVIVO) TIPOIDDONANTEVIVO,
  DECODE(NROIDDONANTEVIVO, NULL,'NO EXISTE',NROIDDONANTEVIVO) NROIDDONANTEVIVO,
  DECODE(ESTPRES, NULL,'NO EXISTE',ESTPRES) ESTPRES
  
  FROM WEBSERV_PRES_PRES pp
  LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa ON paa.CODIGO=pp.CODAMBATE
  LEFT JOIN WEBSERV_REF_PRE_ENF_UER EH ON EH.CODIGO=pp.CODENFHUERFANA
  LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXPPAL ON CIE_CODDXPPAL.CODIGO=PP.CODDXPPAL
  LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL1 ON CIE_CODDXREL1.CODIGO=PP.CODDXREL1
  LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL2 ON CIE_CODDXREL2.CODIGO=PP.CODDXREL2
  LEFT JOIN WEBSERV_REF_PRE_TI_TR TT ON PP.TIPOTRANSC=TT.CODIGO
  LEFT JOIN WEBSERV_REF_PRE_EPS PE ON PP.CODEPS=PE.CODIGO
  LEFT JOIN (SELECT B.ESTADO,B.TIDPODOCUMENTO,B.DOCUMENTO, B.DEPARTAMENTO, B.MUNICIPIO,B.NOM_MPIO,B.NOM_DPTO,B.MES 
             FROM ZZZ_BDUAHISSUB@PDBLCSTBY01 B 
             WHERE B.MES IN (SELECT MAX(MES) 
                             FROM ZZZ_BDUAHISSUB@PDBLCSTBY01)) UB ON UB.TIDPODOCUMENTO=PP.TIPOIDPACIENTE AND UB.DOCUMENTO=PP.NROIDPACIENTE
  LEFT JOIN Client C ON C.ClientId = NROIDPACIENTE and C.TYPEDOCUMENTID=TIPOIDPACIENTE
  LEFT join GeographicLocation gl  on c.CompanyId = gl.CompanyId and c.GeographicLocationId = gl.GeographicLocationId
where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";

  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);



  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("PRESC GENERAL"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion
  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'PERIODO_WEBSERVICE');
  $objSheet->setCellValue('C1', 'FPRESCRIPCION');
  $objSheet->setCellValue('D1', 'HPRESCRIPCION');
  $objSheet->setCellValue('E1', 'CODHABIPS');
  $objSheet->setCellValue('F1', 'TIPOIDIPS');
  $objSheet->setCellValue('G1', 'NROIDIPS');
  $objSheet->setCellValue('H1', 'CODDANEMUNIPS');
  $objSheet->setCellValue('I1', 'DIRSEDEIPS');
  $objSheet->setCellValue('J1', 'TELSEDEIPS');
  $objSheet->setCellValue('K1', 'TIPOIDPROF');
  $objSheet->setCellValue('L1', 'NUMIDPROF');
  $objSheet->setCellValue('M1', 'PNPROFS');
  $objSheet->setCellValue('N1', 'SNPROFS'); //SNPROFS
  $objSheet->setCellValue('O1', 'PAPROFS'); //PAPROFS
  $objSheet->setCellValue('P1', 'SAPROFS'); //SAPROFS
  $objSheet->setCellValue('Q1', 'REGPROFS'); //REGPROFS
  $objSheet->setCellValue('R1', 'TIPOIDPACIENTE');
  $objSheet->setCellValue('S1', 'NROIDPACIENTE');
  $objSheet->setCellValue('T1', 'PNPACIENTE');
  $objSheet->setCellValue('U1', 'SNPACIENTE');
  $objSheet->setCellValue('V1', 'PAPACIENTE');
  $objSheet->setCellValue('W1', 'SAPACIENTE');
  $objSheet->setCellValue('X1', 'MUNICIPIO');
  $objSheet->setCellValue('Y1', 'DEPARTAMENTO');

  //$objSheet->setCellValue('Z1', 'CODAMBATE');
  $objSheet->setCellValue('Z1', 'DESC_CODAMBATE');
  ///$objSheet->setCellValue('AB1', 'REFAMBATE');
  $objSheet->setCellValue('AA1', 'DESC_REFAMBATE');

  //$objSheet->setCellValue('AD1', 'ENFHUERFANA');
  $objSheet->setCellValue('AB1', 'DESC_ENFHUERFANA');
  //$objSheet->setCellValue('AF1', 'CODENFHUERFANA');
  $objSheet->setCellValue('AC1', 'DESC_CODENFHUERFANA');
  // $objSheet->setCellValue('AH1', 'ENFHUERFANADX');
  $objSheet->setCellValue('AD1', 'DESC_ENFHUERFANADX');
  // $objSheet->setCellValue('AJ1', 'CODDXPPAL');
  $objSheet->setCellValue('AE1', 'DESC_CODDXPPAL');
  // $objSheet->setCellValue('AL1', 'CODDXREL1');
  $objSheet->setCellValue('AF1', 'DESC_CODDXREL1');
  //$objSheet->setCellValue('AN1', 'CODDXREL2');
  $objSheet->setCellValue('AG1', 'DESC_CODDXREL2');
  $objSheet->setCellValue('AH1', 'SOPNUTRICIONAL'); //
  //  $objSheet->setCellValue('AQ1', 'CODEPS'); //
  $objSheet->setCellValue('AI1', 'DESC_CODEPS'); //
  $objSheet->setCellValue('AJ1', 'TIPOIDMADREPACIENTE'); //
  $objSheet->setCellValue('AK1', 'NROIDMADREPACIENTE'); //
  // $objSheet->setCellValue('AU1', 'TIPOTRANSC');
  $objSheet->setCellValue('AL1', 'DESC_TIPOTRANSC');
  $objSheet->setCellValue('AM1', 'TIPOIDDONANTEVIVO');
  $objSheet->setCellValue('AN1', 'NROIDDONANTEVIVO');
  $objSheet->setCellValue('AO1', 'ESTPRES');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;

    $objSheet->setCellValue('A' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('B' . $i, $row["PERIODO_WEBSERVICE"]);
    $objSheet->setCellValue('C' . $i, $row["FPRESCRIPCION"]);
    $objSheet->setCellValue('D' . $i, $row["HPRESCRIPCION"]);
    $objSheet->setCellValue('E' . $i, '="' . $row["CODHABIPS"] . '"');
    $objSheet->setCellValue('F' . $i, $row["TIPOIDIPS"]);
    $objSheet->setCellValue('G' . $i, $row["NROIDIPS"]);
    $objSheet->setCellValue('H' . $i, '="' . $row["CODDANEMUNIPS"] . '"');
    $objSheet->setCellValue('I' . $i, $row["DIRSEDEIPS"]);
    $objSheet->setCellValue('J' . $i, $row["TELSEDEIPS"]);
    $objSheet->setCellValue('K' . $i, $row["TIPOIDPROF"]);
    $objSheet->setCellValue('L' . $i, $row["NUMIDPROF"]);
    $objSheet->setCellValue('M' . $i, $row["PNPROFS"]);
    $objSheet->setCellValue('N' . $i, $row["SNPROFS"]);
    $objSheet->setCellValue('O' . $i, $row["PAPROFS"]);
    $objSheet->setCellValue('P' . $i, $row["SAPROFS"]);
    $objSheet->setCellValue('Q' . $i, $row["REGPROFS"]);
    $objSheet->setCellValue('R' . $i, $row["TIPOIDPACIENTE"]);
    $objSheet->setCellValue('S' . $i, $row["NROIDPACIENTE"]);
    $objSheet->setCellValue('T' . $i, $row["PNPACIENTE"]);
    $objSheet->setCellValue('U' . $i, $row["SNPACIENTE"]);
    $objSheet->setCellValue('V' . $i, $row["PAPACIENTE"]);
    $objSheet->setCellValue('W' . $i, $row["SAPACIENTE"]);
    $objSheet->setCellValue('X' . $i, $row["MUNICIPIO"]);
    $objSheet->setCellValue('Y' . $i, $row["DEPARTAMENTO"]);

    // $objSheet->setCellValue('Z' . $i, $row["CODAMBATE"]);
    $DESC_CODAMBATE = utf8_encode($row["DESC_CODAMBATE"]);
    $objSheet->setCellValue('Z' . $i, $DESC_CODAMBATE);
    // $objSheet->setCellValue('AB' . $i, $row["REFAMBATE"]);
    $DESC_REFAMBATE = utf8_encode($row["DESC_REFAMBATE"]);
    $objSheet->setCellValue('AA' . $i, $DESC_REFAMBATE);

    //$objSheet->setCellValue('AD' . $i, $row["ENFHUERFANA"]);
    $DESC_ENFHUERFANA = utf8_encode($row["DESC_ENFHUERFANA"]);
    $objSheet->setCellValue('AB' . $i, $DESC_ENFHUERFANA);

    //$objSheet->setCellValue('AF' . $i, $row["CODENFHUERFANA"]);
    $DESC_CODENFHUERFANA = utf8_encode($row["DESC_CODENFHUERFANA"]);
    $objSheet->setCellValue('AC' . $i, $DESC_CODENFHUERFANA);

    //$objSheet->setCellValue('AH' . $i, $row["ENFHUERFANADX"]);
    $DESC_ENFHUERFANADX = utf8_encode($row["DESC_ENFHUERFANADX"]);
    $objSheet->setCellValue('AD' . $i, $DESC_ENFHUERFANADX);

    //$objSheet->setCellValue('AJ' . $i, $row["CODDXPPAL"]);
    $DESC_CODDXPPAL = utf8_encode($row["DESC_CODDXPPAL"]);
    $objSheet->setCellValue('AE' . $i, $DESC_CODDXPPAL);

    //$objSheet->setCellValue('AL' . $i, $row["CODDXREL1"]);
    $DESC_CODDXREL1 = utf8_encode($row["DESC_CODDXREL1"]);
    $objSheet->setCellValue('AF' . $i, $DESC_CODDXREL1);

    //$objSheet->setCellValue('AN' . $i, $row["CODDXREL2"]);
    $DESC_CODDXREL2 = utf8_encode($row["DESC_CODDXREL2"]);
    $objSheet->setCellValue('AG' . $i, $DESC_CODDXREL2);

    $objSheet->setCellValue('AH' . $i, $row["SOPNUTRICIONAL"]);

    //$objSheet->setCellValue('AQ' . $i, $row["CODEPS"]);
    $DESC_CODEPS = utf8_encode($row["DESC_CODEPS"]);
    $objSheet->setCellValue('AI' . $i, $DESC_CODEPS);

    $objSheet->setCellValue('AJ' . $i, $row["TIPOIDMADREPACIENTE"]);
    $objSheet->setCellValue('AK' . $i, $row["NROIDMADREPACIENTE"]);

    //$objSheet->setCellValue('AU' . $i, $row["TIPOTRANSC"]);
    $DESC_TIPOTRANSC = utf8_encode($row["DESC_TIPOTRANSC"]);
    $objSheet->setCellValue('AL' . $i, $DESC_TIPOTRANSC);

    $objSheet->setCellValue('AM' . $i, $row["TIPOIDDONANTEVIVO"]);
    $objSheet->setCellValue('AN' . $i, $row["NROIDDONANTEVIVO"]);
    $objSheet->setCellValue('AO' . $i, $row["ESTPRES"]);
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
  $objXLS->getActiveSheet()->getColumnDimension("AO")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("AP")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AQ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AR")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AS")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AT")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AU")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AV")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AW")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AX")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AY")->setAutoSize(true);*/

  // Renombrar Hoja
  //$objPHPExcel->getActiveSheet()->setTitle('Base');

  //////////////////////////////////////////////Hoja Base (Fin)
  // $objXLS->getActiveSheet()->setCellValueExplicitByColumnAndRow("D", 0, "CODHABIPS", PHPExcel_Cell_DataType::TYPE_STRING);


  /*
$objXLS->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->applyFromArray($styleArray1);
unset($styleArray);
$objXLS->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objXLS->getActiveSheet()->getStyle('A2:G'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
*/

  /***************************************************************************************/
  /***************************************************************************************/
  /////////////////////////////Hoja 1 Datos Generales(Fin)///////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/

  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 2 Medicamentos(Inicio)////////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 1;
  $query = " 
  SELECT 
  ID_MEDI,
  PP.NOPRESCRIPCION,
  DECODE(CONORDEN, NULL,'NO EXISTE',CONORDEN) CONORDEN,
  
  DECODE(TIPOMED,NULL,'NO EXISTE',TIPOMED) AS TIPOMED,  --6.Lista(Medi-TipoMed)
  DECODE(TM.DESCRIPCION,NULL,'NO EXISTE',DBMS_LOB.SUBSTR(TM.DESCRIPCION)) AS DESC_TIPOMED,
  
  DECODE(TIPOPREST,NULL,'NO EXISTE',TIPOPREST) AS TIPOPREST,--7.Lista(Medi-TipoPrest)
  DECODE(DBMS_LOB.SUBSTR( TP.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR( TP.DESCRIPCION)) AS DESC_TIPOPREST,
  
  DECODE(CAUSAS1, NULL,'NO EXISTE',CAUSAS1) CAUSAS1,  --2. Lista(Si-NO)
  DECODE(CAUSAS1,0,'NO',1,'SI','NO EXISTE') AS DESC_CAUSAS1,
  
  DECODE(CAUSAS2, NULL,'NO EXISTE',CAUSAS2) CAUSAS2,--2. Lista(Si-NO)
  DECODE(CAUSAS2,0,'NO',1,'SI','NO EXISTE') AS DESC_CAUSAS2,
  
  DECODE(CAUSAS3, NULL,'NO EXISTE',CAUSAS3) CAUSAS3,--2. Lista(Si-NO)
  DECODE(CAUSAS3,0,'NO',1,'SI','NO EXISTE') AS DESC_CAUSAS3,
  
  DECODE(MEDPBSUTILIZADO, NULL,'NO EXISTE',MEDPBSUTILIZADO) MEDPBSUTILIZADO,
  
  DECODE(RZNCAUSAS31, NULL,'NO EXISTE',RZNCAUSAS31) RZNCAUSAS31,
  DECODE(RZNCAUSAS31,0,'NO',1,'SI','NO EXISTE') AS DESC_RZNCAUSAS31, 
    
  DECODE(DESCRZN31, NULL,'NO EXISTE',DESCRZN31) DESCRZN31,
  
  DECODE(RZNCAUSAS32, NULL,'NO EXISTE',RZNCAUSAS32) RZNCAUSAS32,--2. Lista(Si-NO)
  DECODE(RZNCAUSAS32,0,'NO',1,'SI','NO EXISTE') AS DESC_RZNCAUSAS32,
  
  DECODE(DESCRZN32, NULL,'NO EXISTE',DESCRZN32) DESCRZN32,
  
  DECODE(CAUSAS4, NULL,'NO EXISTE',CAUSAS4) CAUSAS4,--2. Lista(Si-NO)
  DECODE(CAUSAS4,0,'NO',1,'SI','NO EXISTE') AS DESC_CAUSAS4,
  
  DECODE(MEDPBSDESCARTADO, NULL,'NO EXISTE',MEDPBSDESCARTADO) MEDPBSDESCARTADO,
  
  DECODE(RZNCAUSAS41, NULL,'NO EXISTE',RZNCAUSAS41) RZNCAUSAS41,--2. Lista(Si-NO)
  DECODE(RZNCAUSAS41,0,'NO',1,'SI','NO EXISTE') AS DESC_RZNCAUSAS41,
  
  DECODE(DESCRZN41, NULL,'NO EXISTE',DESCRZN41) DESCRZN41,
  
  DECODE(RZNCAUSAS42, NULL,'NO EXISTE',RZNCAUSAS42) RZNCAUSAS42,--2. Lista(Si-NO)
  DECODE(RZNCAUSAS42,0,'NO',1,'SI','NO EXISTE') DESC_RZNCAUSAS42,
  
  DECODE(DESCRZN42, NULL,'NO EXISTE',DESCRZN42) DESCRZN42,
  
  DECODE(RZNCAUSAS43, NULL,'NO EXISTE',RZNCAUSAS43) RZNCAUSAS43,--2. Lista(Si-NO)
  DECODE(RZNCAUSAS43,0,'NO',1,'SI','NO EXISTE') AS DESC_RZNCAUSAS43,
  
  DECODE(DESCRZN43, NULL,'NO EXISTE',DESCRZN43) DESCRZN43,
  
  DECODE(RZNCAUSAS44, NULL,'NO EXISTE',RZNCAUSAS44) RZNCAUSAS44,--2. Lista(Si-NO)
  DECODE(RZNCAUSAS44,0,'NO',1,'SI','NO EXISTE') AS DESC_RZNCAUSAS44,
  
  DECODE(DESCRZN44, NULL,'NO EXISTE',DESCRZN44) DESCRZN44,
  
  DECODE(CAUSAS5, NULL,'NO EXISTE',CAUSAS5) CAUSAS5,   --2. Lista(Si-NO)
  DECODE(CAUSAS5,0,'NO',1,'SI','NO EXISTE') AS DESC_CAUSAS5,
  
  DECODE(RZNCAUSAS5, NULL,'NO EXISTE',RZNCAUSAS5) RZNCAUSAS5,--2. Lista(Si-NO)
  DECODE(RZNCAUSAS5,0,'NO',1,'SI','NO EXISTE') AS DESC_RZNCAUSAS5,
  
  DECODE(CAUSAS6, NULL,'NO EXISTE',CAUSAS6) CAUSAS6,   --2. Lista(Si-NO)
  DECODE(CAUSAS6,0,'NO',1,'SI','NO EXISTE') AS DESC_CAUSAS6,
  
  DECODE(DBMS_LOB.SUBSTR(DESCMEDPRINACT), NULL,'NO EXISTE',DBMS_LOB.SUBSTR(DESCMEDPRINACT)) DESCMEDPRINACT,
    
  DECODE(CODFF, NULL,'NO EXISTE',CODFF) CODFF,--8. Lista(Medi-Form farmacéu)
  DECODE(FF.DESCRIPCION,NULL,'NO EXISTE',FF.DESCRIPCION) AS DESC_CODFF,
  
  DECODE(CODVA, NULL,'NO EXISTE',CODVA) CODVA,--9. Lista(Vías de admin)
  DECODE(VA.DESCRIPCION,NULL,'NO EXISTE',VA.DESCRIPCION)DESC_CODVA,
  
  DECODE(JUSTNOPBS, NULL,'NO EXISTE',JUSTNOPBS) JUSTNOPBS,
  DECODE(DOSIS, NULL,'NO EXISTE',DOSIS) DOSIS,
  
  DECODE(DOSISUM, NULL,'NO EXISTE',DOSISUM) DOSISUM,--10. Lista(Medi-Unid medi)
  DECODE(DBMS_LOB.SUBSTR( UM.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR( UM.DESCRIPCION)) AS DESC_DOSISUM,
  DECODE(DBMS_LOB.SUBSTR( UM.DESCRIPCION2),NULL,'NO EXISTE',DBMS_LOB.SUBSTR( UM.DESCRIPCION2)) AS DESC_DOSISUM2,
  
  
  DECODE(NOFADMON, NULL,'NO EXISTE',NOFADMON) NOFADMON,
  
  DECODE(CODFREADMON, NULL,'NO EXISTE',CODFREADMON) CODFREADMON,--11. Lista(Medi-CodFreAdmon)
  DECODE(DBMS_LOB.SUBSTR(FA.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(FA.DESCRIPCION)) AS DESC_CODFREADMON,
  
  DECODE(INDESP, NULL,'NO EXISTE',INDESP) INDESP,     --12. Lista(Medi-IndEsp)  Indicaciones Especiales
  DECODE(DBMS_LOB.SUBSTR(IE.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(IE.DESCRIPCION)) AS DESC_INDESP,
  DECODE(CANTRAT, NULL,'NO EXISTE',CANTRAT) CANTRAT,
  
  DECODE(DURTRAT, NULL,'NO EXISTE',DURTRAT) DURTRAT,--11. Lista(Medi-DurTrat )
  DECODE(DBMS_LOB.SUBSTR(DT.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(DT.DESCRIPCION)) AS DESC_DURTRAT,
  
  DECODE(CANTTOTALF, NULL,'NO EXISTE',CANTTOTALF) CANTTOTALF,
  
  DECODE(UFCANTTOTAL, NULL,'NO EXISTE',UFCANTTOTAL) UFCANTTOTAL,--13. Lista(Medi-Presentación)
  DECODE(DBMS_LOB.SUBSTR(UC.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(UC.DESCRIPCION)) AS DESC_UFCANTTOTAL,
  
  DECODE(INDREC, NULL,'NO EXISTE',INDREC) INDREC,
  
  DECODE(ESTJM, NULL,'NO EXISTE',ESTJM) ESTJM,--14. Lista (Medi-EstJM)
  DECODE(EJ.DESCRIPCION,NULL,'NO EXISTE',EJ.DESCRIPCION) AS DESC_ESTJM
  
FROM WEBSERV_PRES_MEDI pm
LEFT JOIN WEBSERV_PRES_PRES       PP ON PM.ID_PRES=    PP.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_TI_ME   TM ON PM.TIPOMED=    TM.CODIGO 
LEFT JOIN WEBSERV_REF_PRE_TI_PR   TP ON PM.TIPOPREST=  TP.CODIGO
LEFT JOIN WEBSERV_REF_PRE_FO_FA   FF ON PM.CODFF=      FF.CODIGO
LEFT JOIN WEBSERV_REF_PRE_VI_AD   VA ON PM.CODVA=      VA.CODIGO
LEFT JOIN WEBSERV_REF_PRE_UN_ME   UM ON PM.DOSISUM=    UM.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS FA ON PM.CODFREADMON=FA.CODIGO
LEFT JOIN WEBSERV_REF_PRE_IN_ES   IE ON PM.INDESP=     IE.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS DT ON PM.DURTRAT=    DT.CODIGO
LEFT JOIN WEBSERV_REF_PRE_UF_CT   UC ON PM.UFCANTTOTAL=UC.CODIGO
LEFT JOIN WEBSERV_REF_PRE_ES_JP   EJ ON PM.ESTJM=EJ.CODIGO
where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";

  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);




  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("MEDI"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'ID_MEDI');
  $objSheet->setCellValue('B1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('C1', 'CONORDEN');
  //$objSheet->setCellValue('D1', 'TIPOMED');
  $objSheet->setCellValue('D1', 'DESC_TIPOMED');
  $objSheet->setCellValue('E1', 'DESC_TIPOPREST');
  //$objSheet->setCellValue('G1', 'CAUSAS1');
  $objSheet->setCellValue('F1', 'DESC_CAUSAS1');
  //$objSheet->setCellValue('I1', 'CAUSAS2');
  $objSheet->setCellValue('G1', 'DESC_CAUSAS2');
  //$objSheet->setCellValue('K1', 'CAUSAS3');
  $objSheet->setCellValue('H1', 'DESC_CAUSAS3');
  $objSheet->setCellValue('I1', 'MEDPBSUTILIZADO'); //
  //$objSheet->setCellValue('N1', 'RZNCAUSAS31'); //
  $objSheet->setCellValue('J1', 'DESC_RZNCAUSAS31'); //
  $objSheet->setCellValue('K1', 'DESCRZN31'); //
  //$objSheet->setCellValue('Q1', 'RZNCAUSAS32'); //
  $objSheet->setCellValue('L1', 'DESC_RZNCAUSAS32');
  $objSheet->setCellValue('M1', 'DESCRZN32');
  // $objSheet->setCellValue('T1', 'CAUSAS4');
  $objSheet->setCellValue('N1', 'DESC_CAUSAS4');
  $objSheet->setCellValue('O1', 'MEDPBSDESCARTADO');
  //$objSheet->setCellValue('W1', 'RZNCAUSAS41');
  $objSheet->setCellValue('P1', 'DESC_RZNCAUSAS41');
  $objSheet->setCellValue('Q1', 'DESCRZN41');
  //$objSheet->setCellValue('Z1', 'RZNCAUSAS42');
  $objSheet->setCellValue('R1', 'DESC_RZNCAUSAS42');
  $objSheet->setCellValue('S1', 'DESCRZN42');
  //$objSheet->setCellValue('AC1', 'RZNCAUSAS43');
  $objSheet->setCellValue('T1', 'DESC_RZNCAUSAS43');
  $objSheet->setCellValue('U1', 'DESCRZN43');
  //$objSheet->setCellValue('AF1', 'RZNCAUSAS44');
  $objSheet->setCellValue('V1', 'DESC_RZNCAUSAS44');
  $objSheet->setCellValue('W1', 'DESCRZN44');
  //$objSheet->setCellValue('AI1', 'CAUSAS5');
  $objSheet->setCellValue('X1', 'DESC_CAUSAS5');
  //$objSheet->setCellValue('AK1', 'RZNCAUSAS5');
  $objSheet->setCellValue('Y1', 'DESC_RZNCAUSAS5');
  // $objSheet->setCellValue('AM1', 'CAUSAS6'); //
  $objSheet->setCellValue('Z1', 'DESC_CAUSAS6'); //
  $objSheet->setCellValue('AA1', 'DESCMEDPRINACT'); //
  // $objSheet->setCellValue('AP1', 'CODFF'); //
  $objSheet->setCellValue('AB1', 'DESC_CODFF'); //
  //$objSheet->setCellValue('AR1', 'CODVA');
  $objSheet->setCellValue('AC1', 'DESC_CODVA');
  $objSheet->setCellValue('AD1', 'JUSTNOPBS');
  $objSheet->setCellValue('AE1', 'DOSIS');
  // $objSheet->setCellValue('AV1', 'DOSISUM');
  $objSheet->setCellValue('AF1', 'DESC_DOSISUM');
  $objSheet->setCellValue('AG1', 'DESC_DOSISUM2');
  $objSheet->setCellValue('AH1', 'NOFADMON');
  //$objSheet->setCellValue('AZ1', 'CODFREADMON');
  $objSheet->setCellValue('AI1', 'DESC_CODFREADMON');
  //$objSheet->setCellValue('BB1', 'INDESP');
  $objSheet->setCellValue('AJ1', 'DESC_INDESP');
  $objSheet->setCellValue('AK1', 'CANTRAT');
  //$objSheet->setCellValue('BE1', 'DURTRAT');
  $objSheet->setCellValue('AL1', 'DESC_DURTRAT');
  $objSheet->setCellValue('AM1', 'CANTTOTALF');
  //$objSheet->setCellValue('BH1', 'UFCANTTOTAL');
  $objSheet->setCellValue('AN1', 'DESC_UFCANTTOTAL');
  $objSheet->setCellValue('AO1', 'INDREC');
  //$objSheet->setCellValue('BK1', 'ESTJM');
  $objSheet->setCellValue('AP1', 'DESC_ESTJM');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $objSheet->setCellValue('A' . $i, $row["ID_MEDI"]);
    $objSheet->setCellValue('B' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('C' . $i, $row["CONORDEN"]);

    //$objSheet->setCellValue('D' . $i, $row["TIPOMED"]);
    $DESC_TIPOMED = utf8_encode($row["DESC_TIPOMED"]);
    $objSheet->setCellValue('D' . $i, $DESC_TIPOMED);


    // $objSheet->setCellValue('E' . $i, $row["TIPOPREST"]);

    $DESC_TIPOPREST = utf8_encode($row["DESC_TIPOPREST"]);
    $objSheet->setCellValue('E' . $i, $DESC_TIPOPREST);

    //$objSheet->setCellValue('G' . $i, $row["CAUSAS1"]);
    $DESC_CAUSAS1 = utf8_encode($row["DESC_CAUSAS1"]);
    $objSheet->setCellValue('F' . $i, $DESC_CAUSAS1);

    //$objSheet->setCellValue('I' . $i, $row["CAUSAS2"]);
    $DESC_CAUSAS2 = utf8_encode($row["DESC_CAUSAS2"]);
    $objSheet->setCellValue('G' . $i, $DESC_CAUSAS2);

    //$objSheet->setCellValue('K' . $i, $row["CAUSAS3"]);
    $DESC_CAUSAS3 = utf8_encode($row["DESC_CAUSAS3"]);
    $objSheet->setCellValue('H' . $i, $DESC_CAUSAS3);

    $objSheet->setCellValue('I' . $i, $row["MEDPBSUTILIZADO"]);

    //$objSheet->setCellValue('N' . $i, $row["RZNCAUSAS31"]);
    $DESC_RZNCAUSAS31 = utf8_encode($row["DESC_RZNCAUSAS31"]);
    $objSheet->setCellValue('J' . $i, $DESC_RZNCAUSAS31);

    $objSheet->setCellValue('K' . $i, $row["DESCRZN31"]);

    //$objSheet->setCellValue('Q' . $i, $row["RZNCAUSAS32"]);
    $DESC_RZNCAUSAS32 = utf8_encode($row["DESC_RZNCAUSAS32"]);
    $objSheet->setCellValue('L' . $i, $DESC_RZNCAUSAS32);

    $objSheet->setCellValue('M' . $i, $row["DESCRZN32"]);
    //$objSheet->setCellValue('T' . $i, $row["CAUSAS4"]);
    $objSheet->setCellValue('N' . $i, $row["DESC_CAUSAS4"]);
    $objSheet->setCellValue('O' . $i, $row["MEDPBSDESCARTADO"]);

    //$objSheet->setCellValue('W' . $i, $row["RZNCAUSAS41"]);
    $DESC_RZNCAUSAS41 = utf8_encode($row["DESC_RZNCAUSAS41"]);
    $objSheet->setCellValue('P' . $i, $DESC_RZNCAUSAS41);


    $objSheet->setCellValue('Q' . $i, $row["DESCRZN41"]);

    //$objSheet->setCellValue('Z' . $i, $row["RZNCAUSAS42"]);
    $DESC_RZNCAUSAS42 = utf8_encode($row["DESC_RZNCAUSAS42"]);
    $objSheet->setCellValue('R' . $i, $DESC_RZNCAUSAS42);

    $objSheet->setCellValue('S' . $i, $row["DESCRZN42"]);

    //$objSheet->setCellValue('AC' . $i, $row["RZNCAUSAS43"]);
    $DESC_RZNCAUSAS43 = utf8_encode($row["DESC_RZNCAUSAS43"]);
    $objSheet->setCellValue('T' . $i, $DESC_RZNCAUSAS43);

    $objSheet->setCellValue('U' . $i, $row["DESCRZN43"]);

    //$objSheet->setCellValue('AF' . $i, $row["RZNCAUSAS44"]);
    $DESC_RZNCAUSAS44 = utf8_encode($row["DESC_RZNCAUSAS44"]);
    $objSheet->setCellValue('V' . $i, $DESC_RZNCAUSAS44);

    $objSheet->setCellValue('W' . $i, $row["DESCRZN44"]);

    //$objSheet->setCellValue('AI' . $i, $row["CAUSAS5"]);
    $DESC_CAUSAS5 = utf8_encode($row["DESC_CAUSAS5"]);
    $objSheet->setCellValue('X' . $i, $DESC_CAUSAS5);

    //$objSheet->setCellValue('AK' . $i, $row["RZNCAUSAS5"]);
    $DESC_RZNCAUSAS5 = utf8_encode($row["DESC_RZNCAUSAS5"]);
    $objSheet->setCellValue('Y' . $i, $DESC_RZNCAUSAS5);

    //$objSheet->setCellValue('AM' . $i, $row["CAUSAS6"]);
    $DESC_CAUSAS6 = utf8_encode($row["DESC_CAUSAS6"]);
    $objSheet->setCellValue('Z' . $i, $DESC_CAUSAS6);

    $DESCMEDPRINACT = utf8_encode($row["DESCMEDPRINACT"]);
    $objSheet->setCellValue('AA' . $i, $DESCMEDPRINACT);

    // $objSheet->setCellValue('AP' . $i, $row["CODFF"]);
    $DESC_CODFF = utf8_encode($row["DESC_CODFF"]);
    $objSheet->setCellValue('AB' . $i, $DESC_CODFF);

    //$objSheet->setCellValue('AR' . $i, $row["CODVA"]);
    $DESC_CODVA = utf8_encode($row["DESC_CODVA"]);
    $objSheet->setCellValue('AC' . $i, $DESC_CODVA);

    $JUSTNOPBS = utf8_encode($row["JUSTNOPBS"]);
    $objSheet->setCellValue('AD' . $i, $JUSTNOPBS);

    $objSheet->setCellValue('AE' . $i, $row["DOSIS"]);

    //$objSheet->setCellValue('AV' . $i, $row["DOSISUM"]);
    $DESC_DOSISUM = utf8_encode($row["DESC_DOSISUM"]);
    $objSheet->setCellValue('AF' . $i, $DESC_DOSISUM);
    $DESC_DOSISUM2 = utf8_encode($row["DESC_DOSISUM2"]);
    $objSheet->setCellValue('AG' . $i, $DESC_DOSISUM2);

    $objSheet->setCellValue('AH' . $i, $row["NOFADMON"]);
    // $objSheet->setCellValue('AZ' . $i, $row["CODFREADMON"]);
    $DESC_CODFREADMON = utf8_encode($row["DESC_CODFREADMON"]);
    $objSheet->setCellValue('AI' . $i, $DESC_CODFREADMON);

    //$objSheet->setCellValue('BB' . $i, $row["INDESP"]);
    $DESC_INDESP = utf8_encode($row["DESC_INDESP"]);
    $objSheet->setCellValue('AJ' . $i, $DESC_INDESP);

    $objSheet->setCellValue('AK' . $i, $row["CANTRAT"]);

    //$objSheet->setCellValue('BE' . $i, $row["DURTRAT"]);
    $DESC_DURTRAT = utf8_encode($row["DESC_DURTRAT"]);
    $objSheet->setCellValue('AL' . $i, $DESC_DURTRAT);

    $objSheet->setCellValue('AM' . $i, $row["CANTTOTALF"]);

    // $objSheet->setCellValue('BH' . $i, $row["UFCANTTOTAL"]);
    $DESC_UFCANTTOTAL = utf8_encode($row["DESC_UFCANTTOTAL"]);
    $objSheet->setCellValue('AN' . $i, $DESC_UFCANTTOTAL);

    $objSheet->setCellValue('AO' . $i, $row["INDREC"]);

    //$objSheet->setCellValue('BK' . $i, $row["ESTJM"]);
    $DESC_ESTJM = utf8_encode($row["DESC_ESTJM"]);
    $objSheet->setCellValue('AP' . $i, $DESC_ESTJM);
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
  $objXLS->getActiveSheet()->getColumnDimension("AP")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("AQ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AR")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AS")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AT")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AU")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AV")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AW")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AX")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AY")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AZ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BA")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BB")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BC")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BD")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BE")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BF")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BG")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BH")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BI")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BJ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BK")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("BL")->setAutoSize(true);*/

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


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 2 Medicamentos(Fin)////////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/

  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 3 Medi- Indicadores UNIRS(Inicio)/////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 2;
  $query = " 
  SELECT 
  PP.NOPRESCRIPCION,
  PIU.ID_MEDI,
  DECODE(PIU.CONORDEN, NULL,'NO EXISTE',PIU.CONORDEN) CONORDEN,
  DECODE(PIU.CODINDICACION, NULL,'NO EXISTE',PIU.CODINDICACION) CODINDICACION, 
  DECODE(FIU.DESCRIPCION, NULL,'NO EXISTE',FIU.DESCRIPCION) DESC_CODINDICACION
  FROM WEBSERV_PRES_INDI_UNIRS PIU
  LEFT JOIN WEBSERV_REF_PRE_IND_UNI FIU ON PIU.CODINDICACION=FIU.CODIGO
  LEFT JOIN WEBSERV_PRES_MEDI PM ON PIU.ID_MEDI=PM.ID_MEDI
  LEFT JOIN WEBSERV_PRES_PRES PP ON PM.ID_PRES=PP.ID_PRES
where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);




  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("MEDI- Indi UNIRS"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'ID_MEDI');
  $objSheet->setCellValue('C1', 'CONORDEN');
  //$objSheet->setCellValue('D1', 'CODINDICACION');
  $objSheet->setCellValue('D1', 'DESC_CODINDICACION');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $objSheet->setCellValue('A' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('B' . $i, $row["ID_MEDI"]);
    $objSheet->setCellValue('C' . $i, $row["CONORDEN"]);

    //$objSheet->setCellValue('D' . $i, $row["CODINDICACION"]);
    $DESC_CODINDICACION = utf8_encode($row["DESC_CODINDICACION"]);
    $objSheet->setCellValue('D' . $i, $DESC_CODINDICACION);
  }
  oci_free_statement($st_tire);

  $objXLS->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);*/

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


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 3 Medi- Indicadores UNIRS(Fin)////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 4 Procedimientos(Inicio)//////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 3;
  $query = " 
    select 
PPR.ID_PROC,
PPR.ID_PRES,
PP.NOPRESCRIPCION,
DECODE(PPR.CONORDEN,NULL,'NO EXISTE',PPR.CONORDEN) CONORDEN,
DECODE(PPR.TIPOPREST,NULL,'NO EXISTE',PPR.TIPOPREST)TIPOPREST,--7.Lista(Medi-TipoPrest)
DECODE(TP.DESCRIPCION,NULL,'NO EXISTE',TP.DESCRIPCION) DESC_TIPOPREST,
DECODE(PPR.CAUSAS11,0,'NO',1,'SI','NO EXISTE')CAUSAS11,--2. Lista(Presc-Si-NO)
DECODE(PPR.CAUSAS12,0,'NO',1,'SI','NO EXISTE')CAUSAS12,--2. Lista(Presc-Si-NO)
DECODE(PPR.CAUSAS2,0,'NO',1,'SI','NO EXISTE') CAUSAS2,--2. Lista(Presc-Si-NO)
DECODE(PPR.CAUSAS3,0,'NO',1,'SI','NO EXISTE') CAUSAS3,--2. Lista(Presc-Si-NO)
DECODE(PPR.CAUSAS4,0,'NO',1,'SI','NO EXISTE') CAUSAS4,--2. Lista(Presc-Si-NO)

DECODE(PPR.PROPBSUTILIZADO,NULL,'NO EXISTE',PPR.PROPBSUTILIZADO) PROPBSUTILIZADO,--16. Lista(CUPS)
DECODE(CPU.DESCRIPCION,NULL,'NO EXISTE',CPU.DESCRIPCION) DESC_PROPBSUTILIZADO,

DECODE (PPR.CAUSAS5,0,'NO',1,'SI',NULL,'NO EXISTE',PPR.CAUSAS5)CAUSAS5,--2. Lista(Presc-Si-NO)

DECODE(PPR.PROPBSDESCARTADO, NULL,'NO EXISTE',PPR.PROPBSDESCARTADO)PROPBSDESCARTADO,--16. Lista(CUPS)
DECODE(CPD.DESCRIPCION,NULL,'NO EXISTE',CPD.DESCRIPCION)DESC_PROPBSDESCARTADO,

DECODE (PPR.RZNCAUSAS51,0,'NO',1,'SI',NULL,'NO EXISTE',PPR.RZNCAUSAS51) RZNCAUSAS51,--2. Lista(Presc-Si-NO)
DECODE(PPR.DESCRZN51,NULL,'NO EXISTE',PPR.DESCRZN51)DESCRZN51,
DECODE (PPR.RZNCAUSAS52,0,'NO',1,'SI',NULL,'NO EXISTE',PPR.RZNCAUSAS52) RZNCAUSAS52,--2. Lista(Presc-Si-NO)
DECODE(PPR.DESCRZN52,NULL,'NO EXISTE',PPR.DESCRZN52)DESCRZN52,
DECODE (PPR.CAUSAS6,0,'NO',1,'SI',NULL,'NO EXISTE',PPR.CAUSAS6) CAUSAS6,--2. Lista(Presc-Si-NO)
DECODE (PPR.CAUSAS7,0,'NO',1,'SI',NULL,'NO EXISTE',PPR.CAUSAS7) CAUSAS7,--2. Lista(Presc-Si-NO)

DECODE (PPR.CODCUPS,NULL,'NO EXISTE',PPR.CODCUPS)CODCUPS,--16. Lista(CUPS)
DECODE(CP.DESCRIPCION,NULL,'NO EXISTE',CP.DESCRIPCION)DESC_CODCUPS,


DECODE(PPR.CANFORM,NULL,'NO EXISTE',PPR.CANFORM)CANFORM,
DECODE(PPR.CADAFREUSO,NULL,'NO EXISTE',PPR.CADAFREUSO) CADAFREUSO,

DECODE(PPR.CODFREUSO,NULL,'NO EXISTE',PPR.CODFREUSO)CODFREUSO,--11. Lista(Medi-CodFreAdmon)
DECODE(FU.DESCRIPCION,NULL,'NO EXISTE',FU.DESCRIPCION)DESC_CODFREUSO,

DECODE(PPR.CANT,NULL,'NO EXISTE',PPR.CANT) CANT,
DECODE(PPR.CANTTOTAL,NULL,'NO EXISTE',PPR.CANTTOTAL) CANTTOTAL,

DECODE(PPR.CODPERDURTRAT,NULL,'NO EXISTE',PPR.CODPERDURTRAT)CODPERDURTRAT,--11. Lista(Medi-CodFreAdmon)
DECODE(DT.DESCRIPCION,NULL,'NO EXISTE',DT.DESCRIPCION)DESC_CODPERDURTRAT,

DECODE(PPR.JUSTNOPBS,NULL,'NO EXISTE',PPR.JUSTNOPBS)JUSTNOPBS,
DECODE(PPR.INDREC,NULL,'NO EXISTE',PPR.INDREC) INDREC,

DECODE(PPR.ESTJM,NULL,'NO EXISTE',PPR.ESTJM)ESTJM,--14. Lista (Medi-EstJM)
DECODE(EJ.DESCRIPCION,NULL,'NO EXISTE',EJ.DESCRIPCION) DESC_ESTJM

from WEBSERV_PRES_PROC PPR
LEFT JOIN WEBSERV_PRES_PRES PP ON PP.ID_PRES=PPR.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_TI_PR TP ON TP.CODIGO=PPR.TIPOPREST 
LEFT JOIN WEBSERV_REF_PRE_CUPS CPU ON CPU.CODIGO=PPR.PROPBSUTILIZADO
LEFT JOIN WEBSERV_REF_PRE_CUPS CPD ON CPD.CODIGO=PPR.PROPBSDESCARTADO  
LEFT JOIN WEBSERV_REF_PRE_CUPS CP ON CP.CODIGO=PPR.CODCUPS  
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS FU ON FU.CODIGO=PPR.CODFREUSO
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS DT ON DT.CODIGO=PPR.CODPERDURTRAT
LEFT JOIN WEBSERV_REF_PRE_ES_JP   EJ ON EJ.CODIGO=PPR.ESTJM
  where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);




  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("PROC"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'CONORDEN');
  //$objSheet->setCellValue('C1', 'TIPOPREST');
  $objSheet->setCellValue('C1', 'DESC_TIPOPREST');
  $objSheet->setCellValue('D1', 'CAUSAS11');
  $objSheet->setCellValue('E1', 'CAUSAS12');
  $objSheet->setCellValue('F1', 'CAUSAS2');
  $objSheet->setCellValue('G1', 'CAUSAS3');
  $objSheet->setCellValue('H1', 'CAUSAS4');
  // $objSheet->setCellValue('J1', 'PROPBSUTILIZADO');
  $objSheet->setCellValue('I1', 'DESC_PROPBSUTILIZADO');
  $objSheet->setCellValue('J1', 'CAUSAS5');
  //$objSheet->setCellValue('M1', 'PROPBSDESCARTADO');
  $objSheet->setCellValue('K1', 'DESC_PROPBSDESCARTADO');
  $objSheet->setCellValue('L1', 'RZNCAUSAS51');
  $objSheet->setCellValue('M1', 'DESCRZN51');
  $objSheet->setCellValue('N1', 'RZNCAUSAS52');
  $objSheet->setCellValue('O1', 'DESCRZN52');
  $objSheet->setCellValue('P1', 'CAUSAS6');
  $objSheet->setCellValue('Q1', 'CAUSAS7');
  $objSheet->setCellValue('R1', 'CODCUPS');
  $objSheet->setCellValue('S1', 'DESC_CODCUPS');
  $objSheet->setCellValue('T1', 'CANFORM');
  $objSheet->setCellValue('U1', 'CADAFREUSO');
  //$objSheet->setCellValue('Y1', 'CODFREUSO');
  $objSheet->setCellValue('V1', 'DESC_CODFREUSO');
  $objSheet->setCellValue('W1', 'CANT');
  $objSheet->setCellValue('X1', 'CANTTOTAL');
  //$objSheet->setCellValue('AC1', 'CODPERDURTRAT');
  $objSheet->setCellValue('Y1', 'DESC_CODPERDURTRAT');
  $objSheet->setCellValue('Z1', 'JUSTNOPBS');
  $objSheet->setCellValue('AA1', 'INDREC');
  $objSheet->setCellValue('AB1', 'ESTJM');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $objSheet->setCellValue('A' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('B' . $i, $row["CONORDEN"]);

    //$objSheet->setCellValue('C' . $i, $row["TIPOPREST"]);
    $DESC_TIPOPREST = utf8_encode($row["DESC_TIPOPREST"]);
    $objSheet->setCellValue('C' . $i, $DESC_TIPOPREST);

    $objSheet->setCellValue('D' . $i, $row["CAUSAS11"]);
    $objSheet->setCellValue('E' . $i, $row["CAUSAS12"]);
    $objSheet->setCellValue('F' . $i, $row["CAUSAS2"]);
    $objSheet->setCellValue('G' . $i, $row["CAUSAS3"]);
    $objSheet->setCellValue('H' . $i, $row["CAUSAS4"]);

    //$objSheet->setCellValue('J' . $i, $row["PROPBSUTILIZADO"]);
    $DESC_PROPBSUTILIZADO = utf8_encode($row["DESC_PROPBSUTILIZADO"]);
    $objSheet->setCellValue('I' . $i, $DESC_PROPBSUTILIZADO);

    $objSheet->setCellValue('J' . $i, $row["CAUSAS5"]);
    // $objSheet->setCellValue('M' . $i, $row["PROPBSDESCARTADO"]);
    $DESC_PROPBSDESCARTADO = utf8_encode($row["DESC_PROPBSDESCARTADO"]);
    $objSheet->setCellValue('K' . $i, $DESC_PROPBSDESCARTADO);

    $objSheet->setCellValue('L' . $i, $row["RZNCAUSAS51"]);
    $objSheet->setCellValue('M' . $i, $row["DESCRZN51"]);
    $objSheet->setCellValue('N' . $i, $row["RZNCAUSAS52"]);
    $objSheet->setCellValue('O' . $i, $row["DESCRZN52"]);
    $objSheet->setCellValue('P' . $i, $row["CAUSAS6"]);
    $objSheet->setCellValue('Q' . $i, $row["CAUSAS7"]);

    $objSheet->setCellValue('R' . $i, $row["CODCUPS"]);
    $DESC_CODCUPS = utf8_encode($row["DESC_CODCUPS"]);
    $objSheet->setCellValue('S' . $i, $DESC_CODCUPS);

    $objSheet->setCellValue('T' . $i, $row["CANFORM"]);
    $objSheet->setCellValue('U' . $i, $row["CADAFREUSO"]);

    //$objSheet->setCellValue('Y' . $i, $row["CODFREUSO"]);
    $DESC_CODFREUSO = utf8_encode($row["DESC_CODFREUSO"]);
    $objSheet->setCellValue('V' . $i, $DESC_CODFREUSO);

    $objSheet->setCellValue('W' . $i, $row["CANT"]);
    $objSheet->setCellValue('X' . $i, $row["CANTTOTAL"]);

    //$objSheet->setCellValue('AC' . $i, $row["CODPERDURTRAT"]);
    $DESC_CODPERDURTRAT = utf8_encode($row["DESC_CODPERDURTRAT"]);
    $objSheet->setCellValue('Y' . $i, $DESC_CODPERDURTRAT);

    $JUSTNOPBS = utf8_encode($row["JUSTNOPBS"]);
    $objSheet->setCellValue('Z' . $i, $JUSTNOPBS);
    $objSheet->setCellValue('AA' . $i, $row["INDREC"]);

    //$objSheet->setCellValue('AG' . $i, $row["ESTJM"]);
    $DESC_ESTJM = utf8_encode($row["DESC_ESTJM"]);
    $objSheet->setCellValue('AB' . $i, $DESC_ESTJM);
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
  $objXLS->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AD")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AE")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AF")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AG")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AH")->setAutoSize(true);*/

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


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 4 Procedimientos(Fin)/////////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 5 Productos Nutricionales(Inicio)/////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 4;
  $query = " 
    select 
    PPN.ID_PRNU,
    PPN.ID_PRES,
    PP.NOPRESCRIPCION,
    PPN.CONORDEN,
    DECODE(PPN.TIPOPREST,NULL,'NO EXISTE',PPN.TIPOPREST)TIPOPREST,--7.Lista(Medi-TipoPrest)
    DECODE(TP.DESCRIPCION,NULL,'NO EXISTE',TP.DESCRIPCION) DESC_TIPOPREST,
    
    DECODE(PPN.CAUSAS1,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.CAUSAS1) CAUSAS1,--2. Lista(Presc-Si-NO)
    DECODE(PPN.CAUSAS2,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.CAUSAS2)CAUSAS2,--2. Lista(Presc-Si-NO
    DECODE(PPN.CAUSAS3,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.CAUSAS3)CAUSAS3,--2. Lista(Presc-Si-NO
    DECODE(PPN.CAUSAS4,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.CAUSAS4)CAUSAS4,--2. Lista(Presc-Si-NO
    DECODE(PPN.PRONUTUTILIZADO,NULL,'NO EXISTE',PPN.PRONUTUTILIZADO)PRONUTUTILIZADO,
    DECODE(PPN.RZNCAUSAS41,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.RZNCAUSAS41)RZNCAUSAS41,--2. Lista(Presc-Si-NO
    DECODE(PPN.DESCRZN41,NULL,'NO EXISTE',PPN.DESCRZN41)DESCRZN41,
    DECODE(PPN.RZNCAUSAS42,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.RZNCAUSAS42)RZNCAUSAS42,--2. Lista(Presc-Si-NO
    DECODE(PPN.DESCRZN42,NULL,'NO EXISTE',PPN.DESCRZN42)DESCRZN42,
    DECODE(PPN.CAUSAS5,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.CAUSAS5)CAUSAS5,--2. Lista(Presc-Si-NO
    DECODE(PPN.PRONUTDESCARTADO,NULL,'NO EXISTE',PPN.PRONUTDESCARTADO)PRONUTDESCARTADO,
    DECODE(PPN.RZNCAUSAS51,NULL,'NO EXISTE',PPN.RZNCAUSAS51)RZNCAUSAS51,--2. Lista(Presc-Si-NO
    DECODE(PPN.DESCRZN51,NULL,'NO EXISTE',PPN.DESCRZN51)DESCRZN51,
    DECODE(PPN.RZNCAUSAS52,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.RZNCAUSAS52)RZNCAUSAS52,--2. Lista(Presc-Si-NO
    DECODE(PPN.DESCRZN52,NULL,'NO EXISTE',PPN.DESCRZN52)DESCRZN52,
    DECODE(PPN.RZNCAUSAS53,0,'NO',1,'SI',NULL,'NO EXISTE',PPN.RZNCAUSAS53)RZNCAUSAS53,--2. Lista(Presc-Si-NO
    DECODE(PPN.DESCRZN53,NULL,'NO EXISTE',PPN.DESCRZN53)DESCRZN53,
    DECODE(PPN.RZNCAUSAS54,0,'NO',1,'SI',NULL,'NO EXISTE')RZNCAUSAS54,--2. Lista(Presc-Si-NO
    DECODE(PPN.DESCRZN54,NULL,'NO EXISTE',PPN.DESCRZN54)DESCRZN54,
    
    DECODE(PPN.DXENFHUER,NULL,'NO EXISTE',PPN.DXENFHUER)DXENFHUER,--18. Lista(Diagnostico)
    DECODE(DXF.DESCRIPCION,NULL,'Sin Diagnostico',DXF.DESCRIPCION)DESC_DXENFHUER,
    
    DECODE(PPN.DXVIH,NULL,'NO EXISTE',PPN.DXVIH)DXVIH,--18. Lista(Diagnostico)
    DECODE(DXV.DESCRIPCION,NULL,'Sin Diagnostico',DXV.DESCRIPCION)DESC_DXVIH,
    
    DECODE(PPN.DXCAPAL,NULL,'NO EXISTE',PPN.DXCAPAL)DXCAPAL,--18. Lista(Diagnostico)
    DECODE(DXC.DESCRIPCION,NULL,'Sin Diagnostico',DXC.DESCRIPCION)DESC_DXCAPAL,
    
    
    DECODE(PPN.DXENFRCEV,NULL,'NO EXISTE',PPN.DXENFRCEV)DXENFRCEV,--18. Lista(Diagnostico)
    DECODE(DXE.DESCRIPCION,NULL,'Sin Diagnostico',DXE.DESCRIPCION) DESC_DXENFRCEV,
    
    
    DECODE(PPN.DXDESPRO,NULL,'NO EXISTE',PPN.DXDESPRO)DXDESPRO,--18. Lista(Diagnostico)
    DECODE(DXD.DESCRIPCION,NULL,'Sin Diagnostico',DXD.DESCRIPCION)DESC_DXDESPRO,
    
    
    DECODE(PPN.TIPPPRONUT,NULL,'NO EXISTE',PPN.TIPPPRONUT)TIPPPRONUT,--19. Lista(Tipos produ nutri)
    DECODE(TN.DESCRIPCION,NULL,'NO EXISTE',TN.DESCRIPCION)DESC_TIPPPRONUT,
    --------------------------------------------------------------------------------------------------------------------------------------------
    DECODE(PPN.DESCPRODNUTR,NULL,'NO EXISTE',PPN.DESCPRODNUTR)DESCPRODNUTR,--20.Lista(productos nutricion)
    DECODE(PN.NOMBRE_COMERCIAL,NULL,'NO EXISTE',PN.NOMBRE_COMERCIAL)DESC_DESCPRODNUTR,
    DECODE(PN.DESCR_GRUPO_NIVEL_1,NULL,'NO EXISTE',PN.DESCR_GRUPO_NIVEL_1)DESCR_GRUPO_NIVEL_1,
    DECODE(PN.PRESENTACION_COMERCIAL,NULL,'NO EXISTE',PN.PRESENTACION_COMERCIAL)PRESENTACION_COMERCIAL,
    DECODE(PN.UNIDADES,NULL,'NO EXISTE',PN.UNIDADES)UNIDADES,
    ---------------------------------------------------------------------------------------------------------------------------------------------
    
    DECODE(PPN.CODFORMA,NULL,'NO EXISTE',PPN.CODFORMA)CODFORMA,--21.Listas(Formas Product Nutri)
    DECODE(FN.DESCRIPCION,NULL,'NO EXISTE',FN.DESCRIPCION)DESC_CODFORMA,
    
    DECODE(PPN.CODVIAADMON,NULL,'NO EXISTE',PPN.CODVIAADMON)CODVIAADMON,--22. Listas(CodViaAdmon)
    DECODE(VA.DESCRIPCION,NULL,'NO EXISTE',VA.DESCRIPCION)DESC_CODVIAADMON,
    
    DECODE(PPN.JUSTNOPBS,NULL,'NO EXISTE',PPN.JUSTNOPBS)JUSTNOPBS,
    DECODE(PPN.DOSIS,NULL,'NO EXISTE',PPN.DOSIS)DOSIS,
    
    DECODE(PPN.DOSISUM,NULL,'NO EXISTE',PPN.DOSISUM) DOSISUM,--10. Lista(Medi-Unid medi)
    DECODE(UM.DESCRIPCION,NULL,'NO EXISTE',UM.DESCRIPCION) AS DESC_DOSISUM,
    DECODE(UM.DESCRIPCION2,NULL,'NO EXISTE',UM.DESCRIPCION2) AS DESC_DOSISUM2,
      
    DECODE(PPN.NOFADMON,NULL,'NO EXISTE',PPN.NOFADMON)NOFADMON,
    
    DECODE(PPN.CODFREADMON,NULL,'NO EXISTE',PPN.CODFREADMON)CODFREADMON,--11. Lista(Medi-CodFreAdmon)
    DECODE(FA.DESCRIPCION,NULL,'NO EXISTE',FA.DESCRIPCION) AS DESC_CODFREADMON,
      
    DECODE(PPN.INDESP,NULL,'NO EXISTE',PPN.INDESP)INDESP,
    DECODE(PPN.CANTRAT,NULL,'NO EXISTE',PPN.CANTRAT)CANTRAT,
    
    DECODE(PPN.DURTRAT,NULL,'NO EXISTE',PPN.DURTRAT) DURTRAT,--11. Lista(Medi-CodFreAdmon)
    DECODE(DT.DESCRIPCION,NULL,'NO EXISTE',DT.DESCRIPCION) AS DESC_DURTRAT,
    
    DECODE(PPN.CANTTOTALF,NULL,'NO EXISTE',PPN.CANTTOTALF)CANTTOTALF,
    
    DECODE(PPN.UFCANTTOTAL,NULL,'NO EXISTE',PPN.UFCANTTOTAL)UFCANTTOTAL,--21.Listas(Formas Product Nutri)
    DECODE(FCT.DESCRIPCION,NULL,'NO EXISTE',FCT.DESCRIPCION)DESC_UFCANTTOTAL,
    
    DECODE(PPN.INDREC,NULL,'NO EXISTE',PPN.INDREC)INDREC,
    DECODE(PPN.NOPRESCASO,NULL,'NO EXISTE',PPN.NOPRESCASO)NOPRESCASO,
    
    DECODE(PPN.ESTJM,NULL,'NO EXISTE',PPN.ESTJM)ESTJM,--14. Lista (Medi-EstJM)
    DECODE(EJ.DESCRIPCION,NULL,'NO EXISTE',EJ.DESCRIPCION) AS DESC_ESTJM
    
    FROM WEBSERV_PRES_PROD_NUTR PPN
    LEFT JOIN WEBSERV_PRES_PRES PP ON PP.ID_PRES=PPN.ID_PRES
    LEFT JOIN WEBSERV_REF_PRE_TI_PR TP ON PPN.TIPOPREST=TP.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_DIAG DXF ON PPN.DXENFHUER=DXF.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_DIAG DXV ON PPN.DXVIH=DXV.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_DIAG DXC ON PPN.DXCAPAL=DXC.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_DIAG DXE ON PPN.DXENFRCEV=DXE.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_DIAG DXD ON PPN.DXDESPRO=DXD.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_TP_NU TN ON PPN.TIPPPRONUT=TN.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_PR_NU PN ON PPN.DESCPRODNUTR=PN.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_FP_NU FN ON PPN.CODFORMA=FN.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_VA_PN VA ON PPN.CODVIAADMON=VA.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_UN_ME UM ON PPN.DOSISUM=    UM.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_TIEMPOS FA ON PPN.CODFREADMON=FA.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_TIEMPOS DT ON PPN.DURTRAT=    DT.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_FP_NU FCT ON PPN.UFCANTTOTAL=FCT.CODIGO
    LEFT JOIN WEBSERV_REF_PRE_ES_JP   EJ ON PPN.ESTJM=EJ.CODIGO
  where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);




  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("PROD NUTR"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'CONORDEN');
  //$objSheet->setCellValue('C1', 'TIPOPREST');
  $objSheet->setCellValue('C1', 'DESC_TIPOPREST');
  $objSheet->setCellValue('D1', 'CAUSAS1');
  $objSheet->setCellValue('E1', 'CAUSAS2');
  $objSheet->setCellValue('F1', 'CAUSAS3');
  $objSheet->setCellValue('G1', 'CAUSAS4');
  $objSheet->setCellValue('H1', 'PRONUTUTILIZADO');
  $objSheet->setCellValue('I1', 'RZNCAUSAS41');
  $objSheet->setCellValue('J1', 'DESCRZN41');
  $objSheet->setCellValue('K1', 'RZNCAUSAS42');
  $objSheet->setCellValue('L1', 'DESCRZN42');
  $objSheet->setCellValue('M1', 'CAUSAS5');
  $objSheet->setCellValue('N1', 'DESCRZN54');
  //$objSheet->setCellValue('P1', 'DXENFHUER');
  $objSheet->setCellValue('O1', 'DESC_DXENFHUER');
  //$objSheet->setCellValue('R1', 'DXVIH');
  $objSheet->setCellValue('P1', 'DESC_DXVIH');
  //$objSheet->setCellValue('T1', 'DXCAPAL');
  $objSheet->setCellValue('Q1', 'DESC_DXCAPAL');
  // $objSheet->setCellValue('V1', 'DXENFRCEV');
  $objSheet->setCellValue('R1', 'DESC_DXENFRCEV');
  //$objSheet->setCellValue('X1', 'DXDESPRO');
  $objSheet->setCellValue('S1', 'DESC_DXDESPRO');
  //$objSheet->setCellValue('Z1', 'TIPPPRONUT');
  $objSheet->setCellValue('T1', 'DESC_TIPPPRONUT');
  $objSheet->setCellValue('U1', 'DESCPRODNUTR');
  $objSheet->setCellValue('V1', 'DESC_DESCPRODNUTR');
  $objSheet->setCellValue('W1', 'DESCR_GRUPO_NIVEL_1');
  $objSheet->setCellValue('X1', 'PRESENTACION_COMERCIAL');
  $objSheet->setCellValue('Y1', 'UNIDADES');

  //$objSheet->setCellValue('AD1', 'CODFORMA');
  $objSheet->setCellValue('Z1', 'DESC_CODFORMA');
  //$objSheet->setCellValue('AF1', 'CODVIAADMON');
  $objSheet->setCellValue('AA1', 'DESC_CODVIAADMON');
  $objSheet->setCellValue('AB1', 'JUSTNOPBS');
  $objSheet->setCellValue('AC1', 'DOSIS');
  //$objSheet->setCellValue('AJ1', 'DOSISUM');
  $objSheet->setCellValue('AD1', 'DESC_DOSISUM');
  $objSheet->setCellValue('AE1', 'DESC_DOSISUM2');
  $objSheet->setCellValue('AF1', 'NOFADMON');
  //$objSheet->setCellValue('AN1', 'CODFREADMON');
  $objSheet->setCellValue('AG1', 'DESC_CODFREADMON');
  $objSheet->setCellValue('AH1', 'INDESP');
  $objSheet->setCellValue('AI1', 'CANTRAT');
  //$objSheet->setCellValue('AR1', 'DURTRAT');
  $objSheet->setCellValue('AJ1', 'DESC_DURTRAT');
  $objSheet->setCellValue('AK1', 'CANTTOTALF');
  //$objSheet->setCellValue('AU1', 'UFCANTTOTAL');
  $objSheet->setCellValue('AL1', 'DESC_UFCANTTOTAL');
  $objSheet->setCellValue('AM1', 'INDREC');
  $objSheet->setCellValue('AN1', 'NOPRESCASO');
  //$objSheet->setCellValue('AY1', 'ESTJM');
  $objSheet->setCellValue('AO1', 'DESC_ESTJM');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $objSheet->setCellValue('A' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('B' . $i, $row["CONORDEN"]);
    //$objSheet->setCellValue('C' . $i, $row["TIPOPREST"]);
    $DESC_TIPOPREST = utf8_encode($row["DESC_TIPOPREST"]);
    $objSheet->setCellValue('C' . $i, $DESC_TIPOPREST);
    $objSheet->setCellValue('D' . $i, $row["CAUSAS1"]);
    $objSheet->setCellValue('E' . $i, $row["CAUSAS2"]);
    $objSheet->setCellValue('F' . $i, $row["CAUSAS3"]);

    $objSheet->setCellValue('G' . $i, $row["CAUSAS4"]);
    $objSheet->setCellValue('H' . $i, $row["PRONUTUTILIZADO"]);

    $objSheet->setCellValue('I' . $i, $row["RZNCAUSAS41"]);

    $DESCRZN41 = utf8_encode($row["DESCRZN41"]);
    $objSheet->setCellValue('J' . $i, $DESCRZN41);

    $objSheet->setCellValue('K' . $i, $row["RZNCAUSAS42"]);

    $DESCRZN42 = utf8_encode($row["DESCRZN42"]);
    $objSheet->setCellValue('L' . $i, $DESCRZN42);

    $objSheet->setCellValue('M' . $i, $row["CAUSAS5"]);

    $DESCRZN54 = utf8_encode($row["DESCRZN54"]);
    $objSheet->setCellValue('N' . $i, $DESCRZN54);

    //$objSheet->setCellValue('P' . $i, $row["DXENFHUER"]);
    $DESC_DXENFHUER = utf8_encode($row["DESC_DXENFHUER"]);
    $objSheet->setCellValue('O' . $i, $DESC_DXENFHUER);

    //$objSheet->setCellValue('R' . $i, $row["DXVIH"]);
    $DESC_DXVIH = utf8_encode($row["DESC_DXVIH"]);
    $objSheet->setCellValue('P' . $i, $DESC_DXVIH);

    //$objSheet->setCellValue('T' . $i, $row["DXCAPAL"]);
    $DESC_DXCAPAL = utf8_encode($row["DESC_DXCAPAL"]);
    $objSheet->setCellValue('Q' . $i, $DESC_DXCAPAL);

    //$objSheet->setCellValue('V' . $i, $row["DXENFRCEV"]);
    $DESC_DXENFRCEV = utf8_encode($row["DESC_DXENFRCEV"]);
    $objSheet->setCellValue('R' . $i, $DESC_DXENFRCEV);

    //$objSheet->setCellValue('X' . $i, $row["DXDESPRO"]);
    $DESC_DXDESPRO = utf8_encode($row["DESC_DXDESPRO"]);
    $objSheet->setCellValue('S' . $i, $DESC_DXDESPRO);

    //$objSheet->setCellValue('Z' . $i, $row["TIPPPRONUT"]);
    $DESC_TIPPPRONUT = utf8_encode($row["DESC_TIPPPRONUT"]);
    $objSheet->setCellValue('T' . $i, $DESC_TIPPPRONUT);


    $objSheet->setCellValue('U' . $i, $row["DESCPRODNUTR"]);
    $DESC_DESCPRODNUTR = utf8_encode($row["DESC_DESCPRODNUTR"]);
    $objSheet->setCellValue('V' . $i, $DESC_DESCPRODNUTR);

    $DESCR_GRUPO_NIVEL_1 = utf8_encode($row["DESCR_GRUPO_NIVEL_1"]);
    $objSheet->setCellValue('W' . $i, $DESCR_GRUPO_NIVEL_1);
    $PRESENTACION_COMERCIAL = utf8_encode($row["PRESENTACION_COMERCIAL"]);
    $objSheet->setCellValue('X' . $i, $PRESENTACION_COMERCIAL);
    $UNIDADES = utf8_encode($row["UNIDADES"]);
    $objSheet->setCellValue('Y' . $i, $UNIDADES);

    //$objSheet->setCellValue('AD' . $i, $row["CODFORMA"]);
    $DESC_CODFORMA = utf8_encode($row["DESC_CODFORMA"]);
    $objSheet->setCellValue('Z' . $i, $DESC_CODFORMA);

    //$objSheet->setCellValue('AF' . $i, $row["CODVIAADMON"]);
    $DESC_CODVIAADMON = utf8_encode($row["DESC_CODVIAADMON"]);
    $objSheet->setCellValue('AA' . $i, $DESC_CODVIAADMON);

    $JUSTNOPBS = utf8_encode($row["JUSTNOPBS"]);
    $objSheet->setCellValue('AB' . $i, $JUSTNOPBS);
    $objSheet->setCellValue('AC' . $i, $row["DOSIS"]);

    //$objSheet->setCellValue('AJ' . $i, $row["DOSISUM"]);
    $DESC_DOSISUM = utf8_encode($row["DESC_DOSISUM"]);
    $objSheet->setCellValue('AD' . $i, $DESC_DOSISUM);
    $DESC_DOSISUM2 = utf8_encode($row["DESC_DOSISUM2"]);
    $objSheet->setCellValue('AE' . $i, $DESC_DOSISUM2);

    $objSheet->setCellValue('AF' . $i, $row["NOFADMON"]);

    //$objSheet->setCellValue('AN' . $i, $row["CODFREADMON"]);
    $DESC_CODFREADMON = utf8_encode($row["DESC_CODFREADMON"]);
    $objSheet->setCellValue('AG' . $i, $DESC_CODFREADMON);

    $objSheet->setCellValue('AH' . $i, $row["INDESP"]);
    $objSheet->setCellValue('AI' . $i, $row["CANTRAT"]);

    //$objSheet->setCellValue('AR' . $i, $row["DURTRAT"]);
    $DESC_DURTRAT = utf8_encode($row["DESC_DURTRAT"]);
    $objSheet->setCellValue('AJ' . $i, $DESC_DURTRAT);

    $objSheet->setCellValue('AK' . $i, $row["CANTTOTALF"]);

    //$objSheet->setCellValue('AU' . $i, $row["UFCANTTOTAL"]);
    $DESC_UFCANTTOTAL = utf8_encode($row["DESC_UFCANTTOTAL"]);
    $objSheet->setCellValue('AL' . $i, $DESC_UFCANTTOTAL);

    $INDREC = utf8_encode($row["INDREC"]);
    $objSheet->setCellValue('AM' . $i, $INDREC);
    $objSheet->setCellValue('AN' . $i, $row["NOPRESCASO"]);

    //$objSheet->setCellValue('AY' . $i, $row["ESTJM"]);
    $DESC_ESTJM = utf8_encode($row["DESC_ESTJM"]);
    $objSheet->setCellValue('AO' . $i, $DESC_ESTJM);
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
  $objXLS->getActiveSheet()->getColumnDimension("AO")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("AP")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AQ")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AR")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AS")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AT")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AU")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AV")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AW")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AX")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AY")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AZ")->setAutoSize(true);*/

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


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 5 Productos Nutricionales (Fin)/////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/


  /***************************************************************************************/
  /***************************************************************************************/
  /////////////////////////Hoja 6 Servicios Complementarios (Inicio)/////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 5;
  $query = " 
SELECT 
SC.ID_SECO,
SC.ID_PRES,
PP.NOPRESCRIPCION,
SC.CONORDEN,

DECODE(SC.TIPOPREST,NULL,'NO EXISTE',SC.TIPOPREST)TIPOPREST,--7.Lista(Medi-TipoPrest)
DECODE(DBMS_LOB.SUBSTR( TP.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR( TP.DESCRIPCION)) AS DESC_TIPOPREST,

DECODE(SC.CAUSAS1,0,'NO',1,'SI',NULL,'NO EXISTE',SC.CAUSAS1) CAUSAS1,--2. Lista(Presc-Si-NO)
DECODE(SC.CAUSAS2,0,'NO',1,'SI',NULL,'NO EXISTE',SC.CAUSAS2)CAUSAS2,--2. Lista(Presc-Si-NO
DECODE(SC.CAUSAS3,0,'NO',1,'SI',NULL,'NO EXISTE',SC.CAUSAS3)CAUSAS3,--2. Lista(Presc-Si-NO
DECODE(SC.CAUSAS4,0,'NO',1,'SI',NULL,'NO EXISTE',SC.CAUSAS4)CAUSAS4,--2. Lista(Presc-Si-NO
DECODE(SC.DESCCAUSAS4,NULL,'NO EXISTE',SC.DESCCAUSAS4)DESCCAUSAS4,
DECODE(SC.CAUSAS5,NULL,'NO EXISTE',SC.CAUSAS5)CAUSAS5,--2. Lista(Presc-Si-NO)

DECODE(SC.CODSERCOMP,NULL,'NO EXISTE',SC.CODSERCOMP)CODSERCOMP,--23. Listas(Serv complem PR)  --(MIPRES)17. Listas Servicios complementarios PR
DECODE(SP.DESCRIPCION,NULL,'NO EXISTE',SP.DESCRIPCION)DESC_CODSERCOMP,

DECODE(SC.DESCSERCOMP,NULL,'NO EXISTE',SC.DESCSERCOMP)DESCSERCOMP,
DECODE(SC.CANFORM,NULL,'NO EXISTE',SC.CANFORM)CANFORM,
DECODE(SC.CADAFREUSO,NULL,'NO EXISTE',SC.CADAFREUSO)CADAFREUSO,

DECODE(SC.CODFREUSO,NULL,'NO EXISTE',SC.CODFREUSO)CODFREUSO,--11. Lista(Medi-CodFreAdmon)
DECODE(FU.DESCRIPCION,NULL,'NO EXISTE',FU.DESCRIPCION) AS DESC_CODFREUSO,

DECODE(SC.CANT,null,'NO EXISTE',SC.CANT)CANT,
DECODE(SC.CANTTOTAL,NULL,'NO EXISTE',SC.CANTTOTAL)CANTTOTAL,

DECODE(SC.CODPERDURTRAT,NULL,'NO EXISTE',SC.CODPERDURTRAT)CODPERDURTRAT,--11. Lista(Medi-CodFreAdmon)
DECODE(PD.DESCRIPCION,NULL,'NO EXISTE',PD.DESCRIPCION) AS DESC_CODPERDURTRAT,

DECODE(SC.TIPOTRANS,NULL,'NO EXISTE',SC.TIPOTRANS)TIPOTRANS,--24. Lista(TipoTrans)
DECODE(TT.DESCRIPCION,NULL,'NO EXISTE',TT.DESCRIPCION)DESC_TIPOTRANS,

DECODE(SC.REQACOM,0,'NO',1,'SI',NULL,'NO EXISTE',SC.REQACOM)REQACOM,--2. Lista(Presc-Si-NO)

DECODE(SC.TIPOIDACOMALB,NULL,'NO EXISTE',SC.TIPOIDACOMALB)TIPOIDACOMALB,--25. Lista(TipoIDAcomAlb)
DECODE(TA.DESCRIPCION,NULL,'NO EXISTE',TA.DESCRIPCION)DESC_TIPOIDACOMALB,

DECODE(SC.NROIDACOMALB,NULL,'NO EXISTE',SC.NROIDACOMALB)NROIDACOMALB,

DECODE(SC.PARENTACOMALB,NULL,'NO EXISTE',SC.PARENTACOMALB)PARENTACOMALB,--26. Lista(ParentAcomAlb)
DECODE(PA.DESCRIPCION,NULL,'NO EXISTE',PA.DESCRIPCION)DESC_PARENTACOMALB,

DECODE(SC.NOMBALB,NULL,'NO EXISTE',SC.NOMBALB)NOMBALB,
DECODE(SC.CODMUNORIALB,NULL,'NO EXISTE',SC.CODMUNORIALB)CODMUNORIALB,
DECODE(SC.CODMUNDESALB,NULL,'NO EXISTE',SC.CODMUNDESALB)CODMUNDESALB,
DECODE(SC.JUSTNOPBS,NULL,'NO EXISTE',SC.JUSTNOPBS)JUSTNOPBS,
DECODE(SC.INDREC,NULL,'NO EXISTE',SC.INDREC)INDREC, 

DECODE(SC.ESTJM,NULL,'NO EXISTE',SC.ESTJM)ESTJM,--14. Lista (Medi-EstJM)
DECODE(EJ.DESCRIPCION,NULL,'NO EXISTE',EJ.DESCRIPCION) AS DESC_ESTJM

FROM WEBSERV_PRES_SERV_COMP SC
LEFT JOIN WEBSERV_REF_PRE_TI_PR TP ON SC.TIPOPREST=  TP.CODIGO
LEFT JOIN WEBSERV_PRES_PRES PP ON PP.ID_PRES=SC.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_SC_PR SP ON SC.CODSERCOMP=SP.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS FU ON SC.CODFREUSO=FU.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS PD ON SC.CODPERDURTRAT=PD.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TI_TRANSPORTE TT ON SC.TIPOTRANS=TT.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TD_AA TA ON SC.TIPOIDACOMALB=TA.CODIGO
LEFT JOIN WEBSERV_REF_PRE_PA_AA PA ON SC.PARENTACOMALB=PA.CODIGO
LEFT JOIN WEBSERV_REF_PRE_ES_JP EJ ON SC.ESTJM=EJ.CODIGO
where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);




  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("SERV COMP"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'CONORDEN');
  //$objSheet->setCellValue('C1', 'TIPOPREST');
  $objSheet->setCellValue('C1', 'DESC_TIPOPREST');
  $objSheet->setCellValue('D1', 'CAUSAS1');
  $objSheet->setCellValue('E1', 'CAUSAS2');
  $objSheet->setCellValue('F1', 'CAUSAS3');
  $objSheet->setCellValue('G1', 'CAUSAS4');
  $objSheet->setCellValue('H1', 'DESCCAUSAS4');
  $objSheet->setCellValue('I1', 'CAUSAS5');
  //$objSheet->setCellValue('K1', 'CODSERCOMP');
  $objSheet->setCellValue('J1', 'DESC_CODSERCOMP');
  $objSheet->setCellValue('K1', 'DESCSERCOMP');
  $objSheet->setCellValue('L1', 'CANFORM');
  $objSheet->setCellValue('M1', 'CADAFREUSO');
  //$objSheet->setCellValue('P1', 'CODFREUSO');
  $objSheet->setCellValue('N1', 'DESC_CODFREUSO');
  $objSheet->setCellValue('O1', 'CANT');
  $objSheet->setCellValue('P1', 'CANTTOTAL');
  // $objSheet->setCellValue('T1', 'CODPERDURTRAT');
  $objSheet->setCellValue('Q1', 'DESC_CODPERDURTRAT');
  // $objSheet->setCellValue('V1', 'TIPOTRANS');
  $objSheet->setCellValue('R1', 'DESC_TIPOTRANS');
  $objSheet->setCellValue('S1', 'REQACOM');
  //$objSheet->setCellValue('Y1', 'TIPOIDACOMALB');
  $objSheet->setCellValue('T1', 'DESC_TIPOIDACOMALB');
  $objSheet->setCellValue('U1', 'NROIDACOMALB');
  // $objSheet->setCellValue('AB1', 'PARENTACOMALB');
  $objSheet->setCellValue('V1', 'DESC_PARENTACOMALB');
  $objSheet->setCellValue('W1', 'NOMBALB');
  $objSheet->setCellValue('X1', 'CODMUNORIALB');
  $objSheet->setCellValue('Y1', 'CODMUNDESALB');
  $objSheet->setCellValue('Z1', 'JUSTNOPBS');
  $objSheet->setCellValue('AA1', 'INDREC');
  //$objSheet->setCellValue('AI1', 'ESTJM');
  $objSheet->setCellValue('AB1', 'DESC_ESTJM');
  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $objSheet->setCellValue('A' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('B' . $i, $row["CONORDEN"]);
    //$objSheet->setCellValue('C' . $i, $row["TIPOPREST"]);
    $DESC_TIPOPREST = utf8_encode($row["DESC_TIPOPREST"]);
    $objSheet->setCellValue('C' . $i, $DESC_TIPOPREST);
    $objSheet->setCellValue('D' . $i, $row["CAUSAS1"]);
    $objSheet->setCellValue('E' . $i, $row["CAUSAS2"]);
    $objSheet->setCellValue('F' . $i, $row["CAUSAS3"]);
    $objSheet->setCellValue('G' . $i, $row["CAUSAS4"]);

    $DESCCAUSAS4 = utf8_encode($row["DESCCAUSAS4"]);
    $objSheet->setCellValue('H' . $i, $DESCCAUSAS4);
    $objSheet->setCellValue('I' . $i, $row["CAUSAS5"]);

    //$objSheet->setCellValue('K' . $i, $row["CODSERCOMP"]);
    $DESC_CODSERCOMP = utf8_encode($row["DESC_CODSERCOMP"]);
    $objSheet->setCellValue('J' . $i, $DESC_CODSERCOMP);

    $DESCSERCOMP = utf8_encode($row["DESCSERCOMP"]);
    $objSheet->setCellValue('K' . $i, $DESCSERCOMP);

    $objSheet->setCellValue('L' . $i, $row["CANFORM"]);
    $objSheet->setCellValue('M' . $i, $row["CADAFREUSO"]);

    //$objSheet->setCellValue('P' . $i, $row["CODFREUSO"]);
    $DESC_CODFREUSO = utf8_encode($row["DESC_CODFREUSO"]);
    $objSheet->setCellValue('N' . $i, $DESC_CODFREUSO);

    $objSheet->setCellValue('O' . $i, $row["CANT"]);
    $objSheet->setCellValue('P' . $i, $row["CANTTOTAL"]);

    //$objSheet->setCellValue('T' . $i, $row["CODPERDURTRAT"]);
    $DESC_CODPERDURTRAT = utf8_encode($row["DESC_CODPERDURTRAT"]);
    $objSheet->setCellValue('Q' . $i, $DESC_CODPERDURTRAT);

    //$objSheet->setCellValue('V' . $i, $row["TIPOTRANS"]);
    $DESC_TIPOTRANS = utf8_encode($row["DESC_TIPOTRANS"]);
    $objSheet->setCellValue('R' . $i, $DESC_TIPOTRANS);

    $objSheet->setCellValue('S' . $i, $row["REQACOM"]);

    //$objSheet->setCellValue('Y' . $i, $row["TIPOIDACOMALB"]);
    $DESC_TIPOIDACOMALB = utf8_encode($row["DESC_TIPOIDACOMALB"]);
    $objSheet->setCellValue('T' . $i, $DESC_TIPOIDACOMALB);

    $objSheet->setCellValue('U' . $i, $row["NROIDACOMALB"]);

    //$objSheet->setCellValue('AB' . $i, $row["PARENTACOMALB"]);
    $DESC_PARENTACOMALB = utf8_encode($row["DESC_PARENTACOMALB"]);
    $objSheet->setCellValue('V' . $i, $DESC_PARENTACOMALB);

    $objSheet->setCellValue('W' . $i, $row["NOMBALB"]);
    $objSheet->setCellValue('X' . $i, $row["CODMUNORIALB"]);
    $objSheet->setCellValue('Y' . $i, $row["CODMUNDESALB"]);

    $JUSTNOPBS = utf8_encode($row["JUSTNOPBS"]);
    $objSheet->setCellValue('Z' . $i, $JUSTNOPBS);

    $objSheet->setCellValue('AA' . $i, $row["INDREC"]);

    // $objSheet->setCellValue('AI' . $i, $row["ESTJM"]);
    $DESC_ESTJM = utf8_encode($row["DESC_ESTJM"]);
    $objSheet->setCellValue('AB' . $i, $DESC_ESTJM);
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
  $objXLS->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AD")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AE")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AF")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AG")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AH")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AI")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("AJ")->setAutoSize(true);*/

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


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 6 Servicios Complementarios (Fin)///////////////////////
  /***************************************************************************************/
  /***************************************************************************************/



  /***************************************************************************************/
  /***************************************************************************************/
  /////////////////////////Hoja 7 Dispositivos Medícos (Inicio)////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
  $hoja = 6;
  $query = " 
SELECT 
PD.ID_DISP,
PD.ID_PRES,
PP.NOPRESCRIPCION,
PD.CONORDEN,

DECODE(PD.TIPOPREST,NULL,'NO EXISTE',PD.TIPOPREST)TIPOPREST,--7.Lista(Medi-TipoPrest)
DECODE( TP.DESCRIPCION,NULL,'NO EXISTE', TP.DESCRIPCION) AS DESC_TIPOPREST,

DECODE(PD.CAUSAS1,0,'NO',1,'SI',NULL,'NO EXISTE',PD.CAUSAS1)CAUSAS1,--2. Lista(Presc-Si-NO)

DECODE(PD.CODDISP,NULL,'NO EXISTE',PD.CODDISP)CODDISP,--17.Lista(Tipos disp médi)
DECODE(TM.DESCRIPCION,NULL,'NO EXISTE',TM.DESCRIPCION)DESC_CODDISP,
DECODE(PD.CANFORM,NULL,'NO EXISTE',PD.CANFORM)CANFORM,
DECODE(PD.CADAFREUSO,NULL,'NO EXISTE',PD.CADAFREUSO)CADAFREUSO,
DECODE(PD.CODFREUSO,NULL,'NO EXISTE',PD.CODFREUSO)CODFREUSO,--11. Lista(Medi-CodFreAdmon)
DECODE(PD.CANT,NULL,'NO EXISTE',PD.CANT)CANT,

DECODE(PD.CODPERDURTRAT,NULL,'NO EXISTE',PD.CODPERDURTRAT)CODPERDURTRAT,--11. Lista(Medi-CodFreAdmon)
DECODE(PDU.DESCRIPCION,NULL,'NO EXISTE',PDU.DESCRIPCION) AS DESC_CODPERDURTRAT,

DECODE(PD.CANTTOTAL,NULL,'NO EXISTE',PD.CANTTOTAL)CANTTOTAL,
DECODE(PD.JUSTNOPBS,NULL,'NO EXISTE',PD.JUSTNOPBS)JUSTNOPBS,
DECODE(PD.INDREC,NULL,'NO EXISTE',PD.INDREC)INDREC,

DECODE(PD.ESTJM,NULL,'NO EXISTE',PD.ESTJM)ESTJM,--14. Lista (Medi-EstJM)
DECODE(EJ.DESCRIPCION,NULL,'NO EXISTE',EJ.DESCRIPCION) AS DESC_ESTJM

FROM WEBSERV_PRES_DISP PD
LEFT JOIN WEBSERV_PRES_PRES PP ON PD.ID_PRES=PP.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_TI_PR   TP ON PD.TIPOPREST=  TP.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TD_ME TM ON PD.CODDISP=TM.CODIGO
LEFT JOIN WEBSERV_REF_PRE_TIEMPOS PDU ON PD.CODPERDURTRAT=PDU.CODIGO
LEFT JOIN WEBSERV_REF_PRE_ES_JP   EJ ON PD.ESTJM=EJ.CODIGO
where  pp.REPO_SERV_ID=" . $servicio_id . " and pp.REPO_TIRE_ID=" . $tipo_id . " and pp.REPO_PERIODO BETWEEN '" . $periodo_inicial_oracle . "' AND '" . $periodo_final_oracle . "'";
  $st_tire = oci_parse($conn_oracle, $query);
  oci_execute($st_tire, OCI_DEFAULT);




  $objSheet = $objXLS->createSheet();
  $objSheet = $objXLS->setActiveSheetIndex($hoja);
  $objXLS->getActiveSheet()->setTitle("DISP MEDI"); // AQUI AGREGO EL NOMBRE A LA HOJA
  // Agregar Informacion

  $objSheet->setCellValue('A1', 'NOPRESCRIPCION');
  $objSheet->setCellValue('B1', 'CONORDEN');
  //$objSheet->setCellValue('C1', 'TIPOPREST');
  $objSheet->setCellValue('C1', 'DESC_TIPOPREST');
  $objSheet->setCellValue('D1', 'CAUSAS1');
  // $objSheet->setCellValue('F1', 'CODDISP');
  $objSheet->setCellValue('E1', 'DESC_CODDISP');
  $objSheet->setCellValue('F1', 'CANFORM');
  $objSheet->setCellValue('G1', 'CADAFREUSO');
  $objSheet->setCellValue('H1', 'CODFREUSO');
  $objSheet->setCellValue('I1', 'CANT');
  //$objSheet->setCellValue('L1', 'CODPERDURTRAT');
  $objSheet->setCellValue('J1', 'DESC_CODPERDURTRAT');
  $objSheet->setCellValue('K1', 'CANTTOTAL');
  $objSheet->setCellValue('L1', 'JUSTNOPBS');
  $objSheet->setCellValue('M1', 'INDREC');
  //$objSheet->setCellValue('Q1', 'ESTJM');
  $objSheet->setCellValue('N1', 'DESC_ESTJM');

  $i = 1;
  while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
    $i = $i + 1;
    $objSheet->setCellValue('A' . $i, '="' . $row["NOPRESCRIPCION"] . '"');
    $objSheet->setCellValue('B' . $i, $row["CONORDEN"]);
    //$objSheet->setCellValue('C' . $i, $row["TIPOPREST"]);
    $DESC_TIPOPREST = utf8_encode($row["DESC_TIPOPREST"]);
    $objSheet->setCellValue('C' . $i, $DESC_TIPOPREST);

    $objSheet->setCellValue('D' . $i, $row["CAUSAS1"]);

    //$objSheet->setCellValue('F' . $i, $row["CODDISP"]);
    $DESC_CODDISP = utf8_encode($row["DESC_CODDISP"]);
    $objSheet->setCellValue('E' . $i, $DESC_CODDISP);

    $objSheet->setCellValue('F' . $i, $row["CANFORM"]);
    $objSheet->setCellValue('G' . $i, $row["CADAFREUSO"]);
    $objSheet->setCellValue('H' . $i, $row["CODFREUSO"]);
    $objSheet->setCellValue('I' . $i, $row["CANT"]);

    //$objSheet->setCellValue('L' . $i, $row["CODPERDURTRAT"]);
    $DESC_CODPERDURTRAT = utf8_encode($row["DESC_CODPERDURTRAT"]);
    $objSheet->setCellValue('J' . $i, $DESC_CODPERDURTRAT);

    $objSheet->setCellValue('K' . $i, $row["CANTTOTAL"]);

    $JUSTNOPBS = utf8_encode($row["JUSTNOPBS"]);
    $objSheet->setCellValue('L' . $i, $JUSTNOPBS);

    $objSheet->setCellValue('M' . $i, $row["INDREC"]);

    //$objSheet->setCellValue('Q' . $i, $row["ESTJM"]);
    $DESC_ESTJM = utf8_encode($row["DESC_ESTJM"]);
    $objSheet->setCellValue('N' . $i, $DESC_ESTJM);
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
  $objXLS->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);/*
  $objXLS->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
  $objXLS->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);*/

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


  /***************************************************************************************/
  /***************************************************************************************/
  ////////////////////////////Hoja 7 Dispositivos Medícos (Fin)////////////////////////////
  /***************************************************************************************/
  /***************************************************************************************/
}






// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objXLS->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
//header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Reporte " . $regimen . " " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save('php://output');
//header("Location:../Administrador/ReportesTecnico.php");
exit;
