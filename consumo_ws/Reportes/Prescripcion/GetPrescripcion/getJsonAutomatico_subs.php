<?php

set_time_limit(9999999);
$servername = "localhost";
$database = "db_app_ambuq";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


include('../../../funciones_generales.php');
///////Declaracion de Variables Generales(Inicio)/////////
$peri_error="";
$peri_error_conteo=0;
$periodos_cargados="";
$periodos_cargados_conteo=0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "subsidiado";
$tipo_id=2;
$servicio_id = 3; // Se asigna el codigo del servicio Prescripcion
//Parametros de la api
$nit = "";
$url_bd="";
$token="";
$Webservice="";
$serv_nombre="";
$url_api_generar_token="";
///////Declaracion de Variables Generales(Fin)/////////

//obtener los parametros la url(inicio)

$consulta = "SELECT serv_url,ws_id,serv_nombre FROM servicios where serv_id=".$servicio_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
   $url_bd=$fila["serv_url"];
   $Webservice=$fila["ws_id"];
   $serv_nombre=$fila["serv_nombre"];
}
}
//obtener los parametros la url(fin)

//obtener el nit y el token(inicio)
$consulta = "SELECT tire_nit,tire_token FROM tiposreportes where tire_id=".$tipo_id;

if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
    $nit=$fila["tire_nit"];
    $token=$fila["tire_token"];
}
}
//obtener el nit y el token(fin)


$periodo_inicial ="17-01-01"; 
$periodo_final =(string)date("y-m-d",strtotime(date('y-m-d')."- 1 day")); 
//$periodo_final =   (string)date('y-m-d');


$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias=$diff->days+1;
echo "//////////////////////////////////////////////////////////////////////////////////////////////";
echo "<br> Servicio cargado: WSPRESCRIPCION-> ".$serv_nombre."-> ".$tipo_get."<br>";
echo ' dia(s) consultado(s): '.$cant_dias;

echo "<br>";
$periodo_conteo=$periodo_inicial;
echo "Periodo consultado: 20".$periodo_inicial." - 20".$periodo_final; 
echo "<br>";


for ($i = 0; $i <= $cant_dias-1; $i++) {
  $periodo_conteo = date("y-m-d",strtotime($periodo_inicial."+ ".$i." day")); 

//Codico para validar si existe el registro antes de insertarlo

$consulta = "SELECT repo_periodo, repo_json FROM reportesws  where serv_id=".$servicio_id." and tire_id=".$tipo_id." and  repo_periodo = '20".$periodo_conteo."'";
//echo "<br>Consulta json: ".$consulta."<br>";
  if ($resultado = $conn->query($consulta)) {//se valida que la consulta se ejecute correctamente
    //$número_filas = mysql_num_rows($resultado);
    //echo "Numero de filas: ".$número_filas;
    $json_anterior=false;
    while ($fila = $resultado->fetch_assoc()) {//Se valida que el periodo no existe en la tabla
      $json_anterior=true;
     }
     if($json_anterior==false){
 
     $url =$url_bd."/".$nit.'/'."20".$periodo_conteo.'/'.$token;

 

  //$url ='https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha/'.$nit.'/'.$token.'/'."20".$periodo_conteo;
  $json = Webservice_get($url);
  //$json = (string)file_get_contents($url);
  if ($json == "" || $json=='{"Message":"Error."}') {


    $peri_error= $peri_error."20".$periodo_conteo."(Error al consumir la API)<br>";
    $peri_error_conteo=$peri_error_conteo+1;
    $sql="INSERT INTO log_errores(serv_id, tire_id, logErr_periodo, log_Err_nombre, logErr_descripcion) 
    VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', 'WSPRESCRIPCION: Error al consumir la API','No se cargó ".$serv_nombre." ".$tipo_get." 20".$periodo_conteo."')";
    mysqli_query($conn, $sql);
    

/////////////////////////////////////////////////////////////////////////////////////insertar en el log de errores

  }else{
        /*Nota 1:Al remplazar los valores se debe hacer con comillas dobles, 
    ya que con commillas simples la funcion str_replace no encuentra los datos buscados*/
    $json = str_replace("\n", "", $json);//quitar \n
    $json = str_replace("\t", "", $json);//quitar \t
    $json = str_replace("\\\"", "\\\\\"", $json);//Colocrale un \ adicional a los cometarios que tengan \"
 $sql="INSERT INTO reportesws (serv_id,tire_id,repo_periodo, repo_json) VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', '".$json."');";
  if (mysqli_query($conn, $sql)) {
        $periodos_cargados=$periodos_cargados."20".$periodo_conteo."<br>";
        $periodos_cargados_conteo=$periodos_cargados_conteo+1;
        $sql="delete from log_errores where serv_id=".$servicio_id." and tire_id=".$tipo_id." and  logErr_periodo = '20".$periodo_conteo."'";
        mysqli_query($conn, $sql);
        //echo "New record created successfully<br>";
  }else{
    //echo $sql."<br>";//Identificar cual es el insert con el error 
    $peri_error= $peri_error."20".$periodo_conteo."(Error al insertar el registro)<br>";
    $peri_error_conteo=$peri_error_conteo+1;
    $sql="INSERT INTO log_errores(serv_id, tire_id, logErr_periodo, log_Err_nombre, logErr_descripcion) 
    VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', 'WSPRESCRIPCION: Error al insertar el registro','No se cargó ".$serv_nombre." ".$tipo_get." 20".$periodo_conteo."')";
    mysqli_query($conn, $sql);
/////////////////////////////////////////////////////////////////////////////////////insertar en el log de errores

  }
   //si el registro ya existe se actualiza el json en la base de datos
  /*
  else {
        $sql="UPDATE reportesws SET repo_json='".$json."' WHERE repo_periodo='20".$periodo_conteo." 00:00:00' and serv_id=".$servicio_id." and tire_id=".$tipo_id.";";
        if (mysqli_query($conn, $sql)) {

        }else{
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        
      }
  */
  
  }

}

}

}
mysqli_close($conn);
echo "<br><br>";
echo "<h3>Dias cargados</h3> <br> cantidad: ".$periodos_cargados_conteo."<br>".$periodos_cargados."<br>";
echo "<h3>Dias no cargados</h3> <br>cantidad: ".$peri_error_conteo."<br>".$peri_error;
 

?>