<?php

/**********************Cargar funciones funciones_generales***************************/
include('../../funciones_generales.php');

/**********************Cargarel procedimiento para la conexion de oracle**************/
include('../../../conexcion_php_oracle.php');
$conn_oracle = conectar_oracle(); //funcion para abir la conexion con QAS

/**********************Variables generales*******************************************/
$tipo_id = 2; //Subsidiado
$servicio_id = 15; // Se asigna el codigo del servicio AnularSuministro

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


$json = '[{"IDSuministro":5874182},
{"IDSuministro":5875405},
{"IDSuministro":5887556},
{"IDSuministro":5898909},
{"IDSuministro":5898909},
{"IDSuministro":5899351},
{"IDSuministro":5899424},
{"IDSuministro":5900793},
{"IDSuministro":5908700},
{"IDSuministro":5900238},
{"IDSuministro":5900215},
{"IDSuministro":5900463},
{"IDSuministro":5899100},
{"IDSuministro":5887461},
{"IDSuministro":5887395},
{"IDSuministro":5887403},
{"IDSuministro":5860877},
{"IDSuministro":5887459},
{"IDSuministro":5873728},
{"IDSuministro":5935555},
{"IDSuministro":5936807},
{"IDSuministro":6608969},
{"IDSuministro":6608975},
{"IDSuministro":6608976},
{"IDSuministro":6892640},
{"IDSuministro":6986533},
{"IDSuministro":6986571},
{"IDSuministro":6986519},
{"IDSuministro":6986518},
{"IDSuministro":6986520},
{"IDSuministro":6995865},
{"IDSuministro":6995585},
{"IDSuministro":6995865},
{"IDSuministro":6987560},
{"IDSuministro":7032732},
{"IDSuministro":7053794},
{"IDSuministro":7061450},
{"IDSuministro":7061415},
{"IDSuministro":7437840},
{"IDSuministro":7443965},
{"IDSuministro":7437877},
{"IDSuministro":7061037},
{"IDSuministro":7456446},
{"IDSuministro":6892641},
{"IDSuministro":7488446},
{"IDSuministro":7488446},
{"IDSuministro":7498524},
{"IDSuministro":7500681},
{"IDSuministro":7456441},
{"IDSuministro":7538586},
{"IDSuministro":6987624},
{"IDSuministro":7545598},
{"IDSuministro":7546429},
{"IDSuministro":7061029},
{"IDSuministro":7567639},
{"IDSuministro":7575266},
{"IDSuministro":6990466},
{"IDSuministro":7705796},
{"IDSuministro":6986569},
{"IDSuministro":7498171},
{"IDSuministro":7498171},
{"IDSuministro":7498447},
{"IDSuministro":7538599},
{"IDSuministro":6995873},
{"IDSuministro":7751748},
{"IDSuministro":7456441},
{"IDSuministro":6892201},
{"IDSuministro":7800792},
{"IDSuministro":7800793},
{"IDSuministro":7854413},
{"IDSuministro":7860993},
{"IDSuministro":7860999},
{"IDSuministro":7859237},
{"IDSuministro":8000376},
{"IDSuministro":8000598},
{"IDSuministro":7860968},
{"IDSuministro":7456441},
{"IDSuministro":6892201},
{"IDSuministro":7800792},
{"IDSuministro":7800793},
{"IDSuministro":7854413},
{"IDSuministro":12474257},
{"IDSuministro":7500051},
{"IDSuministro":7500051},
{"IDSuministro":7860993},
{"IDSuministro":7860999},
{"IDSuministro":8000376},
{"IDSuministro":8000598},
{"IDSuministro":7860968},
{"IDSuministro":8151196},
{"IDSuministro":8000618},
{"IDSuministro":8001812},
{"IDSuministro":8181128},
{"IDSuministro":8124937},
{"IDSuministro":8002514},
{"IDSuministro":8123579},
{"IDSuministro":7962887},
{"IDSuministro":8234484},
{"IDSuministro":8234567},
{"IDSuministro":8370438},
{"IDSuministro":8370942},
{"IDSuministro":8673248},
{"IDSuministro":8646464},
{"IDSuministro":8673322},
{"IDSuministro":8726957},
{"IDSuministro":8726972},
{"IDSuministro":9140204},
{"IDSuministro":8726978},
{"IDSuministro":8727059},
{"IDSuministro":8727069},
{"IDSuministro":8727260},
{"IDSuministro":7961979},
{"IDSuministro":8727658},
{"IDSuministro":8727694},
{"IDSuministro":14038257},
{"IDSuministro":8728798},
{"IDSuministro":8728895},
{"IDSuministro":14044569},
{"IDSuministro":14044792},
{"IDSuministro":14040823},
{"IDSuministro":14052881},
{"IDSuministro":14054053},
{"IDSuministro":8728890},
{"IDSuministro":8728892},
{"IDSuministro":8728895},
{"IDSuministro":8728896},
{"IDSuministro":8728897},
{"IDSuministro":5900655},
{"IDSuministro":8728899},
{"IDSuministro":8728901}]';


$json_array = json_decode($json, true);
foreach ($json_array as $clave) {
    echo "<br>IDSuministro: " . $clave["IDSuministro"] . "=> ";
    $url = $url_bd . "/" . $nit . '/' . $token_temporal . '/' .  $clave["IDSuministro"];
    $json_anulacion = '';
    $respuesta = direccionar_put($url, $json_anulacion);
    //echo "<br>url: $url<br>";
    echo "$respuesta";
}
