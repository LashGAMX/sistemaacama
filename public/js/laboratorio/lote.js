var base_url = "https://dev.sistemaacama.com.mx";

//var quill;

//Opciones del editor de texto Quill
/*var options = {
    placeholder: 'Introduce procedimiento/validación',
    theme: 'snow'
};*/

$(document).ready(function () {

    $('#summernote').summernote({
        placeholder: '',
        tabsize: 2,
        height: 100,

      });

    table = $('#table').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

    //quill = new Quill('#editor', options);

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

function getDatalote()
{
    let tabla = document.getElementById('divTableFormulaGlobal');
    let tab = '';
    let summer = document.getElementById("divSummer");
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/getDatalote",
        data: {
            idLote:$("#idLoteHeader").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tableFormulasGlobales" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Fórmula</th>';
            tab += '          <th>Resultado</th> ';
            tab += '          <th>Núm. Decimales</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.constantes, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Constante+'</td>';
                tab += '<td>'+item.Valor+'</td>';
                tab += '<td>3</td>';
            
              tab += '</tr>';
            }); 
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            summer.innerHTML = '<div id="summernote">'+response.reporte.Texto+'</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
        
              });
        
        }
    });
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
    let texto = document.getElementById("summernote");    
    let summer = document.getElementById("divSummer");
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/lote/procedimiento",
        data: {
            texto: texto.textContent, 
            lote: $("#"+idLote).val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log("REGISTRO EXITOSO");
            console.log(response);
            summer.innerHTML = '<div id="summernote">'+response.texto.Texto+'</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
        
              });
        }
    });
}

//Función que guarda todos los input de la vista Lote > Modal > Equipo
$('#guardarTodo').click(function() {
    console.log("Valor de IDLote: " + $('#idLoteHeader').val());

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/lote/equipo/guardarDatosGenerales",
        data: {
            idLote: $('#idLoteHeader').val(),
            flama_loteId: $('#flama_loteId').val(),
            flama_fechaHoraDig: $('#flama_fechaHoraDig').val(),
            flama_longOnda: $('#flama_longOnda').val(),
            flama_flujoGas: $('#flama_flujoGas').val(),
            flama_equipoForm: $('#flama_equipoForm').val(),
            flama_numInventario: $('#flama_numInventario').val(),
            flama_numInvLamp: $('#flama_numInvLamp').val(),
            flama_slit: $('#flama_slit').val(),
            flama_corriente: $('#flama_corriente').val(),
            flama_energia: $('#flama_energia').val(),
            flama_concStd: $('#flama_concStd').val(),
            flama_gas: $('#flama_gas').val(),
            flama_aire: $('#flama_aire').val(),
            flama_oxidoN: $('#flama_oxidoN').val(),

            blanco_verifBlanco: $('#blanco_verifBlanco').val(),
            blanco_absTeoBlanco: $('#blanco_absTeoBlanco').val(),
            blanco_abs1: $('#blanco_abs1').val(),
            blanco_abs2: $('#blanco_abs2').val(),
            blanco_abs3: $('#blanco_abs3').val(),
            blanco_abs4: $('#blanco_abs4').val(),
            blanco_abs5: $('#blanco_abs5').val(),
            blanco_absProm: $('#blanco_absProm').val(),
            blanco_concBlanco: $('#blanco_concBlanco').val(),

            verif_stdCal: $('#verif_stdCal').val(),
            verif_absTeorica: $('#verif_absTeorica').val(),
            verif_concMgL: $('#verif_concMgL').val(),
            verif_Abs1: $('#verif_Abs1').val(),
            verif_Abs2: $('#verif_Abs2').val(),
            verif_Abs3: $('#verif_Abs3').val(),
            verif_Abs4: $('#verif_Abs4').val(),
            verif_Abs5: $('#verif_Abs5').val(),
            verif_AbsProm: $('#verif_AbsProm').val(),
            verif_masaCarac: $('#verif_masaCarac').val(),
            verif_conclusion: $('#verif_conclusion').val(),
            verif_conclusionObtenida: $('#verif_conclusionObtenida').val(),
            verif_rec: $('#verif_rec').val(),
            verif_cumple: $('#verif_cumple').val(),

            std_conc: $('#std_conc').val(),
            std_desvStd: $('#std_desvStd').val(),
            std_cumple: $('#std_cumple').val(),
            std_abs1: $('#std_abs1').val(),
            std_abs2: $('#std_abs2').val(),
            std_abs3: $('#std_abs3').val(),
            std_abs4: $('#std_abs4').val(),
            std_abs5: $('#std_abs5').val(),

            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            //swal("Registro!", "Datos guardados correctamente!", "success");
        }
    });
});