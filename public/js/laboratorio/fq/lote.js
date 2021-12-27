var base_url = "https://dev.sistemaacama.com.mx";
var area = "fq";

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
        url: base_url + "/admin/laboratorio/"+area+"/createLote",
        data: {
            tipo: $("#tipoFormula").val(),
            fecha: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            swal("Registro!", "Lote creado correctamente!", "success");
            $('#modalCrearLote').modal('hide')
        }
    });
}

function buscarLote()
{
    let tabla = document.getElementById('divTable');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/buscarLote",
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
    window.location = base_url + "/admin/laboratorio/"+area+"/asgnarMuestraLote/"+id;
}

//Adaptando para FQ
function getDatalote()
{
    let tabla = document.getElementById('divTableFormulaGlobal');
    let tab = '';
    let summer = document.getElementById("divSummer");
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getDatalote",
        data: {
            idLote:$("#idLoteHeader").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            
            console.log(response);  
            
            //console.log("Valor de idLote: " + response[5]);
            
            if(response.idLoteIf == 0 || response.idLoteIf < 0 || !response.idLoteIf){
                tab += '<table id="tableFormulasGlobales" class="table table-sm">';
                tab += '<thead>'
                tab +=      '<tr>'
                tab +=          '<th scope="col">Fórmula</th>'
                tab +=          '<th scope="col">Resultado</th>'
                tab +=          '<th scope="col">Núm.Decimales</th>'
                tab +=      '</tr>'
                tab += '</thead>'
                tab += '<tbody>'
                tab +=      '<tr>'
                //tab +=          '<td></td>'
                //tab +=          '<td></td>'
                //tab +=          '<td></td>'
                tab +=      '</tr>'
                tab += '</tbody>'
                tab += '</table>';
                tabla.innerHTML = tab;
            }else if(response.idLoteIf > 0){
                tab += '<table id="tableFormulasGlobales" class="table table-sm">';
                tab += '    <thead class="thead-dark">';
                tab += '        <tr>';
                tab += '          <th>Fórmula</th>';
                tab += '          <th>Resultado</th> ';
                tab += '          <th>Núm. Decimales</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                
                if(response.curvaConstantes !== null){
                    tab +=          '<tr>';
                    tab +=              '<td>B</td>';
                    tab +=              '<td>'+response.curvaConstantes.B+'</td>';
                    tab +=              '<td>3</td>';
                    tab +=          '</tr>';

                    tab +=          '<tr>';
                    tab +=              '<td>M</td>';
                    tab +=              '<td>'+response.curvaConstantes.M+'</td>';
                    tab +=              '<td>3</td>';
                    tab +=          '</tr>';

                    tab +=          '<tr>';
                    tab +=              '<td>R</td>';
                    tab +=              '<td>'+response.curvaConstantes.R+'</td>';
                    tab +=              '<td>3</td>';
                    tab +=          '</tr>';
                }
                
                /* $.each(response[0], function (key, item) {
                    tab += '<tr>';
                    tab +=      '<td>'+item.Constante+'</td>';
                    tab +=      '<td>'+item.Valor+'</td>';
                    tab +=      '<td>3</td>';                
                    tab += '</tr>';
                }); */ 

                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;
            }
            
            if((response.dataColi[0] !== null) && (response.dataColi[1] !== null) && (response.dataColi[2] !== null)){
                //Formatea la fecha a un formato admitido por el input datetime
                let fecha = response.dataColi[0].Sembrado;
                let fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');                                

                $("#sembrado_loteId").val(response.dataColi[0].Id_lote);
                $("#sembrado_sembrado").val(fechaFormateada);                
                $("#sembrado_fechaResiembra").val(response.dataColi[0].Fecha_resiembra);
                $("#sembrado_tuboN").val(response.dataColi[0].Tubo_n);
                $("#sembrado_bitacora").val(response.dataColi[0].Bitacora);

                //--------------------------
                fecha = response.dataColi[1].Preparacion;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaPresuntiva_preparacion').val(fechaFormateada);
                
                fecha = response.dataColi[1].Lectura;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaPresuntiva_lectura').val(fechaFormateada);

                //--------------------------
                $('#pruebaConfirmativa_medio').val(response.dataColi[2].Medio);

                fecha = response.dataColi[2].Preparacion;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaConfirmativa_preparacion').val(fechaFormateada);

                fecha = response.dataColi[2].Lectura;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaConfirmativa_lectura').val(fechaFormateada);

            }else{                
                $("#sembrado_loteId").val('');
                $("#sembrado_sembrado").val('');
                $("#sembrado_fechaResiembra").val('');
                $("#sembrado_tuboN").val('');
                $("#sembrado_bitacora").val('');                

                $('#pruebaPresuntiva_preparacion').val('');
                $('#pruebaPresuntiva_lectura').val('');                

                $('#pruebaConfirmativa_medio').val('');
                $('#pruebaConfirmativa_preparacion').val('');
                $('#pruebaConfirmativa_lectura').val('');                                
            }    
            
            //------------DQO------------
            if(response.dataDqo !== null){
                $("#ebullicion_loteId").val(response.dataDqo.Id_lote);
                
                /* let fecha = response.dataDqo.Inicio;
                let fechaIngresada = moment(fecha, 'DD-MM-YYYY');
                let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DD'); */
                $("#ebullicion_inicio").val(response.dataDqo.Inicio);
                
                /* fecha = response.dataDqo.Fin;
                fechaIngresada = moment(fecha, 'DD-MM-YYYY');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DD'); */
                $("#ebullicion_fin").val(response.dataDqo.Fin);

                $("#ebullicion_invlab").val(response.dataDqo.Invlab);
            }else{
                $("#ebullicion_loteId").val('');
                $("#ebullicion_inicio").val('');
                $("#ebullicion_fin").val('');
                $("#ebullicion_invlab").val('');
            }
            //-----------------------------------------

            console.log("actualizado");            

            if(response.reporte !== null){
                summer.innerHTML = '<div id="summernote">'+response.reporte.Texto+'</div>';
                $('#summernote').summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 100,
            
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: base_url + "/admin/laboratorio/"+area+"/getDataLote/plantillaPredeterminada",
                    data: {
                        idLote: 0,
                        _token: $('input[name="_token"]').val(),
                    },
                    dataType: "json",
                    async: false,
                    success: function (response) {
                        //console.log(response);                        
                        summer.innerHTML = '<div id="summernote">'+response.Texto+'</div>';
                        $('#summernote').summernote({
                            placeholder: '',
                            tabsize: 2,
                            height: 100,
                    
                        });
                    }
                });
            }
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
    let lote = document.getElementById(idLote).value;
    let texto = document.getElementById("summernote");
    let summer = document.getElementById("divSummer");

    console.log("Antes de ajax");
    
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/lote/procedimiento",
        data: {            
            texto: $("#summernote").summernote('code'), 
            lote: lote,
            idArea: 5
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log("REGISTRO EXITOSO");            
            //console.log(response);
            summer.innerHTML = '<div id="summernote">'+response.texto.Texto+'</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,            
            });
        }
    });
}

