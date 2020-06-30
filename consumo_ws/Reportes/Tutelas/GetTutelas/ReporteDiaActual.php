<?php
include("../../../../conexion.php");
include('../../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////
$json="";
$Json_final="";
$peri_error="";
$error_encontrado="";
//pamemetros de entrada
//$servicio_get='ReporteEntregaXFecha';
//$tipo_get='contributivo';


//$tipo_id = 0;
$servicio_id = 3; // Se asigna el codigo del servicio Prescripcion
$tipo_id = $_POST['tipo'];
//Parametros de la api
$nit = "";
$url_bd="";
$token="";
$url_api_generar_token="";

$consulta = "SELECT serv_url FROM servicios where serv_id=".$servicio_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
   $url_bd=$fila["serv_url"];
}
}
$consulta = "SELECT tire_nit,tire_token FROM tiposreportes where tire_id=".$tipo_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
    $nit=$fila["tire_nit"];
    $token=$fila["tire_token"];
}
}

$periodo_inicial='20'.date('y-m-d');
//echo $periodo_inicial."<br>";
$periodo_inicial='2019-10-29';

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
      $url =$url_bd."/".$nit.'/'."20".$periodo_conteo.'/'.$token;
      //echo $url;
      $json = Webservice_get($url);
      //$json = file_get_contents($url);
      
      /*$json = str_replace("\n", "", $json);
      $json = str_replace("\t", "", $json);*/
      $json = str_replace("\\\"", "\\\\\"", $json);
      if ($json == "" || (strlen($json) >= 3 && strlen($json) <= 100)) {
        $peri_error= $peri_error."20".$periodo_conteo."<br>";
      }else{
    
      }
      $Json_final=$Json_final.$json;
    
    }


if($Json_final==""){

  echo "<script>alert('Error al conectar con la API, favor volver a intentar.');</script>";
}else{
  
  $filecontent=$json;
$downloadfile="Json prescripcion ".$_POST['tipo']." ".$periodo_inicial." - ".$periodo_final.".txt";
 /*
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($filecontent));
header("Pragma: no-cache");
header("Expires: 0");
 
echo $filecontent;
*/
}

  }

echo $json; //Escribir el Json en la vista
mysqli_close($conn);

?>