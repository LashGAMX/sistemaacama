var id = 0
$(document).ready(function()
{
    $('.select2').select2();

    getParametrosAli()
    
    $("#btnGuardar").click(function(){
        setParametroAli()
    });

    
 
}); 

function getDataParametroAli(){
    $.ajax({
        url: base_url + '/admin/analisisQ/getDataParametroAli', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id:id,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false,  
        success: function (response) {
          console.log(response)
          $('#parametro').val(response.model.Id_parametro).trigger('change');
          $('#matriz').val(response.model.Id_matriz_parametro);
          $('#unidad').val(response.model.Id_unidad);
          $('#limite').val(response.model.Limite).trigger('change');
          $('#Dias').val(response.model.Dias_analisis).trigger('change');
        }
    }); 
}

function getParametrosAli(){
    $.ajax({
        url: base_url + '/admin/analisisQ/getParametrosAli', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false,  
        success: function (response) {
          console.log(response);
          let modelHTML = "";
            response.model.forEach((item) => {
                modelHTML += `
                    <tr>
                        <td>${item.Id}</td>
                        <td>(${item.Id_parametro}) ${item.parametro.Parametro}</td>
                        <td>${item.matriz.Matriz}</td>
                        <td>${item.unidad.Unidad}</td>
                        <td>${item.Limite}</td>
                        <td>${item.Dias_analisis}</td>

                        <td>
                            <button class="btn-danger" type="submit" onclick="delParametroAli(${item.Id})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            });

           $("#tabParametros").html(`
                <div class="row">
                    <div class="col-md-12">
                       <table id="tableParametros" class="table">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Parametro</th>
                                <th>Matriz</th>
                                <th>Unidad</th>
                                <th>Limite</th>s
                                <th>Dias Analisis</th>
                                <th>Opc</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${modelHTML}
                            </tbody>
                        </table>
                    </div>
                </div>
            `);
            var tablP  = $("#tableParametros").DataTable({
                ordering: false,
                paging: false,
                language: {
                    lengthMenu: "# _MENU_ por pagina",
                    zeroRecords: "No hay datos encontrados",
                    info: "Pagina _PAGE_ de _PAGES_",
                    infoEmpty: "No hay datos encontrados",
                },
            });
            $("#tableParametros tbody").on("click", "tr", function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    tablP.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                    id = $(this).children(":first").html();
                    getDataParametroAli()
                    
                }
            });
        }
    }); 
}
function setParametroAli(){
     $.ajax({
        url: base_url + '/admin/analisisQ/setLimiteAli', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            parametro:$("#parametro").val(),
            matriz:$("#matriz").val(),
            unidad:$("#unidad").val(),
            limite:$("#limite").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          let modelHTML = "";
          alert("Matriz asignada correctamente")
            response.model.forEach((item) => {
                modelHTML += `
                    <tr>
                        <td>${item.Id}</td>
                        <td>(${item.Id_parametro}) ${item.parametro.Parametro}</td>
                        <td>${item.matriz.Matriz}</td>
                        <td>${item.unidad.Unidad}</td>
                        <td>${item.Limite}</td>
                        <td><button class="btn-danger" type="submit" onclick="delParametroAli(${item.Id})"><i class="fas fa-trash"></i></button></td>
                    </tr>
                `;
            });

           $("#tabParametros").html(`
                <div class="row">
                    <div class="col-md-12">
                       <table class="table">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Parametro</th>
                                <th>Matriz</th>
                                <th>Unidad</th>
                                <th>Limite</th>
                                <th>Opc</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${modelHTML}
                            </tbody>
                        </table>
                    </div>
                </div>
            `);
        }
    }); 
}
function delParametroAli(id){
     $.ajax({
        url: base_url + '/admin/analisisQ/delParametroAli', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id:id,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          alert(response.msg)
          getParametrosAli()
        }
    }); $
}
function updateParaAli(){
    $.ajax({
        url: base_url + '/admin/analisisQ/updateParaAli', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id:id,
            parametro:$("#parametro").val(),
            matriz:$("#matriz").val(),
            unidad:$("#unidad").val(),
            limite:$("#limite").val(),
             dias:$("#Dias").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          alert(response.msg)
          getParametrosAli()
        }
    }); 
}