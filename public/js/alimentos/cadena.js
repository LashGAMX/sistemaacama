
var idSol;

$(document).ready(function () {
    var table = $('#tableCadena').DataTable({
        "processing": true,
        "lengthChange": false,
        "serverSide": true,      //activamos server-side para el procesamiento de grandes cantidades de datos
        "ajax": base_url + "/admin/alimentos/setDataCadena",
        "columns": [
            { "data": "Id_solicitud" },
            { "data": "Folio" },
            { "data": "Fecha_muestreo" },
            { "data": "Hora_recepcion" },
            { "data": "Cliente" },
            { "data": "Norma" },
            { "data": "Recibio" },
            { "data": "created_at" },
            { "data": "updated_at" }
        ],
        "ordering": false,
        "paging": true,
        "pageLength": 1000, 
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados"
        }
    });

    //filtros por columna (igual que antes)
    let auxtab = 0;
    $('#tableCadena thead th').each(function () {
        var title = $(this).text();
        let width;
        switch (auxtab) {
            case 0: width = "50px"; break;
            case 4: width = "300px"; break;
            case 6: width = "70px"; break;
            default: width = "100px"; break;
        }
        $(this).html('<input type="text" style="width:' + width + '" placeholder="' + title + '" />');
        auxtab++;
    });

    table.columns().every(function () {
        var that = this;
        $('input', this.header()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    // eventos fila
    $('#tableCadena tbody').on('dblclick', 'tr', function () {
        let dato = table.row(this).data().Id_solicitud;
        idSol = dato;
        window.location = base_url + "/admin/alimentos/detalleCadena/" + dato;
    });

    $('#tableCadena tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            let dato = table.row(this).data().Id_solicitud;
            idSol = dato;
        }
    });

    $('#btnCadena').click(function () {
        if (idSol) {
            window.location = base_url + "/admin/informes/exportPdfCustodiaInterna/" + idSol;
        } else {
            alert("Selecciona una fila primero");
        }
    });
});
