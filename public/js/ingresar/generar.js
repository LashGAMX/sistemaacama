
function buscadorSol(busqueda, valIngreso, valProceso, valRepListo, valCliente, msj){
    let busquedaIn = document.getElementById(busqueda).value;
    let ingreso = document.getElementById(valIngreso);
    let proceso = document.getElementById(valProceso);
    let reporteListo = document.getElementById(valRepListo);
    let cliente = document.getElementById(valCliente);
    let mensaje = document.getElementById(msj);

    $.ajax({        
        url: base_url + '/admin/ingresar/generar/buscarSol',
        type: 'GET',
        data: {
            busquedaIn: busquedaIn
        },
        dataType: "json",
        async: false,
        success: function (response) {
            if(response.model !== null){   
                mensaje.innerHTML = "";                 
                if(response.model.Ingreso == "Establecido"){
                    ingreso.setAttribute("class", "icono-circulo");
                }else{
                    ingreso.setAttribute("class", "icono-circulo-default");
                }

                if(response.model.Proceso == "Establecido"){
                    proceso.setAttribute("class", "icono-circulo");
                }else{
                    proceso.setAttribute("class", "icono-circulo-default");
                }

                if(response.model.Reporte == "Establecido"){
                    reporteListo.setAttribute("class", "icono-circulo");
                }else{
                    reporteListo.setAttribute("class", "icono-circulo-default");
                }

                if(response.model.ClienteG == "Establecido"){
                    cliente.setAttribute("class", "icono-circulo");
                }else{
                    cliente.setAttribute("class", "icono-circulo-default");
                }
            }else{
                ingreso.setAttribute("class", "icono-circulo-default");
                proceso.setAttribute("class", "icono-circulo-default");
                reporteListo.setAttribute("class", "icono-circulo-default");
                cliente.setAttribute("class", "icono-circulo-default");
                mensaje.innerHTML = "No se encontraron resultados"
            }
        }
    });
}