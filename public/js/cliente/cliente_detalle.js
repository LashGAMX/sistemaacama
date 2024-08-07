function tabalaclientes() {

    const idClienteElement = document.getElementById('idCliente');
    const idCliente = idClienteElement ? idClienteElement.textContent.trim() : '';

    if (!idCliente) {
        console.error("No se encontró el ID del cliente.");
        return;
    }

    let tabla = document.getElementById('TableCliente');

    if (tabla) {
        tabla.parentNode.removeChild(tabla);
    }

    tabla = document.createElement('table');
    tabla.id = 'TableCliente';
    tabla.className = 'table table-sm';
    document.getElementById('SucurcalCliente').appendChild(tabla);

    $.ajax({
        type: "GET",
        url: base_url + '/admin/clientes/datosClientes/' + idCliente,
        dataType: "json",
        async: false,
        success: function(response) {
            const tablasucursal = response.datos;
            let tipo='';
            let tab = "";
            tab += '<thead class="thead-dark">';
            tab +=    '<tr>';
            tab +=       '<th>ID</th>';
            tab +=       '<th>Nombre</th>';
            tab +=       '<th>Estado</th>';
            tab +=       '<th>Tipo de Cliente</th>';
            tab +=       '<th>Acción</th>';
            tab +=    '</tr>';
            tab += '</thead>';
            tab += '<tbody>';
            tablasucursal.forEach(element => {
                tab +=    '<tr>';
                tab +=       '<td>' + element.Id_cliente + '</td>';
                tab +=       '<td>' + element.Empresa + '</td>';
                tab +=       '<td>' + element.Estado + '</td>';
                if(element.Id_siralab==1)
                    {
                     tipo='Reporte'
                    }else if (element.Id_siralab==2){
                         tipo="Reporte Siralab"
                    }else{
                        tipo="Quien sabe Carnal"

                    }
                tab +=       '<td>' +tipo + '</td>';
                tab +=       '<td>';
                tab +=           '<button type="button" class="btn btn-warning boton-editar" activo="si" data-toggle="modal" data-target="#modalEditar"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>';
                tab +=           '<button type="button" class="btn btn-primary boton-ver"><i class="voyager-external"></i><span hidden-sm hidden-xs>Ver</span></button>';
                tab +=       '</td>';
                tab +=    '</tr>';
            });
            tab += '</tbody>';

            tabla.innerHTML = tab;

            $('#TableCliente').DataTable({
                "ordering": false, 
                "language": {
                    "lengthMenu": "# _MENU_ por página",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": "500px",
                "scrollCollapse": true,
                "paging": true,
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar datos:', textStatus, errorThrown);
        }
    });
}

// Llama a la función para cargar los datos
tabalaclientes();
