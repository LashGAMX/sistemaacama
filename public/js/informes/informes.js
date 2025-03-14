 var idSol = 0;
var idPunto = 0;
$(document).ready(function () {
    let folioSeleccionado = '';

    let table = $('#tableServicios').DataTable({        
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        
    });     

    $('#tableServicios tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            // console.log(this.children[1].innerHTML);
            folioSeleccionado = '';
        }
        else { 
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            folioSeleccionado = this.children[1].innerHTML;
            // console.log(this.children[1].innerHTML);
        }
    } );
    $('#tableServicios tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idSol = dato;
      });

    $('#btnImprimir').on('click', function(){
        switch ($("#tipoReporte").val()) {
            case "1":
            case "2":
                window.open(base_url+"/admin/informes/exportPdfInforme/"+idSol+"/"+$("#puntoMuestreo").val()+"/"+$("#tipoReporte").val());       
                break;
            case "3":
                window.open(base_url+"/admin/informes/exportPdfInformeCampo/"+idSol+"/"+$("#puntoMuestreo").val()) 
                break;
            case "4":
                window.open(base_url+"/admin/informes/cadena/pdf/"+$("#puntoMuestreo").val()) 
                break;
            case "5":
                window.open(base_url+"/admin/informes/exportPdfInformeVidrio/"+idSol+"/"+$("#puntoMuestreo").val()) 
                break;
            case "6":
                window.open(base_url+"/admin/informes/exportPdfInformeAdd/"+idSol+"/"+$("#puntoMuestreo").val()) 
                break;
            case "7":
                window.open(base_url+"/admin/informes/exportHojaCampoAdd/"+$("#puntoMuestreo").val()) 
                break; 
            default:
                break;
        }
    });

    $('#btnNota').on('click', function(){
        setNota4(idSol)
    });
    $('#firmaAut').on('click', function(){
        setFirmaAut(idSol)
    });

    $(document).on('change', '#puntoMuestreo', function(){
        setTimeout(() => { 
            // console.log(folioSeleccionado);
            // console.log($(this).val());
            setDatosTablaParametro(folioSeleccionado, $(this).val());
        }, 200);
    });

}); 
function setFirmaAut(id){
    if (confirm("Estas seguro de firmas estos informes?")) {
        $.ajax({
            url: base_url + '/admin/informes/setFirmaAut',
            type: 'POST', //método de envio
            data: {
                id: id,
                firma: $("#firmaAut").prop("checked"),
                _token: $('input[name="_token"]').val(),
            },
            dataType: 'json', 
            async: false, 
            success: function (response) {
                console.log(response)
                alert(response.msg)
            }
        });  
    }
}
 
function setNota4(id)
{
    if (id != 0) {
        console.log("Entro al if")
        $.ajax({
            url: base_url + '/admin/informes/setNota4',
            type: 'POST', //método de envio
            data: {
                id: id,
                nota: $("#nota").prop("checked"),
                _token: $('input[name="_token"]').val(),
            },
            dataType: 'json', 
            async: false, 
            success: function (response) {
                console.log(response)
             alert("Nota modificada")
            }
        });  
       } else{
        alert("Tienes que seleccionar un informe antes de seleccionar la nota 4")
       }
}


function getPuntoMuestro(id) 
{
    let tabla = document.getElementById('selPuntos');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/informes/getPuntoMuestro',
        type: 'POST', //método de envio
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab = '';
          tab += '<select class="form-control" id="puntoMuestreo">'; 
          $.each(response.model, function (key, item) {
            tab += '  <option value="'+item.Id_solicitud+'">'+item.Punto+'</option>';
         }); 
          tab += '</select>';
          tabla.innerHTML = tab;
          $("#puntoMuestreo").trigger('change');
        }
    });  
}

function getSolParametro()
{
    let tabla = document.getElementById('divServicios');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/informes/getSolParametro',
        type: 'POST', //método de envio
        data: {
            id: idSol,
            idPunto:$("#puntoMuestreo").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab += '<table id="tablaParametro" class="table" style="width: 100%; font-size: 10px">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '          <th>Norma</th>';
          tab += '          <th>Parametro</th>';
          tab += '          <th>Unidad</th>';
          tab += '          <th>Resultado</th>';
        //   tab += '          <th>Concentracion</th>';
        //   tab += '          <th>Diagnostico</th>';
          tab += '          <th>Liberado</th>';
          tab += '          <th>#</th>';
          tab += '          <th># Muestra</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
              tab += '<td>'+item.Norma+'</td>';
              tab += '<td>'+item.Parametro+'</td>';
              tab += '<td>'+item.Unidad+'</td>';
              tab += '<td>'+item.Resultado2+'</td>';
            //   tab += '<td></td>';
            //   tab += '<td></td>';
            if (item.Resultado != "NULL") {
                tab += '<td>Sin Liberar</td>';
            } else {
                tab += '<td>Liberado</td>';
            }
              
            tab += '<td>'+item.Num_muestra+'</td>';
            tab += '<td>'+item.Codigo+'</td>';
              tab += '</tr>';
          }); 
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;

          
    let table = $('#tablaParametro').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

        }
    });  
}

function setDatosTablaParametro(folio, puntoMuestreo){
    $.ajax({
        url: base_url + '/admin/informes/getInformacionPuntosMuestreo',
        type: 'POST',
        data: {
            folio: folio,
            puntoMuestreo: puntoMuestreo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        success: function(response){
            const listaParametros = response.model;
            let template = ``;
            console.log(listaParametros);

            listaParametros.forEach(element => {
                let limiteExcedido = verificarExcedido(element.Resultado2, element.Limite_cuantificacion);

                template += `
                    <tr>
                        <td></td>
                        <td>${element.Parametro}</td>
                        <td>${element.Unidad}</td>
                        <td>${element.Resultado2}</td>
                        ${limiteExcedido == true ? `<td style="background-color: red; color: white;">${element.Limite_cuantificacion}</td>` : `<td>${element.Limite_cuantificacion}</td>`}
                    </tr>
                `;
            });

            $("#datosTablaParametro").html(template);
        }
    });
}

function verificarExcedido(resultado, limite){
    if(limite == 'N/A' || !limite || !resultado){
        return false;
    }
    let resultadoNumerico = parseFloat(resultado);
    if(!limite.includes("-")){
        if(resultadoNumerico > parseFloat(limite)){
            console.log(`resultado: ${resultadoNumerico} es mayor a ${limite}`);
            return true;
        }
        else{
            console.log(`resultado: ${resultadoNumerico} es menor a ${limite}`);
            return false;
        }
    }
    else{
        let rango = limite.split('-');
        if(resultadoNumerico < parseFloat(rango[0]) || resultadoNumerico > parseFloat(rango[1])){
            console.log(`resultado: ${resultadoNumerico} es menor a ${rango[0]} o mayor a ${rango[1]}`);
            return true;
        }
        else{
            console.log(`resultado: ${resultadoNumerico} esta dentro del limite ${limite}`);
            return false;
        }
    }
}