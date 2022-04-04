var idSol = 0;
$(document).ready(function () {

    let table = $('#tableServicios').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    

    $('#tableServicios tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    $('#tableServicios tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idSol = dato;
      });

    $('#btnImprimir').on('click', function(){

        console.log("Valor de tipoReporte: " + $("#tipoReporte").val());

        if($("#tipoReporte").val() == 1){
            window.open(base_url+"/admin/informes/exportPdfConComparacion/"+idSol);
        }else if($("#tipoReporte").val() == 2){
            window.open(base_url+"/admin/informes/exportPdfSinComparacion/"+idSol);
        }        
    });

    $("#btnSC").click(function()
    {
        window.open(base_url+"/admin/informes/informeMensualSinComparacion/"+idSol);
    });
    $("#btnCc").click(function()
    {
        window.open(base_url+"/admin/informes/informeMensualConComparacion/"+idSol);
    });
}); 