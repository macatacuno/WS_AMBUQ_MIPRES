<?php
include("../../conexion.php");

//Variable Generales
$json="";
$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

$tipo_id = $_POST['tipo'];
$servicio_id = 3; // Se asigna el codigo del servicio Prescripcion

//Se calcula el rango de lso periodos
$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias=$diff->days+1;
$periodo_conteo=$periodo_inicial;
/*
echo 'Periodo Cargado';
echo "<br>";
echo $periodo_inicial." - ".$periodo_final; 
echo "<br><br>";
*/
if($periodo_final<$periodo_inicial){
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
}else{

  $consulta = "SELECT repo_periodo, repo_json FROM reportesws where repo_json<>'[]' and  serv_id=".$servicio_id." and tire_id=".$tipo_id." and repo_periodo between '".$periodo_inicial."' and '".$periodo_final."' order by repo_periodo";

if ($resultado = $conn->query($consulta)) {


/** Incluir la libreria PHPExcel */
require_once '../../Plugins/PHPExcel/Classes/PHPExcel.php';
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

// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'ID')
->setCellValue('B1', 'IDReporteEntrega')
->setCellValue('C1', 'NoPrescripcion')
->setCellValue('D1', 'TipoTec')
->setCellValue('E1', 'ConTec')
->setCellValue('F1', 'TipoIDPaciente')
->setCellValue('G1', 'NoEntrega')
->setCellValue('H1', 'EstadoEntrega')
->setCellValue('I1', 'CausaNoEntrega')
->setCellValue('J1', 'CodTecEntregado')
->setCellValue('K1', 'CantTotEntregada')
->setCellValue('L1', 'NoLote')
->setCellValue('M1', 'FecEntrega')
->setCellValue('N1', 'FecRepEntrega')
->setCellValue('O1', 'EstRepEntrega')
->setCellValue('P1', 'FecAnulacion');

    /* obtener un array asociativo */
    $i=1;
    while ($fila = $resultado->fetch_assoc()) {
      $ultimo_dato_json_con_corchete=substr($fila["repo_json"], -31,31);
      $ultimo_dato_json_sin_corchete=substr($fila["repo_json"], -31,30);

      
      $fila["repo_json"] = str_replace("[{\"prescripcion\"", "{\"prescripcion\"", $fila["repo_json"]); 
      $fila["repo_json"] = str_replace($ultimo_dato_json_con_corchete, $ultimo_dato_json_sin_corchete.",", $fila["repo_json"]);
     // $fila["repo_json"] = str_replace("]", ',', $fila["repo_json"]);
     // echo strlen($fila["repo_json"]);
     /* echo $ultimo_dato_json_con_corchete."<br>"; 
      echo $ultimo_dato_json_sin_corchete; 
      echo "<br><br><br><br><br><br>";*/
      $json=$json.$fila["repo_json"];
  }

  $json="[".$json."]";
  $json = str_replace("\n", '', $json);
 /* $json = str_replace("[,", '[', $json); 
  $json = str_replace(",]", ']', $json); 
  $json = str_replace("\n", '', $json); */
/*
  $json = str_replace("[", '[<br>', $json); 
  $json = str_replace("{", '  {<br>', $json); 
  $json = str_replace("]", ']<br>', $json); 
  $json = str_replace("}", '  }<br>', $json); 
  $json = str_replace(",", ',<br>', $json);  
*/
 
  //$json_array = json_decode($json); 

/* 

  $json_array = json_decode($json, true);

  //echo $json; //Escribir el Json en la vista
  foreach($json_array as $clave) {
   echo $clave[1];
    $i=$i+1;
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i, $clave["NoPrescripcion"]);
   ->setCellValue('B'.$i, $clave["IDReporteEntrega"])
    ->setCellValue('C'.$i, $clave["NoPrescripcion"])
    ->setCellValue('D'.$i, $clave["TipoTec"])
    ->setCellValue('E'.$i, $clave["ConTec"])
    ->setCellValue('F'.$i, $clave["TipoIDPaciente"])
    ->setCellValue('G'.$i, $clave["NoEntrega"])
    ->setCellValue('H'.$i, $clave["EstadoEntrega"])
    ->setCellValue('I'.$i, $clave["CausaNoEntrega"])
    ->setCellValue('J'.$i, $clave["CodTecEntregado"])
    ->setCellValue('K'.$i, $clave["CantTotEntregada"])
    ->setCellValue('L'.$i, $clave["NoLote"])
    ->setCellValue('M'.$i, $clave["FecEntrega"])
    ->setCellValue('N'.$i, $clave["FecRepEntrega"])
    ->setCellValue('O'.$i, $clave["EstRepEntrega"])
    ->setCellValue('P'.$i, $clave["FecAnulacion"]);
       
}
  */

 

}
/*
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Tecnologia Simple');
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

*/



$filecontent=$json;
$downloadfile="Json prescripcion ".$_POST['tipo']." ".$periodo_inicial." - ".$periodo_final.".txt";
 
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($filecontent));
header("Pragma: no-cache");
header("Expires: 0");
 
echo $filecontent;








//echo $json; //Escribir el Json en la vista
mysqli_close($conn);
}
?>