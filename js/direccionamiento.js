var val_NoPrescripcion_antes = "";
function cargar_tipotec_pres() {
    val_NoPrescripcion_antes = $('#NoPrescripcion').val();
}
function cargar_tipotec() {

    //Cargar la informacion           
    val_NoPrescripcion = $('#NoPrescripcion').val();

    if (val_NoPrescripcion != val_NoPrescripcion_antes) {
        $.ajax({
            url: './consumo_ws/envios/Direccionamiento/obtener_datos_tipo_tec.php',
            type: "POST",
            data: {
                NoPrescripcion: val_NoPrescripcion
            }
        })
            .done(function (data) {
                json = jQuery.parseJSON(data);
                sw = 0;
                lista = '<select disabled="disabled" onchange="cargar_datos_con_tec();" id="TipoTec" name="TipoTec" class="form-control"><option value="">Seleccionar opción</option>';
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
                    lista = '<select disabled="disabled" onchange="cargar_datos_con_tec();" id="TipoTec" name="TipoTec" class="form-control">';
                } else {
                    myVar = setTimeout(habilitar_TipoTec, 200);
                };
                lista = lista + '</select>';

                $("#div_TipoTec").html(lista);
                //deshabilitar todos los objetos del ormulario cada ves que se ejecute esta funcion


                $("#div_ConTec").html('<select onchange="cargar_datos_pres();" id="ConTec"  disabled="disabled"  name="ConTec" class="form-control"></select>');
                limpiar();

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
        url: './consumo_ws/envios/Direccionamiento/obtener_datos_con_tec.php',
        type: "POST",
        data: {
            NoPrescripcion: val_NoPrescripcion,
            TipoTec: val_TipoTec
        }
    })
        .done(function (data) {
            json = jQuery.parseJSON(data);
            sw = 0;
            lista = '<select onchange="cargar_datos_pres();" id="ConTec" disabled="disabled" name="ConTec" class="form-control">';
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
                cargar_tipotec();
                lista = '<select onchange="cargar_datos_pres();"  id="ConTec" disabled="disabled" name="ConTec" class="form-control">';
                limpiar();
            } else {
                myVar = setTimeout(habilitar_ConTec, 200);
            };
            lista = lista + '</select>';
            $("#div_ConTec").html(lista);
            cargar_datos_pres();
        })
        .fail(function (data) {
            alert("error:" + data);
        });


}


function cargar_datos_pres() {
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_TipoTec = $('#TipoTec').val();
    val_ConTec = $('#ConTec').val();

    $.ajax({
        url: './consumo_ws/envios/Direccionamiento/obtener_datos_pres.php',
        type: "POST",
        data: {
            NoPrescripcion: val_NoPrescripcion,
            TipoTec: val_TipoTec,
            ConTec: val_ConTec
        }
    })
        .done(function (data) {
            json = jQuery.parseJSON(data);
            for (var key in json) {
                sw = 1;
                //document.write("<br>" + key + " - " + json[key]);
                sub_json = json[key];



                for (var sub_key in sub_json) {

                    $("#TipoIDProv").val('NI');//El tipo de proveedor tambien se sacar de la tabla prescriociones en el campo tipoidips
                    //el numero de la ips tambien se optiene de la prescripcion
                    $("#NoSubEntrega").val(0);
                    if (sub_key == 'REGIMEN') {
                        //alert("Regimen:"+ sub_json[sub_key]);
                        if (sub_json[sub_key] == 'C') {
                            $("#tipo option[value=1]").attr("selected", true);
                        } else if (sub_json[sub_key] == 'S') {
                            $("#tipo option[value=2]").attr("selected", true);
                        }
                    } else if (sub_key == 'TIPOIDPACIENTE') {
                        $("#TipoIDPaciente").val(sub_json[sub_key]);

                    } else if (sub_key == 'NROIDPACIENTE') {
                        $("#NoIDPaciente").val(sub_json[sub_key]);

                    } else if (sub_key == 'CODMUNENT') {
                        $("#CodMunEnt").val(sub_json[sub_key]);

                    } else if (sub_key == 'DIRPACIENTE') {
                        $("#DirPaciente").val(sub_json[sub_key]);

                    } else if (sub_key == 'FECHA_MAXIMA_DE_ENTREGA') {
                        $("#FecMaxEnt").val(sub_json[sub_key]);

                    } else if (sub_key == 'CODSERTECAENTREGAR') {
                        $("#CodSerTecAEntregar").val(sub_json[sub_key]);

                    } else if (sub_key == 'DESC_CODSERTECAENTREGAR') {
                        $("#desc_CodSerTecAEntregar").val(sub_json[sub_key]);

                    };
                }
            }
        })
        .fail(function (data) {
            alert("error:" + data);
        });
}

