
//Activar las animaciones de las notificaciones
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
});
var val_NoPrescripcion_antes = "";
/*
$(document).ready(function() {  
    $('#CodSerTecAEntregar').hide();
    $('#desc_CodSerTecAEntregar').hide();
 });
*/

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
                    lista = '<select disabled="disabled" onchange="cargar_datos_con_tec();" id="TipoTec" name="TipoTec" class="form-control"><option value=""></option>';
                } else {
                    myVar = setTimeout(habilitar_TipoTec, 200);
                };
                lista = lista + '</select>';

                $("#div_TipoTec").html(lista);
                //deshabilitar todos los objetos del ormulario cada ves que se ejecute esta funcion


                $("#div_ConTec").html('<select onchange="cargar_datos_pres();" id="ConTec"  disabled="disabled"  name="ConTec" class="form-control"><option value=""></option><option value=""></option></select>');
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
                lista = '<select onchange="cargar_datos_pres();"  id="ConTec" disabled="disabled" name="ConTec" class="form-control"><option value=""></option>';
                limpiar();
            } else {
                myVar = setTimeout(habilitar_ConTec, 200);
            };
            lista = lista + '</select>';
            $("#div_ConTec").html(lista);
            cargar_datos_pres();
            cargar_datos_proveedores();

            /*
                        if (val_TipoTec == 'M') {
                            $('#CodSerTecAEntregar').hide();
                            $('#desc_CodSerTecAEntregar').hide();
                            cargar_lista_medicamentos();
                            $('#div_list_medi').show();
            
                        } else if (val_TipoTec == '') {
                            $('#CodSerTecAEntregar').hide();
                            $('#desc_CodSerTecAEntregar').hide();
                            $('#div_list_medi').show();
            
                        } else {
                            // limpiar_lista_medicamentos();
                            $('#div_list_medi').hide();
                            $('#CodSerTecAEntregar').show();
                            $('#desc_CodSerTecAEntregar').show();
                        }*/
        })
        .fail(function (data) {
            alert("error:" + data);
        });


}


function cargar_datos_proveedores() {
    // val_NoPrescripcion = $('#NoPrescripcion').val();
    // val_TipoTec = $('#TipoTec').val();

    $.ajax({
        url: './consumo_ws/envios/Direccionamiento/obtener_datos_proveedores.php',
        type: "POST",
        data: {
            /* NoPrescripcion: val_NoPrescripcion,
             TipoTec: val_TipoTec*/
        }
    })
        .done(function (data) {
            json = jQuery.parseJSON(data);
            sw = 0;
            lista = '';
            // $('#NoIDProv').append('<option value="">Seleccionar opción</option>');
            $('#NoIDProv').append('');
            for (var key in json) {
                sw = 1;
                //document.write("<br>" + key + " - " + json[key]);
                sub_json = json[key];
                for (var sub_key in sub_json) {
                    if (sub_key == 'NOIDPROV') {
                        lista = lista + "<option value='" + sub_json[sub_key] + "'>" + sub_json[sub_key] + '-';
                    } else if (sub_key == 'NOMBRE') {
                        lista = lista + sub_json[sub_key] + '</option>';
                    }

                }
            }
            if (sw == 0) {
                lista = '';
            } else {
                myVar = setTimeout(habilitar_NoIDProv, 200);
            };
            //lista = lista + '</select>';
            // $('#NoIDProv').removeAttr('disabled');
            $('#NoIDProv').append(lista);
            //  $("#div_proveedores").html(lista);
        })
        .fail(function (data) {
            alert("error:" + data);
        });


}

