<?php
include("../../../conexion.php");


///////Declaracion de Variables Generales(Inicio)/////////
$json="";
//pamemetros de entrada
$servicio_id = 7;
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



      $url =$url_bd."/".$nit.'/'.$token.'/'.$numero_prescripcion;
      $json = file_get_contents($url);

if($json==""){
  echo "<script>alert('Error al conectar con la API, favor volver a intentar.');</script>";
}else{
  
  $filecontent=$json;
$downloadfile="Reporte prescripcion numero: ".$numero_prescripcion.".txt";
 
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