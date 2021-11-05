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

function createLote()
{
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/createLote",
        data: {
            tipo: $("#tipoFormula").val(),
            fecha: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);

        }
    });
}
function

function buscarLote()
{
    let tabla = document.getElementById('divTable');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/buscarLote",
        data: {
            tipo: $("#tipo").val(),
            fecha: $("#fecha").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Fecha creacion</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_lote+'</td>';
                tab += '<td>'+item.Tipo_formula+'</td>';
                tab += '<td>'+item.Fecha+'</td>';
                tab += '<td>'+item.created_at+'</td>';
                tab += '<td><button type="button" id="btnAsignar" onclick="setAsignar('+item.Id_lote+')"  class="btn btn-primary">Agregar</button></td>';
              tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}
function setAsignar(id)
{
    window.location = base_url + "/admin/laboratorio/asgnarMuestraLote/"+id;
}

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
function guardarTexto(idLote){    
    let lote = document.getElementById(idLote).value;
    let texto = quill.container.firstChild.innerHTML;    
    
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/lote/procedimiento",
        data: {
            texto: texto, 
            lote: lote
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log("REGISTRO EXITOSO");
            console.log(response);
        }
    });
}

function busquedaPlantilla(idLote){
    let lote = document.getElementById(idLote).value;     

    //Si está vacío
    if(lote.length == 0){
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaPlantilla",
            data: {
                lote: lote,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            async: false,
            success: function (response) {
                quill.setText(response.textoDefault);                
            }
        });
    }else if(lote > 0){                
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaPlantilla",
            data: {
                lote: lote,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            async: false,
            success: function (response) {                                            
                //Si encuentra texto almacenado en la BD entonces procede a mostrarlo en el editor de texto
                if(response.textoRecuperado.Texto !== null){                    
                    quill.setText(response.textoEncontrado);                    
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