$(document).ready(function () {
    let tablaBitacora;

    // Inicializar la tabla DataTable
    function TablaBitacora() {
        tablaBitacora = $("#DataBitacora").DataTable({
            destroy: true, // Permite reinicializar la tabla
            data: [], // Inicialmente sin datos
            columns: [
                { data: "Folio" },
                { data: "Cliente" },
                { data: "Hora_recepcion" },
                { data: "Hora_entrada" },
                { data: "Id_user_c", title: "QUIEN dio Entrada" },
                { data: "Muestra" },
                { data: "Fecha_muestreo" },
                { data: "Id_direccion", title: "Id_direcion a" },
                { data: "Atencion" },
                { data: "Norma" },
                { 
                    data: "parametro", 
                    render: function(data, type, row) {
                        return data; // Renderiza el contenido HTML directamente
                    } 
                },
            ],
        });
    }

    // Función para cargar los datos
    function Bitacora() {
        let Fini = $("#Finicio").val();
        let Ffin = $("#Fin").val();
    
        $.ajax({
            type: "POST",
            url: base_url + "/admin/alimentos/getbitacoras",
            data: {
                Fini: Fini,
                Ffin: Ffin,
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            success: function (response) {
                console.log("Respuesta del servidor:", response);
    
                if (response.success) {
                    let datosProcesados = [];
    
                    response.Proceso.forEach(function (item, index) {
                        let solicitud = response.Solicitud[index] || {};
                        let muestra = response.Muestra[index] || {};
    
                        let parametrosFiltrados = response.Parametro.filter(param => param.Id_proceso === item.Id_proceso);
                        let parametrosTexto = parametrosFiltrados
                            .map(param => param.par ? param.par.Parametro : "N/A")
                            .join(", ");
    
                        let datosItem = {
                            Folio: item.Folio || "N/A",
                            Cliente: item.Cliente || "N/A",
                            Hora_recepcion: item.Hora_recepcion || "N/A",
                            Hora_entrada: item.Hora_entrada || "N/A",
                            Id_user_c: item.user ? item.user.name : "N/A",
                            Muestra: muestra.Muestra || "N/A",
                            Fecha_muestreo: solicitud.Fecha_muestreo || "N/A",
                            Id_direccion: solicitud.dir ? solicitud.dir.Direccion : "N/A",
                            Atencion: solicitud.Atencion || "N/A",
                            Norma: solicitud.Norma || "N/A",
                            parametro: `<textarea rows="2" cols="30" readonly>${parametrosTexto || "N/A"}</textarea>`,
                        };
    
                        datosProcesados.push(datosItem);
                    });
    
                    tablaBitacora.clear().rows.add(datosProcesados).draw();
                } else {
                    alert("No se encontraron registros. Verifica el rango de fechas.");
                    tablaBitacora.clear().draw();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
                alert("Error del servidor al enviar datos. !No seleccionaste  las fechas del rango a buscar!");
            },
        });
    }
    

    $("#btnbuscarbitacora").click(function () {
        Bitacora();
    });

    // Llamar a la función para inicializar la tabla al cargar la página
    TablaBitacora();
});
