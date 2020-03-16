<?php
include("../../../../conexion.php");
include('../../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////
$json="";
//pamemetros de entrada
$servicio_id = 6;
$tipo_id = $_POST['tipo'];
$fecha = $_POST['fecha'];
$tipo_documento = $_POST['tipo_documento'];
$Numero_documento = $_POST['Numero_documento'];
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


      $url =$url_bd."/".$nit.'/'.$fecha.'/'.$token.'/'.$tipo_documento.'/'.$Numero_documento;
      $json = Webservice_get($url);
      //$json = file_get_contents($url);

if($json==""){
  echo "<script>alert('Error al conectar con la API, favor volver a intentar.');</script>";
}else{

  $filecontent=$json;
$downloadfile="Json prescripcion ".$_POST['tipo']." para el cliente: ".$Numero_documento.".txt";
 
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($filecontent));
header("Pragma: no-cache");
header("Expires: 0");
 
echo $filecontent;



}
  
//echo $json; //Escribir el Json en la vista
mysqli_close($conn);

?>