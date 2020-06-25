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

$periodo_inicial = "20-06-16";
$periodo_final = "20-06-16";
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
    //$json = Webservice_get($url); //$json = file_get_contents($url);
    $json = '{"tutela":{"NoTutela":"20200616225001122163","FTutela":"2020-06-16T00:00:00","HTutela":"12:11:53","CodEPS":"ESSC76","TipoIDEPS":"NI","NroIDEPS":"818000140","TipoIDProf":"CC","NumIDProf":"31402004","PNProfS":"MARTHA","SNProfS":"TERESA","PAProfS":"LOPEZ","SAProfS":"SILVA","RegProfS":"06947","TipoIDPaciente":"CC","NroIDPaciente":"38901272","PNPaciente":"OFELIA","SNPaciente":"","PAPaciente":"RAMIREZ","SAPaciente":"MONTOYA","NroFallo":"2018008700","FFalloTutela":"2018-02-22T00:00:00","F1Instan":null,"F2Instan":null,"FCorte":null,"FDesacato":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C509","CodDxRel1":null,"CodDxRel2":null,"AclFalloTut":"TUTELAINTEGRAL,ORDENATRANSPORTEYVIÁTICOSCONACOMPAÑANTEFUERADESUCIUDAD.ASISTIRYRECIBIRTRATAMIENTO,CITAS,TERAPIAS,ACCESOASERVICIOSDESALUD","CodDxMotS1":"C509","CodDxMotS2":null,"CodDxMotS3":null,"JustifMed":"\tTUTELAINTEGRAL,ORDENATRANSPORTEYVIÁTICOSCONACOMPAÑANTEFUERADESUCIUDAD.ASISTIRYRECIBIRTRATAMIENTO,CITAS,TERAPIAS,ACCESOASERVICIOSDESALUD","CritDef1CC":1,"CritDef2CC":0,"CritDef3CC":1,"CritDef4CC":1,"TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"EstTut":4},"fallosAdicionales":[{"FFalloAdic":"2018-02-22T00:00:00","NroFalloAdic":"656"},{"FFalloAdic":"2018-02-22T00:00:00","NroFalloAdic":"4"}],"medicamentos":[{"ConOrden":3,"TipoMed":1,"TipTut":1,"TipoPrest":1,"TipMed":4,"DscMedPA":"515","CodFF":"516","CodVA":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115","PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"},{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"515"},{"ConOrden":4,"CodIndicacion":"515"}]},{"ConOrden":3,"TipoMed":1,"TipTut":1,"TipoPrest":1,"TipMed":4,"DscMedPA":"515","CodFF":"516","CodVA":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115","PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"},{"ConOrden":4,"CodPriAct":"516","ConcCant":"517","UMedConc":"518","CantCont":"519","UMedCantCont":"5110"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"515"},{"ConOrden":4,"CodIndicacion":"515"}]}],"procedimientos":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TipPro":4,"CodCUPS":"515","NomProc":"516","CanForm":"517","CadaFreUso":"518","CodFreUso":9,"Cant":"5110","CodPerDurTrat":1,"CantTotal":"5111","JustNoPBS":"5112","IndRec":"5113","Objetivo":"5114"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TipPro":4,"CodCUPS":"515","NomProc":"516","CanForm":"517","CadaFreUso":"518","CodFreUso":9,"Cant":"5110","CodPerDurTrat":1,"CantTotal":"5111","JustNoPBS":"5112","IndRec":"5113","Objetivo":"5114"}],"dispositivos":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodDisp":"514","CanForm":"515","CadaFreUso":"516","CodFreUso":7,"Cant":"518","CodPerDurTrat":1,"CantTotal":"519","JustNoPBS":"5110","IndRec":"5111","Objetivo":"5112"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodDisp":"514","CanForm":"515","CadaFreUso":"516","CodFreUso":7,"Cant":"518","CodPerDurTrat":1,"CantTotal":"519","JustNoPBS":"5110","IndRec":"5111","Objetivo":"5112"}],"productosnutricionales":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TippProNut":"514","DescProdNutr":"515","CodForma":"516","CodViaAdmon":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"TippProNut":"514","DescProdNutr":"515","CodForma":"516","CodViaAdmon":"517","JustNoPBS":"518","Dosis":"519","DosisUM":"5110","NoFAdmon":"5111","CodFreAdmon":1,"IndEsp":1,"CanTrat":"5112","DurTrat":1,"CantTotalF":"5113","UFCantTotal":"5114","IndRec":"5115"}],"serviciosComplementarios":[{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodSerComp":4,"DescSerComp":"515","CanForm":"516","CadaFreUso":"517","CodFreUso":8,"Cant":"519","CodPerDurTrat":1,"CantTotal":"5110","JustNoPBS":"5111","IndRec":"5112"},{"ConOrden":3,"TipTut":1,"TipoPrest":1,"CodSerComp":4,"DescSerComp":"515","CanForm":"516","CadaFreUso":"517","CodFreUso":8,"Cant":"519","CodPerDurTrat":1,"CantTotal":"5110","JustNoPBS":"5111","IndRec":"5112"}]}';


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
      $cont_tute = 0;
      for ($i = 0; $i < $longitud; $i++) {
        $cont_tute = $cont_tute + 1;
        //////////////////////////////////////////////////////////////////////////////////////////////////////////Tutela
        //Obtener cadena general de Tutela
        $cad_busc_ini = '{"tutela"';
        $cad_busc_fin = ',"fallosAdicionales"';
        $cadena = $array[$i];
        // echo "<br>cadena: $cadena<br>";
        $posInicial = strpos($cadena, $cad_busc_ini) + 10;
        $posFinal = strpos($cadena, $cad_busc_fin);
        $subCadenaGene = substr($cadena, $posInicial, $posFinal - $posInicial);
        $subCadenaGene = "[$subCadenaGene]";
        echo "<br>subCadenaGene: $subCadenaGene<br>";
        if ($cadena != '') {

          // insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'SI', $serv_nombre, $tipo_get, $periodo_conteo);
          $json_array = json_decode($subCadenaGene, true);


          foreach ($json_array as $clave) {

            //$CantTotAEntregar = str_replace("'", "", $clave["CantTotAEntregar"]);
            /////Insertar Tutela (Inicio)
            echo "<br>----------------------Insertar tutela general<br>";
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
            $INSERCIONGENERALTUTELA = true; //Se debe eliminar despues
            /* $st = oci_parse($conn_oracle, $sql_exc);
                    $result = oci_execute($st);
                    oci_free_statement($st);
                    if ($result) {
                      $INSERCIONGENERALTUTELA=true;
                       // echo  "<br>Insercion Correcta ";
                    } else {
                      
                      $INSERCIONGENERALTUTELA=false;
                        echo  "<br>Insercion Incorrecta en el direccionamiento #" . $clave["IDDireccionamiento"];
                    } */
          }

        }


        //////////////////////////////////////////////////////////////////////////////////////////////////////////Tutela
        //Obtener cadena general de Tutela
        if ($INSERCIONGENERALTUTELA) {
          $cad_busc_ini = ',"fallosAdicionales"';
          $cad_busc_fin = ',"medicamentos"';
          $cadena = $array[$i];
          $posInicial = strpos($cadena, $cad_busc_ini) + 21;
          $posFinal = strpos($cadena, $cad_busc_fin);
          $subCadenaGene = substr($cadena, $posInicial, $posFinal - $posInicial);
          $subCadenaGene = $subCadenaGene;
          //echo "<br>subCadenaGene: $subCadenaGene<br>";
          if ($cadena != '') {
            $json_array = json_decode($subCadenaGene, true);

            foreach ($json_array as $clave) {

              echo "<br>----------------------Insertar fallosAdicionales<br>";
              $sql_exc = "INSERT
              INTO WEBSERV_TUTELA_FALLOS_ADIC
                (
                  ID_TUTE,
                  FFALLOADIC,
                  NROFALLOADIC
                )
                VALUES
                (
                  '" . "SEQ_WEBSERV_TUTELA_TUTELA.currval" . "',
                  '" . $clave["FFalloAdic"] . "',
                  '" . $clave["NroFalloAdic"] . "'
                )";
              echo "<br>sql: $sql_exc";
              /* $st = oci_parse($conn_oracle, $sql_exc);
                    $result = oci_execute($st);
                    oci_free_statement($st);
                    if ($result) {
                       // echo  "<br>Insercion Correcta ";
                    } else {
                        echo  "<br>Insercion Incorrecta;
                    } */
            }
          }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      }
      
           echo "<br>--------Cantidad de Tutelas insertadas: $cont_tute ";
    }
  }
}
