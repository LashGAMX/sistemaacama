$(document).ready(function(){
    getClientes();

    $("#intermediarioSelectCrear").select2();
    $("#intermediarioSelectEditar").select2();

    $("#crearClienteGen").click(function(){
        setClientesGen();
        setTimeout(() => {getClientes(); alert('Cliente creado')}, 100);
    });

    $("#editarClienteGen").click(function(){
        upClientesGen();
        setTimeout(() => {getClientes(); alert('Cliente editado')}, 100);
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

function getClientes(){
    $.ajax({
        type: 'POST',
        data: { 
            "_token": $("meta[name='csrf-token']").attr("content")
        },
        dataType: 'json',
        url: base_url + '/admin/clientes/getClientesGen',


        success: function(response){
            const listaClientesGen = response.clienteGen;
            let template = `
                        <table class="display" id="tablaClientesGen" data-order='[[ 0, "desc" ]]'>
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>Cliente</th>
                                    <th>Id inter</th>
                                    <th>Intermediario</th>
                                    <th>Creación</th>
                                    <th>Creó</th>
                                    <th>Modificación</th>
                                    <th>Modificó</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            listaClientesGen.forEach(element => {
                if(element.deleted_at == null){
                    template += `
                                <tr>
                                    <td>${element.Id_cliente}</td>
                                    <td>${element.Empresa}</td>
                                    <td>${element.Id_intermediario}</td>
                                    <td>${element.Nombres}${element.A_paterno}</td>
                                    <td>${element.created_at}</td>
                                    <td>${element.Id_user_c}</td>
                                    <td>${element.updated_at}</td>
                                    <td>${element.Id_user_m}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning boton-editar" activo="si" data-toggle="modal" data-target="#modalEditar"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>
                                        <button type="button" class="btn btn-primary boton-ver"><i class="voyager-external"></i><span hidden-sm hidden-xs>Ver</span></button>
                                    </td>
                                </tr>
                `;
                }
                else{
                    template += `
                                <tr>
                                    <td class="bg-danger">${element.Id_cliente}</td>
                                    <td class="bg-danger">${element.Empresa}</td>
                                    <td class="bg-danger">${element.Id_intermediario}</td>
                                    <td class="bg-danger">${element.Nombres}${element.A_paterno}</td>
                                    <td class="bg-danger">${element.created_at}</td>
                                    <td class="bg-danger">${element.Id_user_c}</td>
                                    <td class="bg-danger">${element.updated_at}</td>
                                    <td class="bg-danger">${element.Id_user_m}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning boton-editar" activo="no" data-toggle="modal" data-target="#modalEditar"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>
                                        <button type="button" class="btn btn-primary boton-ver"><i class="voyager-external"></i><span hidden-sm hidden-xs>Ver</span></button>
                                    </td>
                                </tr>
                `;
                }
            });
            template += `
                            </tbody>
                        </table>
            `;
            $("#clientesGen").html(template);
            $("#tablaClientesGen").DataTable();
        }
    });
}

function setClientesGen(){
    let checked = false;
    if($("#activoCheck").is(":checked")){ checked = true; } else { checked = false; }
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
        }
    });
}

function upClientesGen(){
    let checked = false;
    if($("#activoCheckEditar").is(":checked")){ checked = true; } else { checked = false; }
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
        }
    })
}