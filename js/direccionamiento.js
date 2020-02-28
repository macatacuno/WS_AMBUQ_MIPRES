
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
                    lista = '<select disabled="disabled" onchange="cargar_datos_con_tec();" id="TipoTec" name="TipoTec" class="form-control">';
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
            }
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
            $('#NoIDProv').append('<option value="">Seleccionar opción</option>');
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


function cargar_lista_medicamentos() {
    $("#CodSerTecAEntregar_medi").load('./consumo_ws/envios/Direccionamiento/Lista_medi_cums.html');
}
/*
function limpiar_lista_medicamentos() {
    // val_NoPrescripcion = $('#NoPrescripcion').val();
    // val_TipoTec = $('#TipoTec').val();
    $("#div_list_medi").html('');
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
    //Activar las animaciones de las notificaciones
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });

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
    if (val_TipoTec == 'M') {
        val_CodSerTecAEntregar = $('#CodSerTecAEntregar_medi').val();
    } else {
        val_CodSerTecAEntregar = $('#CodSerTecAEntregar').val();
    }
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
        $("#div_NoIDProv").addClass("is-invalid");
    } else {
        $("#div_NoIDProv").removeClass("is-invalid");
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

    /*
        if (val_CodSerTecAEntregar == '') {
            count_valid_incumpl = count_valid_incumpl + 1;
            $("#CodSerTecAEntregar_medi").addClass("is-invalid");
        } else {
            $("#CodSerTecAEntregar_medi").removeClass("is-invalid");
        };
    */


    if (count_valid_incumpl > 0) {
        Toast.fire({
            type: 'error',
            title: 'hay ' + count_valid_incumpl + ' campo(s) que no se han llenado'
        });
    } else if (val_FecMaxEnt == '2011-11-11') {
        $("#FecMaxEnt").addClass("is-invalid");
        Toast.fire({
            type: 'error',
            title: 'Fecha Invalida: Puede que el ámbito de atención de la prescripción no tenga un tiempo de entrega definido'
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
                        title: 'error al direccionar'
                    });
                } else {
                    Toast.fire({
                        type: 'success',
                        title: 'Direccionamiento enviado correctamente'
                    });

                    cargar_datos_con_tec();//Cadavez que se deireccione se debera volver a cargar la lista de numero de tecnologia

                };
                $('#textarea').text(data);
            })
            .fail(function (data) {
                alert("error:" + data);
            });
    }


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
    $("#tipo option[value=" + val_tipo + "]").attr("selected", false);
    $("#tipo option[value='']").attr("selected", true);

    //Limpiar lista NoEntrega
    $("#NoEntrega option[value=" + val_NoEntrega + "]").attr("selected", false);
    $("#NoEntrega option[value='']").attr("selected", true);

    //Limpiar lista NoIDProv
    $("#NoIDProv option[value=" + val_NoIDProv + "]").attr("selected", false);
    $("#NoIDProv option[value='']").attr("selected", true);

    //Limpiar lista de proveedores
    $("#NoIDProv").empty();
    $('#NoIDProv').append('<option value="">Seleccionar opción</option>');

    //Limpiar lista de NoEntrega
    $("#NoEntrega").empty();
    $('#NoEntrega').append('<option value="">Seleccionar opción</option><option value="1">1</option><option value="2">2</option>    <option value="3">3</option>    <option value="4">4</option>    <option value="5">5</option>    <option value="6">6</option>    <option value="7">7</option>    <option value="8">8</option>    <option value="9">9</option>    <option value="10">10</option>    <option value="11">11</option><option value="12">12</option>');

    //Limpiar lista de medicamentos 
    /*  $("#CodSerTecAEntregar_medi option[value=" + $('#CodSerTecAEntregar_medi').val() + "]").attr("selected", false);
      $("#CodSerTecAEntregar_medi option[value='']").attr("selected", true);
  */
    $("#CodSerTecAEntregar_medi").empty();
    $('#CodSerTecAEntregar_medi').append('<option value="">Seleccionar opción</option>');
}




