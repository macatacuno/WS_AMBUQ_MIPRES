<?php

/**********************Cargar funciones funciones_generales***************************/
include('../../../funciones_generales.php');

/**********************Cargarel procedimiento para la conexion de oracle**************/
include('../../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

/**********************Variables generales*******************************************/
$tipo_get = "subsidiado";
$tipo_id = 2; //Subsidiado
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

$periodo_inicial = "17-01-01";
$periodo_final = "20-07-08";
//$periodo_final = (string) date("y-m-d", strtotime(date('y-m-d') . "- 1 day"));

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
    echo "<br>url: $url<br>";
    $json = Webservice_get($url); //$json = file_get_contents($url);
    //$json = '{"tutela":{"NoTutela":"20200616225001122163","FTutela":"2020-06-16T00:00:00","HTutela":"12:11:53","CodEPS":"ESSC76","TipoIDEPS":"NI","NroIDEPS":"818000140","TipoIDProf":"CC","NumIDProf":"31402004","PNProfS":"MARTHA","SNProfS":"TERESA","PAProfS":"LOPEZ","SAProfS":"SILVA","RegProfS":"06947","TipoIDPaciente":"CC","NroIDPaciente":"38901272","PNPaciente":"OFELIA","SNPaciente":"","PAPaciente":"RAMIREZ","SAPaciente":"MONTOYA","NroFallo":"2018008700","FFalloTutela":"2018-02-22T00:00:00","F1Instan":null,"F2Instan":null,"FCorte":null,"FDesacato":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C509","CodDxRel1":null,"CodDxRel2":null,"AclFalloTut":"TUTELAINTEGRAL,ORDENATRANSPORTEYVIÁTICOSCONACOMPAÑANTEFUERADESUCIUDAD.ASISTIRYRECIBIRTRATAMIENTO,CITAS,TERAPIAS,ACCESOASERVICIOSDESALUD","CodDxMotS1":"C509","CodDxMotS2":null,"CodDxMotS3":null,"JustifMed":"\tTUTELAINTEGRAL,ORDENATRANSPORTEYVIÁTICOSCONACOMPAÑANTEFUERADESUCIUDAD.ASISTIRYRECIBIRTRATAMIENTO,CITAS,TERAPIAS,ACCESOASERVICIOSDESALUD","CritDef1CC":1,"CritDef2CC":0,"CritDef3CC":1,"CritDef4CC":1,"TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"EstTut":4},"fallosAdicionales":[{"FFalloAdic":"2018-02-22T00:00:00","NroFalloAdic":"656"},{"FFalloAdic":"2018-02-22T00:00:00","NroFalloAdic":"4"}],"medicamentos":[{"ConOrden":3,"TipoMed":1,"TipTut":1,"TipoPrest":1,"TipMed":4,"DscMedPA":"515","CodFF":"516","CodVA":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115","PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"},{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"515"},{"ConOrden":4,"CodIndicacion":"452"}]},{"ConOrden":3,"TipoMed":1,"TipTut":1,"TipoPrest":1,"TipMed":4,"DscMedPA":"515","CodFF":"516","CodVA":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115","PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"},{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"724"},{"ConOrden":4,"CodIndicacion":"196"}]}],"procedimientos":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TipPro":4,"CodCUPS":"515","NomProc":"516","CanForm":"517","CadaFreUso":"518","CodFreUso":9,"Cant":"5110","CodPerDurTrat":1,"CantTotal":"5111","JustNoPBS":"5112","IndRec":"5113","Objetivo":"5114"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TipPro":4,"CodCUPS":"515","NomProc":"516","CanForm":"517","CadaFreUso":"518","CodFreUso":9,"Cant":"5110","CodPerDurTrat":1,"CantTotal":"5111","JustNoPBS":"5112","IndRec":"5113","Objetivo":"5114"}],"dispositivos":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodDisp":"514","CanForm":"515","CadaFreUso":"516","CodFreUso":7,"Cant":"518","CodPerDurTrat":1,"CantTotal":"519","JustNoPBS":"5110","IndRec":"5111","Objetivo":"5112"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodDisp":"514","CanForm":"515","CadaFreUso":"516","CodFreUso":7,"Cant":"518","CodPerDurTrat":1,"CantTotal":"519","JustNoPBS":"5110","IndRec":"5111","Objetivo":"5112"}],"productosnutricionales":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TippProNut":"514","DescProdNutr":"515","CodForma":"516","CodViaAdmon":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TippProNut":"514","DescProdNutr":"515","CodForma":"516","CodViaAdmon":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115"}],"serviciosComplementarios":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodSerComp":4,"DescSerComp":"515","CanForm":"516","CadaFreUso":"517","CodFreUso":8,"Cant":"519","CodPerDurTrat":1,"CantTotal":"44444","JustNoPBS":"22222222","IndRec":"333333333"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodSerComp":4,"DescSerComp":"515","CanForm":"516","CadaFreUso":"517","CodFreUso":8,"Cant":"519","CodPerDurTrat":1,"CantTotal":"5110","JustNoPBS":"5555555","IndRec":"777777"}]}';


    $json = formatear_json_general($json);
    $fecha_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato original "y/m/d"
    echo "<br>/////////////////////// Json #" . $i_Principal . " Periodo: 20" . $periodo_conteo . "<br>";

    if ($json == "" || (strlen($json) >= 3 && strlen($json) <= 100)) {
      //insertar_log_de_error($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, $serv_nombre, $tipo_get, $periodo_conteo);
    } else if ($json == "[]") {
      // insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'NO', $serv_nombre, $tipo_get, $periodo_conteo);
    } else {

      insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'SI', $serv_nombre, $tipo_get, $periodo_conteo);
      //echo "<br>json: $json<br>";

      $subCadenaBuscadaInicial   = '{"tutela"';
      $cadena = $json;
      $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
      $count_report = 0;

      unset($array); //Vaciar el vector
      // Notese el uso de ===. Puesto que == simple no funcionará como se espera
      while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

        /***************************Se separan las 5 subcadenas de la Tutela (Inicio)************************************/
        ////////(Tutela,Medicamentos, procedimientos, dispositivos, productosnutricionales,serviciosComplementarios)//////
        /**********************************************************************************************************************/
        ///////////////////////////////////////////Tutela/////////////////////////////////////////
        $subCadenaBuscadaInicial   = '{"tutela"';
        // echo "<br> sub Cadena Buscada Inicial: " . $subCadenaBuscadaInicial;
        $subCadenaBuscadaFinal   = ',{"tutela"';
        //echo "<br> sub Cadena Buscada Final: " . $subCadenaBuscadaFinal;
        $cadena = $json;

        //$posInicial = strpos('cadena completa', 'Subcadena buscada','se especifica si se buscara la primera o la segunda coinsidencia (Este ultimo parametro es mejor no usarlo porque no funciona bien)');
        //$posInicial = strpos($cadena, $subCadenaBuscadaInicial,0);
        $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
        //echo "<br> pos Inicial: " . $posInicial;
        $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
        if ($posFinal == "") {
          $posFinal = strlen($cadena) - 2; //Sera igual a la última posición de la cadena
          //echo "<br> pos Final no encontrado: " . $posFinal;
        }

        //echo "<br> pos Final: " . $posFinal;
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
      /************(Inicio)Bloque para separar el json en diferentes Tutelaes que se retornan******/
      /**************************************************************************************************/
      /*****Notas:
        //Nota 1: Las cadenas de búsqueda deben ser únicas en la cadena en donde se están buscando para que no hayan inconsistencias Ej: la cadena de búsqueda que esta guardada en las variables $cad_busc_ini y  $cad_busc_fin solo debe existir una sola vez en la cadena $cadena.
       **************************************************************************************************/

      $longitud = count($array);
      $INSERCIONGENERALTUTELA = false;
      $INSERCIONTUTELAMEDI = false;
      $cont_tute = 0;
      for ($i = 0; $i < $longitud; $i++) {
        $cont_tute = $cont_tute + 1;
        echo "<br>---------------------------------------------Insertar tutela #: $cont_tute<br>";
        //////////////////////////////////////////////////////////////////////////////////////////////////////////Tutela
        //Obtener cadena general de Tutela

        //se valida si el ultimo valor del json es una "," y de ser asi, esta se elimina.
        $posFinal = strlen($array[$i]);
        $ultimo_val_json = substr($array[$i], $posFinal - 1, $posFinal);
        if ($ultimo_val_json == ',') {
          $array[$i] = substr($array[$i], 0, $posFinal - 1);
        }

        //echo "<br>Cadena General: " . $array[$i];
        $subCadenaGene = obtener_sub_cadena_tutelas(',"tutela"', ',"fallosAdicionales"', $array[$i], 10);
        $subCadenaGene = "[$subCadenaGene]";
        //echo "<br><br>subCadenaGene: $subCadenaGene<br>";

        if ($array[$i] != '') {

          $json_array = json_decode($subCadenaGene, true);


          foreach ($json_array as $clave) {

            //$CantTotAEntregar = str_replace("'", "", $clave["CantTotAEntregar"]);
            /////Insertar Tutela (Inicio)
            echo "<br>----------------------Insertar tutela general<br>";
            //Cambiar formato a las fechas
            $FTutela_oracle = "";
            if ($clave["FTutela"] != '') {
              $FTutela_oracle = date("d/m/Y", strtotime($clave["FTutela"]));
            }
            $FFalloTutela_oracle = "";
            if ($clave["FFalloTutela"] != '') {
              $FFalloTutela_oracle = date("d/m/Y", strtotime($clave["FFalloTutela"]));
            }
            $F1Instan_oracle = "";
            if ($clave["F1Instan"] != '') {
              $F1Instan_oracle = date("d/m/Y", strtotime($clave["F1Instan"]));
            }
            $F2Instan_oracle = "";
            if ($clave["F2Instan"] != '') {
              $F2Instan_oracle = date("d/m/Y", strtotime($clave["F2Instan"]));
            }
            $FCorte_oracle = "";
            if ($clave["FCorte"] != '') {
              $FCorte_oracle = date("d/m/Y", strtotime($clave["FCorte"]));
            }
            $FDesacato_oracle = "";
            if ($clave["FDesacato"] != '') {
              $FDesacato_oracle = date("d/m/Y", strtotime($clave["FDesacato"]));
            }

            $sql_exc = "INSERT
            INTO WEBSERV_TUTELA_TUTELA
              (
                ID_TUTE,
                REPO_PERIODO,
                REPO_SERV_ID,
                REPO_TIRE_ID,
                NOTUTELA,
                FTUTELA,
                HTUTELA,
                CODEPS,
                TIPOIDEPS,
                NROIDEPS,
                TIPOIDPROF,
                NUMIDPROF,
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
                        '" . $fecha_oracle . "',
                        '" . $servicio_id . "',
                        '" . $tipo_id . "',
                        '" . $clave["NoTutela"] . "',
                        '" . $FTutela_oracle . "',
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
                        '" . $FFalloTutela_oracle . "',
                        '" . $F1Instan_oracle . "',
                        '" . $F2Instan_oracle . "',
                        '" . $FCorte_oracle . "',
                        '" . $FDesacato_oracle . "',
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
            //echo "<br>sql: $sql_exc";
            $st = oci_parse($conn_oracle, $sql_exc);
            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              $INSERCIONGENERALTUTELA = true;
              echo  "<br>Insercion Correcta ";
            } else {

              $INSERCIONGENERALTUTELA = false;
              echo  "<br>Insercion Incorrecta en la tutela #" . $clave["NoTutela"];
            }
          }
        }


        //Obtener cadena general de Tutela
        if ($INSERCIONGENERALTUTELA) {

          ////////////fallosAdicionales--fallosAdicionales--fallosAdicionales--fallosAdicionales////////////////////Tutela
          ///////////////////////////////////////////////////////////////////////////////////////////////////////
          $subCadenaGene = obtener_sub_cadena_tutelas(',"fallosAdicionales"', ',"medicamentos"', $array[$i], 21);
          //echo "<br>subCadenaGene: $subCadenaGene<br>";

          $json_array = json_decode($subCadenaGene, true);
          $FFalloAdic_oracle = '';
          echo "<br>----------------------Insertar fallosAdicionales";
          foreach ($json_array as $clave) {
            if ($clave["FFalloAdic"] != '') {
              $FFalloAdic_oracle = date("d/m/Y", strtotime($clave["FFalloAdic"]));
            }
            $sql_exc = "INSERT
                INTO WEBSERV_TUTELA_FALLOS_ADIC
                  (
                    ID_TUTE,
                    FFALLOADIC,
                    NROFALLOADIC
                  )
                  VALUES
                  (
                    " . "SEQ_WEBSERV_TUTELA_TUTELA.currval" . ",
                    '" . $FFalloAdic_oracle . "',
                    '" . $clave["NroFalloAdic"] . "'
                  )";
            //echo "<br>sql: $sql_exc";
            $st = oci_parse($conn_oracle, $sql_exc);
            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              echo  "<br>Insercion Correcta ";
            } else {
              echo  "<br>Insercion Incorrecta";
            }
          }


          ////////////medicamentos--medicamentos--medicamentos--medicamentos--medicamentos////////////////////Tutela
          //Obtener cadena general de Tutela-medicamentos
          echo "<br>----------------------Insertar Medicamentos";
          $cadMedicamentos = obtener_sub_cadena_tutelas(',"medicamentos"', ',"procedimientos"', $array[$i], 16);
          //echo "<br>cadMedicamentos: $cadMedicamentos<br>";

          //obtener cada uno de los medicamentos
          $INSERCIONTUTELAMEDI = false;
          while ($cadMedicamentos != '[') {
            $cadMedicamento = obtener_sub_cadena_tutelas('[{', '}]}', $cadMedicamentos, 1) . '}]}';
            //echo "<br>cadMedicamento: $cadMedicamento<br>";
            //Eliminamos de la cadena de medicamentos el medicamento que ya leimos.
            $posInicial = strlen($cadMedicamento) + 2;
            $posFinal = strlen($cadMedicamentos);
            $cadMedicamentos = '[' . substr($cadMedicamentos, $posInicial, $posFinal);
            //Leer la cadena del medicamento
            $vecMedi = obtener_sub_cadena_tutelas('{', ',"PrincipiosActivos"', $cadMedicamento, 0);

            if ($vecMedi != '' && $vecMedi != '[]') {
              echo "<br>----------------Medicamento";
              $vecMedi = '[' . $vecMedi . '}]';
              //echo "<br>vecMedi: $vecMedi<br>";
              $vecMedi_array = json_decode($vecMedi, true);
              foreach ($vecMedi_array as $clave) {

                $sql_exc = "INSERT
                  INTO WEBSERV_TUTELA_MEDICAMENTOS
                    (
                      ID_MEDI,
                      ID_TUTE,
                      CONORDEN,
                      TIPOMED,
                      TIPTUT,
                      TIPOPREST,
                      TIPMED,
                      DSCMEDPA,
                      CODFF,
                      CODVA,
                      JUSTNOPBS,
                      DOSIS,
                      DOSISUM,
                      NOFADMON,
                      CODFREADMON,
                      INDESP,
                      CANTRAT,
                      DURTRAT,
                      CANTTOTALF,
                      UFCANTTOTAL,
                      INDREC
                    )
                    VALUES
                    (
                      SEQ_WEBSERV_TUTELA_MEDI.nextval,
                      SEQ_WEBSERV_TUTELA_TUTELA.currval,
                      '" . $clave["ConOrden"] . "',
                      '" . $clave["TipoMed"] . "',
                      '" . $clave["TipTut"] . "',
                      '" . $clave["TipoPrest"] . "',
                      '" . $clave["TipMed"] . "',
                      '" . $clave["DscMedPA"] . "',
                      '" . $clave["CodFF"] . "',
                      '" . $clave["CodVA"] . "',
                      '" . $clave["JustNoPBS"] . "',
                      '" . $clave["Dosis"] . "',
                      '" . $clave["DosisUM"] . "',
                      '" . $clave["NoFAdmon"] . "',
                      '" . $clave["CodFreAdmon"] . "',
                      '" . $clave["IndEsp"] . "',
                      '" . $clave["CanTrat"] . "',
                      '" . $clave["DurTrat"] . "',
                      '" . $clave["CantTotalF"] . "',
                      '" . $clave["UFCantTotal"] . "',
                      '" . $clave["IndRec"] . "'
                    )";
                //echo "<br>sql: " . $sql_exc . "<br>";
                $st = oci_parse($conn_oracle, $sql_exc);
                $result = oci_execute($st);
                oci_free_statement($st);
                if ($result) {

                  $INSERCIONTUTELAMEDI = true;
                  echo  "<br>Insercion Correcta ";
                } else {
                  echo  "<br>Insercion Incorrecta";
                }
              }
            }

            if ($INSERCIONTUTELAMEDI) {

              //consultar el id del medicamento
              echo "<br>------Prinsipios activos";
              $vecPriAct = null;
              $vecPriAct = obtener_sub_cadena_tutelas(',"PrincipiosActivos"', ',"IndicacionesUNIRS"', $cadMedicamento, strlen(',"PrincipiosActivos"') + 1);
              //echo "<br>vecPriAct: " . $vecPriAct;
              if (strlen($vecPriAct) != 4) {
                $vecPriAct_array = json_decode($vecPriAct, true);
                foreach ($vecPriAct_array as $clave) {
                  $sql_exc = "INSERT
                  INTO WEBSERV_TUTELA_PRIN_ACTI
                    (
                      ID_MEDI,
                      CONORDEN,
                      CODPRIACT,
                      CONCCANT,
                      UMEDCONC,
                      CANTCONT,
                      UMEDCANTCONT
                    )
                    VALUES
                    (
                      SEQ_WEBSERV_TUTELA_MEDI.currval,
                       '" . $clave["ConOrden"] . "',
                       '" . $clave["CodPriAct"] . "',
                       '" . $clave["ConcCant"] . "',
                       '" . $clave["UMedConc"] . "',
                       '" . $clave["CantCont"] . "',
                       '" . $clave["UMedCantCont"] . "'
                     )";
                  //echo "<br>sql: " . $sql_exc . "<br>";
                  $st = oci_parse($conn_oracle, $sql_exc);
                  $result = oci_execute($st);
                  oci_free_statement($st);
                  if ($result) {
                    echo  "<br>Insercion Correcta ";
                  } else {
                    echo  "<br>Insercion Incorrecta";
                  }
                }
              }
              echo "<br>------IndicacionesUNIRS";
              $vecIndUni = obtener_sub_cadena_tutelas(',"IndicacionesUNIRS"', ']}', $cadMedicamento, strlen(',"IndicacionesUNIRS"') + 1) . ']';
              //echo "<br>vecIndUni: $vecIndUni<br>";
              if ($vecIndUni != null) {
                $vecIndUni_array = json_decode($vecIndUni, true);
                foreach ($vecIndUni_array as $clave) {
                  $sql_exc = "INSERT
                  INTO WEBSERV_TUTELA_INDI_UNIRS
                    (
                      ID_MEDI,
                      CONORDEN,
                      CODINDICACION
                    )
                    VALUES
                    (
                      SEQ_WEBSERV_TUTELA_MEDI.currval,
                       '" . $clave["ConOrden"] . "',
                       '" . $clave["CodIndicacion"] . "'
                     )";
                  //echo "<br>sql: " . $sql_exc . "<br>";
                  $st = oci_parse($conn_oracle, $sql_exc);
                  $result = oci_execute($st);
                  oci_free_statement($st);
                  if ($result) {
                    echo  "<br>Insercion Correcta ";
                  } else {
                    echo  "<br>Insercion Incorrecta";
                  }
                }
              }
            }
          }


          ////////////procedimientos--procedimientos--procedimientos--procedimientos--procedimientos/////////////
          ///////////////////////////////////////////////////////////////////////////////////////////////////////
          echo "<br>----------------------Insertar procedimientos";
          $subCadenaGene = obtener_sub_cadena_tutelas(',"procedimientos"', ',"dispositivos"', $array[$i], strlen(',"procedimientos"') + 1);
          //echo "<br>subCadenaGene: $subCadenaGene<br>";

          $json_array = json_decode($subCadenaGene, true);
          $FFalloAdic_oracle = '';
          foreach ($json_array as $clave) {

            $sql_exc = "INSERT
            INTO WEBSERV_TUTELA_PROCEDIMIENTOS
              (
                ID_TUTE,
                CONORDEN,
                TIPTUT,
                TIPOPREST,
                TIPPRO,
                CODCUPS,
                NOMPROC,
                CANFORM,
                CADAFREUSO,
                CODFREUSO,
                CANT,
                CODPERDURTRAT,
                CANTTOTAL,
                JUSTNOPBS,
                INDREC,
                OBJETIVO
              )
              VALUES
              (
                " . "SEQ_WEBSERV_TUTELA_TUTELA.currval" . ",
                '" . $clave["ConOrden"] . "',
                '" . $clave["TipTut"] . "',
                '" . $clave["TipoPrest"] . "',
                '" . $clave["TipPro"] . "',
                '" . $clave["CodCUPS"] . "',
                '" . $clave["NomProc"] . "',
                '" . $clave["CanForm"] . "',
                '" . $clave["CadaFreUso"] . "',
                '" . $clave["CodFreUso"] . "',
                '" . $clave["Cant"] . "',
                '" . $clave["CodPerDurTrat"] . "',
                '" . $clave["CantTotal"] . "',
                '" . $clave["JustNoPBS"] . "',
                '" . $clave["IndRec"] . "',
                '" . $clave["Objetivo"] . "'
                  )";
            // echo "<br>sql: $sql_exc";
            $st = oci_parse($conn_oracle, $sql_exc);
            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              echo  "<br>Insercion Correcta ";
            } else {
              echo  "<br>Insercion Incorrecta";
            }
          }
          ////////////procedimientos--procedimientos--procedimientos--procedimientos--procedimientos/////////////

          ////////////dispositivos--dispositivos--dispositivos--dispositivos--dispositivos/////////////
          ///////////////////////////////////////////////////////////////////////////////////////////////////////
          echo "<br>----------------------Insertar dispositivos";
          $subCadenaGene = obtener_sub_cadena_tutelas(',"dispositivos"', ',"productosnutricionales"', $array[$i], strlen(',"dispositivos"') + 1);
          //echo "<br>subCadenaGene: $subCadenaGene<br>";

          $json_array = json_decode($subCadenaGene, true);
          $FFalloAdic_oracle = '';
          foreach ($json_array as $clave) {

            $sql_exc = "INSERT
            INTO WEBSERV_TUTELA_DISPOSITIVOS
              (
                ID_TUTE,
                CONORDEN,
                TIPTUT,
                TIPOPREST,
                CODDISP,
                CANFORM,
                CADAFREUSO,
                CODFREUSO,
                CANT,
                CODPERDURTRAT,
                CANTTOTAL,
                JUSTNOPBS,
                INDREC,
                OBJETIVO
              )
              VALUES
              (
                " . "SEQ_WEBSERV_TUTELA_TUTELA.currval" . ",
                '" . $clave["ConOrden"] . "',
                '" . $clave["TipTut"] . "',
                '" . $clave["TipoPrest"] . "',
                '" . $clave["CodDisp"] . "',
                '" . $clave["CanForm"] . "',
                '" . $clave["CadaFreUso"] . "',
                '" . $clave["CodFreUso"] . "',
                '" . $clave["Cant"] . "',
                '" . $clave["CodPerDurTrat"] . "',
                '" . $clave["CantTotal"] . "',
                '" . $clave["JustNoPBS"] . "',
                '" . $clave["IndRec"] . "',
                '" . $clave["Objetivo"] . "'
                  )";
            //echo "<br>sql: $sql_exc";
            $st = oci_parse($conn_oracle, $sql_exc);
            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              echo  "<br>Insercion Correcta ";
            } else {
              echo  "<br>Insercion Incorrecta";
            }
          }
          ////////////dispositivos--dispositivos--dispositivos--dispositivos--dispositivos/////////////

          ////////////productosnutricionales--productosnutricionales--productosnutricionales--/////////////
          ///////////////////////////////////////////////////////////////////////////////////////////////////////
          echo "<br>----------------------Insertar productosnutricionales";
          $subCadenaGene = obtener_sub_cadena_tutelas(',"productosnutricionales"', ',"serviciosComplementarios"', $array[$i], strlen(',"productosnutricionales"') + 1);
          //echo "<br>subCadenaGene: $subCadenaGene<br>";

          $json_array = json_decode($subCadenaGene, true);
          $FFalloAdic_oracle = '';
          foreach ($json_array as $clave) {

            $sql_exc = "INSERT
            INTO WEBSERV_TUTELA_PROD_NUTR
              (
                ID_TUTE,
                CONORDEN,
                TIPTUT,
                TIPOPREST,
                TIPPPRONUT,
                DESCPRODNUTR,
                CODFORMA,
                CODVIAADMON,
                JUSTNOPBS,
                DOSIS,
                DOSISUM,
                NOFADMON,
                CODFREADMON,
                INDESP,
                CANTRAT,
                DURTRAT,
                CANTTOTALF,
                UFCANTTOTAL,
                INDREC
              )
              VALUES
              (
                " . "SEQ_WEBSERV_TUTELA_TUTELA.currval" . ",
                '" . $clave["ConOrden"] . "',
                '" . $clave["TipTut"] . "',
                '" . $clave["TipoPrest"] . "',
                '" . $clave["TippProNut"] . "',
                '" . $clave["DescProdNutr"] . "',
                '" . $clave["CodForma"] . "',
                '" . $clave["CodViaAdmon"] . "',
                '" . $clave["JustNoPBS"] . "',
                '" . $clave["Dosis"] . "',
                '" . $clave["DosisUM"] . "',
                '" . $clave["NoFAdmon"] . "',
                '" . $clave["CodFreAdmon"] . "',
                '" . $clave["IndEsp"] . "',
                '" . $clave["CanTrat"] . "',
                '" . $clave["DurTrat"] . "',
                '" . $clave["CantTotalF"] . "',
                '" . $clave["UFCantTotal"] . "',
                '" . $clave["IndRec"] . "'
                  )";
            //echo "<br>sql: $sql_exc";
            $st = oci_parse($conn_oracle, $sql_exc);
            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              echo  "<br>Insercion Correcta ";
            } else {
              echo  "<br>Insercion Incorrecta";
            }
          }
          ////////////productosnutricionales--productosnutricionales--productosnutricionales/////////////

          ////////////serviciosComplementarios--serviciosComplementarios--serviciosComplementarios--/////////////
          ///////////////////////////////////////////////////////////////////////////////////////////////////////

          echo "<br>----------------------Insertar serviciosComplementarios";
          $cad_busc_ini = ',"serviciosComplementarios"';
          $posInicial = strpos($array[$i], $cad_busc_ini) + strlen(',"serviciosComplementarios"') + 1;
          $posFinal = strlen($array[$i]);
          $subCadenaGene = substr($array[$i], $posInicial, ($posFinal - 1) - $posInicial);
          //echo "<br>subCadenaGene: $subCadenaGene<br>";

          $json_array = json_decode($subCadenaGene, true);
          $FFalloAdic_oracle = '';
          foreach ($json_array as $clave) {


            $sql_exc = "INSERT
            INTO WEBSERV_TUTELA_SERV_COMP
              (
                ID_TUTE,
                CONORDEN,
                TIPTUT,
                TIPOPREST,
                CODSERCOMP,
                DESCSERCOMP,
                CANFORM,
                CADAFREUSO,
                CODFREUSO,
                CANT,
                CODPERDURTRAT,
                CANTTOTAL,
                JUSTNOPBS,
                INDREC
              )
              VALUES
              (
                " . "SEQ_WEBSERV_TUTELA_TUTELA.currval" . ",
                '" . $clave["ConOrden"] . "',
                '" . $clave["TipTut"] . "',
                '" . $clave["TipoPrest"] . "',
                '" . $clave["CodSerComp"] . "',
                '" . $clave["DescSerComp"] . "',
                '" . $clave["CanForm"] . "',
                '" . $clave["CadaFreUso"] . "',
                '" . $clave["CodFreUso"] . "',
                '" . $clave["Cant"] . "',
                '" . $clave["CodPerDurTrat"] . "',
                '" . $clave["CantTotal"] . "',
                '" . $clave["JustNoPBS"] . "',
                '" . $clave["IndRec"] . "'
                  )";
            //echo "<br>sql: $sql_exc";
            $st = oci_parse($conn_oracle, $sql_exc);
            $result = oci_execute($st);
            oci_free_statement($st);
            if ($result) {
              echo  "<br>Insercion Correcta ";
            } else {
              echo  "<br>Insercion Incorrecta";
            }
          }
          ////////////serviciosComplementarios--serviciosComplementarios--serviciosComplementarios/////////////

        } else {
          echo "<br>Error al insertar la tutela general";
        }
      }

      echo "<br>-----------------------------------------------------------";
      echo "<br>--------Cantidad de Tutelas insertadas: $cont_tute ";
    }
  }
}
