<?php
include("../../conexion.php");
$cantidad="";
$consulta = "SELECT count(*) cantidad FROM log_errores";

if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
      $cantidad=$fila["cantidad"];
}
}
echo $cantidad;
?>