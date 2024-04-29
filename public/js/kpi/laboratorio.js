$(document).ready(function () {
    $('#kpiTable').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        paging: false,
        scrollCollapse: true,
        scrollY: '500px'
    });
});