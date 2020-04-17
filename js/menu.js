

function cargarActualizacionManual() {
      window.open('./consumo_ws/reportes/getJsonAutomaticoGlobal.php', 'Actualización General');
}

/**********************************************************************************************************/
/////////////////////////////////////Panel Principal////////////////////////////////////////////////////////
/**********************************************************************************************************/
function cargarPanel() {
      $('#contenido_principal').load('./vistas/panel.php');

      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga
            $("#a_panel").addClass("active");//agrega la clase active al item seleccionado
      })
}

/**********************************************************************************************************/
//////////////////////////////////WSSUMINISTROAPI///////////////////////////////////////////////////////////
/**********************************************************************************************************/


//////////////////////////////////Direccionamiento///////////////////////////////////////////////////////////
function cargarNumDirecXFecha() {
      $('#contenido_principal').load('./consumo_ws/Reportes/Direccionamientos/GetDireccionamiento/vistaDireccionamiento.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc_x_fecha").addClass("active");//agrega la clase active al item seleccionado
      })

}

function cargarNumDirecXNoPrescripcion() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc_x_numero_presc").addClass("active");//agrega la clase active al item seleccionado
      })
}

function cargarNumDirecXPacienteFecha() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc_x_pacientefecha").addClass("active");//agrega la clase active al item seleccionado
      })
}


function EnviarDireccionamiento() {
      //$('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      $('#contenido_principal').load('./consumo_ws/envios/Direccionamiento/vistaPutDirec.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_direc_envio").addClass("active");//agrega la clase active al item seleccionado
      })
}





//////////////////////////////////No Direccionamiento///////////////////////////////////////////////////////////
function cargarNumNoDirecXFecha() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_no_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_no_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_no_direc_x_fecha").addClass("active");//agrega la clase active al item seleccionado
      })

}

function cargarNumNoDirecXNoPrescripcion() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_no_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_no_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_no_direc_x_numero_presc").addClass("active");//agrega la clase active al item seleccionado
      })
}

function cargarNumNoDirecXPacienteFecha() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_numero_no_direc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_no_direc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_numero_no_direc_x_pacientefecha").addClass("active");//agrega la clase active al item seleccionado
      })
}

//////////////////////////////////Reporte Entrega///////////////////////////////////////////////////////////
function cargarEntregaXFecha() {
      $('#contenido_principal').load('./consumo_ws/reportes/ReporteEntrega/GetReporteEntregaXFecha/vistaEntregaXFecha.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_entrega").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_entrega").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_entrega_x_fecha").addClass("active");//agrega la clase active al item seleccionado
      })

}

function cargarEntregaXNoPrescripcion() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      //$('#contenido_principal').load('./consumo_ws/reportes/ReporteEntrega/GetReporteEntregaXNoPrescripcion/ReporteEntregaXNoPrescripcion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_entrega").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_entrega").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_entrega_x_numero_presc").addClass("active");//agrega la clase active al item seleccionado
      })
}

function cargarEntregaXPacienteFecha() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      //$('#contenido_principal').load('./consumo_ws/reportes/ReporteEntrega/GetReporteEntregaXPacienteFecha/ReporteEntregaXPacienteFecha.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_sumi").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_sumi_entrega").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_sumi").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_entrega").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_sumi_entrega_x_pacientefecha").addClass("active");//agrega la clase active al item seleccionado
      })
}

/**********************************************************************************************************/
///////////////////////////////////WSPRESCRIPCIÓN///////////////////////////////////////////////////////////
/**********************************************************************************************************/


//////////////////////////////////Anulaciones///////////////////////////////////////////////////////////
function cargarAnulacionPorPresc() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_anula").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_anula").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_anula_x_presc").addClass("active");//agrega la clase active al item seleccionado
      })
}
//////////////////////////////////Junta profesional///////////////////////////////////////////////////////////
function cargarJuntProfPorJP() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_junta_profe").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_junta_profe").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_junta_profe_x_junta_prefe").addClass("active");//agrega la clase active al item seleccionado
      })
}
function cargarJuntProfPorpaciente() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_junta_profe").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_junta_profe").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_junta_profe_x_paciente").addClass("active");//agrega la clase active al item seleccionado
      })
}

function cargarJuntProfPorfecha() {
      //$('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      $('#contenido_principal').load('./consumo_ws/reportes/JuntaProfesionales/GetJuntaProfesional/vistaJuntaProfesional.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_junta_profe").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_junta_profe").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_junta_profe_x_fecha").addClass("active");//agrega la clase active al item seleccionado
      })
}

//////////////////////////////////Prescripción///////////////////////////////////////////////////////////
function cargarPrescripcionPorFecha() {
     // $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      $('#contenido_principal').load('./consumo_ws/reportes/Prescripcion/GetPrescripcion/vistaPrescripcion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_presc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc_x_fecha").addClass("active");//agrega la clase active al item seleccionado
      })
}

function cargarPrescripcionPorPaciente() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      //$('#contenido_principal').load('./consumo_ws/reportes/Prescripcion/GetPrescripcionPaciente/PrescripcionPaciente.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_presc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc_x_paciente").addClass("active");//agrega la clase active al item seleccionado
      })
}


function cargarPrescripcionXNumero() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      //$('#contenido_principal').load('./consumo_ws/reportes/Prescripcion/GetPrescripcionXNumero/PrescripcionXNumero.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_presc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc_x_numero").addClass("active");//agrega la clase active al item seleccionado
      })
}

function cargarNovedadesPrescripcion() {
      $('#contenido_principal').load('./vistas/pagina_en_construccion.html');
      //$('#contenido_principal').load('./consumo_ws/reportes/Prescripcion/GetNovedadesPrescripcion/vistaNovedadesPrescripcion.html');
      jQuery.get("./menu.php", function (resul) {
            $("#div_menu").html(resul);
            $(".nav-link").removeClass("active");//elimina la clase active de cualquir item que la tenga       

            $("#li_open_pres").addClass("menu-open");//agrega la clase active al item seleccionado
            $("#li_open_pres_presc").addClass("menu-open");//agrega la clase active al item seleccionado

            $("#a_active_pres").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc").addClass("active");//agrega la clase active al item seleccionado
            $("#a_active_pres_presc_x_novedades_prescripcion").addClass("active");//agrega la clase active al item seleccionado
      })
}


