$(document).ready(function () {
    $("#btnBuscar").click(function () {
        getbuscarFolio()
    });
});

function getbuscarFolio(){
    let tabPuntos = document.getElementById("divPuntos")
    let tab = ''

    let tabCotizacion = document.getElementById("divCotizacion").innerHTML = ``
    let tabOrden = document.getElementById("divOrden").innerHTML = ``
    let tabMuestreo = document.getElementById("divMuestreo").innerHTML = ``
    let tabRecepcion = document.getElementById("divRecepcion").innerHTML = ``
    let tabLab = document.getElementById("divLab").innerHTML = ``
    let tabImpresion = document.getElementById("divImpresion").innerHTML = ``

    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/getbuscarFolio",
        data: {
            folio: $("#txtFolio").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
            tab += `
                <select class="custom-select select2" id="selPunto" onchange="getSeguimiento()">
                    <option selected>No hay punto de muestreo</option>
                    ${
                        $.map(response.puntos, function (item) {
                            return `<option value="${item.Id_solicitud}">${item.Punto}</option>`;
                        }).join('')
                    }    
                </select>
            `;        
            tabPuntos.innerHTML = tab
            $("#selPunto").select2()

            
        },
    });
}
function getSeguimiento(){
    let tabCotizacion = document.getElementById("divCotizacion")
    let tabOrden = document.getElementById("divOrden")
    let tabMuestreo = document.getElementById("divMuestreo")
    let tabRecepcion = document.getElementById("divRecepcion")
    let tabLab = document.getElementById("divLab")
    let tabImpresion = document.getElementById("divImpresion")

    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/getSeguimiento",
        data: {
            id: $("#selPunto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
            
            tabOrden.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <p>Cliente : ${response.model.Empresa_suc}</p>
                        <p>Creacion : ${response.model.created_at}</p>
                        <p>Folio : ${response.model.Folio_servicio}</p>
                    </div>
                    <div class="col-md-6">
                        <p>Norma : ${response.model.Clave_norma}</p>
                        <p>Servicio : ${response.model.Servicio}</p>
                        <p>Siralab: ${response.model.Siralab === 0 ? 'No' : 'Si'}</p>
                    </div>
                </div>
            `
            if (parseInt(response.model.Id_servicio) != 3) {
                tabMuestreo.innerHTML = `
                  <p>Punto : ${response.campo.Punto_muestreo}</p>
                  <p>Captura : ${response.campo.Captura}</p>
                  <p>Muestreador : ${response.campo.Nombres}</p>
                `
            }else{
                tabMuestreo.innerHTML =  `
                    <p>Muestra remitida</p>
                `
            }
            const recepcionMuestra = new Date(response.proceso.Hora_recepcion); // Obtiene la fecha actual

            tabRecepcion.innerHTML = `
                <p>Hora recepcion : ${response.proceso.Hora_recepcion}</p>
                <p>Hora entrada : ${response.proceso.Hora_entrada}</p>
                <p>Intermediario : ${response.proceso.Cliente}</p>
                ${
                    (() => {
                        switch (parseInt(response.model.Id_norma)) {
                            case 1:
                            case 27:
                                recepcionMuestra.setDate(recepcionMuestra.getDate() + 11);
                                return ' <p>Salida: '+recepcionMuestra.toISOString()+'</p>'; // No se mostrará nada para el caso 1
                            case 5:
                            case 30:
                                        recepcionMuestra.setDate(recepcionMuestra.getDate() + 14);
                                        return ' <p>Salida: '+recepcionMuestra.toISOString()+'</p>'; // No se mostrará nada para el caso 1
                            default:
                                recepcionMuestra.setDate(recepcionMuestra.getDate() + 11);
                                return ' <p>Salida: '+recepcionMuestra.toISOString()+'</p>'; // No se mostrará nada para el caso 1
                        }
                    })()
                }
            `
            let stdClass = ""
            tabLab.innerHTML = `
             <table class="table talbe-sm" id="tabCodigo">
                <thead>
                    <th># Id</th>
                    <th>Codigo</th>
                    <th>Parametro</th>
                    <th>Area</th>
                </thead>
                <tbody>
                    ${
                        $.map(response.codigo, function (item) {
                            stdClass = "bg-danger" 
                            if (item.Resultado2 != null) {
                                stdClass = "bg-success";
                            } else {
                                stdClass = "bg-danger"
                            }
                            return `
                                <tr>
                                    <td class="${stdClass}">${item.Id_codigo}</td>
                                    <td class="${stdClass}">${item.Codigo}</td>
                                    <td class="${stdClass}">${item.Parametro}</td>
                                    <td class="${stdClass}">${item.Area_analisis}</td>
                                </tr>
                            `;
                        }).join('')
                    }  
                </tbody>
             </table>
            `

            if (response.informe.length > 0) {
                tabImpresion.innerHTML = `
                    ${
                        $.map(response.informe, function (item) {
                            const fechaISO = item.created_at
                            const fechaNormal = new Date(fechaISO).toISOString()
                            return `
                                <p>Fecha: ${fechaNormal}</p>
                            `;
                        }).join('')
                    }  
                `
            }else{
                tabImpresion.innerHTML = `
                    <p>No hay informe impreso</p>
                `
            }
            


            $('#tabCodigo').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                paging: false,
                scrollCollapse: true,
                scrollY: '250px'
            });
           
        },
    });
}