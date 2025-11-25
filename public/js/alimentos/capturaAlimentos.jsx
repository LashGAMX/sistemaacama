var area = "analisis";

$(document).ready(function () {
    $("#tblLote").DataTable({
        ordering: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });
 

    $(".select2").select2();
});
