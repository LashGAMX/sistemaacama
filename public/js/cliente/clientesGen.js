$(document).ready(function(){
    getclientes();

    $("#intermediarioSelectCrear").select2();
    $("#intermediarioSelectEditar").select2();

    $("#crearClienteGen").click(function(){
        setClientesGen();
    });
    
    $(document).ready(function(){
        $("#editarClienteGen").click(function(){
            upClientesGen();
            setTimeout(() => {getClientes(); alert('Cliente editado')}, 100);
        });
    });
     $(document).on('click', '.boton-editar', function(){
        idSeleccionado = $(this)[0].parentNode.parentNode.children[0].innerHTML;
        let nombreSeleccionado = $(this)[0].parentNode.parentNode.children[1].innerHTML;
        let intermediarioSeleccionado = $(this)[0].parentNode.parentNode.children[2].innerHTML;
        let activo = $(this)[0].getAttribute('activo');
        if(activo == 'si'){
            $("#activoCheckEditar").attr('checked', true);
        }
        else{
            $("#activoCheckEditar").attr('checked', false);
        }
        $("#nombreClienteEditar").val(nombreSeleccionado);
        $("#intermediarioSelectEditar").val(intermediarioSeleccionado).change();
    });
   
    $(document).on('click', '.boton-ver', function(){
        idSeleccionado = $(this)[0].parentNode.parentNode.children[0].innerHTML;
        window.location.href = base_url + '/admin/clientes/clientesGenDetalle/' + idSeleccionado;
    });
});

var idSeleccionado;

function getclientes() {
    let color = "";
    let tabla = document.getElementById('clientesGen');
    
    if (!tabla) {
        console.error('No ESTA EL ELEMENTO CARNAL.');
        return;
    }

    let tab = "";
    $.ajax({
        type: 'POST',
        url: base_url + '/admin/clientes/getClientesGen',
        data: {},
        dataType: "json",
        async: false,
        success: function(response) {
            const listaClientesGen = response.clienteGen;
            tab += '<table id="tablaClientesGen" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Id</th>';
            tab += '          <th>Cliente</th>';
            tab += '          <th>Id inter</th>';
            tab += '          <th>Intermediario</th> ';
            tab += '          <th>Creación</th> ';
            tab += '          <th>Creó</th> ';
            tab += '          <th>Modificación</th> ';
            tab += '          <th>Modificó</th> ';
            tab += '          <th>Acciones</th>';
            tab += '      </tr> ';
            tab += '    </thead> ';
            tab += '    <tbody> ';
            
            listaClientesGen.forEach(element => {
                if (element.deleted_at == null) {
                    color = '';
                } else {
                    color = 'danger';
                }
                tab += '<tr>';
                tab += '<td class="bg-' + color + '">' + element.Id_cliente + '</td>';
                tab += '<td class="bg-' + color + '">' + element.Empresa + '</td>';
                tab += '<td class="bg-' + color + '">' + element.Id_intermediario + '</td>';
                tab += '<td class="bg-' + color + '">' + element.Nombres + '</td>';
                tab += '<td class="bg-' + color + '">' + element.created_at + '</td>';
                tab += '<td class="bg-' + color + '">' + element.Id_user_c + '</td>';
                tab += '<td class="bg-' + color + '">' + element.updated_at + '</td>';
                tab += '<td class="bg-' + color + '">' + element.Id_user_m + '</td>';
                tab += '<td>';
                tab += '<button type="button" class="btn btn-warning boton-editar" activo="si" data-toggle="modal" data-target="#modalEditar"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>';
                tab += '<button type="button" class="btn btn-primary boton-ver"><i class="voyager-external"></i><span hidden-sm hidden-xs>Ver</span></button>';
                tab += '</td>';
                tab += '</tr>';
            }); 
            tab += '    </tbody> ';
            tab += '</table>';
            
            tabla.innerHTML = tab;
            
            $('#tablaClientesGen').DataTable({
                "language": {
                    "ordering": false,
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": "500px",
                "scrollCollapse": true,
                "paging": true,
                "ordering": false, 
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar datos:', textStatus, errorThrown);
        }
    });
}


function setClientesGen(){
    let checked = $("#activoCheck").is(":checked");
    $.ajax({
        type: 'POST',
        data: { 
            nombres: $("#nombreClienteCrear").val(),
            idIntermediario: $("#intermediarioSelectCrear").val(),
            activoCheck: checked,
            "_token": $("meta[name='csrf-token']").attr("content")
         },
        dataType: 'json',
        url: base_url + '/admin/clientes/setClientesGen',
        success: function(response){
            console.log(response);
            alert('Cliente Creado exitosamente');
        },
        error: function(xhr, status, error){
            console.error(xhr.responseText);
            alert('Error al crear el cliente. Por favor, intenta de nuevo.');
        }
    });
}

function upClientesGen(){
    let checked = false;
    if($("#activoCheckEditar").is(":checked")) { 
        checked = true; 
    } else { 
        checked = false; 
    }
    $.ajax({
        type: 'POST',
        data: {
            idCliente: idSeleccionado,
            nombres: $("#nombreClienteEditar").val(),
            idIntermediario: $("#intermediarioSelectEditar").val(),
           
            activoCheckEditar: checked,
            "_token": $("meta[name='csrf-token']").attr("content")
        },
        dataType: 'json',
        url: base_url + '/admin/clientes/upClientesGen',
        success: function(response){
            console.log(response);
            if (response.success) {
                alert('No se pudo editar el cliente');
            } else {
                alert('Cliente editado correctamente');
       
                $('#tablaClientesGen tbody tr').each(function() {
                    var idCliente = $(this).find('td:eq(0)').text();
                    if (idCliente == idSeleccionado) {
                        $(this).find('td:eq(1)').text($("#nombreClienteEditar").val());
                        $(this).find('td:eq(2)').text($("#intermediarioSelectEditar").val());
                        $(this).find('td:eq(3)').text($("#intermediarioSelectEditar option:selected").text());
                        if (checked) {
                            $(this).removeClass('danger');
                        } else {
                            $(this).addClass('bg-danger');
                        }
                        return false; 
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Error en la solicitud AJAX');
        }
    });
}


    



