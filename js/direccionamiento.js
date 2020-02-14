var val_NoPrescripcion_antes="";
function cargar_tipotec_pres(){
    val_NoPrescripcion_antes = $('#NoPrescripcion').val();

}
function cargar_tipotec() {

    //Cargar la informacion           
    val_NoPrescripcion = $('#NoPrescripcion').val();

    if(val_NoPrescripcion!=val_NoPrescripcion_antes){
        $.ajax({
            url: './put/Direccionamiento/obtener_datos_tipo_tec.php',
            type: "POST",
            data: {
                NoPrescripcion: val_NoPrescripcion
            }
        })
            .done(function (data) {
                json = jQuery.parseJSON(data);
                sw = 0;
                lista = '<select disabled="disabled" onchange="cargar_datos_con_tec();cargar_datos_pres();" id="TipoTec" name="TipoTec" class="form-control"><option value="">Seleccionar opción</option>';
                for (var key in json) {
                    sw = 1;
                    //document.write("<br>" + key + " - " + json[key]);
                    sub_json = json[key];
                    for (var sub_key in sub_json) {
                        if (sub_key == 'TIPOTEC') {
                            lista = lista + "<option value='" + sub_json[sub_key] + "'>" + sub_json[sub_key];
                        } else if (sub_key == 'DESC_TIPOTEC') {
                            lista = lista + '-' + sub_json[sub_key] + '</option>';
                        }
                    }
                }
                if (sw == 0) {
                    lista = '<select disabled="disabled" onchange="cargar_datos_con_tec();cargar_datos_pres();" id="TipoTec" name="TipoTec" class="form-control">';
                }else {
                    myVar = setTimeout(habilitar_TipoTec, 200);
                };
                lista = lista + '</select>';
    
                $("#div_TipoTec").html(lista);
                //deshabiliotar todos los objetos del ormulario cada ves que se ejecute esta funcion
                $("#div_ConTec").html('<select disabled="disabled" id="ConTec" name="ConTec" class="form-control"></select>');
                $(".limpiar").val('');
            })
            .fail(function (data) {
                alert("error:" + data);
            });
    }

}


function cargar_datos_con_tec() {
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_TipoTec = $('#TipoTec').val();

    $.ajax({
        url: './put/Direccionamiento/obtener_datos_con_tec.php',
        type: "POST",
        data: {
            NoPrescripcion: val_NoPrescripcion,
            TipoTec: val_TipoTec
        }
    })
        .done(function (data) {
            json = jQuery.parseJSON(data);
            sw = 0;
            lista = '<select id="ConTec" disabled="disabled" name="ConTec" class="form-control">';
            for (var key in json) {
                sw = 1;
                //document.write("<br>" + key + " - " + json[key]);
                sub_json = json[key];
                for (var sub_key in sub_json) {
                    if (sub_key == 'CONORDEN') {
                        lista = lista + "<option value='" + sub_json[sub_key] + "'>" + sub_json[sub_key] + '</option>';
                    }

                }
            }
            if (sw == 0) {
                lista = '<select disabled="disabled" id="ConTec" name="ConTec" class="form-control">';
            } else {
                myVar = setTimeout(habilitar_ConTec, 200);
            };
            lista = lista + '</select>';
            $("#div_ConTec").html(lista);
        })
        .fail(function (data) {
            alert("error:" + data);
        });


}


function cargar_datos_pres() {
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_TipoTec = $('#TipoTec').val();

    $.ajax({
        url: './put/Direccionamiento/obtener_datos_pres.php',
        type: "POST",
        data: {
            NoPrescripcion: val_NoPrescripcion,
            TipoTec: val_TipoTec
        }
    })
        .done(function (data) {
            json = jQuery.parseJSON(data);
            for (var key in json) {
                sw = 1;
                //document.write("<br>" + key + " - " + json[key]);
                sub_json = json[key];
                for (var sub_key in sub_json) {

                    $("#TipoIDProv").val('NI');
                    $("#NoSubEntrega").val(0);
                    if (sub_key == 'TIPOIDPACIENTE') {
                        $("#TipoIDPaciente").val(sub_json[sub_key]);

                    } else if (sub_key == 'NROIDPACIENTE') {
                        $("#NoIDPaciente").val(sub_json[sub_key]);

                    } else if (sub_key == 'CODMUNENT') {
                        $("#CodMunEnt").val(sub_json[sub_key]);

                    } else if (sub_key == 'DIRPACIENTE') {
                        $("#DirPaciente").val(sub_json[sub_key]);

                    };
                        
                }
            }
        })
        .fail(function (data) {
            alert("error:" + data);
        });
}

function eviar_direc(){
    

}

function habilitar_ConTec() {
    $('#ConTec').removeAttr('disabled');
}


function habilitar_TipoTec() {
    $('#TipoTec').removeAttr('disabled');
}
