<?php

/**********************Cargar funciones funciones_generales***************************/
include('../../../funciones_generales.php');

/**********************Cargarel procedimiento para la conexion de oracle**************/
include('../../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

/**********************Variables generales*******************************************/
$tipo_get = "subsidiado";
$tipo_id = 2; //subsidiado
$servicio_id = 1; // Se asigna el codigo del servicio GetReporteEntrega

/**********************Obtener los datos para armar la URL***************************/
$url_bd = obtener_datos_url("URL", $servicio_id, $conn_oracle);
$serv_nombre = obtener_datos_url("SERV_NOMBRE", $servicio_id, $conn_oracle);
$ws_nombre = obtener_datos_url("WS_NOMBRE", $servicio_id, $conn_oracle);
$Webservice = obtener_datos_url("WS_ID", $servicio_id, $conn_oracle);

/**********************Obtener el nit y el token**************************************/
$nit = obtener_datos_token_nit("NIT", $tipo_id, $conn_oracle);
$token = obtener_datos_token_nit("TOKEN", $tipo_id, $conn_oracle);
$horas_de_diferencia = obtener_datos_token_nit("HORAS_DE_DIFERENCIA", $tipo_id, $conn_oracle);
$token_temporal = obtener_datos_token_nit("TOKEN_TEMPORAL", $tipo_id, $conn_oracle);
$token_temporal = actualizar_token_temporal($horas_de_diferencia, $conn_oracle, $nit, $token, $token_temporal);

/**********************Cargar Encabezado**********************************************/
$periodo_inicial = "19-01-01";
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

        $url = $url_bd . "/" . $nit . '/' . $token_temporal . '/' . "20" . $periodo_conteo;
        $json = Webservice_get($url); //$json = file_get_contents($url);  
        $json = str_replace("\\n", "", $json);
        $json = str_replace("\\t", "", $json);
        $json = formatear_json_general($json);
        //echo "<br>url: $url<br>";
        //echo "<br>json: $json<br>";
        $fecha_oracle = date("d/m/Y", strtotime($periodo_conteo)); //formato original "y/m/d"
        echo "<br>/////////////////////// Json #" . $i_Principal . " Periodo: 20" . $periodo_conteo . "<br>";

        if ($json == "" || (strlen($json) >= 3 && strlen($json) <= 100)) {
            insertar_log_de_error($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, $serv_nombre, $tipo_get, $periodo_conteo);
        } else if ($json == "[]") {
            insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'NO', $serv_nombre, $tipo_get, $periodo_conteo);
        } else {
            /******************************************************************/
            /************(Inicio)Bloque para Insertar el json******/
            /******************************************************************/
            insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'SI', $serv_nombre, $tipo_get, $periodo_conteo);
            $json_array = json_decode($json, true);
            $cont_rep_ent = 0;
            foreach ($json_array as $clave) {
                $cont_rep_ent = $cont_rep_ent + 1;
                // echo "<br>--------Reporte de entrega #$cont_rep_ent ";

                $FecEntrega_oracle = date("d/m/Y", strtotime($clave["FecEntrega"])); //formato originar "y/m/d"
                $FecRepEntrega_oracle = date("d/m/Y H:i:s", strtotime($clave["FecRepEntrega"])); //formato originar "y/m/d"
                $FecAnulacion_oracle = "";
                if ($clave["FecAnulacion"] != '') {
                    $FecAnulacion_oracle = date("d/m/Y H:i:s", strtotime($clave["FecAnulacion"])); //formato originar "y/m/d"

                }
                
                $CodTecEntregado = str_replace(" ", "", $clave["CodTecEntregado"]);
                /////Insertar prescripcion (Inicio)
                $sql_exc = "INSERT
                INTO WEBSERV_REPORTE_ENTREGA
                  (
                    REPO_SERV_ID,
                    REPO_PERIODO,
                    REPO_TIRE_ID,
                    ID,
                    IDREPORTEENTREGA,
                    NOPRESCRIPCION,
                    TIPOTEC,
                    CONTEC,
                    TIPOIDPACIENTE,
                    NOIDPACIENTE,
                    NOENTREGA,
                    ESTADOENTREGA,
                    CAUSANOENTREGA,
                    VALORENTREGADO,
                    CODTECENTREGADO,
                    CANTTOTENTREGADA,
                    NOLOTE,
                    FECENTREGA,
                    FECREPENTREGA,
                    ESTREPENTREGA,
                    FECANULACION
                  )
                  VALUES
                  (
                    '" . $servicio_id . "',
                    '" . $fecha_oracle . "',
                    '" . $tipo_id . "',
                    '" . $clave["ID"] . "',
                    '" . $clave["IDReporteEntrega"] . "',
                    '" . $clave["NoPrescripcion"] . "',
                    '" . $clave["TipoTec"] . "',
                    '" . $clave["ConTec"] . "',
                    '" . $clave["TipoIDPaciente"] . "',
                    '" . $clave["NoIDPaciente"] . "',
                    '" . $clave["NoEntrega"] . "',
                    '" . $clave["EstadoEntrega"] . "',
                    '" . $clave["CausaNoEntrega"] . "',
                    '" . $clave["ValorEntregado"] . "',
                    '" . $CodTecEntregado . "',
                    '" . $clave["CantTotEntregada"] . "',
                    '" . $clave["NoLote"] . "',
                    '" . $FecEntrega_oracle . "',
                    '" . $FecRepEntrega_oracle . "',                    
                    '" . $clave["EstRepEntrega"] . "',
                    '" . $FecAnulacion_oracle . "'
                  )";
                  
                $st = oci_parse($conn_oracle, $sql_exc);
                $result = oci_execute($st);
                oci_free_statement($st);
                if ($result) {
                    // echo  "<br>Insercion Correcta ";
                } else {
                    
                echo "<br>sql: $sql_exc";
                echo  "<br>Insercion Incorrecta en la rescrion: ".$clave["NoPrescripcion"]." y en Reporte de entrega #" . $clave["IDReporteEntrega"];
                }
            }
            echo "<br>--------Cantidad de Reportes de entrega insertados: $cont_rep_ent ";
        }
    }
}
