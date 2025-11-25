$(document).ready(function () {
    let tablaBitacora;

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
             console.log("respuesta",response);
            $("#DataBitacora").wrap("<div class='scroll-container'></div>");
            $(".scroll-container").css({
                "overflow-x": "auto",
                "max-width": "100%",
            });
                const tableBody = $("#DataBitacora tbody");
                tableBody.empty(); // Limpia el contenido anterior

                if (response.length === 0) {
                    tableBody.append(
                        '<tr><td colspan="18" class="text-center">No se encontraron registros</td></tr>'
                    );
                    return;
                }

                response.forEach(function (item) {
                    let parametros = item.parametros.join(", ");

                    let row = `
                    <tr>
                        <td>${item.subfolio}</td>
                        <td>${item.cliente}</td>
                        <td>
                          <textarea  rows="2">${item.direccion}</textarea>
                        </td>
                        <td>${item.atencion}</td>
                        <td>${item.norma}</td>
                        <td> <textarea  rows="2">${item.muestra}</textarea></td>
                        <td>${item.recibio}</td>
                        <td>${item.hora_recepcion}</td>
                        <td>${item.hora_entrada}</td>
                        <td>${item.fechamuestreo}</td>
                        <td>${item.cantidad}</td>
                        <td>${item.unidad}</td>
                        <td>${item.tem_recepcion}</td>
                        <td>${item.tem_muestra}</td>
                        <td> <textarea  rows="2">${
                            item.observacion
                        }</textarea></td>
                        <td> <textarea  rows="2">${item.motivo}</textarea></td>
                        <td>${item.cumple == 1 ? "Sí" : "No"}</td>
                        <td><textarea >${parametros} </textarea></td>
                        <td>${item.calculo}</td>
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
    $("#btnbuscarbitacora").click(function () {
        Bitacora();
    });
});
