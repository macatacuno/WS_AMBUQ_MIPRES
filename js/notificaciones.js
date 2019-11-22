function cargarNotificaciones(){
        jQuery.get("./vistas/notificaciones/notificaciones_cantidad.php",function(resul){
          if(resul=='0'){
            //$("#cantidad_alertas").html("");
            $("#cantidad_alertas").html('');
            $("#id_cant_alert").removeClass("badge badge-danger badge-counter");
          }else{ 
            $("#id_cant_alert").addClass("badge badge-danger badge-counter");
            $("#cantidad_alertas").html(resul);
          }
      })

      jQuery.get("./vistas/notificaciones/notificaciones.php",function(resul){
        $("#div_alertas").html(resul);
      })
}