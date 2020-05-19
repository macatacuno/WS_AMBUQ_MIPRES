<?php
include("../../../../conexion.php");
include('../../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////
$json="";
$Json_final="";
$peri_error="";
$error_encontrado="";
//pamemetros de entrada
$servicio_get='ReporteEntregaXFecha';
//$tipo_get='contributivo';

$servicio_id = 0;
//$tipo_id = 0;
$tipo_id = $_POST['tipo'];
//Parametros de la api
$nit = "";
$url_bd="";
$token="";
$url_api_generar_token="";




//obtener los parametros la url(inicio)
if($servicio_get=='ReporteEntregaXFecha'){
  $servicio_id = 1;
}else if($servicio_get=='Prescripcion'){
  $servicio_id = 2;
}
$consulta = "SELECT serv_url FROM servicios where serv_id=".$servicio_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
   $url_bd=$fila["serv_url"];
}
}
//obtener los parametros la url(fin)

//obtener el nit y el token(inicio)
/*
if($tipo_get=='subsidiado'){
  $tipo_id=2;
 // $token = "208F5DB1-95D0-446E-AAD7-2674C6360A46";
}else if($tipo_get=='contributivo'){
  $tipo_id=1;
}
*/
$consulta = "SELECT tire_nit,tire_token FROM tiposreportes where tire_id=".$tipo_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
    $nit=$fila["tire_nit"];
    $token=$fila["tire_token"];
}
}
//obtener el nit y el token(fin)

//Generar token para contibutivo(inicio)
  //Si el tipo es contributivo entonces genera el token temporal y se usa este en lugar del token contributivo
//if($tipo_id==1){
  $consulta = "SELECT serv_url FROM servicios where serv_nombre='GenerarToken'";
  if ($resultado = $conn->query($consulta)) {
    while ($fila = $resultado->fetch_assoc()) { 
     $url_api_generar_token=$fila["serv_url"];
  }
  }

  $url_token =$url_api_generar_token."/".$nit."/".$token;
  $token = Webservice_get($url_token);
//$token = file_get_contents($url_token);
  $token = str_replace("\"", '', $token);
//}
//Generar token para contributivo(fin)



/*
$url_token ='https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/818000140/3858A1E4-E9BB-40D1-90E7-C127480363F2';
$token = file_get_contents($url_token);
$token = str_replace("\"", '', $token);

$nit = '818000140';

*/
$periodo_inicial='20'.date('y-m-d');
$periodo_final = $periodo_inicial;



$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias=$diff->days+1;

if($periodo_final<$periodo_inicial){
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
  }else{

  
    for ($i = 0; $i <= $cant_dias-1; $i++) {
      $periodo_conteo = date("y-m-d",strtotime($periodo_inicial."+ ".$i." day")); 
    
      //$url ='https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha/'.$nit.'/'.$token.'/'."20".$periodo_conteo;
      $url =$url_bd."/".$nit.'/'.$token.'/'."20".$periodo_conteo;
      //echo $url;
      $json = Webservice_get($url);
      //$json = file_get_contents($url);
      if ($json == "" || $json=='{"Message":"Error."}') {
        $peri_error= $peri_error."20".$periodo_conteo."<br>";
      }else{
    
      }
      $Json_final=$Json_final.$json;
    
    }


if($Json_final==""){

  echo "<script>alert('Error al conectar con la API, favor volver a intentar.');</script>";
}else{
  
/** Incluir la libreria PHPExcel */
require_once '../../../../plugins/PHPExcel/Classes/PHPExcel.php';
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

     
    //$json_array = json_decode($json); 
    $json_array = json_decode($Json_final, true);
    foreach($json_array as $clave) {
      $i=$i+1;
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, $clave["ID"])
      ->setCellValue('B'.$i, $clave["IDReporteEntrega"])
      ->setCellValue('C'.$i, $clave["NoPrescripcion"])
      ->setCellValue('D'.$i, $clave["TipoTec"])
      ->setCellValue('E'.$i, $clave["ConTec"])
      ->setCellValue('F'.$i, $clave["TipoIDPaciente"])
      ->setCellValue('G'.$i, $clave["NoIDPaciente"])
      ->setCellValue('H'.$i, $clave["NoEntrega"])
      ->setCellValue('I'.$i, $clave["EstadoEntrega"])
      ->setCellValue('J'.$i, $clave["CausaNoEntrega"])
      ->setCellValue('K'.$i, $clave["ValorEntregado"])
      ->setCellValue('L'.$i, $clave["CodTecEntregado"])
      ->setCellValue('M'.$i, $clave["CantTotEntregada"])
      ->setCellValue('N'.$i, $clave["NoLote"])
      ->setCellValue('O'.$i, $clave["FecEntrega"])
      ->setCellValue('P'.$i, $clave["FecRepEntrega"])
      ->setCellValue('Q'.$i, $clave["EstRepEntrega"])
      ->setCellValue('R'.$i, $clave["FecAnulacion"]);
  
  }
    



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

}


  }



//echo $json; --Escribir el Json en la vista
mysqli_close($conn);

?>