/*
function cargar_lista_medicamentos() {
    $("#CodSerTecAEntregar_medi").load('./consumo_ws/envios/Direccionamiento/Lista_medi_cums.html');
}
*/
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


            /*Recragar la informacion del formulario (Inicio)*/
            limpiar();
            // cargar_lista_medicamentos();
            cargar_datos_proveedores();


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

                    } else if (sub_key == 'DESC_CODAMBATE') {
                        $("#desc_codambate").val(sub_json[sub_key]);

                    } else if (sub_key == 'CODSERTECAENTREGAR') {
                        if (sub_json[sub_key] == 'NO EXISTE') {
                            $("#CodSerTecAEntregar").val('');
                            $("#CodSerTecAEntregar").removeAttr('disabled');
                            $("#CodSerTecAEntregar").attr("placeholder", "Ingresa código CUMS con guion #####-#");
                        } else {
                            $("#CodSerTecAEntregar").val(sub_json[sub_key]);
                            $("#CodSerTecAEntregar").attr("disabled", true);
                            $("#CodSerTecAEntregar").attr("placeholder", "");
                        }

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

/*
function cargar_datos_pres_sin_limpiar() {
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

                    } else if (sub_key == 'DESC_CODAMBATE') {
                        $("#desc_codambate").val(sub_json[sub_key]);

                    } else if (sub_key == 'CODSERTECAENTREGAR') {
                        if (val_TipoTec != 'M') {
                            if (sub_json[sub_key] == 'NO EXISTE') {
                                $("#CodSerTecAEntregar").val('');
                                $("#CodSerTecAEntregar").removeAttr('disabled');
                                $("#CodSerTecAEntregar").attr("placeholder", "Ingresa código CUMS con guion #####-#");
                            } else {
                                $("#CodSerTecAEntregar").val(sub_json[sub_key]);
                                $("#CodSerTecAEntregar").attr("disabled", true);
                                $("#CodSerTecAEntregar").attr("placeholder", "");
                            }
                        }
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
*/

function cargar_datos_direc() {
    quitar_color_de_campos();
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_TipoTec = $('#TipoTec').val();
    val_ConTec = $('#ConTec').val();
    val_NoEntrega = $('#NoEntrega').val();
    if (val_NoEntrega != '') {
        $.ajax({
            url: './consumo_ws/envios/Direccionamiento/obtener_datos_direc.php',
            type: "POST",
            data: {
                NoPrescripcion: val_NoPrescripcion,
                TipoTec: val_TipoTec,
                ConTec: val_ConTec,
                NoEntrega: val_NoEntrega
            }
        })
            .done(function (data) {
                if (data != '[]') {
                    json = jQuery.parseJSON(data);

                    for (var key in json) {
                        sw = 1;
                        //document.write("<br>" + key + " - " + json[key]);
                        sub_json = json[key];

                        for (var sub_key in sub_json) {

                            if (sub_key == 'FECMAXENT') {
                                $("#FecMaxEnt").val(sub_json[sub_key]);
                            } else if (sub_key == 'DIR_ID') {
                                $("#dir_id").text("Id: " + sub_json[sub_key]);

                            } else if (sub_key == 'DIR_IDDIRECCIONAMIENTO') {
                                $("#dir_id_direccionamiento").text("Id Direccionamiento: " + sub_json[sub_key]);
                                if (sub_json[sub_key] != 0) {
                                    //$("#btn_confirm_direc").attr("disabled", true);
                                    $("#btn_confirm_direc").hide();
                                    $("#btn_anular_direc").show();
                                } else {
                                    $("#btn_confirm_direc").show();
                                    $("#btn_anular_direc").hide();
                                    //$("#btn_confirm_direc").attr("disabled", false);
                                }
                            };
                        }
                    }
                } else {
                    /*Se consulta en la base de datos si la tecnologia tiene 
                    entregas direccioandas y si tiene algunas entonces se calcula la fecha maxima de entrega apartir
                    de la fecha maxima de entrega tomando la fecha mayor de la ultima entrega de del direccionamiento*/
                    $.ajax({
                        url: './consumo_ws/envios/Direccionamiento/obtener_FECMAXENT_direc.php',
                        type: "POST",
                        data: {
                            NoPrescripcion: val_NoPrescripcion,
                            TipoTec: val_TipoTec,
                            ConTec: val_ConTec
                        }
                    })
                        .done(function (data) {
                            $fecha_maxima_entrega = '';
                            $cantidad_entregas = 0;
                            if (data != '[]') {
                                json = jQuery.parseJSON(data);

                                for (var key in json) {
                                    sub_json = json[key];

                                    for (var sub_key in sub_json) {
                                        if (sub_key == 'FECMAXENT') {
                                            $fecha_maxima_entrega = sub_json[sub_key];
                                        } else if (sub_key == 'CANTIDAD_ENTREGAS') {
                                            $cantidad_entregas = sub_json[sub_key];
                                        };
                                    }
                                }
                                if ($cantidad_entregas > 0) {
                                    $("#FecMaxEnt").val($fecha_maxima_entrega);
                                } else {

                                }
                            };

                        })
                        .fail(function (data) {
                            alert("error:" + data);
                        });

                    $("#btn_confirm_direc").show();
                    $("#btn_anular_direc").hide();
                    $("#dir_id").text("Id: 0");
                    $("#dir_id_direccionamiento").text("Id Direccionamiento: 0");
                }

            })
            .fail(function (data) {
                alert("error:" + data);
            });
    } else {
        //cargar_datos_pres_sin_limpiar();
        //$("#FecMaxEnt").val('');
        $("#btn_confirm_direc").show();
        $("#btn_anular_direc").hide();
        $("#dir_id").text("Id: 0");
        $("#dir_id_direccionamiento").text("Id Direccionamiento: 0");
    }

}


function enviar_direc() {


    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_TipoTec = $('#TipoTec').val();
    val_ConTec = $('#ConTec').val();
    val_tipo = $('#tipo').val();
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
    val_CodSerTecAEntregar = '';
    val_CodSerTecAEntregar = $('#CodSerTecAEntregar').val();
    /*
        if (val_TipoTec == 'M') {
            val_CodSerTecAEntregar = $('#CodSerTecAEntregar_medi').val();
        } else {
            val_CodSerTecAEntregar = $('#CodSerTecAEntregar').val();
        }
    
    */
    count_valid_incumpl = validar_campos();


    if (count_valid_incumpl > 0) {

        Toast.fire({
            type: 'error',
            title: 'hay ' + count_valid_incumpl + ' campo(s) que no se han llenado'
        });
    } else if (val_FecMaxEnt == '2011-11-11') {
        $("#FecMaxEnt").addClass("is-invalid");


        Toast.fire({
            type: 'error',
            title: 'Error al direccionar'
        });
        alertify.alert('Error al direccionar', 'Fecha Invalida: Puede que el ámbito de atención de la prescripción no tenga un tiempo de entrega definido', function () {
            //alertify.message('');
        });
    } else {

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
                if (data.includes('Message')) {
                    Toast.fire({
                        type: 'error',
                        title: 'Error al direccionar'
                    });
                    alertify.alert('Error al Direccionar', data, function () {
                        //alertify.message('');
                    });

                } else {
                    Toast.fire({
                        type: 'success',
                        title: 'Direccionamiento enviado correctamente: '
                    });
                    // cargar_datos_pres();//Cadavez que se deireccione se debera volver a cargar la lista de numero de tecnologia
                    cargar_datos_direc();
                };

                //$('#textarea').text(data);
            })
            .fail(function (data) {
                alert("Error sin identificar:" + data);
            });
    }


}


