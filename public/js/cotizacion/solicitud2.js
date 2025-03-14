$(document).ready(function () {
    
    let table = $("#tablaSolicitud").DataTable({
        ordering: false,
        pageLength: 1000,
        lengthMenu: [
            [100, 500, 1000],
            [100, 500, 1000],
        ],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "No hay registros disponibles",
            zeroRecords: "No se encontraron resultados",
            paginate: {
                next: "Siguiente",
                previous: "Anterior",
            },
        },
    });

    $("#tablaSolicitud thead th").each(function () {
        let title = $(this).text();
        $(this).html(
            title +
                '<br><input type="text" class="form-control form-control-sm column-filter" placeholder="Buscar">'
        );
    });

    $(".column-filter").on("keyup change", function () {
        let index = $(this).parent().index();
        table.column(index).search(this.value).draw();
        getSolicitudes(table);
    });

    var idCot = 0; // ID del registro seleccionado

    // Manejo de selección de filas
    $("#tablaSolicitud tbody").on("click", "tr", function () {
        // Asignamos el ID de la fila seleccionada
        let dato = $(this).find("td:first").html();
        idCot = dato;

        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            $("#btnEdit").prop("disabled", true); // Deshabilitar el botón de editar
            idCot = 0; // Resetear el valor de idCot
        } else {
            $(this).addClass("selected").siblings().removeClass("selected");
            $("#btnEdit").prop("disabled", false); // Habilitar el botón de editar
        }
    });

    // Evento para el botón de edición (btnEdit)
    $("#btnEdit").click(function () {
        if (idCot > 0) {
            // Redirige a la URL de actualización con el ID seleccionado
                       window.open(base_url + "/admin/cotizacion/solicitud/updateOrden/" + idCot, "_blank");

        } else {
            alert("Por favor, selecciona un registro antes de editar.");
        }
    });

    // Doble clic para editar
    $("#tablaSolicitud tbody").on("dblclick", "tr", function () {
        let dato = $(this).find("td:first").html();
        idCot = dato;
    
        if (idCot > 0) {
            window.open(base_url + "/admin/cotizacion/solicitud/updateOrden/" + idCot, "_blank");
        } else {
            alert("Primero selecciona una orden");
        }
    });
    
    // Botón Crear
    $("#btnCreate").click(function () {
        if (idCot > 0) {
            window.location =
                base_url + "/admin/cotizacion/solicitud/create/" + idCot;
        } else {
            window.location = base_url + "/admin/cotizacion/solicitud/create";
        }
    });

    $("#btnImprimir").click(function () {
        // alert("Imprimir PDF");
        window.open(
            base_url + "/admin/cotizacion/solicitud/exportPdfOrden/" + idCot
        );
        //window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    });

    
    $("#btnDuplicar").click(function () {
        window.location =
            base_url + "/admin/cotizacion/solicitud/duplicarSolicitud/" + idCot;
    });
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

});
