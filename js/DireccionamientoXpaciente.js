
function buscar_direc() {

    //Cargar la informacion           
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_NoIDPaciente = $('#NoIDPaciente').val();
    $("#btn_buscar_direc").attr("disabled", true);
    $.ajax({
        url: './consumo_ws/Reportes/Direccionamientos/GetDireccionamientoPorPaciente/consultar_direc.php',
        type: "POST",
        data: {
            NoPrescripcion: val_NoPrescripcion,
            NoIDPaciente: val_NoIDPaciente
        }
    })
        .done(function (tabla) {
            if (tabla != '') {
                $("#contenido_tabla").html(tabla);
                agregar_paginacion_datatable();
            } else {
                alert("Debe llenar alguno de los dos campos de busqueda");
            }

            $("#btn_buscar_direc").removeAttr('disabled');
        })
        .fail(function (tabla) {
            $("#btn_buscar_direc").removeAttr('disabled');
            alert("error:" + tabla);

        });
}

function agregar_paginacion_datatable() {

    $('.AllDataTables').DataTable({
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]]
    });
}