function anular_direc() {
    dir_id = $("#dir_id").text();
    dir_id = dir_id.substr(4, dir_id.length);//Se extrae solamente el id de la cadena
    //alert(dir_id);
    dir_id_direccionamiento = $("#dir_id_direccionamiento").text();
    dir_id_direccionamiento = dir_id_direccionamiento.substr(21, dir_id_direccionamiento.length);//Se extrae solamente el id de la cadena
    //alert(dir_id_direccionamiento)
    val_tipo = $('#tipo').val();
    val_TipoTec = $('#TipoTec').val();
    $.ajax({
        url: './consumo_ws/envios/Direccionamiento/Anulacion_put.php',
        type: "POST",
        data: {
            dir_id_direccionamiento: dir_id_direccionamiento,
            dir_id: dir_id,
            tipo: val_tipo,
            TipoTec: val_TipoTec
        }
    })
        .done(function (data) {
            if (data.includes('Exitosa')) {

                Toast.fire({
                    type: 'success',
                    title: 'Direccionamiento anulado correctamente'
                });

                // cargar_datos_pres();//Cadavez que se deireccione se debera volver a cargar la lista de numero de tecnologia
                cargar_datos_direc();
            } else {

                Toast.fire({
                    type: 'error',
                    title: 'Error al anular'
                });
                alertify.alert('Error al Anular', data, function () {
                    //alertify.message('');
                });


            };

            //$('#textarea').text(data);
        })
        .fail(function (data) {
            alert("Error sin identificar:" + data);
        });
}

