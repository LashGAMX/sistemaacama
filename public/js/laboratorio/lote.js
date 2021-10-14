var base_url = "https://dev.sistemaacama.com.mx";

var quill;

//Opciones del editor de texto Quill
var options = {
    placeholder: 'Introduce procedimiento/validación',
    theme: 'snow'
};

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

    quill = new Quill('#editor', options);
});

function isSelectedProcedimiento(procedimientoTab){
    let valorProcedimientoTab = 'https://dev.sistemaacama.com.mx/admin/laboratorio/lote#procedimiento';
    let pestañaProcedimiento = document.getElementById(procedimientoTab);
    let btnActualizar = document.getElementById('btnRefresh');
    let annex = '';
    let evento = "(onclick='busquedaPlantilla('idLoteHeader');')";

    if(pestañaProcedimiento == valorProcedimientoTab){        
        annex+= '<button type="button" class="btn btn-primary" evento.value><i class="fas fa-sync-alt"></i></button>';
    }else{        
        annex = '';
    }

    btnActualizar.innerHTML = annex;
}

//Método que guarda el texto ingresado en el editor de texto Quill en la BD
function guardarTexto(editor, idLote){
    let texto = document.getElementById(editor).textContent;
    let lote = document.getElementById(idLote).value;
    
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/lote/procedimiento",
        data: {texto, lote},
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
        }
    });
}

function busquedaPlantilla(idLote){
    let lote = document.getElementById(idLote).value;    

    if(lote.length == 0){
        $.ajax({
            type: "GET",
            url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaPlantilla",
            data: {lote},
            dataType: "json",
            async: false,
            success: function (response) {
                quill.setText(response.textoRecuperadoPredeterminado.Texto);
            }
        });
    }else if(lote > 0){
        $.ajax({
            type: "GET",
            url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaPlantilla",
            data: {lote},
            dataType: "json",
            async: false,
            success: function (response) {                            
                //Si encuentra texto almacenado en la BD entonces procede a mostrarlo en el editor de texto
                if(response.textoRecuperado.Texto !== null){
                    quill.setText(response.textoRecuperado.Texto);                
                }else{
                    //Si no encuentra texto almacenado en la BD entonces no muestra ningún texto en el editor de texto
                    quill.setText('');
                }
            }
        });
    }else if(lote <= 0){
        quill.setText('');
    }    
}

//Método que se activa al dar clic izq sobre el botón cerrar de la ventana modal
function limpiezaDatos(){    
    //Vacía el input del ID Lote de la ventana modal
    $('#idLoteHeader').val('');

    //Vacía el contenido del editor de texto Quill
    quill.setText('');
}