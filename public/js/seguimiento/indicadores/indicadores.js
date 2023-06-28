var table
$(document).ready(function () {
    table = $('#tableIndicadores').DataTable({
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY": 800,
        "scrollCollapse": true
    });
    $('#tableIndicadores tbody').on( 'dblclick', 'tr', function () {
        let dato = $(this).find('td:first').html()
        idSeg = dato
        $("#detalleIndicadores").modal("show")
    });
    $('#btnGraficos').click(function(){
        window.location = base_url + "/admin/indicadores/graficos";
    });
});