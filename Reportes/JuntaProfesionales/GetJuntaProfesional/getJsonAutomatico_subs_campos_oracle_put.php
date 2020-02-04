<?php

set_time_limit(9999999);
//ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');


///////Declaracion de Variables Generales(Inicio)/////////
$peri_error = "";
$peri_error_conteo = 0;
$periodos_cargados = "";
$periodos_cargados_conteo = 0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "subsidiado";
$tipo_id = 2;
$servicio_id = 9; // Se asigna el codigo del servicio Prescripcion
//Parametros de la api
$nit = "";
$url_bd = "";
$token = "";
$Webservice = "";
$serv_nombre = "";
$url_api_generar_token = "";
$json = "";
$count_report = 0;
///////Declaracion de Variables Generales(Fin)/////////

$id_pres = ""; //llave primaria de la tabla WEBSERV_PRES_PRES
$id_medi = ""; //llave primaria de la tabla WEBSERV_PRES_MEDI
$dato_vec[] = "";

/////obtener los parametros la url(inicio)
$query = "select S.URL,S.NOMBRE AS SERV_NOMBRE,TS.WS_ID from WEBSERV_SERVICIOS S JOIN WEBSERV_TIPOSERVICIOS TS ON S.TISE_ID=TS.TISE_ID WHERE S.SERV_ID=" . $servicio_id;
$st_serv = oci_parse($conn_oracle, $query);
oci_execute($st_serv, OCI_DEFAULT);
while (($row = oci_fetch_array($st_serv, OCI_BOTH)) != false) {
  // Usar nombres de columna en mayúsculas para los índices del array asociativo
  $url_bd = $row["URL"];
  $serv_nombre = $row["SERV_NOMBRE"];
  $Webservice = $row["WS_ID"];
  // $row[1];
}
oci_free_statement($st_serv);


//////obtener los parametros la url(fin)

//////obtener el nit y el token(inicio)

$query = "select NIT,TOKEN from WEBSERV_TIPOREPORTES WHERE TIRE_ID=" . $tipo_id;
$st_tire = oci_parse($conn_oracle, $query);
oci_execute($st_tire, OCI_DEFAULT);
while (($row = oci_fetch_array($st_tire, OCI_BOTH)) != false) {
  // Usar nombres de columna en mayúsculas para los índices del array asociativo
  $nit = $row["NIT"];
  $token = $row["TOKEN"];
  // $row[1];
}
oci_free_statement($st_tire);


//////obtener el nit y el token(fin)


/*
$periodo_inicial ="17-01-01"; 
$periodo_final =(string)date("y-m-d",strtotime(date('y-m-d')."- 1 day")); 
*/
//19-10-29  ---YY/mm/dd
$periodo_inicial = "17-01-01";
$periodo_final = (string) date("y-m-d", strtotime(date('y-m-d') . "- 1 day"));






//$periodo_final =   (string)date('y-m-d');


$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias = $diff->days + 1;
echo "///////////////////////////Json por periodos////////////////////////////////////////////";
echo "<br> Servicio cargado: WSPRESCRIPCION-> " . $serv_nombre . "-> " . $tipo_get . "<br>";
echo ' dia(s) consultado(s): ' . $cant_dias;

echo "<br>";
$periodo_conteo = $periodo_inicial;
echo "Periodo consultado: 20" . $periodo_inicial . " - 20" . $periodo_final;
echo "<br>";

for ($i_Principal = 0; $i_Principal <= $cant_dias - 1; $i_Principal++) {


  $periodo_conteo = date("y-m-d", strtotime($periodo_inicial . "+ " . $i_Principal . " day"));


  //Codico para validar si existe el registro antes de insertarlo


  $periodo_conteo_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato originar "y/m/d"
  $query_exist = "select count(1) CANTIDAD from WEBSERV_REPORTES_JSON where SERV_ID=" . $servicio_id . " and TIRE_ID=" . $tipo_id . " and PERIODO='" . $periodo_conteo_oracle . "'";
  //echo "<br> query_exist: ".$query_exist."<br>";
  $st_exist = oci_parse($conn_oracle, $query_exist);
  $resultado = oci_execute($st_exist, OCI_DEFAULT);
  if ($resultado) {
    $cantidad_registros = 0;
    while (($row = oci_fetch_array($st_exist, OCI_BOTH)) != false) {
      $cantidad_registros = $row["CANTIDAD"];
      // echo "<br> cantidad_registros:".$cantidad_registros."<br>";
    }
    oci_free_statement($st_exist);
    if ($cantidad_registros == 0) {




      $url = $url_bd . "/" . $nit . '/' . $token . '/' . "20" . $periodo_conteo;
     
     
     
     
      //datos a enviar
      $data = array("a" => "a");
      //url contra la que atacamos
      $ch = curl_init($url_bd);
      //a true, obtendremos una respuesta de la url, en otro caso, 
      //true si es correcto, false si no lo es
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //establecemos el verbo http que queremos utilizar para la petición
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      //enviamos el array data
      curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
      //obtenemos la respuesta
      $response = curl_exec($ch);
      // Se cierra el recurso CURL y se liberan los recursos del sistema
      curl_close($ch);
      if(!$response) {
        //  return false;
      }else{
      
      //  var_dump($response);
      }
      
       echo "<br> Response: ".$response."<br>";

      $json = (string) file_get_contents($url);
      echo "<br> json: ".$json."<br>";

    } else {
      //echo "<br>El registro ya exsiste";
    }
  }
}
echo "<br><br>----------------------------Fin-----------------------";
/*echo "<h3>Dias cargados</h3> <br> cantidad: ".$periodos_cargados_conteo."<br>".$periodos_cargados."<br>";
echo "<h3>Dias no cargados</h3> <br>cantidad: ".$peri_error_conteo."<br>".$peri_error;*/
//echo "<br> restante de la cadena: " . $json;


