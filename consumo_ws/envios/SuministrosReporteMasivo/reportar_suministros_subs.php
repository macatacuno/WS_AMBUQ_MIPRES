<?php

/**********************Cargar funciones funciones_generales***************************/
include('../../funciones_generales.php');

/**********************Cargarel procedimiento para la conexion de oracle**************/
include('../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS


require_once '../../../plugins/PHPExcel/Classes/PHPExcel.php';

/**********************Variables generales*******************************************/
$tipo_id = 2; //Subsidiado
$servicio_id = 16; // Se asigna el codigo del servicio ReportarSuministro

/**********************Obtener los datos para armar la URL***************************/
$url_bd = obtener_datos_url("URL", $servicio_id, $conn_oracle);
$serv_nombre = obtener_datos_url("SERV_NOMBRE", $servicio_id, $conn_oracle);
$ws_nombre = obtener_datos_url("WS_NOMBRE", $servicio_id, $conn_oracle);
$Webservice = obtener_datos_url("WS_ID", $servicio_id, $conn_oracle);

/**********************Obtener el nit y el token**************************************/
$nit = obtener_datos_token_nit("NIT", $tipo_id, $conn_oracle);
$token = obtener_datos_token_nit("TOKEN", $tipo_id, $conn_oracle);
$horas_de_diferencia = obtener_datos_token_nit("HORAS_DE_DIFERENCIA", $tipo_id, $conn_oracle);
$token_temporal = obtener_datos_token_nit("TOKEN_TEMPORAL", $tipo_id, $conn_oracle);
$token_temporal = actualizar_token_temporal($horas_de_diferencia, $conn_oracle, $nit, $token, $token_temporal);




$archivo = "tabla.xlsx";
$inputFileType = PHPExcel_IOFactory::identify($archivo);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($archivo);
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

for ($row = 2; $row <= $highestRow; $row++) {
  $id = $sheet->getCell("A" . $row)->getValue();
  $ultentrega = $sheet->getCell("B" . $row)->getValue();
  $entregacompleta = $sheet->getCell("C" . $row)->getValue();
  $causanoentrega = $sheet->getCell("D" . $row)->getValue();
  $noprescripcionasociada = $sheet->getCell("E" . $row)->getValue();
  $contecasociada = $sheet->getCell("F" . $row)->getValue();
  $canttotentregada = $sheet->getCell("G" . $row)->getValue();
  $nolote = $sheet->getCell("H" . $row)->getValue();
  $valorentregado = $sheet->getCell("I" . $row)->getValue();
  if ($id <> "" || $id <> null) {
    $json_reporte_sumi = "{
      \"ID\": $id,
      \"UltEntrega\": $ultentrega,
      \"EntregaCompleta\": $entregacompleta,
      \"CausaNoEntrega\": $causanoentrega,
      \"NoPrescripcionAsociada\": \"$noprescripcionasociada\",
      \"ConTecAsociada\": $contecasociada,
      \"CantTotEntregada\": \"$canttotentregada\",
      \"NoLote\": \"$nolote\",
      \"ValorEntregado\": \"$valorentregado\"
    }";
    echo "$json_reporte_sumi<br>";

    $url = $url_bd . "/" . $nit . '/' . $token_temporal;
    echo "url: $url<br>";

    $respuesta = direccionar_put($url, $json_reporte_sumi);
    echo "$respuesta<br><br>";
  }
}

/*
$json="drdsfsdf";
$json_array = json_decode($json, true);
foreach ($json_array as $clave) {
    echo "<br>IDSuministro: " . $clave["IDSuministro"] . "=> ";
    $url = $url_bd . "/" . $nit . '/' . $token_temporal . '/' .  $clave["IDSuministro"];
    //$json_reporte_sumi = '';
   // $respuesta = direccionar_put($url, $json_reporte_sumi);
    echo "<br>url: $url<br>";
   // echo "$respuesta";
}*/