//Función que guarda todos los input de la vista Lote > Modal > [Grasas, Coliformes, DBO, DQO, Metales]
$('#guardarTodo').click(function() {
    //console.log("Valor de IDLote: " + $('#idLoteHeader').val());

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/lote/guardarDatos",
        data: {
            idLote: $('#idLoteHeader').val(),

            //-----------------Coliformes------------------
            sembrado_loteId: $('#sembrado_loteId').val(),
            sembrado_sembrado: $('#sembrado_sembrado').val(),
            sembrado_fechaResiembra: $('#sembrado_fechaResiembra').val(),
            sembrado_tuboN: $('#sembrado_tuboN').val(),
            sembrado_bitacora: $('#sembrado_bitacora').val(),

            pruebaPresuntiva_preparacion: $('#pruebaPresuntiva_preparacion').val(),
            pruebaPresuntiva_lectura: $('#pruebaPresuntiva_lectura').val(),           

            pruebaConfirmativa_medio: $('#pruebaConfirmativa_medio').val(),
            pruebaConfirmativa_preparacion: $('#pruebaConfirmativa_preparacion').val(),
            pruebaConfirmativa_lectura: $('#pruebaConfirmativa_lectura').val(),

            //------------Fin de coliformes---------------
            
            //--------------------DQO---------------------
            ebullicion_loteId: $("#ebullicion_loteId").val(),
            ebullicion_inicio: $("#ebullicion_inicio").val(),
            ebullicion_fin: $("#ebullicion_fin").val(),
            ebullicion_invlab: $("#ebullicion_invlab").val(),

            //-------------Fin de DQO---------------------

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