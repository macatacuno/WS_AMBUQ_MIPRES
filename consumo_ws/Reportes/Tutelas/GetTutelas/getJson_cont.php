<?php

/**********************Cargar funciones funciones_generales***************************/
include('../../../funciones_generales.php');

/**********************Cargarel procedimiento para la conexion de oracle**************/
include('../../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

/**********************Variables generales*******************************************/
$tipo_get = "contributivo";
$tipo_id = 1; //contributivo
$servicio_id = 12; // Se asigna el codigo del servicio Tutelas

/**********************Obtener los datos para armar la URL***************************/
$url_bd = obtener_datos_url("URL", $servicio_id, $conn_oracle);
$serv_nombre = obtener_datos_url("SERV_NOMBRE", $servicio_id, $conn_oracle);
$ws_nombre = obtener_datos_url("WS_NOMBRE", $servicio_id, $conn_oracle);
$Webservice = obtener_datos_url("WS_ID", $servicio_id, $conn_oracle);

/**********************Obtener el nit y el token**************************************/
$nit = obtener_datos_token_nit("NIT", $tipo_id, $conn_oracle);
$token = obtener_datos_token_nit("TOKEN", $tipo_id, $conn_oracle);
$horas_de_diferencia = obtener_datos_token_nit("HORAS_DE_DIFERENCIA", $tipo_id, $conn_oracle);

/*$token_temporal = obtener_datos_token_nit("TOKEN_TEMPORAL", $tipo_id, $conn_oracle);
$token_temporal = actualizar_token_temporal($horas_de_diferencia, $conn_oracle, $nit, $token, $token_temporal);
*/

/**********************Cargar Encabezado**********************************************/

$periodo_inicial = "20-06-01";
$periodo_final = (string) date("y-m-d", strtotime(date('y-m-d') . "- 1 day"));

$cant_dias = armar_encabezado($periodo_inicial, $periodo_final, $ws_nombre, $serv_nombre, $tipo_get);

/********************************************************************************************/
/********************************************************************************************/
/**********************Leer cada periodo************************************************/
/********************************************************************************************/
/********************************************************************************************/

for ($i_Principal = 0; $i_Principal <= $cant_dias - 1; $i_Principal++) {

  //identificar cada uno de los periodos a cargar
  $periodo_conteo = date("y-m-d", strtotime($periodo_inicial . "+ " . $i_Principal . " day"));

  $periodo_existe = validar_que_el_periodo_exista($conn_oracle, $periodo_conteo, $servicio_id, $tipo_id);
  if ($periodo_existe) {
    //echo "<br>El registro ya exsiste";
  } else {
    echo "<br>___________________________________________________________________________________________________________________________________________________________________________________________";

    $url = $url_bd . "/" . $nit . '/' . "20" . $periodo_conteo . '/' . $token;
    $json = Webservice_get($url); //$json = file_get_contents($url);
    $json = formatear_json_general($json);
    $fecha_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato original "y/m/d"
    echo "<br>/////////////////////// Json #" . $i_Principal . " Periodo: 20" . $periodo_conteo . "<br>";

    if ($json == "" || (strlen($json) >= 3 && strlen($json) <= 100)) {
      //insertar_log_de_error($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, $serv_nombre, $tipo_get, $periodo_conteo);
    } else if ($json == "[]") {
      // insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'NO', $serv_nombre, $tipo_get, $periodo_conteo);
    } else {

      // echo "json: $json";

      $subCadenaBuscadaInicial   = '{"tutela"';
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
        $subCadenaBuscadaInicial   = '{"tutela"';
        // echo "<br> sub Cadena Buscada Inicial: " . $subCadenaBuscadaInicial;
        $subCadenaBuscadaFinal   = ',{"tutela"';
        //echo "<br> sub Cadena Buscada Final: " . $subCadenaBuscadaFinal;
        $cadena = $json;

        //$posInicial = strpos('cadena completa', 'Subcadena buscada','se especifica si se buscara la primera o la segunda coinsidencia (Este ultimo parametro es mejor no usarlo porque no funciona bien)');
        //$posInicial = strpos($cadena, $subCadenaBuscadaInicial,0);
        $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
        echo "<br> pos Inicial: " . $posInicial;
        $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
        if ($posFinal == "") {
          $posFinal = strlen($cadena) - 2; //Sera igual a la última posición de la cadena
          //echo "<br> pos Final no encontrado: " . $posFinal;
        }

        echo "<br> pos Final: " . $posFinal;
        //$subCadenaFinal = substr($cadena, posicion Inicial,cantidad de caracteres despues de la pocicion inicial);
        $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
        // echo "<br> Sub Cadena: " . $subCadenaFinal;
        if ($subCadenaFinal != '[' && $subCadenaFinal != '') {

          $array[$count_report] = $subCadenaFinal;
          $count_report++;
        }
        ////////////////////////////////////////////medicamentos////////////////////////////////////////

        $json = str_replace($subCadenaFinal, "", $json);

      }

      /**************************************************************************************************/
      /************(Inicio)Bloque para separar el json en diferentes prescripciones que se retornan******/
      /**************************************************************************************************/
      /*****Notas:
        //Nota 1: Las cadenas de búsqueda deben ser únicas en la cadena en donde se están buscando para que no hayan inconsistencias Ej: la cadena de búsqueda que esta guardada en las variables $cad_pres_busc_ini y  $cad_pres_busc_fin solo debe existir una sola vez en la cadena $cadena_presc.
       **************************************************************************************************/

      $longitud = count($array);
      for ($i = 0; $i < $longitud; $i++) {
        //////////////////////////////////////////////////////////////////////////////////////////////////////////Prescripcion
        //Obtener cadena general de prescripcion
        $cad_pres_busc_ini = '{"tutela"';
        $cad_pres_busc_fin = ',"fallosAdicionales"';
        $cadena_presc = $array[$i];
        $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini) + 10;
        $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
        $subCadenaPresGene = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
        $subCadenaPresGene = "[$subCadenaPresGene]";

        if ($cadena_presc != '') {

          $json_array = json_decode($subCadenaPresGene, true);

          $cont_dir = 0;
          foreach ($json_array as $clave) {
            $cont_dir = $cont_dir + 1;

            //$CantTotAEntregar = str_replace("'", "", $clave["CantTotAEntregar"]);
            /////Insertar prescripcion (Inicio)
            $sql_exc = "INSERT
            INTO WEBSERV_TUTELA_TUTELA
              (
                ID_TUTE,
                REPO_PERIODO,
                REPO_SERV_ID,
                REPO_TIRE_ID,
                NoTutela,
                FTUTELA,
                HTUTELA,
                CODEPS,
                TIPOIDEPS,
                NROIDEPS,
                PNPROFS,
                SNPROFS,
                PAPROFS,
                SAPROFS,
                REGPROFS,
                TIPOIDPACIENTE,
                NROIDPACIENTE,
                PNPACIENTE,
                SNPACIENTE,
                PAPACIENTE,
                SAPACIENTE,
                NROFALLO,
                FFALLOTUTELA,
                F1INSTAN,
                F2INSTAN,
                FCORTE,
                FDESACATO,
                ENFHUERFANA,
                CODENFHUERFANA,
                ENFHUERFANADX,
                CODDXPPAL,
                CODDXREL1,
                CODDXREL2,
                ACLFALLOTUT,
                CODDXMOTS1,
                CODDXMOTS2,
                CODDXMOTS3,
                JUSTIFMED,
                CRITDEF1CC,
                CRITDEF2CC,
                CRITDEF3CC,
                CRITDEF4CC,
                TIPOIDMADREPACIENTE,
                NROIDMADREPACIENTE,
                ESTTUT
              )
              VALUES
              (
                      SEQ_WEBSERV_TUTELA_TUTELA.nextval,
                      '" . $servicio_id . "',
                      '" . $fecha_oracle . "',
                      '" . $tipo_id . "',
                      '" . $clave["NoTutela"] . "',
                      '" . $clave["FTutela"] . "',
                      '" . $clave["HTutela"] . "',
                      '" . $clave["CodEPS"] . "',
                      '" . $clave["TipoIDEPS"] . "',
                      '" . $clave["NroIDEPS"] . "',
                      '" . $clave["TipoIDProf"] . "',
                      '" . $clave["NumIDProf"] . "',
                      '" . $clave["PNProfS"] . "',
                      '" . $clave["SNProfS"] . "',
                      '" . $clave["PAProfS"] . "',
                      '" . $clave["SAProfS"] . "',
                      '" . $clave["RegProfS"] . "',
                      '" . $clave["TipoIDPaciente"] . "',
                      '" . $clave["NroIDPaciente"] . "',
                      '" . $clave["PNPaciente"] . "',
                      '" . $clave["SNPaciente"] . "',
                      '" . $clave["PAPaciente"] . "',
                      '" . $clave["SAPaciente"] . "',
                      '" . $clave["NroFallo"] . "',
                      '" . $clave["FFalloTutela"] . "',
                      '" . $clave["F1Instan"] . "',
                      '" . $clave["F2Instan"] . "',
                      '" . $clave["FCorte"] . "',
                      '" . $clave["FDesacato"] . "',
                      '" . $clave["EnfHuerfana"] . "',
                      '" . $clave["CodEnfHuerfana"] . "',
                      '" . $clave["EnfHuerfanaDX"] . "',
                      '" . $clave["CodDxPpal"] . "',
                      '" . $clave["CodDxRel1"] . "',
                      '" . $clave["CodDxRel2"] . "',
                      '" . $clave["AclFalloTut"] . "',
                      '" . $clave["CodDxMotS1"] . "',
                      '" . $clave["CodDxMotS2"] . "',
                      '" . $clave["CodDxMotS3"] . "',
                      '" . $clave["JustifMed"] . "',
                      '" . $clave["CritDef1CC"] . "',
                      '" . $clave["CritDef2CC"] . "',
                      '" . $clave["CritDef3CC"] . "',
                      '" . $clave["CritDef4CC"] . "',
                      '" . $clave["TipoIDMadrePaciente"] . "',
                      '" . $clave["NroIDMadrePaciente"] . "',
                      '" . $clave["EstTut"] . "'
                    )";
            echo "<br>sql: $sql_exc";
            /* $st = oci_parse($conn_oracle, $sql_exc);
                  $result = oci_execute($st);
                  oci_free_statement($st);
                  if ($result) {
                     // echo  "<br>Insercion Correcta ";
                  } else {
                      echo  "<br>Insercion Incorrecta en el direccionamiento #" . $clave["IDDireccionamiento"];
                  } */
          }
          // echo "<br>--------Cantidad de direccionamientos insertados: $cont_dir ";

        }
      }
    }
  }
}