function habilitar_TipoTec() {
    $('#TipoTec').removeAttr('disabled');
}

function habilitar_ConTec() {
    $('#ConTec').removeAttr('disabled');
}

function habilitar_NoIDProv() {
    $('#NoIDProv').removeAttr('disabled');
}


function limpiar() {
    $(".limpiar").val('');

    val_tipo = $('#tipo').val();
    val_NoEntrega = $('#NoEntrega').val();
    val_NoIDProv = $('#NoIDProv').val();

    //Limpiar lista tipo(regimen)
    if (val_tipo != null && val_tipo != '') {
        $("#tipo option[value=" + val_tipo + "]").attr("selected", false);
        $("#tipo option[value='']").attr("selected", true);
    }

    //Limpiar lista NoEntrega
    if (val_NoEntrega != null && val_NoEntrega != '') {
        $("#NoEntrega option[value=" + val_NoEntrega + "]").attr("selected", false);
        $("#NoEntrega option[value='']").attr("selected", true);
    }

    //Limpiar lista NoIDProv
    if (val_NoIDProv != null && val_NoIDProv != '') {
        $("#NoIDProv option[value=" + val_NoIDProv + "]").attr("selected", false);
        $("#NoIDProv option[value='']").attr("selected", true);
    }

    //Limpiar lista de proveedores
    $("#NoIDProv").empty();
    $('#NoIDProv').append('<option value="">Seleccionar opción</option>');

    //Limpiar lista de NoEntrega
    $("#NoEntrega").empty();
    $('#NoEntrega').append('<option value="">Seleccionar opción</option><option value="1">1</option><option value="2">2</option>    <option value="3">3</option>    <option value="4">4</option>    <option value="5">5</option>    <option value="6">6</option>    <option value="7">7</option>    <option value="8">8</option>    <option value="9">9</option>    <option value="10">10</option>    <option value="11">11</option><option value="12">12</option>');

    /*
        $("#CodSerTecAEntregar_medi").empty();
        $('#CodSerTecAEntregar_medi').append('<option value="">Seleccionar opción</option>');
    */
    //Limpiar campo de medicamentos
    $("#CodSerTecAEntregar").attr("disabled", true);
    $("#CodSerTecAEntregar").attr("placeholder", "");
    //Limpiar codigos de direccionamiento
    $("#dir_id").text("Id: 0");
    $("#dir_id_direccionamiento").text("Id Direccionamiento: 0");

    //Mostar el boton de direccionamiento
    //$("#btn_confirm_direc").attr("disabled", false);
    $("#btn_confirm_direc").show();
    $("#btn_anular_direc").hide();
    /*Recragar la informacion del formulario (Fin)*/
    quitar_color_de_campos();
}



