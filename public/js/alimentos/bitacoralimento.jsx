$(document).ready(function () {
    let tablaBitacora;

    function Bitacora() {
        let Fini = $("#Finicio").val();
        let Ffin = $("#Fin").val();

        $.ajax({
            type: "POST",
            url: base_url + "/admin/alimentos/getbitacorasAlimentos",
            data: {
                Fini: Fini,
                Ffin: Ffin,
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                const tableBody = $("#recepcionalimentos tbody");
                tableBody.empty();

                if (response.length === 0) {
                    tableBody.append(
                        '<tr><td colspan="12" class="text-center">No se encontraron registros</td></tr>'
                    );
                    return;
                }

                response.forEach(function (item) {
                    let row = `
            <tr>
                <td>${item.Folio}</td>
                <td>${item.Muestra}</td>
                <td>${item.Recibio}</td>
                <td>${item.Hora_recepcion}</td>
                <td>${item.Fecha}</td>
                <td>${item.AnalistaRecep}</td>}
                <td>${item.Resguardo}</td>
                <td>${item.AnalistaRes}</td>
                <td>${item.Fecha_inicio}</td>
                <td>${item.Fecha_resguardo}</td>
                <td>${item.Resguardo2}</td>
                <td>${item.Analista_desecho}</td>
                <td>${item.Fecha_desecho}</td>
                <td>${item.Lugar_desecho}</td>
                <td>${item.Fecha_muestreo ? item.Fecha_muestreo :'N/P'}</td>
                <td>${item.Horas ? item.Horas : 'N/P'}</td>
      
            </tr>
        `;
                    tableBody.append(row);
                });
            },

            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
                alert(
                    "Error del servidor al enviar datos. ¡No seleccionaste las fechas del rango a buscar!"
                );
            },
        });
    }
    function ImprimirBitacora() {
        let Fini = $("#Finicio").val();
        let Ffin = $("#Fin").val();

        if (!Fini || !Ffin) {
            alert("Seleccione el Rango");
            return;
        }

        window.open(
            `${base_url}/admin/alimentos/BitacoraPdf/${Fini}/${Ffin}`,
            "_blank"
        );
    }

    $("#btnbuscarbitacora").click(function () {
        Bitacora();
    });
    $("#btnimprimir").click(function () {
        ImprimirBitacora();
    });
});
