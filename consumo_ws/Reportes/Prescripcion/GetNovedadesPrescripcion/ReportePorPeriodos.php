<?php
include("../../../../conexion.php");

//Variable Generales
$json="";
$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

$tipo_id = $_POST['tipo'];
$servicio_id = 8; // Se asigna el codigo del servicio Prescripcion

//Se calcula el rango de los periodos
$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias=$diff->days+1;
$periodo_conteo=$periodo_inicial;

if($periodo_final<$periodo_inicial){
  echo "<script>alert('La fecha final no puede ser menor que la fecha inicial.');</script>";
}else{

  $consulta = "SELECT repo_periodo, repo_json FROM reportesws where repo_json<>'[]' and  serv_id=".$servicio_id." and tire_id=".$tipo_id." and repo_periodo between '".$periodo_inicial."' and '".$periodo_final."' order by repo_periodo";

if ($resultado = $conn->query($consulta)) {

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
 
    }

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