function quitar_color_de_campos() {
    $("#NoPrescripcion").removeClass("is-invalid");
    $("#TipoTec").removeClass("is-invalid");
    $("#ConTec").removeClass("is-invalid");
    $("#tipo").removeClass("is-invalid");
    $("#TipoIDPaciente").removeClass("is-invalid");
    $("#NoIDPaciente").removeClass("is-invalid");
    $("#NoEntrega").removeClass("is-invalid");
    $("#NoSubEntrega").removeClass("is-invalid");
    $("#TipoIDProv").removeClass("is-invalid");
    // $("#text_NoIDProv").css({ 'color': 'black' });
    $("#CodMunEnt").removeClass("is-invalid");
    $("#FecMaxEnt").removeClass("is-invalid");
    $("#CantTotAEntregar").removeClass("is-invalid");
    $("#DirPaciente").removeClass("is-invalid");
    $("#CodSerTecAEntregar").removeClass("is-invalid");
    //$(".is-invalid").removeClass("is-invalid");
    $("#text_NoIDProv").css({ 'color': 'black' });
    //$("#text_CodSerTecAEntregar").css({ 'color': 'black' });
}
function validar_campos() {
    count_valid_incumpl = 0;

    /*
      is-warning
      is-valid
      is-invalid
    */

    if (val_NoPrescripcion == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#NoPrescripcion").addClass("is-invalid");
    } else {
        $("#NoPrescripcion").removeClass("is-invalid");
    };


    if (val_TipoTec == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#TipoTec").addClass("is-invalid");
    } else {
        $("#TipoTec").removeClass("is-invalid");
    };


    if (val_ConTec == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#ConTec").addClass("is-invalid");
    } else {
        $("#ConTec").removeClass("is-invalid");
    };


    if (val_tipo == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#tipo").addClass("is-invalid");
    } else {
        $("#tipo").removeClass("is-invalid");
    };


    if (val_TipoIDPaciente == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#TipoIDPaciente").addClass("is-invalid");
    } else {
        $("#TipoIDPaciente").removeClass("is-invalid");
    };

    if (val_NoIDPaciente == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#NoIDPaciente").addClass("is-invalid");
    } else {
        $("#NoIDPaciente").removeClass("is-invalid");
    };

    if (val_NoEntrega == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#NoEntrega").addClass("is-invalid");
    } else {
        $("#NoEntrega").removeClass("is-invalid");
    };

    if (val_NoSubEntrega == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#NoSubEntrega").addClass("is-invalid");
    } else {
        $("#NoSubEntrega").removeClass("is-invalid");
    };

    if (val_TipoIDProv == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#TipoIDProv").addClass("is-invalid");
    } else {
        $("#TipoIDProv").removeClass("is-invalid");
    };

    if (val_NoIDProv == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#text_NoIDProv").css({ 'color': 'red' });
    } else {
        $("#text_NoIDProv").css({ 'color': 'black' });
    };

    if (val_CodMunEnt == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#CodMunEnt").addClass("is-invalid");
    } else {
        $("#CodMunEnt").removeClass("is-invalid");
    };

    if (val_FecMaxEnt == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#FecMaxEnt").addClass("is-invalid");
    } else {
        $("#FecMaxEnt").removeClass("is-invalid");
    };

    if (val_CantTotAEntregar == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#CantTotAEntregar").addClass("is-invalid");
    } else {
        $("#CantTotAEntregar").removeClass("is-invalid");
    };

    if (val_DirPaciente == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#DirPaciente").addClass("is-invalid");
    } else {
        $("#DirPaciente").removeClass("is-invalid");
    };


    if (val_CodSerTecAEntregar == '') {
        count_valid_incumpl = count_valid_incumpl + 1;
        $("#CodSerTecAEntregar").addClass("is-invalid");
    } else {
        $("#CodSerTecAEntregar").removeClass("is-invalid");
    };

    return count_valid_incumpl;
}

