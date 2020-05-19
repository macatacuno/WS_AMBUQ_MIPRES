<?php

set_time_limit(9999999);
//ini_set('memory_limit', '-1');

$conn_oracle = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');

include('../../../funciones_generales.php');
///////Declaracion de Variables Generales(Inicio)/////////
$peri_error = "";
$peri_error_conteo = 0;
$periodos_cargados = "";
$periodos_cargados_conteo = 0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "Contributivo";
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
      $json = Webservice_get($url);
      //$json = (string) file_get_contents($url);
      $json = str_replace("\\\"", "", $json);
      $json = str_replace("'", "", $json);
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
        $cadena_NoPrescripcion = "";
        //Recorro todos los elementos
        for ($i = 0; $i < $longitud; $i++) {
          //////////////////////////////////////////////////////////////////////////////////////////////////////////Prescripcion
          //Obtener cadena general de Junta profesional
          $subCadenaPresGene = $array[$i];
          if ($subCadenaPresGene != '') {
            echo"<br>----------------------------------Junta #" . ($i + 1) . "-------------------------------------------------------------------------------------";


            //NoPrescripcion
            $NoPrescripcion_busc_ini = '"NoPrescripcion":';
            $NoPrescripcion_busc_fin = ',"FPrescripcion"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NoPrescripcion_busc_ini) + strlen($NoPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NoPrescripcion_busc_fin);
            $NoPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            $NoPrescripcion = str_replace('"', "'", $NoPrescripcion);
            //echo"<br> NoPrescripcion: " . $NoPrescripcion;



            //FPrescripcion
            $FPrescripcion_busc_ini = '"FPrescripcion":';
            $FPrescripcion_busc_fin = ',"TipoTecnologia"';
            $cadena_FPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_FPrescripcion, $FPrescripcion_busc_ini) + strlen($FPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_FPrescripcion, $FPrescripcion_busc_fin);
            $FPrescripcion = substr($cadena_FPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            $FPrescripcion = str_replace('"', "'", $FPrescripcion);

            $posIniFPrescripcion = 2;
            $posFinFPrescripcion = strpos($FPrescripcion, "T") - 2;
            $FPrescripcion = substr($FPrescripcion, $posIniFPrescripcion, $posFinFPrescripcion);
            $FPrescripcion = "'" . date("d/m/Y", strtotime($FPrescripcion)) . "'"; //formato originar "y/m/d"
            //echo"<br> FPrescripcion: " . $FPrescripcion;

            //TipoTecnologia
            $TipoTecnologia_busc_ini = '"TipoTecnologia":';
            $TipoTecnologia_busc_fin = ',"Consecutivo"';
            $cadena_TipoTecnologia = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_TipoTecnologia, $TipoTecnologia_busc_ini) + strlen($TipoTecnologia_busc_ini);
            $posPresFinal = strpos($cadena_TipoTecnologia, $TipoTecnologia_busc_fin);
            $TipoTecnologia = substr($cadena_TipoTecnologia, $posPresInicial, $posPresFinal - $posPresInicial);
            $TipoTecnologia = str_replace('"', "'", $TipoTecnologia);
            //echo"<br> TipoTecnologia: " . $TipoTecnologia;

            //Consecutivo
            $Consecutivo_busc_ini = '"Consecutivo":';
            $Consecutivo_busc_fin = ',"EstJM"';
            $cadena_Consecutivo = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_Consecutivo, $Consecutivo_busc_ini) + strlen($Consecutivo_busc_ini);
            $posPresFinal = strpos($cadena_Consecutivo, $Consecutivo_busc_fin);
            $Consecutivo = substr($cadena_Consecutivo, $posPresInicial, $posPresFinal - $posPresInicial);
            $Consecutivo = str_replace('"', "'", $Consecutivo);
            //echo"<br> Consecutivo: " . $Consecutivo;

            //EstJM
            $EstJM_busc_ini = '"EstJM":';
            $EstJM_busc_fin = ',"CodEntProc"';
            $cadena_EstJM = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
            $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
            $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
            $EstJM = str_replace('"', "'", $EstJM);
            //echo"<br> EstJM: " . $EstJM;


            //CodEntProc
            $CodEntProc_busc_ini = '"CodEntProc":';
            $CodEntProc_busc_fin = ',"Observaciones"';
            $cadena_CodEntProc = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_CodEntProc, $CodEntProc_busc_ini) + strlen($CodEntProc_busc_ini);
            $posPresFinal = strpos($cadena_CodEntProc, $CodEntProc_busc_fin);
            $CodEntProc = substr($cadena_CodEntProc, $posPresInicial, $posPresFinal - $posPresInicial);
            $CodEntProc = str_replace('"', "'", $CodEntProc);
            //echo"<br> CodEntProc: " . $CodEntProc;

            //Observaciones
            $Observaciones_busc_ini = '"Observaciones":';
            $Observaciones_busc_fin = ',"JustificacionTecnica"';
            $cadena_Observaciones = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_Observaciones, $Observaciones_busc_ini) + strlen($Observaciones_busc_ini);
            $posPresFinal = strpos($cadena_Observaciones, $Observaciones_busc_fin);
            $Observaciones = substr($cadena_Observaciones, $posPresInicial, $posPresFinal - $posPresInicial);
            $Observaciones = str_replace('"', "'", $Observaciones);
            //echo"<br> Observaciones: " . $Observaciones;


            //JustificacionTecnica
            $JustificacionTecnica_busc_ini = '"JustificacionTecnica":';
            $JustificacionTecnica_busc_fin = ',"Modalidad"';
            $cadena_JustificacionTecnica = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_JustificacionTecnica, $JustificacionTecnica_busc_ini) + strlen($JustificacionTecnica_busc_ini);
            $posPresFinal = strpos($cadena_JustificacionTecnica, $JustificacionTecnica_busc_fin);
            $JustificacionTecnica = substr($cadena_JustificacionTecnica, $posPresInicial, $posPresFinal - $posPresInicial);
            $JustificacionTecnica = str_replace('"', "'", $JustificacionTecnica);
            //echo"<br> JustificacionTecnica: " . $JustificacionTecnica;

            //Modalidad
            $Modalidad_busc_ini = '"Modalidad":';
            $Modalidad_busc_fin = ',"NoActa"';
            $cadena_Modalidad = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_Modalidad, $Modalidad_busc_ini) + strlen($Modalidad_busc_ini);
            $posPresFinal = strpos($cadena_Modalidad, $Modalidad_busc_fin);
            $Modalidad = substr($cadena_Modalidad, $posPresInicial, $posPresFinal - $posPresInicial);
            $Modalidad = str_replace('"', "'", $Modalidad);
            //echo"<br> Modalidad: " . $Modalidad;


            //NoActa
            $NoActa_busc_ini = '"NoActa":';
            $NoActa_busc_fin = ',"FechaActa"';
            $cadena_NoActa = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoActa, $NoActa_busc_ini) + strlen($NoActa_busc_ini);
            $posPresFinal = strpos($cadena_NoActa, $NoActa_busc_fin);
            $NoActa = substr($cadena_NoActa, $posPresInicial, $posPresFinal - $posPresInicial);
            $NoActa = str_replace('"', "'", $NoActa);
            //echo"<br> NoActa: " . $NoActa;



            //FechaActa
            $FechaActa_busc_ini = '"FechaActa":';
            $FechaActa_busc_fin = ',"FProceso"';
            $cadena_FechaActa = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_FechaActa, $FechaActa_busc_ini) + strlen($FechaActa_busc_ini);
            $posPresFinal = strpos($cadena_FechaActa, $FechaActa_busc_fin);
            $FechaActa = substr($cadena_FechaActa, $posPresInicial, $posPresFinal - $posPresInicial);
            $FechaActa = str_replace('"', "'", $FechaActa);

            $posIniFechaActa = 2;
            $posFinFechaActa = strpos($FechaActa, "T") - 2;
            $FechaActa = substr($FechaActa, $posIniFechaActa, $posFinFechaActa);
            $FechaActa = "'" . date("d/m/Y", strtotime($FechaActa)) . "'"; //formato originar "y/m/d"
            //echo"<br> FechaActa: " . $FechaActa;

            //FProceso
            $FProceso_busc_ini = '"FProceso":';
            $FProceso_busc_fin = ',"TipoIDPaciente"';
            $cadena_FProceso = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_FProceso, $FProceso_busc_ini) + strlen($FProceso_busc_ini);
            $posPresFinal = strpos($cadena_FProceso, $FProceso_busc_fin);
            $FProceso = substr($cadena_FProceso, $posPresInicial, $posPresFinal - $posPresInicial);
            $FProceso = str_replace('"', "'", $FProceso);
            //echo"<br> FProceso: " . $FProceso;


            //TipoIDPaciente
            $TipoIDPaciente_busc_ini = '"TipoIDPaciente":';
            $TipoIDPaciente_busc_fin = ',"NroIDPaciente"';
            $cadena_TipoIDPaciente = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_TipoIDPaciente, $TipoIDPaciente_busc_ini) + strlen($TipoIDPaciente_busc_ini);
            $posPresFinal = strpos($cadena_TipoIDPaciente, $TipoIDPaciente_busc_fin);
            $TipoIDPaciente = substr($cadena_TipoIDPaciente, $posPresInicial, $posPresFinal - $posPresInicial);
            $TipoIDPaciente = str_replace('"', "'", $TipoIDPaciente);
            //echo"<br> TipoIDPaciente: " . $TipoIDPaciente;

            //NroIDPaciente
            $NroIDPaciente_busc_ini = '"NroIDPaciente":';
            $NroIDPaciente_busc_fin = ',"CodEntJM"';
            $cadena_NroIDPaciente = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NroIDPaciente, $NroIDPaciente_busc_ini) + strlen($NroIDPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NroIDPaciente, $NroIDPaciente_busc_fin);
            $NroIDPaciente = substr($cadena_NroIDPaciente, $posPresInicial, $posPresFinal - $posPresInicial);
            $NroIDPaciente = str_replace('"', "'", $NroIDPaciente);
            //echo"<br> NroIDPaciente: " . $NroIDPaciente;


            //CodEntJM
            $CodEntJM_busc_ini = '"CodEntJM":';
            $CodEntJM_busc_fin = '}';
            $cadena_CodEntJM = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_CodEntJM, $CodEntJM_busc_ini) + strlen($CodEntJM_busc_ini);
            $posPresFinal = strpos($cadena_CodEntJM, $CodEntJM_busc_fin);
            $CodEntJM = substr($cadena_CodEntJM, $posPresInicial, $posPresFinal - $posPresInicial);
            $CodEntJM = str_replace('"', "'", $CodEntJM);
            //echo"<br> CodEntJM: " . $CodEntJM;



            /*
                        $NoPrescripcion,
                        $FPrescripcion,
                        $TipoTecnologia,
                        $Consecutivo,
                        $EstJM,
                        $CodEntProc,
                        $Observaciones,
                        $JustificacionTecnica,
                        $Modalidad,
                        $NoActa,
                        $FechaActa,
                        $FProceso,
                        $TipoIDPaciente,
                        $NroIDPaciente,
                        $CodEntJM,
                        
                        */






            /////Insertar prescripcion (Inicio)
            $sql_exc = "INSERT INTO WEBSERV_JUNTA_PROFESIONAL 
            (ID_JUPR,REPO_SERV_ID,REPO_TIRE_ID,REPO_PERIODO,NoPrescripcion,
            FPrescripcion,TipoTecnologia,Consecutivo,EstJM,CodEntProc,
            Observaciones,JustificacionTecnica,Modalidad,NoActa,FechaActa,FProceso,
            TipoIDPaciente,NroIDPaciente,CodEntJM
            )  
            VALUES (SEQ_WEBSERV_JUNTA_PROFESIONAL.nextval" . "," . $servicio_id . "," . $tipo_id . ",'"
              . $fecha_oracle . "',"  . $NoPrescripcion . "," . $FPrescripcion . ","
              . $TipoTecnologia . "," . $Consecutivo . "," . $EstJM . "," . $CodEntProc . ","
              . $Observaciones . "," . $JustificacionTecnica . "," . $Modalidad . "," . $NoActa . ","
              . $FechaActa . "," . $FProceso . "," . $TipoIDPaciente . "," . $NroIDPaciente
              . "," . $CodEntJM . ")";

            //echo "<br>" . $sql_exc . "<br>";
            
            $st = oci_parse($conn_oracle, $sql_exc);

            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              echo  "<br>Insercion Correcta ";
            } else {
              echo  "<br>Insercion Incorrecta ";
            }
            /////Insertar Junta profesional (Fin)

          }
        } //Fin del for principal
        echo "<br>Cantidad de Juntas profesionales: " . $longitud . "<br>";
      }
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
