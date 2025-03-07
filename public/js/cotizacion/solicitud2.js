$(document).ready(function() {
    let table = $('#tablaSolicitud').DataTable({
        "ordering": false,
        "language": {
            "search": "Buscar:",
            "lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "No hay registros disponibles",
            "zeroRecords": "No se encontraron resultados",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    // Agregar un campo de búsqueda en cada columna del encabezado
    $('#tablaSolicitud thead th').each(function() {
        let title = $(this).text();
        $(this).html(title + '<br><input type="text" class="form-control form-control-sm column-filter" placeholder="Buscar">');
    });

    // Detectar cambios en los filtros de las columnas
    $('.column-filter').on('keyup change', function() {
        let index = $(this).parent().index();
        table.column(index).search(this.value).draw();

         getSolicitudes(table);
    });
    let originalTbody = $('#tablaSolicitud tbody').html();

});

// Función getSolicitudes que recibe el objeto DataTable
function getSolicitudes(table) {
    let filters = [];
    $('#tablaSolicitud thead th').each(function(index) {
        let filterValue = $('.column-filter').eq(index).val();
        if (filterValue) {
            filters.push({
                column: $(this).text(),
                value: filterValue
            });
        }
    });

    // console.log("Filtros aplicados:", filters);

    $.ajax({
        url: base_url + "/admin/cotizacion/solicitud/getSolicitudes",
        type: "POST",
        data: {
            filters: filters,
            _token: $('input[name="_token"]').val(), // CSRF token
        },
        dataType: "json", // Indicamos que esperamos una respuesta JSON
        success: function(response) {
         
            // Mostra la respuesta del servidor en la consola
             console.log("Respuesta: ",response);
    
           
            // response.forEach(function(item) {
            //     console.log(item); 
            // });
        },
        error: function(xhr, status, error) {
            console.error("Error:", status, error);
        }
    });
    
    
}

// $('#resetFilters').click(function() {
//     $('.column-filter').val('');
//     table.search('').columns().search('').draw();
// });
