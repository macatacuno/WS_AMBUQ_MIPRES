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


///////Declaracion de Variables Generales(Inicio)/////////
$peri_error = "";
$peri_error_conteo = 0;
$periodos_cargados = "";
$periodos_cargados_conteo = 0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "subsidiado";
$tipo_id = 1;
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
$periodo_inicial = "20-01-15";
$periodo_final = "20-01-15";
//$periodo_final = (string) date("y-m-d", strtotime(date('y-m-d') . "- 1 day"));






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
      $json = (string) file_get_contents($url);
      $json = str_replace("\\\"", "", $json);
      $fecha_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato originar "y/m/d"

      if ($json == "") {


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



        /**************************************************************************************************/
        /************(Inicio)Bloque para separar el json en diferentes prescripciones que se retornan******/
        /**************************************************************************************************/
        /////////////////////////////////////////General////////////////////////////////////////////////////

        $subCadenaBuscadaInicial   = '{"junta_profesional"';
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
          $subCadenaBuscadaInicial   = '{"junta_profesional"';
          // echo "<br> sub Cadena Buscada Inicial: " . $subCadenaBuscadaInicial;
          $subCadenaBuscadaFinal   = ',{"junta_profesional"';
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
          //Obtener cadena general de Junta profecional
          
          if ($array != '') {
            //echo "<br> array: " . $array;
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
            $FPrescripcion_busc_fin = ',"TipoTecnologia"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $FPrescripcion_busc_ini) + strlen($FPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $FPrescripcion_busc_fin);
            $FPrescripcion = "'" . substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial) . "'";
            //echo "<br> FPrescripcion: " . $FPrescripcion;
            //HPrescripcion
            $HPrescripcion_busc_ini = '"TipoTecnologia":';
            $HPrescripcion_busc_fin = ',"Consecutivo"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $HPrescripcion_busc_ini) + strlen($HPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $HPrescripcion_busc_fin);
            $HPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> HPrescripcion: " . $HPrescripcion;
            //CodHabIPS
            $CodHabIPS_busc_ini = '"Consecutivo":';
            $CodHabIPS_busc_fin = ',"EstJM"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodHabIPS_busc_ini) + strlen($CodHabIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodHabIPS_busc_fin);
            $CodHabIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodHabIPS: " . $CodHabIPS;
            //TipoIDIPS
            $TipoIDIPS_busc_ini = '"EstJM":';
            $TipoIDIPS_busc_fin = ',"Observaciones"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDIPS_busc_ini) + strlen($TipoIDIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDIPS_busc_fin);
            $TipoIDIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDIPS: " . $TipoIDIPS;
            //NroIDIPS
            $NroIDIPS_busc_ini = '"Observaciones":';
            $NroIDIPS_busc_fin = ',"JustificacionTecnica"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDIPS_busc_ini) + strlen($NroIDIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDIPS_busc_fin);
            $NroIDIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NroIDIPS: " . $NroIDIPS;
            //CodDANEMunIPS
            $CodDANEMunIPS_busc_ini = '"JustificacionTecnica":';
            $CodDANEMunIPS_busc_fin = ',"Modalidad"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDANEMunIPS_busc_ini) + strlen($CodDANEMunIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDANEMunIPS_busc_fin);
            $CodDANEMunIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> CodDANEMunIPS: " . $CodDANEMunIPS;
            //DirSedeIPS
            $DirSedeIPS_busc_ini = '"Modalidad":';
            $DirSedeIPS_busc_fin = ',"NoActa"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $DirSedeIPS_busc_ini) + strlen($DirSedeIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $DirSedeIPS_busc_fin);
            $DirSedeIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> DirSedeIPS: " . $DirSedeIPS;
            //TelSedeIPS
            $TelSedeIPS_busc_ini = '"NoActa":';
            $TelSedeIPS_busc_fin = ',"FechaActa"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TelSedeIPS_busc_ini) + strlen($TelSedeIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TelSedeIPS_busc_fin);
            $TelSedeIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TelSedeIPS: " . $TelSedeIPS;
            //TipoIDProf
            $TipoIDProf_busc_ini = '"FechaActa":';
            $TipoIDProf_busc_fin = ',"FProceso"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDProf_busc_ini) + strlen($TipoIDProf_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDProf_busc_fin);
            $TipoIDProf = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> TipoIDProf: " . $TipoIDProf;
            //NumIDProf
            $NumIDProf_busc_ini = '"FProceso":';
            $NumIDProf_busc_fin = ',"TipoIDPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NumIDProf_busc_ini) + strlen($NumIDProf_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NumIDProf_busc_fin);
            $NumIDProf = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> NumIDProf: " . $NumIDProf;
            //PNProfS
            $PNProfS_busc_ini = '"TipoIDPaciente":';
            $PNProfS_busc_fin = ',"NroIDPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PNProfS_busc_ini) + strlen($PNProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PNProfS_busc_fin);
            $PNProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            //echo "<br> PNProfS: " . $PNProfS;
            //SNProfS
            $SNProfS_busc_ini = '"NroIDPaciente":';
            $SNProfS_busc_fin = ',"CodEntJM"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SNProfS_busc_ini) + strlen($SNProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SNProfS_busc_fin);
            $SNProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
          
            //EstPres
            $EstPres_busc_ini = '"CodEntJM":';
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

            //$EstPres;
            $EstPres = str_replace('"', "'", $EstPres);
            /* if ($EstPres == "null") {
              $EstPres = '';
            }*/
            //oci_bind_by_name($st, ":EstPres", $EstPres);

/*

            /////Insertar prescripcion (Inicio)
            $sql_exc = "INSERT INTO WEBSERV_PRES_PRES 
            (ID_PRES,REPO_SERV_ID, REPO_TIRE_ID, REPO_PERIODO,NOPRESCRIPCION, FPRESCRIPCION,HPRESCRIPCION,CODHABIPS,TIPOIDIPS,NROIDIPS,CODDANEMUNIPS,DIRSEDEIPS,TELSEDEIPS,TIPOIDPROF,NUMIDPROF,PNPROFS,SNPROFS,PAPROFS,SAPROFS,REGPROFS,TIPOIDPACIENTE,NROIDPACIENTE,PNPACIENTE,SNPACIENTE,PAPACIENTE,SAPACIENTE,CODAMBATE,REFAMBATE,ENFHUERFANA,CODENFHUERFANA,ENFHUERFANADX,CODDXPPAL,CODDXREL1,CODDXREL2,SOPNUTRICIONAL,CODEPS,TIPOIDMADREPACIENTE,NROIDMADREPACIENTE,TIPOTRANSC,TIPOIDDONANTEVIVO,NROIDDONANTEVIVO,ESTPRES
          )  VALUES (".$id_pres.",". $servicio_id .",". $tipo_id .",'". $fecha_oracle . "'," . $noPrescripcion . "," . $FPrescripcion . "," . $HPrescripcion . "," . $CodHabIPS . "," . $TipoIDIPS . "," . $NroIDIPS . "," . $CodDANEMunIPS . "," . $DirSedeIPS . "," . $TelSedeIPS . "," . $TipoIDProf . "," . $NumIDProf . "," . $PNProfS . "," . $SNProfS . "," . $PAProfS . "," . $SAProfS . "," . $RegProfS . "," . $TipoIDPaciente . "," . $NroIDPaciente . "," . $PNPaciente . "," . $SNPaciente . "," . $PAPaciente . "," . $SAPaciente . "," . $CodAmbAte . "," . $RefAmbAte . "," . $EnfHuerfana . "," . $CodEnfHuerfana . "," . $EnfHuerfanaDX . "," . $CodDxPpal . "," . $CodDxRel1 . "," . $CodDxRel2 . "," . $SopNutricional . "," . $CodEPS . "," . $TipoIDMadrePaciente . "," . $NroIDMadrePaciente . "," . $TipoTransc . "," . $TipoIDDonanteVivo . "," . $NroIDDonanteVivo . "," . $EstPres . ")";


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
*/
          }

        }//Fin del for principal
        echo "Cantidad de prescripciones: " . $longitud . "<br>";
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
