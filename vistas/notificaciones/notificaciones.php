<?php
include("../../conexion.php");

$notificaciones="";

$consulta = "SELECT log_Err_nombre, logErr_descripcion FROM log_errores";
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) { 
$notificaciones=$notificaciones."   <a class='dropdown-item d-flex align-items-center' href='#'>";
$notificaciones=$notificaciones."      <div class='mr-3'>";
$notificaciones=$notificaciones."        <div class='icon-circle bg-primary'>";
$notificaciones=$notificaciones."          <i class='fas fa-exclamation-triangle text-white'></i>";
$notificaciones=$notificaciones."        </div>";
$notificaciones=$notificaciones."      </div>";
$notificaciones=$notificaciones."      <div>";
$notificaciones=$notificaciones."        <div class='small text-gray-500'>".$fila["log_Err_nombre"]."</div>";
$notificaciones=$notificaciones."        <span class='font-weight-bold'>".$fila["logErr_descripcion"]."</span>";
$notificaciones=$notificaciones."      </div>";
$notificaciones=$notificaciones."    </a>";
}
}
echo $notificaciones;
?>