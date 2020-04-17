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

/**********************Cargar Encabezado**********************************************/
$periodo_inicial = "20-03-20";
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

        if ($json == "" || $json == '{"Message":"Error."}') {
            insertar_log_de_error($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, $serv_nombre, $tipo_get, $periodo_conteo);
        } else if ($json == "[]") {
            insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'NO',$serv_nombre, $tipo_get, $periodo_conteo);
        } else {
            /**************************************************************************************************/
            /************(Inicio)Bloque para Insertar el json consolidado******/
            /**************************************************************************************************/
            /////////////////////////////////////////General////////////////////////////////////////////////////
            insertar_periodo_json($conn_oracle, $servicio_id, $tipo_id, $fecha_oracle, 'SI',$serv_nombre, $tipo_get, $periodo_conteo);
        }

    }
}
