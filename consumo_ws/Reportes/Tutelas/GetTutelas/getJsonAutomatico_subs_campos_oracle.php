<?php

set_time_limit(9999999);
//ini_set('memory_limit', '-1');

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

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
include('../../../funciones_generales.php');

///////Declaracion de Variables Generales(Inicio)/////////
$peri_error = "";
$peri_error_conteo = 0;
$periodos_cargados = "";
$periodos_cargados_conteo = 0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "subsidiado";
$tipo_id = 2;
$servicio_id = 3; // Se asigna el codigo del servicio Prescripcion
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
/*$consulta = "SELECT serv_url,ws_id,serv_nombre FROM servicios where serv_id=" . $servicio_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) {
    $url_bd = $fila["serv_url"];
    $Webservice = $fila["ws_id"];
    $serv_nombre = $fila["serv_nombre"];
  }
}*/

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
/*$consulta = "SELECT tire_nit,tire_token FROM tiposreportes where tire_id=" . $tipo_id;

if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) {
    $nit = $fila["tire_nit"];
    $token = $fila["tire_token"];
  }
}

*/

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
$periodo_inicial = "20-01-01";
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
/////////////////////////////////////////////////////////////////////////////////////////////
       $url = $url_bd . "/" . $nit . '/' . "20" . $periodo_conteo . '/' . $token;
       $json = Webservice_get($url);
       //$json = (string)file_get_contents($url);
      $json = str_replace("\\\"", "", $json);
      $fecha_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato originar "y/m/d"

      if ($json == "" || $json=='{"Message":"Error."}') {


        $peri_error = $peri_error . "20" . $periodo_conteo . "(Error al insertar el registro)<br>";
        $peri_error_conteo = $peri_error_conteo + 1;
        $sql_log_err = "INSERT INTO webserv_log_errores (serv_id, tire_id,periodo, nombre, descripcion) 
        VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "', 'WSPRESCRIPCION: Error al consumir el WebService','No se cargó " . $serv_nombre . " " . $tipo_get . " 20" . $periodo_conteo . "')";
        echo $sql_log_err;

        $st_log_err = oci_parse($conn_oracle, $sql_log_err);

        $result = oci_execute($st_log_err);
        oci_free_statement($st_log_err);
        echo "<br>Insercion Incorrecta ";

        /////////////////////////////////////////////////////////////////////////////////////insertar en el log de errores

      } else if ($json == "[]") {
        echo "<br>/////////////////////// Json #" . $i_Principal . " Periodo: 20" . $periodo_conteo . "<br>";
        $sql_exc = "INSERT INTO webserv_reportes_json ( serv_id, tire_id,periodo, json) VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "', 'NO')"; //no se inserta el json porque provoca error al insertar el registro
        //$repo_json_periodo="'".$servicio_id."-".$tipo_id."-".$fecha_oracle."'";
        echo $sql_exc;

        $st = oci_parse($conn_oracle, $sql_exc);

        $result = oci_execute($st);
        oci_free_statement($st);
      } else {
        echo "<br>/////////////////////// Json #" . $i_Principal . " Periodo: 20" . $periodo_conteo . "<br>";
        /*Nota 1:Al remplazar los valores se debe hacer con comillas dobles, 
    ya que con commillas simples la funcion str_replace no encuentra los datos buscados*/
        $json = str_replace("\n", "", $json); //quitar \n
        $json = str_replace("\t", "", $json); //quitar \t
        //$json = str_replace("\\\"", "\\\\\"", $json); //Colocrale un \ adicional a los cometarios que tengan \"





        /**************************************************************************************************/
        /************(Inicio)Bloque para Insertar el json consolidado******/
        /**************************************************************************************************/
        /////////////////////////////////////////General////////////////////////////////////////////////////


        /////Insertar prescripcion (Inicio)

        $sql_exc = "INSERT INTO webserv_reportes_json ( serv_id, tire_id,periodo, json) VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "', 'SI')"; //no se inserta el json porque provoca error al insertar el registro
        //$repo_json_periodo="'".$servicio_id."-".$tipo_id."-".$fecha_oracle."'";
        echo "<br>" . $sql_exc;

        $st = oci_parse($conn_oracle, $sql_exc);

        $result = oci_execute($st);
        oci_free_statement($st);

        if ($result) {
          //echo "<br>Insercion Correcta ";
          $periodos_cargados = $periodos_cargados . "20" . $periodo_conteo . "<br>";
          $periodos_cargados_conteo = $periodos_cargados_conteo + 1;
          $sql_log_err = "delete from webserv_log_errores where serv_id=" . $servicio_id . " and tire_id=" . $tipo_id . " and  periodo = '" . $fecha_oracle . "'";

          // echo $sql_log_err;

          $st_log_err = oci_parse($conn_oracle, $sql_log_err);

          $result = oci_execute($st_log_err);
          oci_free_statement($st_log_err);
          if ($result) {
            //echo ""<br>Se elimino el error ";
          } else {
            //echo ""<br>No se elimino el error ";
          }
        } else {


          $peri_error = $peri_error . "20" . $periodo_conteo . "(Error al insertar el registro)<br>";
          $peri_error_conteo = $peri_error_conteo + 1;
          $sql_log_err = "INSERT INTO webserv_log_errores (serv_id, tire_id,periodo, nombre, descripcion) 
           VALUES (" . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "', 'WSPRESCRIPCION: Error al insertar el registro','No se cargó " . $serv_nombre . " " . $tipo_get . " 20" . $periodo_conteo . "')";
          echo $sql_log_err;

          $st_log_err = oci_parse($conn_oracle, $sql_log_err);

          $result = oci_execute($st_log_err);
          oci_free_statement($st_log_err);
          echo "<br>Insercion Incorrecta ";
        }
        /////Insertar prescripcion (Fin)


        /*
    $sql="INSERT INTO webserv_reportes_json ( serv_id, tire_id,periodo, json, fecha_actualizacion, fecha_registro) VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', '".$json."')";
    if (mysqli_query($conn, $sql)) {
          $periodos_cargados=$periodos_cargados."20".$periodo_conteo."<br>";
          $periodos_cargados_conteo=$periodos_cargados_conteo+1;
          $sql="delete from log_errores where serv_id=".$servicio_id." and tire_id=".$tipo_id." and  logErr_periodo = '20".$periodo_conteo."'";
          mysqli_query($conn, $sql);
    }else{


      $peri_error= $peri_error."20".$periodo_conteo."(Error al insertar el registro)<br>";
      $peri_error_conteo=$peri_error_conteo+1;
      $sql="INSERT INTO webserv_log_errores (serv_id, tire_id,periodo, nombre, descripcion) 
      VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', 'WSPRESCRIPCION: Error al insertar el registro','No se cargó ".$serv_nombre." ".$tipo_get." 20".$periodo_conteo."')";
      mysqli_query($conn, $sql);
  }*/
        /*
    $sql="INSERT INTO reportesws (serv_id,tire_id,repo_periodo, repo_json) VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', '".$json."');";
  if (mysqli_query($conn, $sql)) {
        $periodos_cargados=$periodos_cargados."20".$periodo_conteo."<br>";
        $periodos_cargados_conteo=$periodos_cargados_conteo+1;
        $sql="delete from log_errores where serv_id=".$servicio_id." and tire_id=".$tipo_id." and  logErr_periodo = '20".$periodo_conteo."'";
        mysqli_query($conn, $sql);
  }else{
    $peri_error= $peri_error."20".$periodo_conteo."(Error al insertar el registro)<br>";
    $peri_error_conteo=$peri_error_conteo+1;
    $sql="INSERT INTO log_errores(serv_id, tire_id, logErr_periodo, log_Err_nombre, logErr_descripcion) 
    VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', 'WSPRESCRIPCION: Error al insertar el registro','No se cargó ".$serv_nombre." ".$tipo_get." 20".$periodo_conteo."')";
    mysqli_query($conn, $sql);
    
/////////////////////////////////////insertar en el log de errores
*/



        /**************************************************************************************************/
        /************(Inicio)Bloque para separar el json en diferentes prescripciones que se retornan******/
        /**************************************************************************************************/
        /////////////////////////////////////////General////////////////////////////////////////////////////
        /*
$cadena = "abqcjhgygabcsabzc";
echo "<br> Cadena: ".$cadena;
$subCadenaBuscadaInicial   = 'cj';
echo "<br> sub Cadena Buscada Inicial: ".$subCadenaBuscadaInicial;
$subCadenaBuscadaFinal   = 'sabz';
echo "<br> sub Cadena Buscada Final: ".$subCadenaBuscadaFinal;
*/
        $subCadenaBuscadaInicial   = '{"prescripcion"';
        $cadena = $json;
        $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
        $count_report = 0;

        unset($array); //Vaciar el vector
        // Notese el uso de ===. Puesto que == simple no funcionará como se espera
        while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

          /***************************Se separan las 5 subcadenas de la prescripcion (Inicio)************************************/
          ////////(Prescripcion,Medicamentos, procedimientos, dispositivos, productosnutricionales,serviciosComplementarios)//////
          /**********************************************************************************************************************/
          ///////////////////////////////////////////Prescripcion/////////////////////////////////////////
          $subCadenaBuscadaInicial   = '{"prescripcion"';
          // echo "<br> sub Cadena Buscada Inicial: " . $subCadenaBuscadaInicial;
          $subCadenaBuscadaFinal   = ',{"prescripcion"';
          //echo "<br> sub Cadena Buscada Final: " . $subCadenaBuscadaFinal;
          $cadena = $json;

          //$posInicial = strpos('cadena completa', 'Subcadena buscada','se especifica si se buscara la primera o la segunda coinsidencia (Este ultimo parametro es mejor no usarlo porque no funciona bien)');
          //$posInicial = strpos($cadena, $subCadenaBuscadaInicial,0);
          $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
          //echo "<br> pos Inicial: " . $posInicial;
          $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
          //echo "<br> pos Final: " . $posFinal;
          if ($posFinal == "") {
            $posFinal = strlen($cadena) - 2; //Sera igual a la última posición de la cadena
            //echo "<br> pos Final no encontrado: " . $posFinal;
          }



          //$subCadenaFinal = substr($cadena, posicion Inicial,cantidad de caracteres despues de la pocicion inicial);
          $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
          // echo "<br> Sub Cadena: " . $subCadenaFinal;
          if ($subCadenaFinal != '[' && $subCadenaFinal != '') {

            $array[$count_report] = $subCadenaFinal;
            $count_report++;
          }


          ////////////////////////////////////////////medicamentos////////////////////////////////////////


          $json = str_replace($subCadenaFinal, "", $json);

          // echo "<br><h1 style='color:#FF0000'>-----------------------------------------------------------------------------------------------------------------------------------</h1>";
        }
        /**************************************************************************************************/
        /************(Inicio)Bloque para separar el json en diferentes prescripciones que se retornan******/
        /**************************************************************************************************/
        /*****Notas:
        //Nota 1: Las cadenas de búsqueda deben ser únicas en la cadena en donde se están buscando para que no hayan inconsistencias Ej: la cadena de búsqueda que esta guardada en las variables $cad_pres_busc_ini y  $cad_pres_busc_fin solo debe existir una sola vez en la cadena $cadena_presc.
         **************************************************************************************************/

        //saco el numero de elementos
        $longitud = count($array);

        //Recorro todos los elementos
        for ($i = 0; $i < $longitud; $i++) {
          //////////////////////////////////////////////////////////////////////////////////////////////////////////Prescripcion
          //Obtener cadena general de prescripcion
          $cad_pres_busc_ini = '{"prescripcion"';
          $cad_pres_busc_fin = ',"medicamentos"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresGene = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          //echo "<br><br>-------------------------------------------------------------------------prescripcion";

          if ($cadena_presc != '') {
            //echo "<br> cadena_presc: " . $cadena_presc;
            //NoPrescripcion
            $NoPrescripcion_busc_ini = '"NoPrescripcion":';
            $NoPrescripcion_busc_fin = ',"FPrescripcion"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NoPrescripcion_busc_ini) + strlen($NoPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NoPrescripcion_busc_fin);
            $noPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NoPrescripcion: " . $noPrescripcion;
            //FPrescripcion
            $FPrescripcion_busc_ini = '"FPrescripcion":';
            $FPrescripcion_busc_fin = ',"HPrescripcion"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $FPrescripcion_busc_ini) + strlen($FPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $FPrescripcion_busc_fin);
            $FPrescripcion = "'" . substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial) . "'";
            //echo "<br> FPrescripcion: " . $FPrescripcion;
            //HPrescripcion
            $HPrescripcion_busc_ini = '"HPrescripcion":';
            $HPrescripcion_busc_fin = ',"CodHabIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $HPrescripcion_busc_ini) + strlen($HPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $HPrescripcion_busc_fin);
            $HPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> HPrescripcion: " . $HPrescripcion;
            //CodHabIPS
            $CodHabIPS_busc_ini = '"CodHabIPS":';
            $CodHabIPS_busc_fin = ',"TipoIDIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodHabIPS_busc_ini) + strlen($CodHabIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodHabIPS_busc_fin);
            $CodHabIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodHabIPS: " . $CodHabIPS;
            //TipoIDIPS
            $TipoIDIPS_busc_ini = '"TipoIDIPS":';
            $TipoIDIPS_busc_fin = ',"NroIDIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDIPS_busc_ini) + strlen($TipoIDIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDIPS_busc_fin);
            $TipoIDIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDIPS: " . $TipoIDIPS;
            //NroIDIPS
            $NroIDIPS_busc_ini = '"NroIDIPS":';
            $NroIDIPS_busc_fin = ',"CodDANEMunIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDIPS_busc_ini) + strlen($NroIDIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDIPS_busc_fin);
            $NroIDIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NroIDIPS: " . $NroIDIPS;
            //CodDANEMunIPS
            $CodDANEMunIPS_busc_ini = '"CodDANEMunIPS":';
            $CodDANEMunIPS_busc_fin = ',"DirSedeIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDANEMunIPS_busc_ini) + strlen($CodDANEMunIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDANEMunIPS_busc_fin);
            $CodDANEMunIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodDANEMunIPS: " . $CodDANEMunIPS;
            //DirSedeIPS
            $DirSedeIPS_busc_ini = '"DirSedeIPS":';
            $DirSedeIPS_busc_fin = ',"TelSedeIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $DirSedeIPS_busc_ini) + strlen($DirSedeIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $DirSedeIPS_busc_fin);
            $DirSedeIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> DirSedeIPS: " . $DirSedeIPS;
            //TelSedeIPS
            $TelSedeIPS_busc_ini = '"TelSedeIPS":';
            $TelSedeIPS_busc_fin = ',"TipoIDProf"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TelSedeIPS_busc_ini) + strlen($TelSedeIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TelSedeIPS_busc_fin);
            $TelSedeIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TelSedeIPS: " . $TelSedeIPS;
            //TipoIDProf
            $TipoIDProf_busc_ini = '"TipoIDProf":';
            $TipoIDProf_busc_fin = ',"NumIDProf"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDProf_busc_ini) + strlen($TipoIDProf_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDProf_busc_fin);
            $TipoIDProf = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDProf: " . $TipoIDProf;
            //NumIDProf
            $NumIDProf_busc_ini = '"NumIDProf":';
            $NumIDProf_busc_fin = ',"PNProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NumIDProf_busc_ini) + strlen($NumIDProf_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NumIDProf_busc_fin);
            $NumIDProf = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NumIDProf: " . $NumIDProf;
            //PNProfS
            $PNProfS_busc_ini = '"PNProfS":';
            $PNProfS_busc_fin = ',"SNProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PNProfS_busc_ini) + strlen($PNProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PNProfS_busc_fin);
            $PNProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> PNProfS: " . $PNProfS;
            //SNProfS
            $SNProfS_busc_ini = '"SNProfS":';
            $SNProfS_busc_fin = ',"PAProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SNProfS_busc_ini) + strlen($SNProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SNProfS_busc_fin);
            $SNProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> SNProfS: " . $SNProfS;
            //PAProfS
            $PAProfS_busc_ini = '"PAProfS":';
            $PAProfS_busc_fin = ',"SAProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PAProfS_busc_ini) + strlen($PAProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PAProfS_busc_fin);
            $PAProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> PAProfS: " . $PAProfS;
            //SAProfS
            $SAProfS_busc_ini = '"SAProfS":';
            $SAProfS_busc_fin = ',"RegProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SAProfS_busc_ini) + strlen($SAProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SAProfS_busc_fin);
            $SAProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> SAProfS: " . $SAProfS;
            //RegProfS
            $RegProfS_busc_ini = '"RegProfS":';
            $RegProfS_busc_fin = ',"TipoIDPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $RegProfS_busc_ini) + strlen($RegProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $RegProfS_busc_fin);
            $RegProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> RegProfS: " . $RegProfS;
            //TipoIDPaciente
            $TipoIDPaciente_busc_ini = '"TipoIDPaciente":';
            $TipoIDPaciente_busc_fin = ',"NroIDPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDPaciente_busc_ini) + strlen($TipoIDPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDPaciente_busc_fin);
            $TipoIDPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDPaciente: " . $TipoIDPaciente;
            //NroIDPaciente
            $NroIDPaciente_busc_ini = '"NroIDPaciente":';
            $NroIDPaciente_busc_fin = ',"PNPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDPaciente_busc_ini) + strlen($NroIDPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDPaciente_busc_fin);
            $NroIDPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NroIDPaciente: " . $NroIDPaciente;
            //PNPaciente
            $PNPaciente_busc_ini = '"PNPaciente":';
            $PNPaciente_busc_fin = ',"SNPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PNPaciente_busc_ini) + strlen($PNPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PNPaciente_busc_fin);
            $PNPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> PNPaciente: " . $PNPaciente;
            //SNPaciente
            $SNPaciente_busc_ini = '"SNPaciente":';
            $SNPaciente_busc_fin = ',"PAPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SNPaciente_busc_ini) + strlen($SNPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SNPaciente_busc_fin);
            $SNPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> SNPaciente: " . $SNPaciente;
            //PAPaciente
            $PAPaciente_busc_ini = '"PAPaciente":';
            $PAPaciente_busc_fin = ',"SAPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PAPaciente_busc_ini) + strlen($PAPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PAPaciente_busc_fin);
            $PAPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> PAPaciente: " . $PAPaciente;
            //SAPaciente
            $SAPaciente_busc_ini = '"SAPaciente":';
            $SAPaciente_busc_fin = ',"CodAmbAte"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SAPaciente_busc_ini) + strlen($SAPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SAPaciente_busc_fin);
            $SAPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> SAPaciente: " . $SAPaciente;
            //CodAmbAte
            $CodAmbAte_busc_ini = '"CodAmbAte":';
            $CodAmbAte_busc_fin = ',"RefAmbAte"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodAmbAte_busc_ini) + strlen($CodAmbAte_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodAmbAte_busc_fin);
            $CodAmbAte = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodAmbAte: " . $CodAmbAte;
            //RefAmbAte
            $RefAmbAte_busc_ini = '"RefAmbAte":';
            $RefAmbAte_busc_fin = ',"EnfHuerfana"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $RefAmbAte_busc_ini) + strlen($RefAmbAte_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $RefAmbAte_busc_fin);
            $RefAmbAte = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> RefAmbAte: " . $RefAmbAte;
            //EnfHuerfana
            $EnfHuerfana_busc_ini = '"EnfHuerfana":';
            $EnfHuerfana_busc_fin = ',"CodEnfHuerfana"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $EnfHuerfana_busc_ini) + strlen($EnfHuerfana_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $EnfHuerfana_busc_fin);
            $EnfHuerfana = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> EnfHuerfana: " . $EnfHuerfana;
            //CodEnfHuerfana
            $CodEnfHuerfana_busc_ini = '"CodEnfHuerfana":';
            $CodEnfHuerfana_busc_fin = ',"EnfHuerfanaDX"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodEnfHuerfana_busc_ini) + strlen($CodEnfHuerfana_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodEnfHuerfana_busc_fin);
            $CodEnfHuerfana = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodEnfHuerfana: " . $CodEnfHuerfana;
            //EnfHuerfanaDX
            $EnfHuerfanaDX_busc_ini = '"EnfHuerfanaDX":';
            $EnfHuerfanaDX_busc_fin = ',"CodDxPpal"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $EnfHuerfanaDX_busc_ini) + strlen($EnfHuerfanaDX_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $EnfHuerfanaDX_busc_fin);
            $EnfHuerfanaDX = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> EnfHuerfanaDX: " . $EnfHuerfanaDX;
            //CodDxPpal
            $CodDxPpal_busc_ini = '"CodDxPpal":';
            $CodDxPpal_busc_fin = ',"CodDxRel1"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDxPpal_busc_ini) + strlen($CodDxPpal_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDxPpal_busc_fin);
            $CodDxPpal = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodDxPpal: " . $CodDxPpal;
            //CodDxRel1
            $CodDxRel1_busc_ini = '"CodDxRel1":';
            $CodDxRel1_busc_fin = ',"CodDxRel2"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDxRel1_busc_ini) + strlen($CodDxRel1_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDxRel1_busc_fin);
            $CodDxRel1 = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodDxRel1: " . $CodDxRel1;
            //CodDxRel2
            $CodDxRel2_busc_ini = '"CodDxRel2":';
            $CodDxRel2_busc_fin = ',"SopNutricional"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDxRel2_busc_ini) + strlen($CodDxRel2_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDxRel2_busc_fin);
            $CodDxRel2 = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodDxRel2: " . $CodDxRel2;
            //SopNutricional
            $SopNutricional_busc_ini = '"SopNutricional":';
            $SopNutricional_busc_fin = ',"CodEPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SopNutricional_busc_ini) + strlen($SopNutricional_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SopNutricional_busc_fin);
            $SopNutricional = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> SopNutricional: " . $SopNutricional;
            //CodEPS
            $CodEPS_busc_ini = '"CodEPS":';
            $CodEPS_busc_fin = ',"TipoIDMadrePaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodEPS_busc_ini) + strlen($CodEPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodEPS_busc_fin);
            $CodEPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodEPS: " . $CodEPS;
            //TipoIDMadrePaciente
            $TipoIDMadrePaciente_busc_ini = '"TipoIDMadrePaciente":';
            $TipoIDMadrePaciente_busc_fin = ',"NroIDMadrePaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDMadrePaciente_busc_ini) + strlen($TipoIDMadrePaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDMadrePaciente_busc_fin);
            $TipoIDMadrePaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDMadrePaciente: " . $TipoIDMadrePaciente;
            //NroIDMadrePaciente
            $NroIDMadrePaciente_busc_ini = '"NroIDMadrePaciente":';
            $NroIDMadrePaciente_busc_fin = ',"TipoTransc"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDMadrePaciente_busc_ini) + strlen($NroIDMadrePaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDMadrePaciente_busc_fin);
            $NroIDMadrePaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NroIDMadrePaciente: " . $NroIDMadrePaciente;
            //TipoTransc
            $TipoTransc_busc_ini = '"TipoTransc":';
            $TipoTransc_busc_fin = ',"TipoIDDonanteVivo"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoTransc_busc_ini) + strlen($TipoTransc_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoTransc_busc_fin);
            $TipoTransc = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoTransc: " . $TipoTransc;
            //TipoIDDonanteVivo
            $TipoIDDonanteVivo_busc_ini = '"TipoIDDonanteVivo":';
            $TipoIDDonanteVivo_busc_fin = ',"NroIDDonanteVivo"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDDonanteVivo_busc_ini) + strlen($TipoIDDonanteVivo_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDDonanteVivo_busc_fin);
            $TipoIDDonanteVivo = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDDonanteVivo: " . $TipoIDDonanteVivo;
            //NroIDDonanteVivo
            $NroIDDonanteVivo_busc_ini = '"NroIDDonanteVivo":';
            $NroIDDonanteVivo_busc_fin = ',"EstPres"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDDonanteVivo_busc_ini) + strlen($NroIDDonanteVivo_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDDonanteVivo_busc_fin);
            $NroIDDonanteVivo = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NroIDDonanteVivo: " . $NroIDDonanteVivo;
            //EstPres
            $EstPres_busc_ini = '"EstPres":';
            $EstPres_busc_fin = '}';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $EstPres_busc_ini) + strlen($EstPres_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $EstPres_busc_fin);
            $EstPres = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> EstPres: " . $EstPres;


            ////ID_PRES
            $sql_id_pres = "select SEQ_WEBSERV_PRES_PRES.nextval as ID_PRES from dual";
            $st_id_pres = oci_parse($conn_oracle, $sql_id_pres);
            oci_execute($st_id_pres, OCI_DEFAULT);
            while (($row = oci_fetch_array($st_id_pres, OCI_BOTH)) != false) {
              $id_pres = $row[0];
              //echo "<br>: id_pres_ver" . $id_pres;
            }
            oci_free_statement($st_id_pres);

            //oci_bind_by_name($st, ":id_pres", $id_pres);

            //$NoPrescripcion;
            $noPrescripcion = str_replace('"', "'", $noPrescripcion);
            /* if ($noPrescripcion == "null") {
              $noPrescripcion = '';
            }*/
            //oci_bind_by_name($st, ":noPrescripcion", $noPrescripcion);

            //$FPrescripcion;
            $posIniFPrescripcion = 2;
            $posFinFPrescripcion = strpos($FPrescripcion, "T") - 2;
            $FPrescripcion = substr($FPrescripcion, $posIniFPrescripcion, $posFinFPrescripcion);
            //echo "<br>: FPrescripcion: " . $FPrescripcion;
            $FPrescripcion = "'" . date("d/m/Y", strtotime($FPrescripcion)) . "'"; //formato originar "y/m/d"
            //echo "<br>: FPrescripcion: " . $FPrescripcion . "<br>";
            //oci_bind_by_name($st, ":FPrescripcion", $FPrescripcion);

            //$HPrescripcion;
            $HPrescripcion = str_replace('"', "'", $HPrescripcion);
            /* if ($HPrescripcion == "null") {
              $HPrescripcion = '';
            }*/
            //oci_bind_by_name($st, ":HPrescripcion", $HPrescripcion);

            //$CodHabIPS;
            $CodHabIPS = str_replace('"', "'", $CodHabIPS);
            /* if ($CodHabIPS == "null") {
              $CodHabIPS = '';
            }*/
            //oci_bind_by_name($st, ":CodHabIPS", $CodHabIPS);

            // $TipoIDIPS;
            $TipoIDIPS = str_replace('"', "'", $TipoIDIPS);
            /* if ($TipoIDIPS == "null") {
              $TipoIDIPS = '';
            }*/
            //oci_bind_by_name($st, ":TipoIDIPS", $TipoIDIPS);

            //$NroIDIPS;
            $NroIDIPS = str_replace('"', "'", $NroIDIPS);
            /* if ($NroIDIPS == "null") {
              $NroIDIPS = '';
            }*/
            //oci_bind_by_name($st, ":NroIDIPS", $NroIDIPS);

            //$CodDANEMunIPS;
            $CodDANEMunIPS = str_replace('"', "'", $CodDANEMunIPS);
            /* if ($CodDANEMunIPS == "null") {
              $CodDANEMunIPS = '';
            }*/
            //oci_bind_by_name($st, ":CodDANEMunIPS", $CodDANEMunIPS);

            //$DirSedeIPS;
            $DirSedeIPS = str_replace('"', "'", $DirSedeIPS);
            /* if ($DirSedeIPS == "null") {
              $DirSedeIPS = '';
            }*/
            //oci_bind_by_name($st, ":DirSedeIPS", $DirSedeIPS);

            //$TelSedeIPS;
            $TelSedeIPS = str_replace('"', "'", $TelSedeIPS);
            $TipoIDProf = str_replace('"', "'", $TipoIDProf);
            /* if ($TelSedeIPS == "null") {
              $TelSedeIPS = '';
            }*/
            //oci_bind_by_name($st, ":TelSedeIPS", $TelSedeIPS);

            //$TipoIDProf;
            $TipoIDProf = str_replace('"', "'", $TipoIDProf);
            /* if ($TipoIDProf == "null") {
              $TipoIDProf = '';
            }*/
            //oci_bind_by_name($st, ":TipoIDProf", $TipoIDProf);

            //$NumIDProf;
            $NumIDProf = str_replace('"', "'", $NumIDProf);
            /* if ($NumIDProf == "null") {
              $NumIDProf = '';
            }*/
            //oci_bind_by_name($st, ":NumIDProf", $NumIDProf);

            //$PNProfS;
            $PNProfS = str_replace('"', "'", $PNProfS);
            /* if ($PNProfS == "null") {
              $PNProfS = '';
            }*/
            //oci_bind_by_name($st, ":PNProfS", $PNProfS);

            //$SNProfS;
            $SNProfS = str_replace('"', "'", $SNProfS);
            /* if ($PAProfS == "null") {
              $PAProfS = '';
            }*/
            //oci_bind_by_name($st, ":SNProfS", $SNProfS);

            //$PAProfS;
            $PAProfS = str_replace('"', "'", $PAProfS);
            /* if ($PAProfS == "null") {
              $PAProfS = '';
            }*/
            //oci_bind_by_name($st, ":PAProfS", $PAProfS);

            //$SAProfS;
            $SAProfS = str_replace('"', "'", $SAProfS);
            /* if ($SAProfS == "null") {
              $SAProfS = '';
            }*/
            //oci_bind_by_name($st, ":SAProfS", $SAProfS);

            //$RegProfS;
            $RegProfS = str_replace('"', "'", $RegProfS);
            /* if ($RegProfS == "null") {
              $RegProfS = '';
            }*/
            //oci_bind_by_name($st, ":RegProfS", $RegProfS);

            //$TipoIDPaciente;
            $TipoIDPaciente = str_replace('"', "'", $TipoIDPaciente);
            /* if ($TipoIDPaciente == "null") {
              $TipoIDPaciente = '';
            }*/
            //oci_bind_by_name($st, ":TipoIDPaciente", $TipoIDPaciente);

            //$NroIDPaciente;
            $NroIDPaciente = str_replace('"', "'", $NroIDPaciente);
            /* if ($NroIDPaciente == "null") {
              $NroIDPaciente = '';
            }*/
            //oci_bind_by_name($st, ":NroIDPaciente", $NroIDPaciente);

            //$PNPaciente;
            $PNPaciente = str_replace('"', "'", $PNPaciente);
            /* if ($PNPaciente == "null") {
              $PNPaciente = '';
            }*/
            //oci_bind_by_name($st, ":PNPaciente", $PNPaciente);

            //$SNPaciente;
            $SNPaciente = str_replace('"', "'", $SNPaciente);
            /* if ($SNPaciente == "null") {
              $SNPaciente = '';
            }*/
            //oci_bind_by_name($st, ":SNPaciente", $SNPaciente);

            //$PAPaciente;
            $PAPaciente = str_replace('"', "'", $PAPaciente);
            /* if ($PAPaciente == "null") {
              $PAPaciente = '';
            }*/
            //oci_bind_by_name($st, ":PAPaciente", $PAPaciente);

            //$SAPaciente;
            $SAPaciente = str_replace('"', "'", $SAPaciente);
            /* if ($SAPaciente == "null") {
              $SAPaciente = '';
            }*/
            //oci_bind_by_name($st, ":SAPaciente", $SAPaciente);

            //$CodAmbAte;
            $CodAmbAte = str_replace('"', "'", $CodAmbAte);
            /* if ($CodAmbAte == "null") {
              $CodAmbAte = '';
            }*/
            //oci_bind_by_name($st, ":CodAmbAte", $CodAmbAte);

            //$RefAmbAte;
            $RefAmbAte = str_replace('"', "'", $RefAmbAte);
            /* if ($RefAmbAte == "null") {
              $RefAmbAte = '';
            }*/
            //oci_bind_by_name($st, ":RefAmbAte", $RefAmbAte);

            //$EnfHuerfana;
            $EnfHuerfana = str_replace('"', "'", $EnfHuerfana);
            /* if ($EnfHuerfana == "null") {
              $EnfHuerfana = '';
            }*/
            //oci_bind_by_name($st, ":EnfHuerfana", $EnfHuerfana);

            //$CodEnfHuerfana;
            $CodEnfHuerfana = str_replace('"', "'", $CodEnfHuerfana);
            /* if ($CodEnfHuerfana == "null") {
              $CodEnfHuerfana = '';
            }*/
            //oci_bind_by_name($st, ":CodEnfHuerfana", $CodEnfHuerfana);

            //$EnfHuerfanaDX;
            $EnfHuerfanaDX = str_replace('"', "'", $EnfHuerfanaDX);
            /* if ($EnfHuerfanaDX == "null") {
              $EnfHuerfanaDX = '';
            }*/
            //oci_bind_by_name($st, ":EnfHuerfanaDX", $EnfHuerfanaDX);

            //$CodDxPpal;
            $CodDxPpal = str_replace('"', "'", $CodDxPpal);
            /* if ($CodDxPpal == "null") {
              $CodDxPpal = '';
            }*/
            //oci_bind_by_name($st, ":CodDxPpal", $CodDxPpal);

            //$CodDxRel1;
            $CodDxRel1 = str_replace('"', "'", $CodDxRel1);
            /* if ($CodDxRel1 == "null") {
              $CodDxRel1 = '';
            }*/
            //oci_bind_by_name($st, ":CodDxRel1", $CodDxRel1);

            //$CodDxRel2;
            $CodDxRel2 = str_replace('"', "'", $CodDxRel2);
            /* if ($CodDxRel2 == "null") {
              $CodDxRel2 = '';
            }*/
            //oci_bind_by_name($st, ":CodDxRel2", $CodDxRel2);

            //$SopNutricional;
            $SopNutricional = str_replace('"', "'", $SopNutricional);
            /* if ($SopNutricional == "null") {
              $SopNutricional = '';
            }*/
            //oci_bind_by_name($st, ":SopNutricional", $SopNutricional);

            //$CodEPS;
            $CodEPS = str_replace('"', "'", $CodEPS);
            /* if ($CodEPS == "null") {
              $CodEPS = '';
            }*/
            //oci_bind_by_name($st, ":CodEPS", $CodEPS);

            //$TipoIDMadrePaciente;
            $TipoIDMadrePaciente = str_replace('"', "'", $TipoIDMadrePaciente);
            /* if ($TipoIDMadrePaciente == "null") {
              $TipoIDMadrePaciente = '';
            }*/
            //oci_bind_by_name($st, ":TipoIDMadrePaciente", $TipoIDMadrePaciente);

            //$NroIDMadrePaciente;
            $NroIDMadrePaciente = str_replace('"', "'", $NroIDMadrePaciente);
            /* if ($NroIDMadrePaciente == "null") {
              $NroIDMadrePaciente = '';
            }*/
            //oci_bind_by_name($st, ":NroIDMadrePaciente", $NroIDMadrePaciente);

            //$TipoTransc;
            $TipoTransc = str_replace('"', "'", $TipoTransc);
            /* if ($TipoTransc == "null") {
              $TipoTransc = '';
            }*/
            //oci_bind_by_name($st, ":TipoTransc", $TipoTransc);

            //$TipoIDDonanteVivo;
            $TipoIDDonanteVivo = str_replace('"', "'", $TipoIDDonanteVivo);
            /* if ($TipoIDDonanteVivo == "null") {
              $TipoIDDonanteVivo = '';
            }*/
            //oci_bind_by_name($st, ":TipoIDDonanteVivo", $TipoIDDonanteVivo);

            //$NroIDDonanteVivo;
            $NroIDDonanteVivo = str_replace('"', "'", $NroIDDonanteVivo);
            /* if ($NroIDDonanteVivo == "null") {
              $NroIDDonanteVivo = '';
            }*/
            ////echo "<br> NroIDDonanteVivo: " . $NroIDDonanteVivo;
            //oci_bind_by_name($st, ":NroIDDonanteVivo", $NroIDDonanteVivo);

            //$EstPres;
            $EstPres = str_replace('"', "'", $EstPres);
            /* if ($EstPres == "null") {
              $EstPres = '';
            }*/
            //oci_bind_by_name($st, ":EstPres", $EstPres);



            /////Insertar prescripcion (Inicio)
            $sql_exc = "INSERT INTO WEBSERV_PRES_PRES 
            (ID_PRES,REPO_SERV_ID, REPO_TIRE_ID, REPO_PERIODO,NOPRESCRIPCION, FPRESCRIPCION,HPRESCRIPCION,CODHABIPS,TIPOIDIPS,NROIDIPS,CODDANEMUNIPS,DIRSEDEIPS,TELSEDEIPS,TIPOIDPROF,NUMIDPROF,PNPROFS,SNPROFS,PAPROFS,SAPROFS,REGPROFS,TIPOIDPACIENTE,NROIDPACIENTE,PNPACIENTE,SNPACIENTE,PAPACIENTE,SAPACIENTE,CODAMBATE,REFAMBATE,ENFHUERFANA,CODENFHUERFANA,ENFHUERFANADX,CODDXPPAL,CODDXREL1,CODDXREL2,SOPNUTRICIONAL,CODEPS,TIPOIDMADREPACIENTE,NROIDMADREPACIENTE,TIPOTRANSC,TIPOIDDONANTEVIVO,NROIDDONANTEVIVO,ESTPRES
          )  VALUES (" . $id_pres . "," . $servicio_id . "," . $tipo_id . ",'" . $fecha_oracle . "'," . $noPrescripcion . "," . $FPrescripcion . "," . $HPrescripcion . "," . $CodHabIPS . "," . $TipoIDIPS . "," . $NroIDIPS . "," . $CodDANEMunIPS . "," . $DirSedeIPS . "," . $TelSedeIPS . "," . $TipoIDProf . "," . $NumIDProf . "," . $PNProfS . "," . $SNProfS . "," . $PAProfS . "," . $SAProfS . "," . $RegProfS . "," . $TipoIDPaciente . "," . $NroIDPaciente . "," . $PNPaciente . "," . $SNPaciente . "," . $PAPaciente . "," . $SAPaciente . "," . $CodAmbAte . "," . $RefAmbAte . "," . $EnfHuerfana . "," . $CodEnfHuerfana . "," . $EnfHuerfanaDX . "," . $CodDxPpal . "," . $CodDxRel1 . "," . $CodDxRel2 . "," . $SopNutricional . "," . $CodEPS . "," . $TipoIDMadrePaciente . "," . $NroIDMadrePaciente . "," . $TipoTransc . "," . $TipoIDDonanteVivo . "," . $NroIDDonanteVivo . "," . $EstPres . ")";

            /*   $sql_exc = "INSERT INTO WEBSERV_PRES_PRES 
            (ID_PRES,NOPRESCRIPCION, FPRESCRIPCION,HPRESCRIPCION,CODHABIPS,TIPOIDIPS,NROIDIPS,CODDANEMUNIPS,DIRSEDEIPS,TELSEDEIPS,TIPOIDPROF,NUMIDPROF,PNPROFS,SNPROFS,PAPROFS,SAPROFS,REGPROFS,TIPOIDPACIENTE,NROIDPACIENTE,PNPACIENTE,SNPACIENTE,PAPACIENTE,SAPACIENTE,CODAMBATE,REFAMBATE,ENFHUERFANA,CODENFHUERFANA,ENFHUERFANADX,CODDXPPAL,CODDXREL1,CODDXREL2,SOPNUTRICIONAL,CODEPS,TIPOIDMADREPACIENTE,NROIDMADREPACIENTE,TIPOTRANSC,TIPOIDDONANTEVIVO,NROIDDONANTEVIVO,ESTPRES
          )  VALUES (SEQ_WEBSERV_PRES_PRES.nextval,:noPrescripcion,:FPrescripcion, :HPrescripcion, :CodHabIPS,:TipoIDIPS, :NroIDIPS, :CodDANEMunIPS, :DirSedeIPS, :TelSedeIPS, :TipoIDProf, :NumIDProf, :PNProfS, :SNProfS, :PAProfS, :SAProfS, :RegProfS, :TipoIDPaciente, :NroIDPaciente, :PNPaciente, :SNPaciente, :PAPaciente, :SAPaciente,:CodAmbAte, :RefAmbAte,:EnfHuerfana, :CodEnfHuerfana, :EnfHuerfanaDX, :CodDxPpal, :CodDxRel1, :CodDxRel2, :SopNutricional, :CodEPS, :TipoIDMadrePaciente, :NroIDMadrePaciente, :TipoTransc, :TipoIDDonanteVivo, :NroIDDonanteVivo,:EstPres)";*/

            /*
          $sql_exc = "INSERT INTO WEBSERV_PRES_PRES 
          (ID_PRES,NOPRESCRIPCION, FPRESCRIPCION,HPRESCRIPCION,CODHABIPS,TIPOIDIPS,NROIDIPS,CODDANEMUNIPS,DIRSEDEIPS,TELSEDEIPS,TIPOIDPROF,NUMIDPROF,PNPROFS,SNPROFS,PAPROFS,SAPROFS,REGPROFS,TIPOIDPACIENTE,NROIDPACIENTE,PNPACIENTE,SNPACIENTE,PAPACIENTE,SAPACIENTE,CODAMBATE,REFAMBATE,ENFHUERFANA,CODENFHUERFANA,ENFHUERFANADX,CODDXPPAL,CODDXREL1,CODDXREL2,SOPNUTRICIONAL,CODEPS,TIPOIDMADREPACIENTE,NROIDMADREPACIENTE,TIPOTRANSC,TIPOIDDONANTEVIVO,NROIDDONANTEVIVO,ESTPRES
         )  VALUES (SEQ_WEBSERV_PRES_PRES.nextval,concat('201941283780012888',SEQ_WEBSERV_PRES_PRES.nextval),:FPRESCRIPCION, :HPRESCRIPCION, '080010003701', 'NI', '890102768', '08001', 'CARRERA 48 # 70-38', '3091999', 'CC', '8755608', 'SAUL', 'ALFREDO', 'CHRISTIANSEN', 'MARTELO', '1572', 'CC', '3754775', 'HERNANDO', '', 'ESTRADA', 'GOMEZ',22, null,0, null, null, 'R579', null, null, null, 'ESS076', null, null, null, null, null,4)";
             */

            //echo $sql_exc;

            $st = oci_parse($conn_oracle, $sql_exc);

            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              //echo  "<br>Insercion Correcta ";
            } else {
              //echo  "<br>Insercion Incorrecta ";
            }
            /////Insertar prescripcion (Fin)

          }

          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          //echo  "<br><br>-------------------------------------------------------------------------medicamentos";
          //Obtener cadena general de prescripcion
          $cad_pres_busc_ini = '},"medicamentos"';
          $cad_pres_busc_fin = ',"procedimientos"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresMedi = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          ////echo "<br> subCadenaPresMedi: " . $subCadenaPresMedi . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los medicamentos////////////////

          $cadenaMedi = $subCadenaPresMedi;
          $cadenaMedi = str_replace('},"medicamentos":[', 'inicio_cad_medi', $cadenaMedi);
          $cadenaMedi = str_replace(']},{"ConOrden"', ']},inicio_cad_medi{"ConOrden"', $cadenaMedi);
          $subcadenaMediBuscadaInicial   = 'inicio_cad_medi{"ConOrden"';
          ////echo "<br> cadenaMedi: " . $cadenaMedi . "<br>";
          $posInicial = strpos($cadenaMedi, $subcadenaMediBuscadaInicial);
          $count_report = 0;

          $vector_medicamentos[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////Se separan Cada uno de los medicamentos (Inicio)/////////////////
            ///////////////////////////////////////////Prescripcion/////////////////////////////////////////

            $subcadenaMediBuscadaInicial   = 'inicio_cad_medi{"ConOrden"';
            // //echo "<br> sub cadenaMedi Buscada Inicial: " . $subcadenaMediBuscadaInicial;
            $subcadenaMediBuscadaFinal   = ']}';
            ////echo "<br> sub cadenaMedi Buscada Final: " . $subcadenaMediBuscadaFinal;
            $posInicial = strpos($cadenaMedi, $subcadenaMediBuscadaInicial);
            // //echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaMedi, $subcadenaMediBuscadaFinal) + 2;
            ////echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaMedi) - 2; //Sera igual a la última posición de la cadenaMedi
              // //echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaMediFinal = substr($cadenaMedi, $posInicial, $posFinal - $posInicial + 1);
            ////echo "<br> Sub cadenaMedi: " . $subcadenaMediFinal;
            if ($subcadenaMediFinal != '[' && $subcadenaMediFinal != '') {
              $vector_medicamentos[$count_report] = str_replace('inicio_cad_medi', '},"medicamentos":[', $subcadenaMediFinal);
              $count_report++;
            }
            $cadenaMedi = str_replace($subcadenaMediFinal, "", $cadenaMedi);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los medicamentos(Inicio)********************************/
          $longitud_vec_medi = count($vector_medicamentos);
          for ($count_vec_medi = 0; $count_vec_medi < $longitud_vec_medi; $count_vec_medi++) {
            //echo "<br><br>-------------------------------------medicamento# " . ($count_vec_medi + 1);
            ////echo "<br> Cadena#" . $count_vec_medi . ": " . $vector_medicamentos[$count_vec_medi];
            $subCadenaPresMedi = $vector_medicamentos[$count_vec_medi];

            /*         
           
            //Guardar en un vector cada una de las variables de la cadena de medicamentos
            $vector_parametros[0] = $subCadenaPresMedi;
            $vector_parametros[1] = '},"medicamentos":[{'; //$subcadena_inicial_a_quitar;
            $vector_parametros[2] = ',"PrincipiosActivos"'; //$subcadena_final_a_quitar;
            $vector_parametros[3] = ',"'; //$subcadena_buscada_inicial;
            $vector_parametros[4] = '":'; //$subcadena_buscada_final;
            $vector_subCadenaPresMedi = obtener_columnas_json($vector_parametros);
            $longitud_vector_subCadenaPresMedi = count($vector_subCadenaPresMedi);
*/
            //obtener los datos de cada una de las variables del json
            //echo "<br> subCadenaPresMedi: " . $subCadenaPresMedi."<br>";
            if ($subCadenaPresMedi !== '},"medicamentos":[]' && $subCadenaPresMedi !== '') {
              ////ID_MEDI
              $sql_id_medi = "select SEQ_WEBSERV_PRES_MEDI.nextval as ID_MEDI from dual";
              $st_id_medi = oci_parse($conn_oracle, $sql_id_medi);
              oci_execute($st_id_medi, OCI_DEFAULT);
              while (($row = oci_fetch_array($st_id_medi, OCI_BOTH)) != false) {
                $id_medi = $row[0];
                //$id_medi = $row['ID_MEDI'];
                // //echo "<br>: id_medi_ver" . $id_medi;
              }
              oci_free_statement($st_id_medi);





              //CONORDEN
              $CONORDEN_busc_ini = '"ConOrden":';
              $CONORDEN_busc_fin = ',"TipoMed"';
              $cadena_CONORDEN = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CONORDEN, $CONORDEN_busc_ini) + strlen($CONORDEN_busc_ini);
              $posPresFinal = strpos($cadena_CONORDEN, $CONORDEN_busc_fin);
              $CONORDEN = substr($cadena_CONORDEN, $posPresInicial, $posPresFinal - $posPresInicial);
              $CONORDEN = str_replace('"', "'", $CONORDEN);
              //echo "<br> CONORDEN: " . $CONORDEN;

              //TIPOMED
              $TIPOMED_busc_ini = '"TipoMed":';
              $TIPOMED_busc_fin = ',"TipoPrest"';
              $cadena_TIPOMED = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_TIPOMED, $TIPOMED_busc_ini) + strlen($TIPOMED_busc_ini);
              $posPresFinal = strpos($cadena_TIPOMED, $TIPOMED_busc_fin);
              $TIPOMED = substr($cadena_TIPOMED, $posPresInicial, $posPresFinal - $posPresInicial);
              $TIPOMED = str_replace('"', "'", $TIPOMED);
              //echo "<br> TIPOMED: " . $TIPOMED;


              //TIPOPREST
              $TIPOPREST_busc_ini = '"TipoPrest":';
              $TIPOPREST_busc_fin = ',"CausaS1"';
              $cadena_TIPOPREST = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_TIPOPREST, $TIPOPREST_busc_ini) + strlen($TIPOPREST_busc_ini);
              $posPresFinal = strpos($cadena_TIPOPREST, $TIPOPREST_busc_fin);
              $TIPOPREST = substr($cadena_TIPOPREST, $posPresInicial, $posPresFinal - $posPresInicial);
              $TIPOPREST = str_replace('"', "'", $TIPOPREST);
              //echo "<br> TIPOPREST: " . $TIPOPREST;

              //CAUSAS1
              $CAUSAS1_busc_ini = '"CausaS1":';
              $CAUSAS1_busc_fin = ',"CausaS2"';
              $cadena_CAUSAS1 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CAUSAS1, $CAUSAS1_busc_ini) + strlen($CAUSAS1_busc_ini);
              $posPresFinal = strpos($cadena_CAUSAS1, $CAUSAS1_busc_fin);
              $CAUSAS1 = substr($cadena_CAUSAS1, $posPresInicial, $posPresFinal - $posPresInicial);
              $CAUSAS1 = str_replace('"', "'", $CAUSAS1);
              //echo "<br> CAUSAS1: " . $CAUSAS1;

              //CAUSAS2
              $CAUSAS2_busc_ini = '"CausaS2":';
              $CAUSAS2_busc_fin = ',"CausaS3"';
              $cadena_CAUSAS2 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CAUSAS2, $CAUSAS2_busc_ini) + strlen($CAUSAS2_busc_ini);
              $posPresFinal = strpos($cadena_CAUSAS2, $CAUSAS2_busc_fin);
              $CAUSAS2 = substr($cadena_CAUSAS2, $posPresInicial, $posPresFinal - $posPresInicial);
              $CAUSAS2 = str_replace('"', "'", $CAUSAS2);
              //echo "<br> CAUSAS2: " . $CAUSAS2;

              //CAUSAS3
              $CAUSAS3_busc_ini = '"CausaS3":';
              $CAUSAS3_busc_fin = ',"MedPBSUtilizado"';
              $cadena_CAUSAS3 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CAUSAS3, $CAUSAS3_busc_ini) + strlen($CAUSAS3_busc_ini);
              $posPresFinal = strpos($cadena_CAUSAS3, $CAUSAS3_busc_fin);
              $CAUSAS3 = substr($cadena_CAUSAS3, $posPresInicial, $posPresFinal - $posPresInicial);
              $CAUSAS3 = str_replace('"', "'", $CAUSAS3);
              //echo "<br> CAUSAS3: " . $CAUSAS3;

              //MEDPBSUTILIZADO
              $MEDPBSUTILIZADO_busc_ini = '"MedPBSUtilizado":';
              $MEDPBSUTILIZADO_busc_fin = ',"RznCausaS31"';
              $cadena_MEDPBSUTILIZADO = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_MEDPBSUTILIZADO, $MEDPBSUTILIZADO_busc_ini) + strlen($MEDPBSUTILIZADO_busc_ini);
              $posPresFinal = strpos($cadena_MEDPBSUTILIZADO, $MEDPBSUTILIZADO_busc_fin);
              $MEDPBSUTILIZADO = substr($cadena_MEDPBSUTILIZADO, $posPresInicial, $posPresFinal - $posPresInicial);
              $MEDPBSUTILIZADO = str_replace('"', "'", $MEDPBSUTILIZADO);
              //echo "<br> MEDPBSUTILIZADO: " . $MEDPBSUTILIZADO;


              //RZNCAUSAS31
              $RZNCAUSAS31_busc_ini = '"RznCausaS31":';
              $RZNCAUSAS31_busc_fin = ',"DescRzn31"';
              $cadena_RZNCAUSAS31 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS31, $RZNCAUSAS31_busc_ini) + strlen($RZNCAUSAS31_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS31, $RZNCAUSAS31_busc_fin);
              $RZNCAUSAS31 = substr($cadena_RZNCAUSAS31, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS31 = str_replace('"', "'", $RZNCAUSAS31);
              //echo "<br> RZNCAUSAS31: " . $RZNCAUSAS31;



              //DESCRZN31
              $DESCRZN31_busc_ini = '"DescRzn31":';
              $DESCRZN31_busc_fin = ',"RznCausaS32"';
              $cadena_DESCRZN31 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCRZN31, $DESCRZN31_busc_ini) + strlen($DESCRZN31_busc_ini);
              $posPresFinal = strpos($cadena_DESCRZN31, $DESCRZN31_busc_fin);
              $DESCRZN31 = substr($cadena_DESCRZN31, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCRZN31 = str_replace('"', "'", $DESCRZN31);
              //echo "<br> DESCRZN31: " . $DESCRZN31;



              //RZNCAUSAS32
              $RZNCAUSAS32_busc_ini = '"RznCausaS32":';
              $RZNCAUSAS32_busc_fin = ',"DescRzn32"';
              $cadena_RZNCAUSAS32 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS32, $RZNCAUSAS32_busc_ini) + strlen($RZNCAUSAS32_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS32, $RZNCAUSAS32_busc_fin);
              $RZNCAUSAS32 = substr($cadena_RZNCAUSAS32, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS32 = str_replace('"', "'", $RZNCAUSAS32);
              //echo "<br> RZNCAUSAS32: " . $RZNCAUSAS32;


              //DESCRZN32
              $DESCRZN32_busc_ini = '"DescRzn32":';
              $DESCRZN32_busc_fin = ',"CausaS4"';
              $cadena_DESCRZN32 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCRZN32, $DESCRZN32_busc_ini) + strlen($DESCRZN32_busc_ini);
              $posPresFinal = strpos($cadena_DESCRZN32, $DESCRZN32_busc_fin);
              $DESCRZN32 = substr($cadena_DESCRZN32, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCRZN32 = str_replace('"', "'", $DESCRZN32);
              //echo "<br> DESCRZN32: " . $DESCRZN32;


              //CAUSAS4
              $CAUSAS4_busc_ini = '"CausaS4":';
              $CAUSAS4_busc_fin = ',"MedPBSDescartado"';
              $cadena_CAUSAS4 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CAUSAS4, $CAUSAS4_busc_ini) + strlen($CAUSAS4_busc_ini);
              $posPresFinal = strpos($cadena_CAUSAS4, $CAUSAS4_busc_fin);
              $CAUSAS4 = substr($cadena_CAUSAS4, $posPresInicial, $posPresFinal - $posPresInicial);
              $CAUSAS4 = str_replace('"', "'", $CAUSAS4);
              //echo "<br> CAUSAS4: " . $CAUSAS4;


              //MEDPBSDESCARTADO
              $MEDPBSDESCARTADO_busc_ini = '"MedPBSDescartado":';
              $MEDPBSDESCARTADO_busc_fin = ',"RznCausaS41"';
              $cadena_MEDPBSDESCARTADO = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_MEDPBSDESCARTADO, $MEDPBSDESCARTADO_busc_ini) + strlen($MEDPBSDESCARTADO_busc_ini);
              $posPresFinal = strpos($cadena_MEDPBSDESCARTADO, $MEDPBSDESCARTADO_busc_fin);
              $MEDPBSDESCARTADO = substr($cadena_MEDPBSDESCARTADO, $posPresInicial, $posPresFinal - $posPresInicial);
              $MEDPBSDESCARTADO = str_replace('"', "'", $MEDPBSDESCARTADO);
              //echo "<br> MEDPBSDESCARTADO: " . $MEDPBSDESCARTADO;


              //RZNCAUSAS41
              $RZNCAUSAS41_busc_ini = '"RznCausaS41":';
              $RZNCAUSAS41_busc_fin = ',"DescRzn41"';
              $cadena_RZNCAUSAS41 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS41, $RZNCAUSAS41_busc_ini) + strlen($RZNCAUSAS41_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS41, $RZNCAUSAS41_busc_fin);
              $RZNCAUSAS41 = substr($cadena_RZNCAUSAS41, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS41 = str_replace('"', "'", $RZNCAUSAS41);
              //echo "<br> RZNCAUSAS41: " . $RZNCAUSAS41;



              //DESCRZN41
              $DESCRZN41_busc_ini = '"DescRzn41":';
              $DESCRZN41_busc_fin = ',"RznCausaS42"';
              $cadena_DESCRZN41 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCRZN41, $DESCRZN41_busc_ini) + strlen($DESCRZN41_busc_ini);
              $posPresFinal = strpos($cadena_DESCRZN41, $DESCRZN41_busc_fin);
              $DESCRZN41 = substr($cadena_DESCRZN41, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCRZN41 = str_replace('"', "'", $DESCRZN41);
              //echo "<br> DESCRZN41: " . $DESCRZN41;


              //RZNCAUSAS42
              $RZNCAUSAS42_busc_ini = '"RznCausaS42":';
              $RZNCAUSAS42_busc_fin = ',"DescRzn42"';
              $cadena_RZNCAUSAS42 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS42, $RZNCAUSAS42_busc_ini) + strlen($RZNCAUSAS42_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS42, $RZNCAUSAS42_busc_fin);
              $RZNCAUSAS42 = substr($cadena_RZNCAUSAS42, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS42 = str_replace('"', "'", $RZNCAUSAS42);
              //echo "<br> RZNCAUSAS42: " . $RZNCAUSAS42;


              //DESCRZN42
              $DESCRZN42_busc_ini = '"DescRzn42":';
              $DESCRZN42_busc_fin = ',"RznCausaS43"';
              $cadena_DESCRZN42 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCRZN42, $DESCRZN42_busc_ini) + strlen($DESCRZN42_busc_ini);
              $posPresFinal = strpos($cadena_DESCRZN42, $DESCRZN42_busc_fin);
              $DESCRZN42 = substr($cadena_DESCRZN42, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCRZN42 = str_replace('"', "'", $DESCRZN42);
              //echo "<br> DESCRZN42: " . $DESCRZN42;


              //RZNCAUSAS43
              $RZNCAUSAS43_busc_ini = '"RznCausaS43":';
              $RZNCAUSAS43_busc_fin = ',"DescRzn43"';
              $cadena_RZNCAUSAS43 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS43, $RZNCAUSAS43_busc_ini) + strlen($RZNCAUSAS43_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS43, $RZNCAUSAS43_busc_fin);
              $RZNCAUSAS43 = substr($cadena_RZNCAUSAS43, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS43 = str_replace('"', "'", $RZNCAUSAS43);
              //echo "<br> RZNCAUSAS43: " . $RZNCAUSAS43;


              //DESCRZN43
              $DESCRZN43_busc_ini = '"DescRzn43":';
              $DESCRZN43_busc_fin = ',"RznCausaS44"';
              $cadena_DESCRZN43 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCRZN43, $DESCRZN43_busc_ini) + strlen($DESCRZN43_busc_ini);
              $posPresFinal = strpos($cadena_DESCRZN43, $DESCRZN43_busc_fin);
              $DESCRZN43 = substr($cadena_DESCRZN43, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCRZN43 = str_replace('"', "'", $DESCRZN43);
              //echo "<br> DESCRZN43: " . $DESCRZN43;



              //RZNCAUSAS44
              $RZNCAUSAS44_busc_ini = '"RznCausaS44":';
              $RZNCAUSAS44_busc_fin = ',"DescRzn44"';
              $cadena_RZNCAUSAS44 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS44, $RZNCAUSAS44_busc_ini) + strlen($RZNCAUSAS44_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS44, $RZNCAUSAS44_busc_fin);
              $RZNCAUSAS44 = substr($cadena_RZNCAUSAS44, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS44 = str_replace('"', "'", $RZNCAUSAS44);
              //echo "<br> RZNCAUSAS44: " . $RZNCAUSAS44;


              //DESCRZN44
              $DESCRZN44_busc_ini = '"DescRzn44":';
              $DESCRZN44_busc_fin = ',"CausaS5"';
              $cadena_DESCRZN44 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCRZN44, $DESCRZN44_busc_ini) + strlen($DESCRZN44_busc_ini);
              $posPresFinal = strpos($cadena_DESCRZN44, $DESCRZN44_busc_fin);
              $DESCRZN44 = substr($cadena_DESCRZN44, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCRZN44 = str_replace('"', "'", $DESCRZN44);
              //echo "<br> DESCRZN44: " . $DESCRZN44;

              //CAUSAS5
              $CAUSAS5_busc_ini = '"CausaS5":';
              $CAUSAS5_busc_fin = ',"RznCausaS5"';
              $cadena_CAUSAS5 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CAUSAS5, $CAUSAS5_busc_ini) + strlen($CAUSAS5_busc_ini);
              $posPresFinal = strpos($cadena_CAUSAS5, $CAUSAS5_busc_fin);
              $CAUSAS5 = substr($cadena_CAUSAS5, $posPresInicial, $posPresFinal - $posPresInicial);
              $CAUSAS5 = str_replace('"', "'", $CAUSAS5);
              //echo "<br> CAUSAS5: " . $CAUSAS5;


              //RZNCAUSAS5
              $RZNCAUSAS5_busc_ini = '"RznCausaS5":';
              $RZNCAUSAS5_busc_fin = ',"CausaS6"';
              $cadena_RZNCAUSAS5 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_RZNCAUSAS5, $RZNCAUSAS5_busc_ini) + strlen($RZNCAUSAS5_busc_ini);
              $posPresFinal = strpos($cadena_RZNCAUSAS5, $RZNCAUSAS5_busc_fin);
              $RZNCAUSAS5 = substr($cadena_RZNCAUSAS5, $posPresInicial, $posPresFinal - $posPresInicial);
              $RZNCAUSAS5 = str_replace('"', "'", $RZNCAUSAS5);
              //echo "<br> RZNCAUSAS5: " . $RZNCAUSAS5;


              //CAUSAS6
              $CAUSAS6_busc_ini = '"CausaS6":';
              $CAUSAS6_busc_fin = ',"DescMedPrinAct"';
              $cadena_CAUSAS6 = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CAUSAS6, $CAUSAS6_busc_ini) + strlen($CAUSAS6_busc_ini);
              $posPresFinal = strpos($cadena_CAUSAS6, $CAUSAS6_busc_fin);
              $CAUSAS6 = substr($cadena_CAUSAS6, $posPresInicial, $posPresFinal - $posPresInicial);
              $CAUSAS6 = str_replace('"', "'", $CAUSAS6);
              //echo "<br> CAUSAS6: " . $CAUSAS6;



              //DESCMEDPRINACT
              $DESCMEDPRINACT_busc_ini = '"DescMedPrinAct":';
              $DESCMEDPRINACT_busc_fin = ',"CodFF"';
              $cadena_DESCMEDPRINACT = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DESCMEDPRINACT, $DESCMEDPRINACT_busc_ini) + strlen($DESCMEDPRINACT_busc_ini);
              $posPresFinal = strpos($cadena_DESCMEDPRINACT, $DESCMEDPRINACT_busc_fin);
              $DESCMEDPRINACT = substr($cadena_DESCMEDPRINACT, $posPresInicial, $posPresFinal - $posPresInicial);
              $DESCMEDPRINACT = str_replace('"', "'", $DESCMEDPRINACT);
              //echo "<br> DESCMEDPRINACT: " . $DESCMEDPRINACT;


              //CODFF
              $CODFF_busc_ini = '"CodFF":';
              $CODFF_busc_fin = ',"CodVA"';
              $cadena_CODFF = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CODFF, $CODFF_busc_ini) + strlen($CODFF_busc_ini);
              $posPresFinal = strpos($cadena_CODFF, $CODFF_busc_fin);
              $CODFF = substr($cadena_CODFF, $posPresInicial, $posPresFinal - $posPresInicial);
              $CODFF = str_replace('"', "'", $CODFF);
              //echo "<br> CODFF: " . $CODFF;


              //CODVA
              $CODVA_busc_ini = '"CodVA":';
              $CODVA_busc_fin = ',"JustNoPBS"';
              $cadena_CODVA = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CODVA, $CODVA_busc_ini) + strlen($CODVA_busc_ini);
              $posPresFinal = strpos($cadena_CODVA, $CODVA_busc_fin);
              $CODVA = substr($cadena_CODVA, $posPresInicial, $posPresFinal - $posPresInicial);
              $CODVA = str_replace('"', "'", $CODVA);
              //echo "<br> CODVA: " . $CODVA;

              //JUSTNOPBS
              $JUSTNOPBS_busc_ini = '"JustNoPBS":';
              $JUSTNOPBS_busc_fin = ',"Dosis"';
              $cadena_JUSTNOPBS = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_JUSTNOPBS, $JUSTNOPBS_busc_ini) + strlen($JUSTNOPBS_busc_ini);
              $posPresFinal = strpos($cadena_JUSTNOPBS, $JUSTNOPBS_busc_fin);
              $JUSTNOPBS = substr($cadena_JUSTNOPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              $JUSTNOPBS = str_replace('"', "'", $JUSTNOPBS);
              //echo "<br> JUSTNOPBS: " . $JUSTNOPBS;

              //DOSIS
              $DOSIS_busc_ini = '"Dosis":';
              $DOSIS_busc_fin = ',"DosisUM"';
              $cadena_DOSIS = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DOSIS, $DOSIS_busc_ini) + strlen($DOSIS_busc_ini);
              $posPresFinal = strpos($cadena_DOSIS, $DOSIS_busc_fin);
              $DOSIS = substr($cadena_DOSIS, $posPresInicial, $posPresFinal - $posPresInicial);
              $DOSIS = str_replace('"', "'", $DOSIS);
              //echo "<br> DOSIS: " . $DOSIS;


              //DOSISUM
              $DOSISUM_busc_ini = '"DosisUM":';
              $DOSISUM_busc_fin = ',"NoFAdmon"';
              $cadena_DOSISUM = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DOSISUM, $DOSISUM_busc_ini) + strlen($DOSISUM_busc_ini);
              $posPresFinal = strpos($cadena_DOSISUM, $DOSISUM_busc_fin);
              $DOSISUM = substr($cadena_DOSISUM, $posPresInicial, $posPresFinal - $posPresInicial);
              $DOSISUM = str_replace('"', "'", $DOSISUM);
              //echo "<br> DOSISUM: " . $DOSISUM;


              //NOFADMON
              $NOFADMON_busc_ini = '"NoFAdmon":';
              $NOFADMON_busc_fin = ',"CodFreAdmon"';
              $cadena_NOFADMON = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_NOFADMON, $NOFADMON_busc_ini) + strlen($NOFADMON_busc_ini);
              $posPresFinal = strpos($cadena_NOFADMON, $NOFADMON_busc_fin);
              $NOFADMON = substr($cadena_NOFADMON, $posPresInicial, $posPresFinal - $posPresInicial);
              $NOFADMON = str_replace('"', "'", $NOFADMON);
              //echo "<br> NOFADMON: " . $NOFADMON;

              //CODFREADMON
              $CODFREADMON_busc_ini = '"CodFreAdmon":';
              $CODFREADMON_busc_fin = ',"IndEsp"';
              $cadena_CODFREADMON = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CODFREADMON, $CODFREADMON_busc_ini) + strlen($CODFREADMON_busc_ini);
              $posPresFinal = strpos($cadena_CODFREADMON, $CODFREADMON_busc_fin);
              $CODFREADMON = substr($cadena_CODFREADMON, $posPresInicial, $posPresFinal - $posPresInicial);
              $CODFREADMON = str_replace('"', "'", $CODFREADMON);
              //echo "<br> CODFREADMON: " . $CODFREADMON;


              //INDESP
              $INDESP_busc_ini = '"IndEsp":';
              $INDESP_busc_fin = ',"CanTrat"';
              $cadena_INDESP = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_INDESP, $INDESP_busc_ini) + strlen($INDESP_busc_ini);
              $posPresFinal = strpos($cadena_INDESP, $INDESP_busc_fin);
              $INDESP = substr($cadena_INDESP, $posPresInicial, $posPresFinal - $posPresInicial);
              $INDESP = str_replace('"', "'", $INDESP);
              //echo "<br> INDESP: " . $INDESP;


              //CANTRAT
              $CANTRAT_busc_ini = '"CanTrat":';
              $CANTRAT_busc_fin = ',"DurTrat"';
              $cadena_CANTRAT = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CANTRAT, $CANTRAT_busc_ini) + strlen($CANTRAT_busc_ini);
              $posPresFinal = strpos($cadena_CANTRAT, $CANTRAT_busc_fin);
              $CANTRAT = substr($cadena_CANTRAT, $posPresInicial, $posPresFinal - $posPresInicial);
              $CANTRAT = str_replace('"', "'", $CANTRAT);
              //echo "<br> CANTRAT: " . $CANTRAT;

              //DURTRAT
              $DURTRAT_busc_ini = '"DurTrat":';
              $DURTRAT_busc_fin = ',"CantTotalF"';
              $cadena_DURTRAT = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_DURTRAT, $DURTRAT_busc_ini) + strlen($DURTRAT_busc_ini);
              $posPresFinal = strpos($cadena_DURTRAT, $DURTRAT_busc_fin);
              $DURTRAT = substr($cadena_DURTRAT, $posPresInicial, $posPresFinal - $posPresInicial);
              $DURTRAT = str_replace('"', "'", $DURTRAT);
              //echo "<br> DURTRAT: " . $DURTRAT;


              //CANTTOTALF
              $CANTTOTALF_busc_ini = '"CantTotalF":';
              $CANTTOTALF_busc_fin = ',"UFCantTotal"';
              $cadena_CANTTOTALF = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_CANTTOTALF, $CANTTOTALF_busc_ini) + strlen($CANTTOTALF_busc_ini);
              $posPresFinal = strpos($cadena_CANTTOTALF, $CANTTOTALF_busc_fin);
              $CANTTOTALF = substr($cadena_CANTTOTALF, $posPresInicial, $posPresFinal - $posPresInicial);
              $CANTTOTALF = str_replace('"', "'", $CANTTOTALF);
              //echo "<br> CANTTOTALF: " . $CANTTOTALF;


              //UFCANTTOTAL
              $UFCANTTOTAL_busc_ini = '"UFCantTotal":';
              $UFCANTTOTAL_busc_fin = ',"IndRec"';
              $cadena_UFCANTTOTAL = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_UFCANTTOTAL, $UFCANTTOTAL_busc_ini) + strlen($UFCANTTOTAL_busc_ini);
              $posPresFinal = strpos($cadena_UFCANTTOTAL, $UFCANTTOTAL_busc_fin);
              $UFCANTTOTAL = substr($cadena_UFCANTTOTAL, $posPresInicial, $posPresFinal - $posPresInicial);
              $UFCANTTOTAL = str_replace('"', "'", $UFCANTTOTAL);
              //echo "<br> UFCANTTOTAL: " . $UFCANTTOTAL;



              //INDREC
              $INDREC_busc_ini = '"IndRec":';
              $INDREC_busc_fin = ',"EstJM"';
              $cadena_INDREC = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_INDREC, $INDREC_busc_ini) + strlen($INDREC_busc_ini);
              $posPresFinal = strpos($cadena_INDREC, $INDREC_busc_fin);
              $INDREC = substr($cadena_INDREC, $posPresInicial, $posPresFinal - $posPresInicial);
              $INDREC = str_replace('"', "'", $INDREC);
              //echo "<br> INDREC: " . $INDREC;


              //ESTJM
              $ESTJM_busc_ini = '"EstJM":';
              $ESTJM_busc_fin = ',"PrincipiosActivos"';
              $cadena_ESTJM = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_ESTJM, $ESTJM_busc_ini) + strlen($ESTJM_busc_ini);
              $posPresFinal = strpos($cadena_ESTJM, $ESTJM_busc_fin);
              $ESTJM = substr($cadena_ESTJM, $posPresInicial, $posPresFinal - $posPresInicial);
              $ESTJM = str_replace('"', "'", $ESTJM);
              //echo "<br> ESTJM: " . $ESTJM;

              //Armar el query
              $sql_exc = "INSERT INTO WEBSERV_PRES_MEDI 
              (ID_MEDI,ID_PRES,CONORDEN,TIPOMED,TIPOPREST,CAUSAS1,CAUSAS2,CAUSAS3,MEDPBSUTILIZADO,RZNCAUSAS31,DESCRZN31,RZNCAUSAS32,DESCRZN32,CAUSAS4,MEDPBSDESCARTADO,RZNCAUSAS41,DESCRZN41,RZNCAUSAS42,DESCRZN42,RZNCAUSAS43,DESCRZN43,RZNCAUSAS44,DESCRZN44,CAUSAS5,RZNCAUSAS5,CAUSAS6,DESCMEDPRINACT,CODFF,CODVA,JUSTNOPBS,DOSIS,DOSISUM,NOFADMON,CODFREADMON,INDESP,CANTRAT,DURTRAT,CANTTOTALF,UFCANTTOTAL,INDREC,ESTJM)
              VALUES (" . $id_medi . "," . $id_pres . "," . $CONORDEN . "," . $TIPOMED . "," . $TIPOPREST . "," . $CAUSAS1 . "," . $CAUSAS2 . "," . $CAUSAS3 . "," . $MEDPBSUTILIZADO . "," . $RZNCAUSAS31 . "," . $DESCRZN31 . "," . $RZNCAUSAS32 . "," . $DESCRZN32 . "," . $CAUSAS4 . "," . $MEDPBSDESCARTADO . "," . $RZNCAUSAS41 . "," . $DESCRZN41 . "," . $RZNCAUSAS42 . "," . $DESCRZN42 . "," . $RZNCAUSAS43 . "," . $DESCRZN43 . "," . $RZNCAUSAS44 . "," . $DESCRZN44 . "," . $CAUSAS5 . "," . $RZNCAUSAS5 . "," . $CAUSAS6 . "," . $DESCMEDPRINACT . "," . $CODFF . "," . $CODVA . "," . $JUSTNOPBS . "," . $DOSIS . "," . $DOSISUM . "," . $NOFADMON . "," . $CODFREADMON . "," . $INDESP . "," . $CANTRAT . "," . $DURTRAT . "," . $CANTTOTALF . "," . $UFCANTTOTAL . "," . $INDREC . "," . $ESTJM. ")";
              //echo "<br><br>".$sql_exc."<br><br>";
              /*
              // Armar SQL
              for ($a = 0; $a < $longitud_vector_subCadenaPresMedi; $a++) {
                $parametro_inicial = '"' . $vector_subCadenaPresMedi[$a] . '":';
                if ($a + 1 < $longitud_vector_subCadenaPresMedi) {
                  $parametro_final = ',"' . $vector_subCadenaPresMedi[$a + 1] . '"';
                } else {
                  $parametro_final = ',"PrincipiosActivos"';
                }
                $nombre_dato = $vector_subCadenaPresMedi[$a];
                if ($nombre_dato != '') {
                  $dato = obtener_dato_json($parametro_inicial, $parametro_final, $subCadenaPresMedi, $nombre_dato);




                  $dato = str_replace('"', "'", $dato);
                 // echo   "<br> " . $nombre_dato . ": " . $dato;
                  //$dato_vec[$a] = $dato;
                  // oci_bind_by_name($st_medi, ":" . $nombre_dato, $dato);
                  $sql_exc = $sql_exc . "," . $dato;
                }
              }
              $sql_exc = $sql_exc . ")";
              


              */
              $st_medi = oci_parse($conn_oracle, $sql_exc);
              unset($vector_subCadenaPresMedi); //Vaciar el vector



              /////Insertar prescripcion (Inicio)

              /////Guardar en la base de datos
              $result = oci_execute($st_medi);
              oci_free_statement($st_medi);
              if ($result) {
                //echo  "<br>Insercion Correcta ";
              } else {
                //echo  "<br>Insercion Incorrecta ";
              }


              /////////////////////////////////PrincipiosActivos (Inicio)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Inicio)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Inicio)//////////////////////////////////////////

              //PrincipiosActivos
              $PrincipiosActivos_busc_ini = '"PrincipiosActivos":';
              $PrincipiosActivos_busc_fin = '}],"IndicacionesUNIRS"';
              $cadena_PrincipiosActivos = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_PrincipiosActivos, $PrincipiosActivos_busc_ini) + strlen($PrincipiosActivos_busc_ini);
              $posPresFinal = strpos($cadena_PrincipiosActivos, $PrincipiosActivos_busc_fin);
              $PrincipiosActivos = substr($cadena_PrincipiosActivos, $posPresInicial, $posPresFinal - $posPresInicial + 2); //Se le suman dos caracteres para incluir el }] en el final de la cadena

              if ($PrincipiosActivos != "") {
                //echo  "<br>---------------------------------";
                //Guardar en un vector cada uno de los principios activos
                $subCadenaBuscadaInicial   = '[{"ConOrden":';
                $cadena = $PrincipiosActivos;
                $posInicial = strpos($cadena, $subCadenaBuscadaInicial);

                $count_report = 0;

                $vector_PrincipiosActivos[0] = '';
                $count = 0;
                while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada
                  $count = $count + 1;
                  $subCadenaBuscadaInicial   = '[{"ConOrden":';
                  $subCadenaBuscadaFinal   = ',{"ConOrden"';
                  $cadena = $PrincipiosActivos;
                  $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
                  $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
                  if ($posFinal == "") {
                    $posFinal = strlen($cadena) - 2;
                  }
                  $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
                  if ($subCadenaFinal !== '') {
                    $vector_PrincipiosActivos[$count_report] = $subCadenaFinal;

                    $count_report++;
                  }
                  $PrincipiosActivos = str_replace($subCadenaFinal, "", $PrincipiosActivos);
                }


                //saco el numero de elementos
                $longitud_vector_PrincipiosActivos = count($vector_PrincipiosActivos);
                //echo "<br> [PrincipiosActivos]: Cantidad:" . $longitud_vector_PrincipiosActivos; //. $PrincipiosActivos;
                //echo "<br>longitud_vector_PrincipiosActivos: " . $longitud_vector_PrincipiosActivos;
                //Recorro todos los elementos
                for ($e = 0; $e < $longitud_vector_PrincipiosActivos; $e++) {
                  //echo  "<br>-------";
                  $cadena_principio_activo = $vector_PrincipiosActivos[$e];
                  if ($cadena_principio_activo != '') {
                    //Obtener la cadena de cada uno de los Principios Activos

                    //ConOrden
                    $parametro_inicial = '"ConOrden":';
                    $parametro_final = ',"CodPriAct"';
                    $nombre_dato = 'ConOrden';
                    $pa_ConOrden = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $pa_ConOrden = str_replace('"', "'", $pa_ConOrden);
                    //echo   "<br> " . $nombre_dato . ": " . $pa_ConOrden;

                    //CodPriAct
                    $parametro_inicial = '"CodPriAct":';
                    $parametro_final = ',"ConcCant"';
                    $nombre_dato = 'CodPriAct';
                    $pa_CodPriAct = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $pa_CodPriAct = str_replace('"', "'", $pa_CodPriAct);
                    //echo   "<br> " . $nombre_dato . ": " . $pa_CodPriAct;

                    //ConcCant
                    $parametro_inicial = '"ConcCant":';
                    $parametro_final = ',"UMedConc"';
                    $nombre_dato = 'ConcCant';
                    $pa_ConcCant = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $pa_ConcCant = str_replace('"', "'", $pa_ConcCant);
                    //echo   "<br> " . $nombre_dato . ": " . $pa_ConcCant;

                    //UMedConc
                    $parametro_inicial = '"UMedConc":';
                    $parametro_final = ',"CantCont"';
                    $nombre_dato = 'UMedConc';
                    $pa_UMedConc = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $pa_UMedConc = str_replace('"', "'", $pa_UMedConc);
                    //echo   "<br> " . $nombre_dato . ": " . $pa_UMedConc;

                    //CantCont
                    $parametro_inicial = '"CantCont":';
                    $parametro_final = ',"UMedCantCont"';
                    $nombre_dato = 'CantCont';
                    $pa_CantCont = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $pa_CantCont = str_replace('"', "'", $pa_CantCont);
                    //echo   "<br> " . $nombre_dato . ": " . $pa_CantCont;

                    //UMedCantCont
                    $parametro_inicial = '"UMedCantCont":';
                    $parametro_final = '}';
                    $nombre_dato = 'UMedCantCont';
                    $pa_UMedCantCont = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $pa_UMedCantCont = str_replace('"', "'", $pa_UMedCantCont);
                    //echo   "<br> " . $nombre_dato . ": " . $pa_UMedCantCont;

                    /////Insertar prescripcion (Inicio)
                    $sql_exc = "INSERT INTO WEBSERV_PRES_PRIN_ACTI 
                    (ID_PRAC,ID_MEDI,CONORDEN,CODPRIACT,CONCCANT,UMEDCONC,CANTCONT,UMEDCANTCONT)  VALUES 
                    (SEQ_WEBSERV_PRES_PRIN_ACTI.nextval," . $id_medi . "," . $pa_ConOrden . "," . $pa_CodPriAct . "," . $pa_ConcCant . "," . $pa_UMedConc . "," . $pa_CantCont . "," . $pa_UMedCantCont . ")";
                   // echo $sql_exc;

                    $st_pr_ac = oci_parse($conn_oracle, $sql_exc);

                    $result = oci_execute($st_pr_ac);
                    oci_free_statement($st_pr_ac);
                    if ($result) {
                      //echo  "<br>Insercion Correcta ";
                    } else {
                      //echo  "<br>Insercion Incorrecta ";
                    }
                    /////Insertar prescripcion (Fin)


                  }
                }
                unset($vector_PrincipiosActivos); //Vaciar el vector




              }


              /////////////////////////////////PrincipiosActivos (Fin)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Fin)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Fin)//////////////////////////////////////////


              /////////////////////////////////IndicacionesUNIRS (Inicio)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Inicio)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Inicio)/////////////////////////////////////////
              //IndicacionesUNIRS
              $IndicacionesUNIRS_busc_ini = '"IndicacionesUNIRS":';
              $IndicacionesUNIRS_busc_fin = '}]}';
              $cadena_IndicacionesUNIRS = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_IndicacionesUNIRS, $IndicacionesUNIRS_busc_ini) + strlen($IndicacionesUNIRS_busc_ini);
              $posPresFinal = strpos($cadena_IndicacionesUNIRS, $IndicacionesUNIRS_busc_fin);
              $IndicacionesUNIRS = substr($cadena_IndicacionesUNIRS, $posPresInicial, $posPresFinal - $posPresInicial + 2); //Se le suman dos caracteres  para incluir el }] en el final de la cadena
              if ($IndicacionesUNIRS != '') {
                //echo "<br>---------------------------------";
                //Guardar en un vector cada uno de los IndicacionesUNIRS
                $subCadenaBuscadaInicial   = '[{"ConOrden":';
                $cadena = $IndicacionesUNIRS;
                $posInicial = strpos($cadena, $subCadenaBuscadaInicial);

                $count_report = 0;

                $vector_IndicacionesUNIRS[0] = '';
                $count = 0;
                while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada
                  $count = $count + 1;
                  $subCadenaBuscadaInicial   = '[{"ConOrden":';
                  $subCadenaBuscadaFinal   = ',{"ConOrden"';
                  $cadena = $IndicacionesUNIRS;
                  $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
                  $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
                  if ($posFinal == "") {
                    $posFinal = strlen($cadena) - 2;
                  }
                  $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
                  if ($subCadenaFinal !== '') {
                    $vector_IndicacionesUNIRS[$count_report] = $subCadenaFinal;

                    $count_report++;
                  }
                  $IndicacionesUNIRS = str_replace($subCadenaFinal, "", $IndicacionesUNIRS);
                }


                //saco el numero de elementos
                $longitud_vector_IndicacionesUNIRS = count($vector_IndicacionesUNIRS);

                //echo "<br> [IndicacionesUNIRS]: Cantidad: " . $longitud_vector_IndicacionesUNIRS; // . $IndicacionesUNIRS;
                ////echo "<br>longitud_vector_IndicacionesUNIRS: " . $longitud_vector_IndicacionesUNIRS;
                //Recorro todos los elementos
                for ($e = 0; $e < $longitud_vector_IndicacionesUNIRS; $e++) {
                  //echo  "<br>-------";
                  $cadena_principio_activo = $vector_IndicacionesUNIRS[$e];
                  if ($cadena_principio_activo != '') {
                    //Obtener la cadena de cada uno de los IndicacionesUNIRS

                    //ConOrden
                    $parametro_inicial = '"ConOrden":';
                    $parametro_final = ',"CodIndicacion"';
                    $nombre_dato = 'ConOrden';
                    $iu_ConOrden = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $iu_ConOrden = str_replace('"', "'", $iu_ConOrden);
                    //echo   "<br> " . $nombre_dato . ": " . $dato;


                    //UMedCantCont
                    $parametro_inicial = '"CodIndicacion":';
                    $parametro_final = '}';
                    $nombre_dato = 'CodIndicacion';
                    $iu_CodIndicacion = obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                    $iu_CodIndicacion = str_replace('"', "'", $iu_CodIndicacion);
                    //echo   "<br> " . $nombre_dato . ": " . $dato;


                    /////Insertar prescripcion (Inicio)
                    $sql_exc = "INSERT INTO WEBSERV_PRES_INDI_UNIRS 
                     (ID_IMUN,ID_MEDI,CONORDEN,CODINDICACION)  VALUES 
                     (SEQ_WEBSERV_PRES_INDI_UNIRS.nextval," . $id_medi . "," . $iu_ConOrden . "," . $iu_CodIndicacion . ")";
                    //echo $sql_exc;

                    $st_in_un = oci_parse($conn_oracle, $sql_exc);

                    $result = oci_execute($st_in_un);
                    oci_free_statement($st_in_un);
                    if ($result) {
                      //echo  "<br>Insercion Correcta ";
                    } else {
                      //echo  "<br>Insercion Incorrecta ";
                    }
                    /////Insertar prescripcion (Fin)

                  }
                }
                unset($vector_IndicacionesUNIRS); //Vaciar el vector

                $ConOrden;
                $CodIndicacion;
              }


              /////////////////////////////////IndicacionesUNIRS (Fin)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Fin)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Fin)/////////////////////////////////////////


              //Columnas de medicamentos
              $ConOrden;
              $TipoMed;
              $TipoPrest;
              $CausaS1;
              $CausaS2;
              $CausaS3;
              $MedPBSUtilizado;
              $RznCausaS31;
              $DescRzn31;
              $RznCausaS32;
              $DescRzn32;
              $CausaS4;
              $MedPBSDescartado;
              $RznCausaS41;
              $DescRzn41;
              $RznCausaS42;
              $DescRzn42;
              $RznCausaS43;
              $DescRzn43;
              $RznCausaS44;
              $DescRzn44;
              $CausaS5;
              $RznCausaS5;
              $CausaS6;
              $DescMedPrinAct;
              $CodFF;
              $CodVA;
              $JustNoPBS;
              $Dosis;
              $DosisUM;
              $NoFAdmon;
              $CodFreAdmon;
              $IndEsp;
              $CanTrat;
              $DurTrat;
              $CantTotalF;
              $UFCantTotal;
              $IndRec;
              $EstJM;
            }
          }
          unset($vector_medicamentos); //Vaciar el vector

          /*******************Leer cada uno de los medicamentos(Fin)********************************/


          ///////////////////////////////////////////////////////////////////////////////////////////procedimientos
          //echo  "<br><br>-------------------------------------------------------------------------procedimientos";


          //Obtener cadena general de prescripcion
          $cad_pres_busc_ini = '],"procedimientos"';
          $cad_pres_busc_fin = ',"dispositivos"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresProc = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          //echo "<br> subCadenaPresProc: " . $subCadenaPresProc . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los procedimientos////////////////

          $cadenaProc = $subCadenaPresProc;
          // $cadenaProc = str_replace('},"procedimientos":[', 'inicio', $cadenaProc);
          // $cadenaProc = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaProc);
          $subcadenaProcBuscadaInicial   = '{"ConOrden"';
          ////echo "<br> cadenaProc: " . $cadenaProc . "<br>";
          $posInicial = strpos($cadenaProc, $subcadenaProcBuscadaInicial);
          $count_report = 0;

          $vector_procedimientos[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////////////////Prescripcion///////////////////////////////////////////
            ///////////////////////////////Se separan Cada uno de los procedimientos (Inicio)/////////////////
            ///////////////////////////////////////////Prescripcion///////////////////////////////////////////

            $subcadenaProcBuscadaInicial   = '{"ConOrden"';
            // //echo "<br> sub cadenaProc Buscada Inicial: " . $subcadenaProcBuscadaInicial;
            $subcadenaProcBuscadaFinal   = '}';
            ////echo "<br> sub cadenaProc Buscada Final: " . $subcadenaProcBuscadaFinal;
            $posInicial = strpos($cadenaProc, $subcadenaProcBuscadaInicial);
            // //echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaProc, $subcadenaProcBuscadaFinal) + 2;
            ////echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaProc) - 2; //Sera igual a la última posición de la cadenaProc
              // //echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaProcFinal = substr($cadenaProc, $posInicial, $posFinal - $posInicial);
            //echo "<br> Sub cadenaProc: " . $subcadenaProcFinal;
            if ($subcadenaProcFinal != '[' && $subcadenaProcFinal != '') {
              //$vector_procedimientos[$count_report] = str_replace('inicio', '},"procedimientos":[', $subcadenaProcFinal);
              $vector_procedimientos[$count_report] =  $subcadenaProcFinal;
              $count_report++;
            }
            $cadenaProc = str_replace($subcadenaProcFinal, "", $cadenaProc);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los procedimientos(Inicio)********************************/
          $longitud_vec_Proc = count($vector_procedimientos);
          for ($count_vec_Proc = 0; $count_vec_Proc < $longitud_vec_Proc; $count_vec_Proc++) {
            ////echo "<br> Cadena#" . $count_vec_Proc . ": " . $vector_procedimientos[$count_vec_Proc];
            $subCadenaPresProc = $vector_procedimientos[$count_vec_Proc];


            if (strlen($subCadenaPresProc) > 5) { //Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              //echo  "<br><br>-------------------------------------procedimiento# " . ($count_vec_Proc + 1);
              //echo "<br> subCadenaPresProc: " . $subCadenaPresProc;
              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              $ConOrden = str_replace('"', "'", $ConOrden);
              //echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS11"';
              $cadena_TipoPrest = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              $TipoPrest = str_replace('"', "'", $TipoPrest);
              //echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS11
              $CausaS11_busc_ini = '"CausaS11":';
              $CausaS11_busc_fin = ',"CausaS12"';
              $cadena_CausaS11 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS11, $CausaS11_busc_ini) + strlen($CausaS11_busc_ini);
              $posPresFinal = strpos($cadena_CausaS11, $CausaS11_busc_fin);
              $CausaS11 = substr($cadena_CausaS11, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS11 = str_replace('"', "'", $CausaS11);
              //echo "<br> CausaS11: " . $CausaS11;
              //CausaS12
              $CausaS12_busc_ini = '"CausaS12":';
              $CausaS12_busc_fin = ',"CausaS2"';
              $cadena_CausaS12 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS12, $CausaS12_busc_ini) + strlen($CausaS12_busc_ini);
              $posPresFinal = strpos($cadena_CausaS12, $CausaS12_busc_fin);
              $CausaS12 = substr($cadena_CausaS12, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS12 = str_replace('"', "'", $CausaS12);
              //echo "<br> CausaS12: " . $CausaS12;
              //CausaS2
              $CausaS2_busc_ini = '"CausaS2":';
              $CausaS2_busc_fin = ',"CausaS3"';
              $cadena_CausaS2 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS2, $CausaS2_busc_ini) + strlen($CausaS2_busc_ini);
              $posPresFinal = strpos($cadena_CausaS2, $CausaS2_busc_fin);
              $CausaS2 = substr($cadena_CausaS2, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS2 = str_replace('"', "'", $CausaS2);
              //echo "<br> CausaS2: " . $CausaS2;
              //CausaS3
              $CausaS3_busc_ini = '"CausaS3":';
              $CausaS3_busc_fin = ',"CausaS4"';
              $cadena_CausaS3 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS3, $CausaS3_busc_ini) + strlen($CausaS3_busc_ini);
              $posPresFinal = strpos($cadena_CausaS3, $CausaS3_busc_fin);
              $CausaS3 = substr($cadena_CausaS3, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS3 = str_replace('"', "'", $CausaS3);
              //echo "<br> CausaS3: " . $CausaS3;
              //CausaS4
              $CausaS4_busc_ini = '"CausaS4":';
              $CausaS4_busc_fin = ',"ProPBSUtilizado"';
              $cadena_CausaS4 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS4, $CausaS4_busc_ini) + strlen($CausaS4_busc_ini);
              $posPresFinal = strpos($cadena_CausaS4, $CausaS4_busc_fin);
              $CausaS4 = substr($cadena_CausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS4 = str_replace('"', "'", $CausaS4);
              //echo "<br> CausaS4: " . $CausaS4;
              //ProPBSUtilizado
              $ProPBSUtilizado_busc_ini = '"ProPBSUtilizado":';
              $ProPBSUtilizado_busc_fin = ',"CausaS5"';
              $cadena_ProPBSUtilizado = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_ProPBSUtilizado, $ProPBSUtilizado_busc_ini) + strlen($ProPBSUtilizado_busc_ini);
              $posPresFinal = strpos($cadena_ProPBSUtilizado, $ProPBSUtilizado_busc_fin);
              $ProPBSUtilizado = substr($cadena_ProPBSUtilizado, $posPresInicial, $posPresFinal - $posPresInicial);
              $ProPBSUtilizado = str_replace('"', "'", $ProPBSUtilizado);
              //echo "<br> ProPBSUtilizado: " . $ProPBSUtilizado;
              //CausaS5
              $CausaS5_busc_ini = '"CausaS5":';
              $CausaS5_busc_fin = ',"ProPBSDescartado"';
              $cadena_CausaS5 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS5, $CausaS5_busc_ini) + strlen($CausaS5_busc_ini);
              $posPresFinal = strpos($cadena_CausaS5, $CausaS5_busc_fin);
              $CausaS5 = substr($cadena_CausaS5, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS5 = str_replace('"', "'", $CausaS5);
              //echo "<br> CausaS5: " . $CausaS5;
              //ProPBSDescartado
              $ProPBSDescartado_busc_ini = '"ProPBSDescartado":';
              $ProPBSDescartado_busc_fin = ',"RznCausaS51"';
              $cadena_ProPBSDescartado = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_ProPBSDescartado, $ProPBSDescartado_busc_ini) + strlen($ProPBSDescartado_busc_ini);
              $posPresFinal = strpos($cadena_ProPBSDescartado, $ProPBSDescartado_busc_fin);
              $ProPBSDescartado = substr($cadena_ProPBSDescartado, $posPresInicial, $posPresFinal - $posPresInicial);
              $ProPBSDescartado = str_replace('"', "'", $ProPBSDescartado);
              //echo "<br> ProPBSDescartado: " . $ProPBSDescartado;
              //RznCausaS51
              $RznCausaS51_busc_ini = '"RznCausaS51":';
              $RznCausaS51_busc_fin = ',"DescRzn51"';
              $cadena_RznCausaS51 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_RznCausaS51, $RznCausaS51_busc_ini) + strlen($RznCausaS51_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS51, $RznCausaS51_busc_fin);
              $RznCausaS51 = substr($cadena_RznCausaS51, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS51 = str_replace('"', "'", $RznCausaS51);
              //echo "<br> RznCausaS51: " . $RznCausaS51;
              //DescRzn51
              $DescRzn51_busc_ini = '"DescRzn51":';
              $DescRzn51_busc_fin = ',"RznCausaS52"';
              $cadena_DescRzn51 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_DescRzn51, $DescRzn51_busc_ini) + strlen($DescRzn51_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn51, $DescRzn51_busc_fin);
              $DescRzn51 = substr($cadena_DescRzn51, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn51 = str_replace('"', "'", $DescRzn51);
              //echo "<br> DescRzn51: " . $DescRzn51;
              //RznCausaS52
              $RznCausaS52_busc_ini = '"RznCausaS52":';
              $RznCausaS52_busc_fin = ',"DescRzn52"';
              $cadena_RznCausaS52 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_RznCausaS52, $RznCausaS52_busc_ini) + strlen($RznCausaS52_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS52, $RznCausaS52_busc_fin);
              $RznCausaS52 = substr($cadena_RznCausaS52, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS52 = str_replace('"', "'", $RznCausaS52);
              //echo "<br> RznCausaS52: " . $RznCausaS52;
              //DescRzn52
              $DescRzn52_busc_ini = '"DescRzn52":';
              $DescRzn52_busc_fin = ',"CausaS6"';
              $cadena_DescRzn52 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_DescRzn52, $DescRzn52_busc_ini) + strlen($DescRzn52_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn52, $DescRzn52_busc_fin);
              $DescRzn52 = substr($cadena_DescRzn52, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn52 = str_replace('"', "'", $DescRzn52);
              //echo "<br> DescRzn52: " . $DescRzn52;
              //CausaS6
              $CausaS6_busc_ini = '"CausaS6":';
              $CausaS6_busc_fin = ',"CausaS7"';
              $cadena_CausaS6 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS6, $CausaS6_busc_ini) + strlen($CausaS6_busc_ini);
              $posPresFinal = strpos($cadena_CausaS6, $CausaS6_busc_fin);
              $CausaS6 = substr($cadena_CausaS6, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS6 = str_replace('"', "'", $CausaS6);
              //echo "<br> CausaS6: " . $CausaS6;
              //CausaS7
              $CausaS7_busc_ini = '"CausaS7":';
              $CausaS7_busc_fin = ',"CodCUPS"';
              $cadena_CausaS7 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS7, $CausaS7_busc_ini) + strlen($CausaS7_busc_ini);
              $posPresFinal = strpos($cadena_CausaS7, $CausaS7_busc_fin);
              $CausaS7 = substr($cadena_CausaS7, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS7 = str_replace('"', "'", $CausaS7);
              //echo "<br> CausaS7: " . $CausaS7;
              //CodCUPS
              $CodCUPS_busc_ini = '"CodCUPS":';
              $CodCUPS_busc_fin = ',"CanForm"';
              $cadena_CodCUPS = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CodCUPS, $CodCUPS_busc_ini) + strlen($CodCUPS_busc_ini);
              $posPresFinal = strpos($cadena_CodCUPS, $CodCUPS_busc_fin);
              $CodCUPS = substr($cadena_CodCUPS, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodCUPS = str_replace('"', "'", $CodCUPS);
              //echo "<br> CodCUPS: " . $CodCUPS;
              //CanForm
              $CanForm_busc_ini = '"CanForm":';
              $CanForm_busc_fin = ',"CadaFreUso"';
              $cadena_CanForm = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CanForm, $CanForm_busc_ini) + strlen($CanForm_busc_ini);
              $posPresFinal = strpos($cadena_CanForm, $CanForm_busc_fin);
              $CanForm = substr($cadena_CanForm, $posPresInicial, $posPresFinal - $posPresInicial);
              $CanForm = str_replace('"', "'", $CanForm);
              //echo "<br> CanForm: " . $CanForm;
              //CadaFreUso
              $CadaFreUso_busc_ini = '"CadaFreUso":';
              $CadaFreUso_busc_fin = ',"CodFreUso"';
              $cadena_CadaFreUso = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CadaFreUso, $CadaFreUso_busc_ini) + strlen($CadaFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CadaFreUso, $CadaFreUso_busc_fin);
              $CadaFreUso = substr($cadena_CadaFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              $CadaFreUso = str_replace('"', "'", $CadaFreUso);
              //echo "<br> CadaFreUso: " . $CadaFreUso;
              //CodFreUso
              $CodFreUso_busc_ini = '"CodFreUso":';
              $CodFreUso_busc_fin = ',"Cant"';
              $cadena_CodFreUso = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CodFreUso, $CodFreUso_busc_ini) + strlen($CodFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CodFreUso, $CodFreUso_busc_fin);
              $CodFreUso = substr($cadena_CodFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodFreUso = str_replace('"', "'", $CodFreUso);
              //echo "<br> CodFreUso: " . $CodFreUso;
              //Cant
              $Cant_busc_ini = '"Cant":';
              $Cant_busc_fin = ',"CantTotal"';
              $cadena_Cant = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_Cant, $Cant_busc_ini) + strlen($Cant_busc_ini);
              $posPresFinal = strpos($cadena_Cant, $Cant_busc_fin);
              $Cant = substr($cadena_Cant, $posPresInicial, $posPresFinal - $posPresInicial);
              $Cant = str_replace('"', "'", $Cant);
              //echo "<br> Cant: " . $Cant;
              //CantTotal
              $CantTotal_busc_ini = '"CantTotal":';
              $CantTotal_busc_fin = ',"CodPerDurTrat"';
              $cadena_CantTotal = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CantTotal, $CantTotal_busc_ini) + strlen($CantTotal_busc_ini);
              $posPresFinal = strpos($cadena_CantTotal, $CantTotal_busc_fin);
              $CantTotal = substr($cadena_CantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              $CantTotal = str_replace('"', "'", $CantTotal);
              //echo "<br> CantTotal: " . $CantTotal;
              //CodPerDurTrat
              $CodPerDurTrat_busc_ini = '"CodPerDurTrat":';
              $CodPerDurTrat_busc_fin = ',"JustNoPBS"';
              $cadena_CodPerDurTrat = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_ini) + strlen($CodPerDurTrat_busc_ini);
              $posPresFinal = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_fin);
              $CodPerDurTrat = substr($cadena_CodPerDurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodPerDurTrat = str_replace('"', "'", $CodPerDurTrat);
              //echo "<br> CodPerDurTrat: " . $CodPerDurTrat;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"IndRec"';
              $cadena_JustNoPBS = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              $JustNoPBS = str_replace('"', "'", $JustNoPBS);
              //echo "<br> JustNoPBS: " . $JustNoPBS;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"EstJM"';
              $cadena_IndRec = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              $IndRec = str_replace('"', "'", $IndRec);
              //echo "<br> IndRec: " . $IndRec;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              $EstJM = str_replace('"', "'", $EstJM);
              //echo "<br> EstJM: " . $EstJM;


              /////Insertar prescripcion (Inicio)
              $sql_exc = "INSERT INTO  WEBSERV_PRES_PROC
            (ID_PROC,ID_PRES,CONORDEN,TIPOPREST,CAUSAS11,CAUSAS12,CAUSAS2,CAUSAS3,CAUSAS4,PROPBSUTILIZADO,CAUSAS5,PROPBSDESCARTADO,RZNCAUSAS51,DESCRZN51,RZNCAUSAS52,DESCRZN52,CAUSAS6,CAUSAS7,CODCUPS,CANFORM,CADAFREUSO,CODFREUSO,CANT,CANTTOTAL,CODPERDURTRAT,JUSTNOPBS,INDREC,ESTJM)  VALUES 
            (SEQ_WEBSERV_PRES_PROC.nextval," . $id_pres . "," . $ConOrden . "," . $TipoPrest . "," . $CausaS11 . "," . $CausaS12 . "," . $CausaS2 . "," . $CausaS3 . "," . $CausaS4 . "," . $ProPBSUtilizado . "," . $CausaS5 . "," . $ProPBSDescartado . "," . $RznCausaS51 . "," . $DescRzn51 . "," . $RznCausaS52 . "," . $DescRzn52 . "," . $CausaS6 . "," . $CausaS7 . "," . $CodCUPS . "," . $CanForm . "," . $CadaFreUso . "," . $CodFreUso . "," . $Cant . "," . $CantTotal . "," . $CodPerDurTrat . "," . $JustNoPBS . "," . $IndRec . "," . $EstJM . ")";

              //echo $sql_exc;

              $st_proc = oci_parse($conn_oracle, $sql_exc);

              $result = oci_execute($st_proc);
              oci_free_statement($st_proc);
              if ($result) {
                //echo  "<br>Insercion Correcta ";
              } else {
                //echo  "<br>Insercion Incorrecta ";
              }
              /////Insertar prescripcion (Fin)

            }
          }
          unset($vector_procedimientos); //Vaciar el vector



          ///////////////////////////////////////////////////////////////////////////////////////////dispositivos (Inicio)
          //echo  "<br><br>-------------------------------------------------------------------------dispositivos";


          //Obtener cadena general de dispositivos
          $cad_pres_busc_ini = '],"dispositivos"';
          $cad_pres_busc_fin = ',"productosnutricionales"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresDisp = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          //echo "<br> subCadenaPresDisp: " . $subCadenaPresDisp . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los dispositivos////////////////

          $cadenaDisp = $subCadenaPresDisp;
          // $cadenaDisp = str_replace('},"dispositivos":[', 'inicio', $cadenaDisp);
          // $cadenaDisp = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaDisp);
          $subcadenaDispBuscadaInicial   = '{"ConOrden"';
          ////echo "<br> cadenaDisp: " . $cadenaDisp . "<br>";
          $posInicial = strpos($cadenaDisp, $subcadenaDispBuscadaInicial);
          $count_report = 0;

          $vector_dispositivos[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////////////////dispositivos///////////////////////////////////////////
            ///////////////////////////////Se separan Cada uno de los dispositivos (Inicio)/////////////////
            ///////////////////////////////////////////dispositivos///////////////////////////////////////////

            $subcadenaDispBuscadaInicial   = '{"ConOrden"';
            // //echo "<br> sub cadenaDisp Buscada Inicial: " . $subcadenaDispBuscadaInicial;
            $subcadenaDispBuscadaFinal   = '}';
            ////echo "<br> sub cadenaDisp Buscada Final: " . $subcadenaDispBuscadaFinal;
            $posInicial = strpos($cadenaDisp, $subcadenaDispBuscadaInicial);
            // //echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaDisp, $subcadenaDispBuscadaFinal) + 2;
            ////echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaDisp) - 2; //Sera igual a la última posición de la cadenaDisp
              // //echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaDispFinal = substr($cadenaDisp, $posInicial, $posFinal - $posInicial);
            //echo "<br> Sub cadenaDisp: " . $subcadenaDispFinal;
            if ($subcadenaDispFinal != '[' && $subcadenaDispFinal != '') {
              //$vector_dispositivos[$count_report] = str_replace('inicio', '},"dispositivos":[', $subcadenaDispFinal);
              $vector_dispositivos[$count_report] =  $subcadenaDispFinal;
              $count_report++;
            }
            $cadenaDisp = str_replace($subcadenaDispFinal, "", $cadenaDisp);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los dispositivos(Inicio)********************************/
          $longitud_vec_Disp = count($vector_dispositivos);
          for ($count_vec_Disp = 0; $count_vec_Disp < $longitud_vec_Disp; $count_vec_Disp++) {
            //echo "<br> Cadena#" . $count_vec_Disp . ": " . $vector_dispositivos[$count_vec_Disp];
            $subCadenaPresDisp = $vector_dispositivos[$count_vec_Disp];


            if (strlen($subCadenaPresDisp) > 5) { //Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              //echo  "<br><br>-------------------------------------dispositivo# " . ($count_vec_Disp + 1);
              //echo "<br> subCadenaPresDisp: " . $subCadenaPresDisp;

              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              $ConOrden = str_replace('"', "'", $ConOrden);
              //echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS1"';
              $cadena_TipoPrest = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              $TipoPrest = str_replace('"', "'", $TipoPrest);
              //echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS1
              $CausaS1_busc_ini = '"CausaS1":';
              $CausaS1_busc_fin = ',"CodDisp"';
              $cadena_CausaS1 = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CausaS1, $CausaS1_busc_ini) + strlen($CausaS1_busc_ini);
              $posPresFinal = strpos($cadena_CausaS1, $CausaS1_busc_fin);
              $CausaS1 = substr($cadena_CausaS1, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS1 = str_replace('"', "'", $CausaS1);
              //echo "<br> CausaS1: " . $CausaS1;
              //CodDisp
              $CodDisp_busc_ini = '"CodDisp":';
              $CodDisp_busc_fin = ',"CanForm"';
              $cadena_CodDisp = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CodDisp, $CodDisp_busc_ini) + strlen($CodDisp_busc_ini);
              $posPresFinal = strpos($cadena_CodDisp, $CodDisp_busc_fin);
              $CodDisp = substr($cadena_CodDisp, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodDisp = str_replace('"', "'", $CodDisp);
              //echo "<br> CodDisp: " . $CodDisp;
              //CanForm
              $CanForm_busc_ini = '"CanForm":';
              $CanForm_busc_fin = ',"CadaFreUso"';
              $cadena_CanForm = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CanForm, $CanForm_busc_ini) + strlen($CanForm_busc_ini);
              $posPresFinal = strpos($cadena_CanForm, $CanForm_busc_fin);
              $CanForm = substr($cadena_CanForm, $posPresInicial, $posPresFinal - $posPresInicial);
              $CanForm = str_replace('"', "'", $CanForm);
              //echo "<br> CanForm: " . $CanForm;
              //CadaFreUso
              $CadaFreUso_busc_ini = '"CadaFreUso":';
              $CadaFreUso_busc_fin = ',"CodFreUso"';
              $cadena_CadaFreUso = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CadaFreUso, $CadaFreUso_busc_ini) + strlen($CadaFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CadaFreUso, $CadaFreUso_busc_fin);
              $CadaFreUso = substr($cadena_CadaFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              $CadaFreUso = str_replace('"', "'", $CadaFreUso);
              //echo "<br> CadaFreUso: " . $CadaFreUso;
              //CodFreUso
              $CodFreUso_busc_ini = '"CodFreUso":';
              $CodFreUso_busc_fin = ',"Cant"';
              $cadena_CodFreUso = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CodFreUso, $CodFreUso_busc_ini) + strlen($CodFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CodFreUso, $CodFreUso_busc_fin);
              $CodFreUso = substr($cadena_CodFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodFreUso = str_replace('"', "'", $CodFreUso);
              //echo "<br> CodFreUso: " . $CodFreUso;
              //Cant
              $Cant_busc_ini = '"Cant":';
              $Cant_busc_fin = ',"CodPerDurTrat"';
              $cadena_Cant = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_Cant, $Cant_busc_ini) + strlen($Cant_busc_ini);
              $posPresFinal = strpos($cadena_Cant, $Cant_busc_fin);
              $Cant = substr($cadena_Cant, $posPresInicial, $posPresFinal - $posPresInicial);
              $Cant = str_replace('"', "'", $Cant);
              //echo "<br> Cant: " . $Cant;
              //CodPerDurTrat
              $CodPerDurTrat_busc_ini = '"CodPerDurTrat":';
              $CodPerDurTrat_busc_fin = ',"CantTotal"';
              $cadena_CodPerDurTrat = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_ini) + strlen($CodPerDurTrat_busc_ini);
              $posPresFinal = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_fin);
              $CodPerDurTrat = substr($cadena_CodPerDurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodPerDurTrat = str_replace('"', "'", $CodPerDurTrat);
              //echo "<br> CodPerDurTrat: " . $CodPerDurTrat;
              //CantTotal
              $CantTotal_busc_ini = '"CantTotal":';
              $CantTotal_busc_fin = ',"JustNoPBS"';
              $cadena_CantTotal = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CantTotal, $CantTotal_busc_ini) + strlen($CantTotal_busc_ini);
              $posPresFinal = strpos($cadena_CantTotal, $CantTotal_busc_fin);
              $CantTotal = substr($cadena_CantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              $CantTotal = str_replace('"', "'", $CantTotal);
              //echo "<br> CantTotal: " . $CantTotal;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"IndRec"';
              $cadena_JustNoPBS = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              $JustNoPBS = str_replace('"', "'", $JustNoPBS);
              //echo "<br> JustNoPBS: " . $JustNoPBS;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"EstJM"';
              $cadena_IndRec = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              $IndRec = str_replace('"', "'", $IndRec);
              //echo "<br> IndRec: " . $IndRec;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              $EstJM = str_replace('"', "'", $EstJM);
              //echo "<br> EstJM: " . $EstJM;

              /////Insertar dispositivos (Inicio)
              $sql_exc = "INSERT INTO  WEBSERV_PRES_DISP
              (ID_DISP,ID_PRES,CONORDEN,TIPOPREST,CAUSAS1,CODDISP,CANFORM,CADAFREUSO,CODFREUSO,CANT,CODPERDURTRAT,CANTTOTAL,JUSTNOPBS,INDREC,ESTJM)  VALUES 
              (SEQ_WEBSERV_PRES_DISP.nextval," . $id_pres . "," . $ConOrden . "," . $TipoPrest . "," . $CausaS1 . "," . $CodDisp . "," . $CanForm . "," . $CadaFreUso . "," . $CodFreUso . "," . $Cant . "," . $CodPerDurTrat . "," . $CantTotal . "," . $JustNoPBS . "," . $IndRec . "," . $EstJM . ")";

              //echo $sql_exc;

              $st_disp = oci_parse($conn_oracle, $sql_exc);

              $result = oci_execute($st_disp);
              oci_free_statement($st_disp);
              if ($result) {
                //echo  "<br>Insercion Correcta ";
              } else {
                //echo  "<br>Insercion Incorrecta ";
              }
              /////Insertar dispositivos (Fin)


            }
          }
          unset($vector_dispositivos); //Vaciar el vector

          ///////////////////////////////////////////////////////////////////////////////////////////dispositivos (Fin)

          ///////////////////////////////////////////////////////////////////////////////////productosnutricionales (Inicio)
          //echo  "<br><br>-------------------------------------------------------------------------productosnutricionales";

          //Obtener cadena general de productosnutricionales
          $cad_pres_busc_ini = '],"productosnutricionales"';
          $cad_pres_busc_fin = ',"serviciosComplementarios"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresProdNutr = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          //echo "<br> subCadenaPresProdNutr: " . $subCadenaPresProdNutr . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los productosnutricionales////////////////

          $cadenaProdNutr = $subCadenaPresProdNutr;
          // $cadenaProdNutr = str_replace('},"productosnutricionales":[', 'inicio', $cadenaProdNutr);
          // $cadenaProdNutr = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaProdNutr);
          $subcadenaProdNutrBuscadaInicial   = '{"ConOrden"';
          ////echo "<br> cadenaProdNutr: " . $cadenaProdNutr . "<br>";
          $posInicial = strpos($cadenaProdNutr, $subcadenaProdNutrBuscadaInicial);
          $count_report = 0;

          $vector_productosnutricionales[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////////////////productosnutricionales///////////////////////////////////////////
            ///////////////////////////////Se separan Cada uno de los productosnutricionales (Inicio)/////////////////
            ///////////////////////////////////////////productosnutricionales///////////////////////////////////////////

            $subcadenaProdNutrBuscadaInicial   = '{"ConOrden"';
            // //echo "<br> sub cadenaProdNutr Buscada Inicial: " . $subcadenaProdNutrBuscadaInicial;
            $subcadenaProdNutrBuscadaFinal   = '}';
            ////echo "<br> sub cadenaProdNutr Buscada Final: " . $subcadenaProdNutrBuscadaFinal;
            $posInicial = strpos($cadenaProdNutr, $subcadenaProdNutrBuscadaInicial);
            // //echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaProdNutr, $subcadenaProdNutrBuscadaFinal) + 2;
            ////echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaProdNutr) - 2; //Sera igual a la última posición de la cadenaProdNutr
              // //echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaProdNutrFinal = substr($cadenaProdNutr, $posInicial, $posFinal - $posInicial);
            //echo "<br> Sub cadenaProdNutr: " . $subcadenaProdNutrFinal;
            if ($subcadenaProdNutrFinal != '[' && $subcadenaProdNutrFinal != '') {
              //$vector_productosnutricionales[$count_report] = str_replace('inicio', '},"productosnutricionales":[', $subcadenaProdNutrFinal);
              $vector_productosnutricionales[$count_report] =  $subcadenaProdNutrFinal;
              $count_report++;
            }
            $cadenaProdNutr = str_replace($subcadenaProdNutrFinal, "", $cadenaProdNutr);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los productosnutricionales(Inicio)********************************/
          $longitud_vec_ProdNutr = count($vector_productosnutricionales);
          for ($count_vec_ProdNutr = 0; $count_vec_ProdNutr < $longitud_vec_ProdNutr; $count_vec_ProdNutr++) {
            ////echo "<br> Cadena#" . $count_vec_ProdNutr . ": " . $vector_productosnutricionales[$count_vec_ProdNutr];
            $subCadenaPresProdNutr = $vector_productosnutricionales[$count_vec_ProdNutr];


            if (strlen($subCadenaPresProdNutr) > 5) { //Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              //echo  "<br><br>-------------------------------------producto Nutricional# " . ($count_vec_ProdNutr + 1);
              //echo "<br> subCadenaPresProdNutr: " . $subCadenaPresProdNutr;

              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              $ConOrden = str_replace('"', "'", $ConOrden);
              //echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS1"';
              $cadena_TipoPrest = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              $TipoPrest = str_replace('"', "'", $TipoPrest);
              //echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS1
              $CausaS1_busc_ini = '"CausaS1":';
              $CausaS1_busc_fin = ',"CausaS2"';
              $cadena_CausaS1 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS1, $CausaS1_busc_ini) + strlen($CausaS1_busc_ini);
              $posPresFinal = strpos($cadena_CausaS1, $CausaS1_busc_fin);
              $CausaS1 = substr($cadena_CausaS1, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS1 = str_replace('"', "'", $CausaS1);
              //echo "<br> CausaS1: " . $CausaS1;
              //CausaS2
              $CausaS2_busc_ini = '"CausaS2":';
              $CausaS2_busc_fin = ',"CausaS3"';
              $cadena_CausaS2 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS2, $CausaS2_busc_ini) + strlen($CausaS2_busc_ini);
              $posPresFinal = strpos($cadena_CausaS2, $CausaS2_busc_fin);
              $CausaS2 = substr($cadena_CausaS2, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS2 = str_replace('"', "'", $CausaS2);
              //echo "<br> CausaS2: " . $CausaS2;
              //CausaS3
              $CausaS3_busc_ini = '"CausaS3":';
              $CausaS3_busc_fin = ',"CausaS4"';
              $cadena_CausaS3 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS3, $CausaS3_busc_ini) + strlen($CausaS3_busc_ini);
              $posPresFinal = strpos($cadena_CausaS3, $CausaS3_busc_fin);
              $CausaS3 = substr($cadena_CausaS3, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS3 = str_replace('"', "'", $CausaS3);
              //echo "<br> CausaS3: " . $CausaS3;
              //CausaS4
              $CausaS4_busc_ini = '"CausaS4":';
              $CausaS4_busc_fin = ',"ProNutUtilizado"';
              $cadena_CausaS4 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS4, $CausaS4_busc_ini) + strlen($CausaS4_busc_ini);
              $posPresFinal = strpos($cadena_CausaS4, $CausaS4_busc_fin);
              $CausaS4 = substr($cadena_CausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS4 = str_replace('"', "'", $CausaS4);
              //echo "<br> CausaS4: " . $CausaS4;
              //ProNutUtilizado
              $ProNutUtilizado_busc_ini = '"ProNutUtilizado":';
              $ProNutUtilizado_busc_fin = ',"RznCausaS41"';
              $cadena_ProNutUtilizado = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_ProNutUtilizado, $ProNutUtilizado_busc_ini) + strlen($ProNutUtilizado_busc_ini);
              $posPresFinal = strpos($cadena_ProNutUtilizado, $ProNutUtilizado_busc_fin);
              $ProNutUtilizado = substr($cadena_ProNutUtilizado, $posPresInicial, $posPresFinal - $posPresInicial);
              $ProNutUtilizado = str_replace('"', "'", $ProNutUtilizado);
              //echo "<br> ProNutUtilizado: " . $ProNutUtilizado;
              //RznCausaS41
              $RznCausaS41_busc_ini = '"RznCausaS41":';
              $RznCausaS41_busc_fin = ',"DescRzn41"';
              $cadena_RznCausaS41 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS41, $RznCausaS41_busc_ini) + strlen($RznCausaS41_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS41, $RznCausaS41_busc_fin);
              $RznCausaS41 = substr($cadena_RznCausaS41, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS41 = str_replace('"', "'", $RznCausaS41);
              //echo "<br> RznCausaS41: " . $RznCausaS41;
              //DescRzn41
              $DescRzn41_busc_ini = '"DescRzn41":';
              $DescRzn41_busc_fin = ',"RznCausaS42"';
              $cadena_DescRzn41 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn41, $DescRzn41_busc_ini) + strlen($DescRzn41_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn41, $DescRzn41_busc_fin);
              $DescRzn41 = substr($cadena_DescRzn41, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn41 = str_replace('"', "'", $DescRzn41);
              //echo "<br> DescRzn41: " . $DescRzn41;
              //RznCausaS42
              $RznCausaS42_busc_ini = '"RznCausaS42":';
              $RznCausaS42_busc_fin = ',"DescRzn42"';
              $cadena_RznCausaS42 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS42, $RznCausaS42_busc_ini) + strlen($RznCausaS42_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS42, $RznCausaS42_busc_fin);
              $RznCausaS42 = substr($cadena_RznCausaS42, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS42 = str_replace('"', "'", $RznCausaS42);
              //echo "<br> RznCausaS42: " . $RznCausaS42;
              //DescRzn42
              $DescRzn42_busc_ini = '"DescRzn42":';
              $DescRzn42_busc_fin = ',"CausaS5"';
              $cadena_DescRzn42 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn42, $DescRzn42_busc_ini) + strlen($DescRzn42_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn42, $DescRzn42_busc_fin);
              $DescRzn42 = substr($cadena_DescRzn42, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn42 = str_replace('"', "'", $DescRzn42);
              //echo "<br> DescRzn42: " . $DescRzn42;
              //CausaS5
              $CausaS5_busc_ini = '"CausaS5":';
              $CausaS5_busc_fin = ',"ProNutDescartado"';
              $cadena_CausaS5 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS5, $CausaS5_busc_ini) + strlen($CausaS5_busc_ini);
              $posPresFinal = strpos($cadena_CausaS5, $CausaS5_busc_fin);
              $CausaS5 = substr($cadena_CausaS5, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS5 = str_replace('"', "'", $CausaS5);
              //echo "<br> CausaS5: " . $CausaS5;
              //ProNutDescartado
              $ProNutDescartado_busc_ini = '"ProNutDescartado":';
              $ProNutDescartado_busc_fin = ',"RznCausaS51"';
              $cadena_ProNutDescartado = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_ProNutDescartado, $ProNutDescartado_busc_ini) + strlen($ProNutDescartado_busc_ini);
              $posPresFinal = strpos($cadena_ProNutDescartado, $ProNutDescartado_busc_fin);
              $ProNutDescartado = substr($cadena_ProNutDescartado, $posPresInicial, $posPresFinal - $posPresInicial);
              $ProNutDescartado = str_replace('"', "'", $ProNutDescartado);
              //echo "<br> ProNutDescartado: " . $ProNutDescartado;
              //RznCausaS51
              $RznCausaS51_busc_ini = '"RznCausaS51":';
              $RznCausaS51_busc_fin = ',"DescRzn51"';
              $cadena_RznCausaS51 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS51, $RznCausaS51_busc_ini) + strlen($RznCausaS51_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS51, $RznCausaS51_busc_fin);
              $RznCausaS51 = substr($cadena_RznCausaS51, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS51 = str_replace('"', "'", $RznCausaS51);
              //echo "<br> RznCausaS51: " . $RznCausaS51;
              //DescRzn51
              $DescRzn51_busc_ini = '"DescRzn51":';
              $DescRzn51_busc_fin = ',"RznCausaS52"';
              $cadena_DescRzn51 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn51, $DescRzn51_busc_ini) + strlen($DescRzn51_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn51, $DescRzn51_busc_fin);
              $DescRzn51 = substr($cadena_DescRzn51, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn51 = str_replace('"', "'", $DescRzn51);
              //echo "<br> DescRzn51: " . $DescRzn51;
              //RznCausaS52
              $RznCausaS52_busc_ini = '"RznCausaS52":';
              $RznCausaS52_busc_fin = ',"DescRzn52"';
              $cadena_RznCausaS52 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS52, $RznCausaS52_busc_ini) + strlen($RznCausaS52_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS52, $RznCausaS52_busc_fin);
              $RznCausaS52 = substr($cadena_RznCausaS52, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS52 = str_replace('"', "'", $RznCausaS52);
              //echo "<br> RznCausaS52: " . $RznCausaS52;
              //DescRzn52
              $DescRzn52_busc_ini = '"DescRzn52":';
              $DescRzn52_busc_fin = ',"RznCausaS53"';
              $cadena_DescRzn52 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn52, $DescRzn52_busc_ini) + strlen($DescRzn52_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn52, $DescRzn52_busc_fin);
              $DescRzn52 = substr($cadena_DescRzn52, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn52 = str_replace('"', "'", $DescRzn52);
              //echo "<br> DescRzn52: " . $DescRzn52;
              //RznCausaS53
              $RznCausaS53_busc_ini = '"RznCausaS53":';
              $RznCausaS53_busc_fin = ',"DescRzn53"';
              $cadena_RznCausaS53 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS53, $RznCausaS53_busc_ini) + strlen($RznCausaS53_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS53, $RznCausaS53_busc_fin);
              $RznCausaS53 = substr($cadena_RznCausaS53, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS53 = str_replace('"', "'", $RznCausaS53);
              //echo "<br> RznCausaS53: " . $RznCausaS53;
              //DescRzn53
              $DescRzn53_busc_ini = '"DescRzn53":';
              $DescRzn53_busc_fin = ',"RznCausaS54"';
              $cadena_DescRzn53 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn53, $DescRzn53_busc_ini) + strlen($DescRzn53_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn53, $DescRzn53_busc_fin);
              $DescRzn53 = substr($cadena_DescRzn53, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn53 = str_replace('"', "'", $DescRzn53);
              //echo "<br> DescRzn53: " . $DescRzn53;
              //RznCausaS54
              $RznCausaS54_busc_ini = '"RznCausaS54":';
              $RznCausaS54_busc_fin = ',"DescRzn54"';
              $cadena_RznCausaS54 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS54, $RznCausaS54_busc_ini) + strlen($RznCausaS54_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS54, $RznCausaS54_busc_fin);
              $RznCausaS54 = substr($cadena_RznCausaS54, $posPresInicial, $posPresFinal - $posPresInicial);
              $RznCausaS54 = str_replace('"', "'", $RznCausaS54);
              //echo "<br> RznCausaS54: " . $RznCausaS54;
              //DescRzn54
              $DescRzn54_busc_ini = '"DescRzn54":';
              $DescRzn54_busc_fin = ',"DXEnfHuer"';
              $cadena_DescRzn54 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn54, $DescRzn54_busc_ini) + strlen($DescRzn54_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn54, $DescRzn54_busc_fin);
              $DescRzn54 = substr($cadena_DescRzn54, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescRzn54 = str_replace('"', "'", $DescRzn54);
              //echo "<br> DescRzn54: " . $DescRzn54;
              //DXEnfHuer
              $DXEnfHuer_busc_ini = '"DXEnfHuer":';
              $DXEnfHuer_busc_fin = ',"DXVIH"';
              $cadena_DXEnfHuer = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXEnfHuer, $DXEnfHuer_busc_ini) + strlen($DXEnfHuer_busc_ini);
              $posPresFinal = strpos($cadena_DXEnfHuer, $DXEnfHuer_busc_fin);
              $DXEnfHuer = substr($cadena_DXEnfHuer, $posPresInicial, $posPresFinal - $posPresInicial);
              $DXEnfHuer = str_replace('"', "'", $DXEnfHuer);
              //echo "<br> DXEnfHuer: " . $DXEnfHuer;
              //DXVIH
              $DXVIH_busc_ini = '"DXVIH":';
              $DXVIH_busc_fin = ',"DXCaPal"';
              $cadena_DXVIH = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXVIH, $DXVIH_busc_ini) + strlen($DXVIH_busc_ini);
              $posPresFinal = strpos($cadena_DXVIH, $DXVIH_busc_fin);
              $DXVIH = substr($cadena_DXVIH, $posPresInicial, $posPresFinal - $posPresInicial);
              $DXVIH = str_replace('"', "'", $DXVIH);
              //echo "<br> DXVIH: " . $DXVIH;
              //DXCaPal
              $DXCaPal_busc_ini = '"DXCaPal":';
              $DXCaPal_busc_fin = ',"DXEnfRCEV"';
              $cadena_DXCaPal = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXCaPal, $DXCaPal_busc_ini) + strlen($DXCaPal_busc_ini);
              $posPresFinal = strpos($cadena_DXCaPal, $DXCaPal_busc_fin);
              $DXCaPal = substr($cadena_DXCaPal, $posPresInicial, $posPresFinal - $posPresInicial);
              $DXCaPal = str_replace('"', "'", $DXCaPal);
              //echo "<br> DXCaPal: " . $DXCaPal;
              //DXEnfRCEV
              $DXEnfRCEV_busc_ini = '"DXEnfRCEV":';
              $DXEnfRCEV_busc_fin = ',"DXDesPro"';
              $cadena_DXEnfRCEV = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXEnfRCEV, $DXEnfRCEV_busc_ini) + strlen($DXEnfRCEV_busc_ini);
              $posPresFinal = strpos($cadena_DXEnfRCEV, $DXEnfRCEV_busc_fin);
              $DXEnfRCEV = substr($cadena_DXEnfRCEV, $posPresInicial, $posPresFinal - $posPresInicial);
              $DXEnfRCEV = str_replace('"', "'", $DXEnfRCEV);
              //echo "<br> DXEnfRCEV: " . $DXEnfRCEV;
              //DXDesPro
              $DXDesPro_busc_ini = '"DXDesPro":';
              $DXDesPro_busc_fin = ',"TippProNut"';
              $cadena_DXDesPro = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXDesPro, $DXDesPro_busc_ini) + strlen($DXDesPro_busc_ini);
              $posPresFinal = strpos($cadena_DXDesPro, $DXDesPro_busc_fin);
              $DXDesPro = substr($cadena_DXDesPro, $posPresInicial, $posPresFinal - $posPresInicial);
              $DXDesPro = str_replace('"', "'", $DXDesPro);
              //echo "<br> DXDesPro: " . $DXDesPro;
              //TippProNut
              $TippProNut_busc_ini = '"TippProNut":';
              $TippProNut_busc_fin = ',"DescProdNutr"';
              $cadena_TippProNut = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_TippProNut, $TippProNut_busc_ini) + strlen($TippProNut_busc_ini);
              $posPresFinal = strpos($cadena_TippProNut, $TippProNut_busc_fin);
              $TippProNut = substr($cadena_TippProNut, $posPresInicial, $posPresFinal - $posPresInicial);
              $TippProNut = str_replace('"', "'", $TippProNut);
              //echo "<br> TippProNut: " . $TippProNut;
              //DescProdNutr
              $DescProdNutr_busc_ini = '"DescProdNutr":';
              $DescProdNutr_busc_fin = ',"CodForma"';
              $cadena_DescProdNutr = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescProdNutr, $DescProdNutr_busc_ini) + strlen($DescProdNutr_busc_ini);
              $posPresFinal = strpos($cadena_DescProdNutr, $DescProdNutr_busc_fin);
              $DescProdNutr = substr($cadena_DescProdNutr, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescProdNutr = str_replace('"', "'", $DescProdNutr);
              //echo "<br> DescProdNutr: " . $DescProdNutr;
              //CodForma
              $CodForma_busc_ini = '"CodForma":';
              $CodForma_busc_fin = ',"CodViaAdmon"';
              $cadena_CodForma = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CodForma, $CodForma_busc_ini) + strlen($CodForma_busc_ini);
              $posPresFinal = strpos($cadena_CodForma, $CodForma_busc_fin);
              $CodForma = substr($cadena_CodForma, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodForma = str_replace('"', "'", $CodForma);
              //echo "<br> CodForma: " . $CodForma;
              //CodViaAdmon
              $CodViaAdmon_busc_ini = '"CodViaAdmon":';
              $CodViaAdmon_busc_fin = ',"JustNoPBS"';
              $cadena_CodViaAdmon = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CodViaAdmon, $CodViaAdmon_busc_ini) + strlen($CodViaAdmon_busc_ini);
              $posPresFinal = strpos($cadena_CodViaAdmon, $CodViaAdmon_busc_fin);
              $CodViaAdmon = substr($cadena_CodViaAdmon, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodViaAdmon = str_replace('"', "'", $CodViaAdmon);
              //echo "<br> CodViaAdmon: " . $CodViaAdmon;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"Dosis"';
              $cadena_JustNoPBS = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              $JustNoPBS = str_replace('"', "'", $JustNoPBS);
              //echo "<br> JustNoPBS: " . $JustNoPBS;
              //Dosis
              $Dosis_busc_ini = '"Dosis":';
              $Dosis_busc_fin = ',"DosisUM"';
              $cadena_Dosis = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_Dosis, $Dosis_busc_ini) + strlen($Dosis_busc_ini);
              $posPresFinal = strpos($cadena_Dosis, $Dosis_busc_fin);
              $Dosis = substr($cadena_Dosis, $posPresInicial, $posPresFinal - $posPresInicial);
              $Dosis = str_replace('"', "'", $Dosis);
              //echo "<br> Dosis: " . $Dosis;
              //DosisUM
              $DosisUM_busc_ini = '"DosisUM":';
              $DosisUM_busc_fin = ',"NoFAdmon"';
              $cadena_DosisUM = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DosisUM, $DosisUM_busc_ini) + strlen($DosisUM_busc_ini);
              $posPresFinal = strpos($cadena_DosisUM, $DosisUM_busc_fin);
              $DosisUM = substr($cadena_DosisUM, $posPresInicial, $posPresFinal - $posPresInicial);
              $DosisUM = str_replace('"', "'", $DosisUM);
              //echo "<br> DosisUM: " . $DosisUM;
              //NoFAdmon
              $NoFAdmon_busc_ini = '"NoFAdmon":';
              $NoFAdmon_busc_fin = ',"CodFreAdmon"';
              $cadena_NoFAdmon = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_NoFAdmon, $NoFAdmon_busc_ini) + strlen($NoFAdmon_busc_ini);
              $posPresFinal = strpos($cadena_NoFAdmon, $NoFAdmon_busc_fin);
              $NoFAdmon = substr($cadena_NoFAdmon, $posPresInicial, $posPresFinal - $posPresInicial);
              $NoFAdmon = str_replace('"', "'", $NoFAdmon);
              //echo "<br> NoFAdmon: " . $NoFAdmon;
              //CodFreAdmon
              $CodFreAdmon_busc_ini = '"CodFreAdmon":';
              $CodFreAdmon_busc_fin = ',"IndEsp"';
              $cadena_CodFreAdmon = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CodFreAdmon, $CodFreAdmon_busc_ini) + strlen($CodFreAdmon_busc_ini);
              $posPresFinal = strpos($cadena_CodFreAdmon, $CodFreAdmon_busc_fin);
              $CodFreAdmon = substr($cadena_CodFreAdmon, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodFreAdmon = str_replace('"', "'", $CodFreAdmon);
              //echo "<br> CodFreAdmon: " . $CodFreAdmon;
              //IndEsp
              $IndEsp_busc_ini = '"IndEsp":';
              $IndEsp_busc_fin = ',"CanTrat"';
              $cadena_IndEsp = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_IndEsp, $IndEsp_busc_ini) + strlen($IndEsp_busc_ini);
              $posPresFinal = strpos($cadena_IndEsp, $IndEsp_busc_fin);
              $IndEsp = substr($cadena_IndEsp, $posPresInicial, $posPresFinal - $posPresInicial);
              $IndEsp = str_replace('"', "'", $IndEsp);
              //echo "<br> IndEsp: " . $IndEsp;
              //CanTrat
              $CanTrat_busc_ini = '"CanTrat":';
              $CanTrat_busc_fin = ',"DurTrat"';
              $cadena_CanTrat = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CanTrat, $CanTrat_busc_ini) + strlen($CanTrat_busc_ini);
              $posPresFinal = strpos($cadena_CanTrat, $CanTrat_busc_fin);
              $CanTrat = substr($cadena_CanTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              $CanTrat = str_replace('"', "'", $CanTrat);
              //echo "<br> CanTrat: " . $CanTrat;
              //DurTrat
              $DurTrat_busc_ini = '"DurTrat":';
              $DurTrat_busc_fin = ',"CantTotalF"';
              $cadena_DurTrat = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DurTrat, $DurTrat_busc_ini) + strlen($DurTrat_busc_ini);
              $posPresFinal = strpos($cadena_DurTrat, $DurTrat_busc_fin);
              $DurTrat = substr($cadena_DurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              $DurTrat = str_replace('"', "'", $DurTrat);
              //echo "<br> DurTrat: " . $DurTrat;
              //CantTotalF
              $CantTotalF_busc_ini = '"CantTotalF":';
              $CantTotalF_busc_fin = ',"UFCantTotal"';
              $cadena_CantTotalF = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CantTotalF, $CantTotalF_busc_ini) + strlen($CantTotalF_busc_ini);
              $posPresFinal = strpos($cadena_CantTotalF, $CantTotalF_busc_fin);
              $CantTotalF = substr($cadena_CantTotalF, $posPresInicial, $posPresFinal - $posPresInicial);
              $CantTotalF = str_replace('"', "'", $CantTotalF);
              //echo "<br> CantTotalF: " . $CantTotalF;
              //UFCantTotal
              $UFCantTotal_busc_ini = '"UFCantTotal":';
              $UFCantTotal_busc_fin = ',"IndRec"';
              $cadena_UFCantTotal = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_UFCantTotal, $UFCantTotal_busc_ini) + strlen($UFCantTotal_busc_ini);
              $posPresFinal = strpos($cadena_UFCantTotal, $UFCantTotal_busc_fin);
              $UFCantTotal = substr($cadena_UFCantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              $UFCantTotal = str_replace('"', "'", $UFCantTotal);
              //echo "<br> UFCantTotal: " . $UFCantTotal;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"NoPrescAso"';
              $cadena_IndRec = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              $IndRec = str_replace('"', "'", $IndRec);
              //echo "<br> IndRec: " . $IndRec;
              //NoPrescAso
              $NoPrescAso_busc_ini = '"NoPrescAso":';
              $NoPrescAso_busc_fin = ',"EstJM"';
              $cadena_NoPrescAso = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_NoPrescAso, $NoPrescAso_busc_ini) + strlen($NoPrescAso_busc_ini);
              $posPresFinal = strpos($cadena_NoPrescAso, $NoPrescAso_busc_fin);
              $NoPrescAso = substr($cadena_NoPrescAso, $posPresInicial, $posPresFinal - $posPresInicial);
              $NoPrescAso = str_replace('"', "'", $NoPrescAso);
              //echo "<br> NoPrescAso: " . $NoPrescAso;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              $EstJM = str_replace('"', "'", $EstJM);
              //echo "<br> EstJM: " . $EstJM;



              /////Insertar productos nutricionales (Inicio)
              $sql_exc = "INSERT INTO  WEBSERV_PRES_PROD_NUTR
              (ID_PRNU,ID_PRES,CONORDEN,TIPOPREST,CAUSAS1,CAUSAS2,CAUSAS3,CAUSAS4,PRONUTUTILIZADO,RZNCAUSAS41,DESCRZN41,RZNCAUSAS42,DESCRZN42,CAUSAS5,PRONUTDESCARTADO,RZNCAUSAS51,DESCRZN51,RZNCAUSAS52,DESCRZN52,RZNCAUSAS53,DESCRZN53,RZNCAUSAS54,DESCRZN54,DXENFHUER,DXVIH,DXCAPAL,DXENFRCEV,DXDESPRO,TIPPPRONUT,DESCPRODNUTR,CODFORMA,CODVIAADMON,JUSTNOPBS,DOSIS,DOSISUM,NOFADMON,CODFREADMON,INDESP,CANTRAT,DURTRAT,CANTTOTALF,UFCANTTOTAL,INDREC,NOPRESCASO,ESTJM)  VALUES 
              (SEQ_WEBSERV_PRES_PROD_NUTR.nextval," . $id_pres . "," . $ConOrden . "," . $TipoPrest . "," . $CausaS1 . "," . $CausaS2 . "," . $CausaS3 . "," . $CausaS4 . "," . $ProNutUtilizado . "," . $RznCausaS41 . "," . $DescRzn41 . "," . $RznCausaS42 . "," . $DescRzn42 . "," . $CausaS5 . "," . $ProNutDescartado . "," . $RznCausaS51 . "," . $DescRzn51 . "," . $RznCausaS52 . "," . $DescRzn52 . "," . $RznCausaS53 . "," . $DescRzn53 . "," . $RznCausaS54 . "," . $DescRzn54 . "," . $DXEnfHuer . "," . $DXVIH . "," . $DXCaPal . "," . $DXEnfRCEV . "," . $DXDesPro . "," . $TippProNut . "," . $DescProdNutr . "," . $CodForma . "," . $CodViaAdmon . "," . $JustNoPBS . "," . $Dosis . "," . $DosisUM . "," . $NoFAdmon . "," . $CodFreAdmon . "," . $IndEsp .  "," . $CanTrat . "," . $DurTrat . "," . $CantTotalF . "," . $UFCantTotal . "," . $IndRec . "," . $NoPrescAso . "," . $EstJM . ")";

              //echo $sql_exc;

              $st_pr_nu = oci_parse($conn_oracle, $sql_exc);

              $result = oci_execute($st_pr_nu);
              oci_free_statement($st_pr_nu);
              if ($result) {
                //echo  "<br>Insercion Correcta ";
              } else {
                //echo  "<br>Insercion Incorrecta ";
              }
              /////Insertar productos nutricionales (Fin)


            }
          }
          unset($vector_productosnutricionales); //Vaciar el vector

          ////////////////////////////////////////////////////////////////////////////////////productosnutricionales (Fin)

          //////////////////////////////////////////////////////////////////////////////serviciosComplementarios (Inicio)
          //echo  "<br><br>-------------------------------------------------------------------------serviciosComplementarios";

          //Obtener cadena general de serviciosComplementarios
          $cad_pres_busc_ini = '],"serviciosComplementarios"';
          // $cad_pres_busc_fin = '';
          $cadena_presc = $array[$i];
          //echo "<br> cadena_presc: " . $cadena_presc . "<br>";
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strlen($cadena_presc); //strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresServComp = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          //echo "<br> subCadenaPresServComp: " . $subCadenaPresServComp . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los serviciosComplementarios////////////////

          $cadenaServComp = $subCadenaPresServComp;
          // $cadenaServComp = str_replace('},"serviciosComplementarios":[', 'inicio', $cadenaServComp);
          // $cadenaServComp = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaServComp);
          $subcadenaServCompBuscadaInicial   = '{"ConOrden"';
          ////echo "<br> cadenaServComp: " . $cadenaServComp . "<br>";
          $posInicial = strpos($cadenaServComp, $subcadenaServCompBuscadaInicial);
          $count_report = 0;

          $vector_serviciosComplementarios[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ////////////////////////////////////serviciosComplementarios////////////////////////////////////////
            ///////////////////////Se separan Cada uno de los serviciosComplementarios (Inicio)/////////////////
            //////////////////////////////////serviciosComplementarios//////////////////////////////////////////

            $subcadenaServCompBuscadaInicial   = '{"ConOrden"';
            // //echo "<br> sub cadenaServComp Buscada Inicial: " . $subcadenaServCompBuscadaInicial;
            $subcadenaServCompBuscadaFinal   = '}';
            ////echo "<br> sub cadenaServComp Buscada Final: " . $subcadenaServCompBuscadaFinal;
            $posInicial = strpos($cadenaServComp, $subcadenaServCompBuscadaInicial);
            // //echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaServComp, $subcadenaServCompBuscadaFinal) + 2;
            ////echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaServComp) - 2; //Sera igual a la última posición de la cadenaServComp
              // //echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaServCompFinal = substr($cadenaServComp, $posInicial, $posFinal - $posInicial);
            //echo "<br> Sub cadenaServComp: " . $subcadenaServCompFinal;
            if ($subcadenaServCompFinal != '[' && $subcadenaServCompFinal != '') {
              //$vector_serviciosComplementarios[$count_report] = str_replace('inicio', '},"serviciosComplementarios":[', $subcadenaServCompFinal);
              $vector_serviciosComplementarios[$count_report] =  $subcadenaServCompFinal;
              $count_report++;
            }
            $cadenaServComp = str_replace($subcadenaServCompFinal, "", $cadenaServComp);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los serviciosComplementarios(Inicio)********************************/
          $longitud_vec_ServComp = count($vector_serviciosComplementarios);
          for ($count_vec_ServComp = 0; $count_vec_ServComp < $longitud_vec_ServComp; $count_vec_ServComp++) {
            ////echo "<br> Cadena#" . $count_vec_ServComp . ": " . $vector_serviciosComplementarios[$count_vec_ServComp];
            $subCadenaPresServComp = $vector_serviciosComplementarios[$count_vec_ServComp];


            if (strlen($subCadenaPresServComp) > 50) { //Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              ////si la cadena tiene solamente el texto que esta en la condicion quiere decir que no hay servicios Complementarios para leer
              //echo  "<br><br>-------------------------------------servicio Complementario # " . ($count_vec_ServComp + 1);
              //echo "<br> subCadenaPresServComp: " . $subCadenaPresServComp;

              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              $ConOrden = str_replace('"', "'", $ConOrden);
              //echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS1"';
              $cadena_TipoPrest = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              $TipoPrest = str_replace('"', "'", $TipoPrest);
              //echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS1
              $CausaS1_busc_ini = '"CausaS1":';
              $CausaS1_busc_fin = ',"CausaS2"';
              $cadena_CausaS1 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS1, $CausaS1_busc_ini) + strlen($CausaS1_busc_ini);
              $posPresFinal = strpos($cadena_CausaS1, $CausaS1_busc_fin);
              $CausaS1 = substr($cadena_CausaS1, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS1 = str_replace('"', "'", $CausaS1);
              //echo "<br> CausaS1: " . $CausaS1;
              //CausaS2
              $CausaS2_busc_ini = '"CausaS2":';
              $CausaS2_busc_fin = ',"CausaS3"';
              $cadena_CausaS2 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS2, $CausaS2_busc_ini) + strlen($CausaS2_busc_ini);
              $posPresFinal = strpos($cadena_CausaS2, $CausaS2_busc_fin);
              $CausaS2 = substr($cadena_CausaS2, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS2 = str_replace('"', "'", $CausaS2);
              //echo "<br> CausaS2: " . $CausaS2;
              //CausaS3
              $CausaS3_busc_ini = '"CausaS3":';
              $CausaS3_busc_fin = ',"CausaS4"';
              $cadena_CausaS3 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS3, $CausaS3_busc_ini) + strlen($CausaS3_busc_ini);
              $posPresFinal = strpos($cadena_CausaS3, $CausaS3_busc_fin);
              $CausaS3 = substr($cadena_CausaS3, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS3 = str_replace('"', "'", $CausaS3);
              //echo "<br> CausaS3: " . $CausaS3;
              //CausaS4
              $CausaS4_busc_ini = '"CausaS4":';
              $CausaS4_busc_fin = ',"DescCausaS4"';
              $cadena_CausaS4 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS4, $CausaS4_busc_ini) + strlen($CausaS4_busc_ini);
              $posPresFinal = strpos($cadena_CausaS4, $CausaS4_busc_fin);
              $CausaS4 = substr($cadena_CausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS4 = str_replace('"', "'", $CausaS4);
              //echo "<br> CausaS4: " . $CausaS4;
              //DescCausaS4
              $DescCausaS4_busc_ini = '"DescCausaS4":';
              $DescCausaS4_busc_fin = ',"CausaS5"';
              $cadena_DescCausaS4 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_DescCausaS4, $DescCausaS4_busc_ini) + strlen($DescCausaS4_busc_ini);
              $posPresFinal = strpos($cadena_DescCausaS4, $DescCausaS4_busc_fin);
              $DescCausaS4 = substr($cadena_DescCausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescCausaS4 = str_replace('"', "'", $DescCausaS4);
              //echo "<br> DescCausaS4: " . $DescCausaS4;
              //CausaS5
              $CausaS5_busc_ini = '"CausaS5":';
              $CausaS5_busc_fin = ',"CodSerComp"';
              $cadena_CausaS5 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS5, $CausaS5_busc_ini) + strlen($CausaS5_busc_ini);
              $posPresFinal = strpos($cadena_CausaS5, $CausaS5_busc_fin);
              $CausaS5 = substr($cadena_CausaS5, $posPresInicial, $posPresFinal - $posPresInicial);
              $CausaS5 = str_replace('"', "'", $CausaS5);
              //echo "<br> CausaS5: " . $CausaS5;
              //CodSerComp
              $CodSerComp_busc_ini = '"CodSerComp":';
              $CodSerComp_busc_fin = ',"DescSerComp"';
              $cadena_CodSerComp = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodSerComp, $CodSerComp_busc_ini) + strlen($CodSerComp_busc_ini);
              $posPresFinal = strpos($cadena_CodSerComp, $CodSerComp_busc_fin);
              $CodSerComp = substr($cadena_CodSerComp, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodSerComp = str_replace('"', "'", $CodSerComp);
              //echo "<br> CodSerComp: " . $CodSerComp;
              //DescSerComp
              $DescSerComp_busc_ini = '"DescSerComp":';
              $DescSerComp_busc_fin = ',"CanForm"';
              $cadena_DescSerComp = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_DescSerComp, $DescSerComp_busc_ini) + strlen($DescSerComp_busc_ini);
              $posPresFinal = strpos($cadena_DescSerComp, $DescSerComp_busc_fin);
              $DescSerComp = substr($cadena_DescSerComp, $posPresInicial, $posPresFinal - $posPresInicial);
              $DescSerComp = str_replace('"', "'", $DescSerComp);
              //echo "<br> DescSerComp: " . $DescSerComp;
              //CanForm
              $CanForm_busc_ini = '"CanForm":';
              $CanForm_busc_fin = ',"CadaFreUso"';
              $cadena_CanForm = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CanForm, $CanForm_busc_ini) + strlen($CanForm_busc_ini);
              $posPresFinal = strpos($cadena_CanForm, $CanForm_busc_fin);
              $CanForm = substr($cadena_CanForm, $posPresInicial, $posPresFinal - $posPresInicial);
              $CanForm = str_replace('"', "'", $CanForm);
              //echo "<br> CanForm: " . $CanForm;
              //CadaFreUso
              $CadaFreUso_busc_ini = '"CadaFreUso":';
              $CadaFreUso_busc_fin = ',"CodFreUso"';
              $cadena_CadaFreUso = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CadaFreUso, $CadaFreUso_busc_ini) + strlen($CadaFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CadaFreUso, $CadaFreUso_busc_fin);
              $CadaFreUso = substr($cadena_CadaFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              $CadaFreUso = str_replace('"', "'", $CadaFreUso);
              //echo "<br> CadaFreUso: " . $CadaFreUso;
              //CodFreUso
              $CodFreUso_busc_ini = '"CodFreUso":';
              $CodFreUso_busc_fin = ',"Cant"';
              $cadena_CodFreUso = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodFreUso, $CodFreUso_busc_ini) + strlen($CodFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CodFreUso, $CodFreUso_busc_fin);
              $CodFreUso = substr($cadena_CodFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodFreUso = str_replace('"', "'", $CodFreUso);
              //echo "<br> CodFreUso: " . $CodFreUso;
              //Cant
              $Cant_busc_ini = '"Cant":';
              $Cant_busc_fin = ',"CantTotal"';
              $cadena_Cant = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_Cant, $Cant_busc_ini) + strlen($Cant_busc_ini);
              $posPresFinal = strpos($cadena_Cant, $Cant_busc_fin);
              $Cant = substr($cadena_Cant, $posPresInicial, $posPresFinal - $posPresInicial);
              $Cant = str_replace('"', "'", $Cant);
              //echo "<br> Cant: " . $Cant;
              //CantTotal
              $CantTotal_busc_ini = '"CantTotal":';
              $CantTotal_busc_fin = ',"CodPerDurTrat"';
              $cadena_CantTotal = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CantTotal, $CantTotal_busc_ini) + strlen($CantTotal_busc_ini);
              $posPresFinal = strpos($cadena_CantTotal, $CantTotal_busc_fin);
              $CantTotal = substr($cadena_CantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              $CantTotal = str_replace('"', "'", $CantTotal);
              //echo "<br> CantTotal: " . $CantTotal;
              //CodPerDurTrat
              $CodPerDurTrat_busc_ini = '"CodPerDurTrat":';
              $CodPerDurTrat_busc_fin = ',"TipoTrans"';
              $cadena_CodPerDurTrat = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_ini) + strlen($CodPerDurTrat_busc_ini);
              $posPresFinal = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_fin);
              $CodPerDurTrat = substr($cadena_CodPerDurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodPerDurTrat = str_replace('"', "'", $CodPerDurTrat);
              //echo "<br> CodPerDurTrat: " . $CodPerDurTrat;
              //TipoTrans
              $TipoTrans_busc_ini = '"TipoTrans":';
              $TipoTrans_busc_fin = ',"ReqAcom"';
              $cadena_TipoTrans = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_TipoTrans, $TipoTrans_busc_ini) + strlen($TipoTrans_busc_ini);
              $posPresFinal = strpos($cadena_TipoTrans, $TipoTrans_busc_fin);
              $TipoTrans = substr($cadena_TipoTrans, $posPresInicial, $posPresFinal - $posPresInicial);
              $TipoTrans = str_replace('"', "'", $TipoTrans);
              //echo "<br> TipoTrans: " . $TipoTrans;
              //ReqAcom
              $ReqAcom_busc_ini = '"ReqAcom":';
              $ReqAcom_busc_fin = ',"TipoIDAcomAlb"';
              $cadena_ReqAcom = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_ReqAcom, $ReqAcom_busc_ini) + strlen($ReqAcom_busc_ini);
              $posPresFinal = strpos($cadena_ReqAcom, $ReqAcom_busc_fin);
              $ReqAcom = substr($cadena_ReqAcom, $posPresInicial, $posPresFinal - $posPresInicial);
              $ReqAcom = str_replace('"', "'", $ReqAcom);
              //echo "<br> ReqAcom: " . $ReqAcom;
              //TipoIDAcomAlb
              $TipoIDAcomAlb_busc_ini = '"TipoIDAcomAlb":';
              $TipoIDAcomAlb_busc_fin = ',"NroIDAcomAlb"';
              $cadena_TipoIDAcomAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_TipoIDAcomAlb, $TipoIDAcomAlb_busc_ini) + strlen($TipoIDAcomAlb_busc_ini);
              $posPresFinal = strpos($cadena_TipoIDAcomAlb, $TipoIDAcomAlb_busc_fin);
              $TipoIDAcomAlb = substr($cadena_TipoIDAcomAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              $TipoIDAcomAlb = str_replace('"', "'", $TipoIDAcomAlb);
              //echo "<br> TipoIDAcomAlb: " . $TipoIDAcomAlb;
              //NroIDAcomAlb
              $NroIDAcomAlb_busc_ini = '"NroIDAcomAlb":';
              $NroIDAcomAlb_busc_fin = ',"ParentAcomAlb"';
              $cadena_NroIDAcomAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_NroIDAcomAlb, $NroIDAcomAlb_busc_ini) + strlen($NroIDAcomAlb_busc_ini);
              $posPresFinal = strpos($cadena_NroIDAcomAlb, $NroIDAcomAlb_busc_fin);
              $NroIDAcomAlb = substr($cadena_NroIDAcomAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              $NroIDAcomAlb = str_replace('"', "'", $NroIDAcomAlb);
              //echo "<br> NroIDAcomAlb: " . $NroIDAcomAlb;
              //ParentAcomAlb
              $ParentAcomAlb_busc_ini = '"ParentAcomAlb":';
              $ParentAcomAlb_busc_fin = ',"NombAlb"';
              $cadena_ParentAcomAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_ParentAcomAlb, $ParentAcomAlb_busc_ini) + strlen($ParentAcomAlb_busc_ini);
              $posPresFinal = strpos($cadena_ParentAcomAlb, $ParentAcomAlb_busc_fin);
              $ParentAcomAlb = substr($cadena_ParentAcomAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              $ParentAcomAlb = str_replace('"', "'", $ParentAcomAlb);
              //echo "<br> ParentAcomAlb: " . $ParentAcomAlb;
              //NombAlb
              $NombAlb_busc_ini = '"NombAlb":';
              $NombAlb_busc_fin = ',"CodMunOriAlb"';
              $cadena_NombAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_NombAlb, $NombAlb_busc_ini) + strlen($NombAlb_busc_ini);
              $posPresFinal = strpos($cadena_NombAlb, $NombAlb_busc_fin);
              $NombAlb = substr($cadena_NombAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              $NombAlb = str_replace('"', "'", $NombAlb);
              //echo "<br> NombAlb: " . $NombAlb;
              //CodMunOriAlb
              $CodMunOriAlb_busc_ini = '"CodMunOriAlb":';
              $CodMunOriAlb_busc_fin = ',"CodMunDesAlb"';
              $cadena_CodMunOriAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodMunOriAlb, $CodMunOriAlb_busc_ini) + strlen($CodMunOriAlb_busc_ini);
              $posPresFinal = strpos($cadena_CodMunOriAlb, $CodMunOriAlb_busc_fin);
              $CodMunOriAlb = substr($cadena_CodMunOriAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodMunOriAlb = str_replace('"', "'", $CodMunOriAlb);
              //echo "<br> CodMunOriAlb: " . $CodMunOriAlb;
              //CodMunDesAlb
              $CodMunDesAlb_busc_ini = '"CodMunDesAlb":';
              $CodMunDesAlb_busc_fin = ',"JustNoPBS"';
              $cadena_CodMunDesAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodMunDesAlb, $CodMunDesAlb_busc_ini) + strlen($CodMunDesAlb_busc_ini);
              $posPresFinal = strpos($cadena_CodMunDesAlb, $CodMunDesAlb_busc_fin);
              $CodMunDesAlb = substr($cadena_CodMunDesAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              $CodMunDesAlb = str_replace('"', "'", $CodMunDesAlb);
              //echo "<br> CodMunDesAlb: " . $CodMunDesAlb;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"IndRec"';
              $cadena_JustNoPBS = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              $JustNoPBS = str_replace('"', "'", $JustNoPBS);
              //echo "<br> JustNoPBS: " . $JustNoPBS;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"EstJM"';
              $cadena_IndRec = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              $IndRec = str_replace('"', "'", $IndRec);
              //echo "<br> IndRec: " . $IndRec;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              $EstJM = str_replace('"', "'", $EstJM);
              //echo "<br> EstJM: " . $EstJM;


              /////Insertar servicios Complementarios (Inicio)
              $sql_exc = "INSERT INTO  WEBSERV_PRES_SERV_COMP
                (ID_SECO,ID_PRES,CONORDEN,TIPOPREST,CAUSAS1,CAUSAS2,CAUSAS3,CAUSAS4,DESCCAUSAS4,CAUSAS5,CODSERCOMP,DESCSERCOMP,CANFORM,CADAFREUSO,CODFREUSO,CANT,CANTTOTAL,CODPERDURTRAT,TIPOTRANS,REQACOM,TIPOIDACOMALB,NROIDACOMALB,PARENTACOMALB,NOMBALB,CODMUNORIALB,CODMUNDESALB,JUSTNOPBS,INDREC,ESTJM)  VALUES 
                (SEQ_WEBSERV_PRES_SERV_COMP.nextval," . $id_pres . "," . $ConOrden . "," . $TipoPrest . "," . $CausaS1 . "," . $CausaS2 . "," . $CausaS3 . "," . $CausaS4 . "," . $DescCausaS4 . "," . $CausaS5 . "," . $CodSerComp . "," . $DescSerComp . "," . $CanForm . "," . $CadaFreUso . "," . $CodFreUso . "," . $Cant . "," . $CantTotal . "," . $CodPerDurTrat . "," . $TipoTrans . "," . $ReqAcom . "," . $TipoIDAcomAlb . "," . $NroIDAcomAlb . "," . $ParentAcomAlb . "," . $NombAlb . "," . $CodMunOriAlb . "," . $CodMunDesAlb . "," . $JustNoPBS . "," . $IndRec . "," . $EstJM . ")";

              //echo "<br>".$sql_exc."<br>";

              $st_se_co = oci_parse($conn_oracle, $sql_exc);

              $result = oci_execute($st_se_co);
              oci_free_statement($st_se_co);
              if ($result) {
                //echo  "<br>Insercion Correcta ";
              } else {
                //echo  "<br>Insercion Incorrecta ";
              }
              /////Insertar servicios Complementarios (Fin)


            }
          }
          unset($vector_serviciosComplementarios); //Vaciar el vector
          ///////////////////////////////////////////////////////////////////////////////////serviciosComplementarios (Fin)

          //Obtener cadena general de prescripcion
          ////echo "<br> Sub Cadena: " . $array[$i];
          //echo  "<br><h1 style='color:#FF0000'>-----------------------------------------------------------------------------------------------------------------------------------</h1>";
        }
        echo "<br>Cantidad de prescripciones: " . $longitud . "<br>";
      }
    } else {
      //echo "<br>El registro ya exsiste";
    }
  }
}
mysqli_close($conn);
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

//oci_close($conn);
