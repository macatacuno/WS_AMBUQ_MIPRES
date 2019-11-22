<?php
set_time_limit(9999999);

//Conexion MySql
 //Local
$servername = "localhost";
$database = "db_app_ambuq";
$username = "root";
$password = "";

/* //Remoto
$servername = "localhost";
$database = "id11032976_db_app_ambuq";
$username = "id11032976_roberto";
$password = "jpkqRJ45DLink";
*/
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully<br>";


//Conexion Oracle
/*
$servername = "10.244.19.75:1521";
$database = "ambuqPRD";
$username = "RPARRA";
$password = "Rparra2019";*/

/*
$Db = oci_connect ( "RPARRA", "Rparra2019", "10.244.19.75:1521/ambuqPRD");

if (!$db) die ( "Error al conectar con la base de datos de Oracle:".oci_error ());

echo "Conectado correctamente a la base de datos Oracle!";
*/
?>