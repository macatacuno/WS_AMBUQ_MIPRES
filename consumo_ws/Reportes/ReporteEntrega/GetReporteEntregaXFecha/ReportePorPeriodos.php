<?php


set_time_limit(9999999);
$servername = "10.249.249.50";
$database = "db_app_ambuq";
$username = "admin";
$password = "jpkqRJ45DLink";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
//include('../../../funciones_generales.php');

//Variable Generales
$json = "";
$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

$tipo_id = $_POST['tipo'];
$servicio_id = 1; // Se asigna el codigo del servicio GetReporteEntregaXFecha

//Se calcula el rango de lso periodos
$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias = $diff->days + 1;
$periodo_conteo = $periodo_inicial;
/*
echo 'Periodo Cargado';
echo "<br>";
echo $periodo_inicial." - ".$periodo_final; 
echo "<br><br>";
*/
if ($periodo_final < $periodo_inicial) {
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
} else {


  $consulta = "SELECT repo_periodo, repo_json FROM reportesws where serv_id=" . $servicio_id . " and tire_id=" . $tipo_id . " and repo_periodo between '" . $periodo_inicial . "' and '" . $periodo_final . "' order by repo_periodo";

  if ($resultado = $conn->query($consulta)) {


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

    /* obtener un array asociativo */

    while ($fila = $resultado->fetch_assoc()) {

      $fila["repo_json"] = str_replace("[]", '', $fila["repo_json"]);
      $fila["repo_json"] = str_replace("[", '', $fila["repo_json"]);
      $fila["repo_json"] = str_replace("]", ',', $fila["repo_json"]);
      $json = $json . $fila["repo_json"];
    }

    $json = "[" . $json . "]";
    $json = str_replace("[,", '[', $json);
    $json = str_replace(",]", ']', $json);
    $json = str_replace("\n", '', $json);
    $json = str_replace("	", '', $json); //En ocaciones se produce un error al convertir la cadena en objeto por este espacio

    /*
  $json = str_replace("[", '[<br>', $json); 
  $json = str_replace("{", '  {<br>', $json); 
  $json = str_replace("]", ']<br>', $json); 
  $json = str_replace("}", '  }<br>', $json); 
  $json = str_replace(",", ',<br>', $json);  
*/
    //echo $json; //Escribir el Json en la vista
    //$json_array = json_decode($json); 
    $json_array = json_decode($json, true);
    $i = 1;
    // Se conprueba que el $json realmente sea una lista o un objeto.
    // if (is_array($json_array) || is_object($json_array)) {
    foreach ($json_array as $clave) {
      $i = $i + 1;
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $clave["ID"])
        ->setCellValue('B' . $i, $clave["IDReporteEntrega"])
        ->setCellValue('C' . $i, $clave["NoPrescripcion"])
        ->setCellValue('D' . $i, $clave["TipoTec"])
        ->setCellValue('E' . $i, $clave["ConTec"])
        ->setCellValue('F' . $i, $clave["TipoIDPaciente"])
        ->setCellValue('G' . $i, $clave["NoIDPaciente"])
        ->setCellValue('H' . $i, $clave["NoEntrega"])
        ->setCellValue('I' . $i, $clave["EstadoEntrega"])
        ->setCellValue('J' . $i, $clave["CausaNoEntrega"])
        ->setCellValue('K' . $i, $clave["ValorEntregado"])
        ->setCellValue('L' . $i, $clave["CodTecEntregado"])
        ->setCellValue('M' . $i, $clave["CantTotEntregada"])
        ->setCellValue('N' . $i, $clave["NoLote"])
        ->setCellValue('O' . $i, $clave["FecEntrega"])
        ->setCellValue('P' . $i, $clave["FecRepEntrega"])
        ->setCellValue('Q' . $i, $clave["EstRepEntrega"])
        ->setCellValue('R' . $i, $clave["FecAnulacion"]);
    }
    /*}else{
      echo "<br>Error al convertir la cadena del json en un objeto";
    }*/
  }

  // Renombrar Hoja
  $objPHPExcel->getActiveSheet()->setTitle('Tecnologia Simple');
  // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
  $objPHPExcel->setActiveSheetIndex(0);
  // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
  header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
  header("Content-Disposition: attachment;filename=Reporte " . $periodo_inicial . " - " . $periodo_final . ".xlsx");
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter(
    $objPHPExcel,
    'Excel2007'
  );
  $objWriter->save('php://output');
  exit;

  mysqli_close($conn);
}
