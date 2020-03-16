<?php
include("../../../../conexion.php");
include('../../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////
$json="";
//pamemetros de entrada
$servicio_id = 4;
$tipo_id = $_POST['tipo'];
$numero_prescripcion=$_POST['numero_prescripcion'];
//Parametros de la api
$nit = "";
$url_bd="";
$token="";
$url_api_generar_token="";
$i=0;




//obtener los parametros la url(inicio)

$consulta = "SELECT serv_url FROM servicios where serv_id=".$servicio_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
   $url_bd=$fila["serv_url"];
}
}
//obtener los parametros la url(fin)

$consulta = "SELECT tire_nit,tire_token FROM tiposreportes where tire_id=".$tipo_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
    $nit=$fila["tire_nit"];
    $token=$fila["tire_token"];
}
}
//obtener el nit y el token(fin)

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

//Generar token para contributivo(fin)

      $url =$url_bd."/".$nit.'/'.$token.'/'.$numero_prescripcion;
      $json = Webservice_get($url);
      //$json = file_get_contents($url);

if($json==""){
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
    $json_array = json_decode($json, true);
    $i=2;//se comenzaran a escribir los datos desde la fila 2 del excel
    foreach($json_array as $clave) {
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
      $i=$i+1;
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
header("Content-Disposition: attachment;filename=Reporte prescripcion numero: ".$numero_prescripcion.".xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,
'Excel2007');
$objWriter->save('php://output');
exit;

}
  
echo $json; //Escribir el Json en la vista
mysqli_close($conn);

?>