function obtener_dato_json($parametro_inicial, $parametro_final, $cadena, $nombre_dato)
{
  $posicion_inicial = strpos($cadena, $parametro_inicial) + strlen($parametro_inicial);
  $posicion_final = strpos($cadena, $parametro_final);
  $dato = substr($cadena, $posicion_inicial, $posicion_final - $posicion_inicial);
  //echo   "<br> " . $nombre_dato . ": " . $dato;
  //return "<br>. " . $nombre_dato . ": " . $dato . "<br>";
  return $dato;
}


function obtener_columnas_json($vector_parametros)
{
  $subCadenaPresMedi = $vector_parametros[0];
  $subcadena_inicial_a_quitar = $vector_parametros[1];
  $subcadena_final_a_quitar = $vector_parametros[2];
  $subCadenaBuscadaInicial = $vector_parametros[3];
  $subCadenaBuscadaFinal = $vector_parametros[4];

  $cadena_nombres_pres_medi = $subCadenaPresMedi;
  $cadena_nombres_pres_medi = str_replace($subcadena_inicial_a_quitar, ",", $cadena_nombres_pres_medi);

  $pos_inicial_nomb = strpos($cadena_nombres_pres_medi, $subcadena_final_a_quitar);
  $cadena_a_borrar = substr($cadena_nombres_pres_medi, $pos_inicial_nomb, strlen($cadena_nombres_pres_medi));
  $cadena_nombres_pres_medi = str_replace($cadena_a_borrar, "", $cadena_nombres_pres_medi);
  //echo "<br> dato: ".$cadena_nombres_pres_medi ."<br>";
  // echo "cadena_nombres_pres_medi: " . $cadena_nombres_pres_medi . "<br>";
  // $subCadenaBuscadaInicial   = ',"';
  $posInicial = strpos($cadena_nombres_pres_medi, $subCadenaBuscadaInicial);
  $count_vector_medi = 0;
  $count = 0;
  $vector_subCadenaPresMedi[0] = "";
  while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada
    $count = $count + 1;
    // $subCadenaBuscadaInicial   = ',"';
    //$subCadenaBuscadaFinal   = '":';
    //$cadena_nombres_pres_medi = $subCadenaPresMedi;
    $posInicial = strpos($cadena_nombres_pres_medi, $subCadenaBuscadaInicial);
    $posFinal = strpos($cadena_nombres_pres_medi, $subCadenaBuscadaFinal);
    // echo "<br> posFinal# " . $count_vector_medi . "posFinal: " . $posFinal . "<br>";
    if ($posFinal != "") {
      $subCadenaFinal = substr($cadena_nombres_pres_medi, $posInicial, $posFinal + 2 - $posInicial);
      $vector_subCadenaPresMedi[$count_vector_medi] = $subCadenaFinal;
      //Se quitan los caracteres que sobran en el nombre de los campos
      $vector_subCadenaPresMedi[$count_vector_medi] = str_replace($subCadenaBuscadaInicial, "", $vector_subCadenaPresMedi[$count_vector_medi]);
      $vector_subCadenaPresMedi[$count_vector_medi] = str_replace($subCadenaBuscadaFinal, "", $vector_subCadenaPresMedi[$count_vector_medi]);
      $count_vector_medi++;
    }

    $cadena_nombres_pres_medi = str_replace($subCadenaFinal, "", $cadena_nombres_pres_medi);
    // echo "<br> sub cadena dato# " . $count_vector_medi . "sub_cadena_dato: " . $subCadenaFinal . "<br>";
    //echo "<br> cadena dato# " . $count_vector_medi . "cadena_dato: " . $cadena_nombres_pres_medi . "<br>";
  }
  //saco el numero de elementos

  return $vector_subCadenaPresMedi;
  // echo "<br>vector_subCadenaPresMedi: " . $longitud_vector_subCadenaPresMedi;
}