function eviar_direc() {
    val_tipo = $('#tipo').val();
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_TipoTec = $('#TipoTec').val();
    val_ConTec = $('#ConTec').val();
    val_TipoIDPaciente = $('#TipoIDPaciente').val();
    val_NoIDPaciente = $('#NoIDPaciente').val();
    val_NoEntrega = $('#NoEntrega').val();
    val_NoSubEntrega = $('#NoSubEntrega').val();
    val_TipoIDProv = $('#TipoIDProv').val();
    val_NoIDProv = $('#NoIDProv').val();
    val_CodMunEnt = $('#CodMunEnt').val();
    val_FecMaxEnt = $('#FecMaxEnt').val();
    val_CantTotAEntregar = $('#CantTotAEntregar').val();
    val_DirPaciente = $('#DirPaciente').val();
    val_CodSerTecAEntregar = $('#CodSerTecAEntregar').val();

    $.ajax({
        url: './consumo_ws/envios/Direccionamiento/Envio_put.php',
        type: "POST",
        data: {
            tipo: val_tipo,
            NoPrescripcion: val_NoPrescripcion,
            TipoTec: val_TipoTec,
            ConTec: val_ConTec,
            TipoIDPaciente: val_TipoIDPaciente,
            NoIDPaciente: val_NoIDPaciente,
            NoEntrega: val_NoEntrega,
            NoSubEntrega: val_NoSubEntrega,
            TipoIDProv: val_TipoIDProv,
            NoIDProv: val_NoIDProv,
            CodMunEnt: val_CodMunEnt,
            FecMaxEnt: val_FecMaxEnt,
            CantTotAEntregar: val_CantTotAEntregar,
            DirPaciente: val_DirPaciente,
            CodSerTecAEntregar: val_CodSerTecAEntregar
        }
    })
        .done(function (data) {
            cargar_datos_con_tec();//Cadavez que se deireccione se debera volver a cargar la lista de numero de tecnologia
            $('#textarea').text(data);
        })
        .fail(function (data) {
            alert("error:" + data);
        });

}

function habilitar_ConTec() {
    $('#ConTec').removeAttr('disabled');
}


function habilitar_TipoTec() {
    $('#TipoTec').removeAttr('disabled');
}



function limpiar() {
    $(".limpiar").val('');

    val_tipo = $('#tipo').val();
    val_NoEntrega = $('#NoEntrega').val();
    val_NoIDProv = $('#NoIDProv').val();

    //Limpiar lista tipo(regimen)
    $("#tipo option[value=" + val_tipo + "]").attr("selected", false);
    $("#tipo option[value='']").attr("selected", true);

    //Limpiar lista NoEntrega
    $("#NoEntrega option[value=" + val_NoEntrega + "]").attr("selected", false);
    $("#NoEntrega option[value='']").attr("selected", true);

    //Limpiar lista NoIDProv
    $("#NoIDProv option[value=" + val_NoIDProv + "]").attr("selected", false);
    $("#NoIDProv option[value='']").attr("selected", true);

}