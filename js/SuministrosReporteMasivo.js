
function buscar_reportes() {
    //Cargar la informacion           
    estado = $('#list_esto_repo').val();
    $("#list_esto_repo").attr("disabled", true);
    $.ajax({
        url: './consumo_ws/envios/SuministrosReporteMasivo/consultar_reportes.php',
        type: "POST",
        data: {
            estado: estado
        }
    })
        .done(function (tabla) {
            if (tabla != '') {
                $("#contenido_tabla").html(tabla);
                agregar_paginacion_datatable_Sumi();
            } else {
                
                $("#contenido_tabla").html('');
               // alert("Debe llenar alguno de los dos campos de busqueda");
            }

            $("#list_esto_repo").removeAttr('disabled');
        })
        .fail(function (tabla) {
            $("#list_esto_repo").removeAttr('disabled');
            alert("error:" + tabla);

        });
}

function agregar_paginacion_datatable_Sumi() {

    var table = $('#tablaSumi').DataTable({
        'columnDefs': [{
            'targets': 0,
            'checkboxes': {
                'selectRow': true
            }
        }],
        'select': {
            'style': 'multi'
        },
        'order': [
            [1, 'asc']
        ],
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
        "lengthMenu": [[-1, 10, 25, 5], ["Todos", 10, 25, 5]]
    });
}

function enviar_sumi() {
    var table = $('#tablaSumi').DataTable();
    var rows = table.column(0).checkboxes.selected();

    $.each(rows, function (index, rowId) {
        //alert(rowId);
    });
}
