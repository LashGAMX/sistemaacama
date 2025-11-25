let idCot = 0;  //Variable Global 
//carga de datos en la tabla de solicitudes
$(document).ready(function () {
    let table = $('#tablaSolicitud').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 1000,
        dom: 'rtip',
        ajax: {
            url: base_url + "/admin/cotizacion/solicitud/GetSolicitudes",
            type: 'GET',
            data: function (d) {
                d.canceladas = $('#mostrarCanceladas').is(':checked') ? '1' : '0';
            }
        },
        columns: [
            { data: 'Id_cotizacion' },
            { data: 'cotizacion_estado.Estado', defaultContent: 'N/A' },
            { data: 'Folio_servicio', defaultContent: 'N/A' },
            { data: 'Folio', defaultContent: 'N/A' },
            { data: 'Fecha_muestreo', defaultContent: 'N/A', },
            { data: 'Nombre', defaultContent: 'N/A' },
            { data: 'clavenorma.Clave_norma', defaultContent: 'N/A' },
            { data: 'descarga.Descarga', defaultContent: 'N/A' },
            { data: 'creador.name', defaultContent: 'N/A' },
            { data: 'created_at', defaultContent: 'N/A', render: formFecha },
            { data: 'actualizado.name', defaultContent: 'N/A' },
            { data: 'updated_at', defaultContent: 'N/A', render: formFecha },
        ],
        order: [[0, 'desc']],
        rowCallback: function (row, data) 
        { 
        if (data.Cancelado == 1) {
                $(row).css({
                    'background-color': '#f8d7da',
                    'color': '#721c24'
                });
            }
        },
        initComplete: function () {
            const api = this.api();
            api.columns().every(function () {
                const column = this;
                const input = $('<input type="text" placeholder="Buscar..." style="width:100%;" />')
                    .appendTo($(column.header()).empty())
                    .on('input', debounce(function () {
                        column.search(this.value).draw();
                    }, 400)); 
            });
        },
        language: 
        {
            processing: "CARGANDO ⏰🔴🟡🟢...",
            zeroRecords: "No se encontraron registros",
            info: "Página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            lengthMenu: "Mostrar _MENU_ registros por página",
        }
    });

    $('#tablaSolicitud tbody').on('click', 'tr', function () {
        let data = table.row(this).data();
        if (data) {
            idCot = data.Id_cotizacion;
            $('#tablaSolicitud tbody tr').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    $('#mostrarCanceladas').on('change', function () {
        table.ajax.reload();
    });

    // limita la frecuencia con la que se ejecuta una función
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
});




// Maneja atajos de teclado
$(document).on("keydown", function (e) {     
    // Ctrl + E → Editar
    if (e.ctrlKey && e.key.toLowerCase() === "e") {
        e.preventDefault();

        if (idCot > 0) {
        
                                  window.location.href = base_url + "/admin/cotizacion/solicitud/updateOrden/" + idCot;

        } else 
        {
            alert("Primero selecciona una orden");
        }
    }

    // Ctrl + P → Exportar PDF
    if (e.ctrlKey && e.key.toLowerCase() === "p") {
        e.preventDefault();

        if (idCot > 0) {
         
                      window.location.href = base_url + "/admin/cotizacion/solicitud/exportPdfOrden/" + idCot;

        } else {
            alert("Primero selecciona una orden");
        }
    }
});

// boton para editar solicitudes (No aplica con cotizacion)
$("#btnEdit").click(function () {
     //console.log("EJE",idCot);
        if (idCot > 0) {
            // Redirige a la URL de actualización con el ID seleccionado
          window.location.href = base_url + "/admin/cotizacion/solicitud/updateOrden/" + idCot;

        } else {
            alert("Por favor, selecciona un registro antes de editar.");
        }
    });
 // Botón Crear solitudes Nuevas 
    
    $("#btnCreate").click(function () {
        //console.log("EJE",idCot);
        if (idCot > 0) {
            window.location =
                base_url + "/admin/cotizacion/solicitud/create/" + idCot;
        } else {
            window.location = base_url + "/admin/cotizacion/solicitud/create";
        }
    });
    // boton para imprimir solicitud
    $("#btnImprimir").click(function () {
         //console.log("EJE",idCot);
      
          window.location =
                base_url + "/admin/cotizacion/solicitud/exportPdfOrden/" + idCot;
        
        //window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    });
    // boton para imprimir duplicar
 
    $("#btnDuplicar").click(function () {
        window.location =
            base_url + "/admin/cotizacion/solicitud/duplicarSolicitud/" + idCot;
    });
    //boton para cancelar una solicitud
    $("#btnCancelar").click(function () {
        // alert(idCot);
        cancelarOrden();
    });

    function cancelarOrden() {
        let confirmObs = prompt(
            "Estas segur@ de cancelar esta orden de servicio?",
            "Por favor escriba el motivo de la cancelacion"
        );
        if (confirmObs == null || confirmObs == "") {
            alert("Es obligatorio escribir el motivo de la cancelacion");
        } else {
            $.ajax({
                url: base_url + "/admin/cotizacion/solicitud/cancelarOrden", //archivo que recibe la peticion
                type: "POST", //método de envio
                data: {
                    id: idCot,
                    obs: confirmObs,
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    alert(response.msg);
                },
            });
        }
    }

    function formFecha(data) 
      {
        if (!data) return 'N/A';
        const date = new Date(data);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
      }
