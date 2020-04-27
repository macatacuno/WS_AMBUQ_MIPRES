buscar_direc();
function buscar_direc() {

    //Cargar la informacion           
    val_NoPrescripcion = $('#NoPrescripcion').val();
    val_NoIDPaciente = $('#NoIDPaciente').val();
    tabla="  <tr>        <td>22383405</td>        <td>21661791</td>        <td>[BISOPROLOL FUMARATO] 2,5mg/1U</td>        <td>816001182</td>        <td>12/03/20 03:13:00 PM</td>        <td>Anulado</td>        <td>12/03/20 03:27:00 PM</td>        <td>20190104182009821673</td>        <td>CC</td>        <td>802449</td>        <td>MEDICAMENTO</td>        <td>1</td>        <td>1</td>        <td>ESS076</td>    </tr>    <tr>        <td>22396513</td>        <td>21674427</td>        <td>[BISOPROLOL FUMARATO] 2,5mg/1U</td>        <td>816001182</td>        <td>12/03/20 04:22:00 PM</td>        <td>Anulado</td>        <td>12/03/20 04:23:00 PM</td>        <td>20190104182009821673</td>        <td>CC</td>        <td>802449</td>        <td>MEDICAMENTO</td>        <td>1</td>        <td>1</td>        <td>ESS076</td>    </tr>    <tr>        <td>22394342</td>        <td>21672325</td>        <td>[BISOPROLOL FUMARATO] 2,5mg/1U</td>        <td>816001182</td>        <td>12/03/20 04:08:00 PM</td>        <td>Anulado</td>        <td>12/03/20 04:21:00 PM</td>        <td>20190104182009821673</td>        <td>CC</td>        <td>802449</td>        <td>MEDICAMENTO</td>        <td>1</td>        <td>1</td>        <td>ESS076</td>    </tr>    <tr>        <td>21472343</td>        <td>20785434</td>        <td>[BISOPROLOL FUMARATO] 2,5mg/1U</td>        <td>816001182</td>        <td>28/02/20 11:09:00 AM</td>        <td>Anulado</td>        <td>28/02/20 11:11:00 AM</td>        <td>20190104182009821673</td>        <td>CC</td>        <td>802449</td>        <td>MEDICAMENTO</td>        <td>1</td>        <td>1</td>        <td>ESS076</td>    </tr>";
    $("#contenido_tabla").html(tabla);
    /*
        $.ajax({
            url: './Reportes/Direccionamientos/GetDireccionamientoPorPaciente/consultar_direc.php',
            type: "POST",
            data: {
                NoPrescripcion: val_NoPrescripcion,
                NoIDPaciente: val_NoIDPaciente

            }
        })
            .done(function (data) {
                tabla = '';
                tabla = tabla + data;
                $("#div_TipoTec").html(tabla);
            })
            .fail(function (data) {
                alert("error:" + data);
            });
    */

}
