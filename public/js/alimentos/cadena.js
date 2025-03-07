var idSol;    
$(document).ready(function () {

    var table = $('#tableCadena').DataTable({        
        "ordering": false,
        "paging":false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
          

        },
        // lengthMenu: [1000],pagina a 1000 registro por pagina 
    });    

    // Agregar filtro por columnas
    let auxtab = 0 
    $('#tableCadena thead th').each(function () {
        var title = $(this).text();
        switch (auxtab) {
            case 0:
                $(this).html('<input type="text" style="width:50px" placeholder="' + title + '" />');
                break;
            case 4:
                $(this).html('<input type="text" style="width:300px" placeholder="' + title + '" />');
                break;
            case 6:
                $(this).html('<input type="text" style="width:70px" placeholder="' + title + '" />');
                break;
            default:
                $(this).html('<input type="text" style="width:100px" placeholder="' + title + '" />');
                break
        }
        auxtab++
    });

    table.columns().every(function () {
        var that = this;

        $('input', this.header()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that
                    .search(this.value)
                    .draw();
            }
        });
    });

    $('#tableCadena tbody').on('dblclick', 'tr', function () {
        let dato = $(this).find('td:first').html();
        idSol = dato;
        window.location = base_url+"/admin/alimentos/detalleCadena/"+dato;
    });

    $('#tableCadena tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            let dato = $(this).find('td:first').html();
            idSol = dato;
        }
    });

    $('#btnCadena').click(function () {
        window.location = base_url + "/admin/informes/exportPdfCustodiaInterna/" + idSol;
    });
});