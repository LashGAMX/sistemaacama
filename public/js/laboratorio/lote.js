var base_url = "https://dev.sistemaacama.com.mx";

var quill;

$(document).ready(function () {
    table = $('#table').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

    quill = new Quill('#editor', {
        theme: 'snow'
    });
       
});

function guardarTexto(editor){
    let texto = document.getElementById(editor).textContent;

    console.log("Valor de: " + texto);
    
    //let contenidoTexto = quill.container.firstChild.innerHTML;
    //console.log(contenidoTexto);

    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/lote/procedimiento",
        data: {texto},
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);            
            console.log("Finaliza AJAX");
        }
    });
}

function busquedaPlantilla(idLote){
    let lote = document.getElementById(idLote).value;

    $.ajax({
        type: "GET",
        url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaPlantilla",
        data: {lote},
        dataType: "json",
        async: false,
        success: function (response) {
            console.log("Dentro de Ajax");
            console.log(response);
        }
    });
}