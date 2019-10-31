


////////////////////////////////Panel Principal////////////////////////////////////////////////////////
function cargarPanel(){
    $('#contenido_principal').load('./vistas/panel.html');
    $(".collapse-item").removeClass("active");//elimina la clase active de cualquir item que la tenga
}
function cargarActualizacionManual(){
      window.open('./Reportes/getJsonAutomaticoGlobal.php', 'Actualización General');
/*jQuery.get("./Reportes/getJsonAutomaticoGlobal.php",function(resul){
    alert(resul);
   })*/
}

//////////////////////////////////WSSUMINISTROAPI///////////////////////////////////////////////////////////
function cargarEntregaXFecha (){
      $('#contenido_principal').load('./Reportes/GetReporteEntregaXFecha/vistaEntregaXFecha.html'); 
      $(".collapse-item").removeClass("active");//elimina la clase active de cualquir item que la tenga
      $("#a_entregaxfecha").addClass("active");//agrega la clase active al item seleccionado
}

function cargarEntregaXNoPrescripcion (){
      $('#contenido_principal').load('./Reportes/GetReporteEntregaXNoPrescripcion/ReporteEntregaXNoPrescripcion.html'); 
      $(".collapse-item").removeClass("active");//elimina la clase active de cualquir item que la tenga
      $("#a_entregaxfecha").addClass("active");//agrega la clase active al item seleccionado
}
//////////////////////////////////WSPRESCRIPCIÓN///////////////////////////////////////////////////////////
function cargarPrescripcionPorFecha (){
      $('#contenido_principal').load('./Reportes/GetPrescripcion/vistaPrescripcion.html'); 
      $(".collapse-item").removeClass("active");//elimina la clase active de cualquir item que la tenga
      $("#a_prescripcionporfecha").addClass("active");//agrega la clase active al item seleccionado
}



