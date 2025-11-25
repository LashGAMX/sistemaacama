$(document).ready(function () {
    var table = $('#tableCadena').DataTable({
        ordering: false,
        paging: true, // Habilita paginación para mejorar rendimiento
        pageLength: 1000, // Opcional: Ajusta la cantidad de registros por página
        language: {
            lengthMenu: "# _MENU_ por página",
            zeroRecords: "No hay datos encontrados",
            info: "Página _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });

    // Optimización de búsqueda con delay
    let searchTimeout;
    $('.column-filter').on('input', function () {
        clearTimeout(searchTimeout);
        let colIndex = $(this).data('column');
        let value = this.value;

        searchTimeout = setTimeout(function () {
            table.column(colIndex).search(value).draw();
        }, 300); // Espera 300ms antes de ejecutar la búsqueda
    });

    // Doble clic para redireccionar
    $('#tableCadena tbody').on('dblclick', 'tr', function () {
        let dato = $(this).find('td:first').text();
        window.location = base_url + "/admin/supervicion/cadena/detalleCadena/" + dato;
    });

    // Selección de fila
    $('#tableCadena tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected').siblings().removeClass('selected');
        idSol = $(this).find('td:first').text();
    });

    // Botón de exportación
    $('#btnCadena').click(function () {
        if (idSol) {
            window.location = base_url + "/admin/informes/exportPdfCustodiaInterna/" + idSol;
        } else {
            alert("Selecciona una fila antes de ver el PDF.");
        }
    });
});
