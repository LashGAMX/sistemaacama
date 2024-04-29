$(document).ready(function () {
  $(".select2").select2()
    $("#btnBuscar").click(function () {
        switch (parseInt($("#selIndicador").val())) {
            case 1:
                solicitudesGeneradas();
                break;
            case 2:
                ordenServicioProceso();
                break;
            case 3:
              cotizacionesGeneradas();
                break;
            case 4:
                break;
            default:
                getMuestrasPendientes();
                break;
        }
    });
});
function cotizacionesGeneradas() {
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");

    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/cotizacionesGeneradas",
        data: {
            fechaInicio: $("#fechaInicio").val(),
            fechaFin: $("#fechaFin").val(),
            norma: $("#norma").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            const MONTHS = response.mes;
            const labels = MONTHS;
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: "Cotizaciones generadas | "+response.totalInd,
                        data: response.totalInd,
                        borderColor: "rgb(255, 99, 132)",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                    },
                ],
            };
            const config = {
                type: "bar",
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        title: {
                            display: true,
                            text: "Cotizaciones generadas",
                        },
                    },
                },
            };
            new Chart(ctx, config);
        },
    });
}
function solicitudesGeneradas() {
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");
    let extra = document.getElementById("divExtra")
    let tabExtra = '';

    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/solicitudesGeneradas",
        data: {
            fechaInicio: $("#fechaInicio").val(),
            fechaFin: $("#fechaFin").val(),
            norma: $("#norma").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            const MONTHS = response.mes;
            const labels = MONTHS;
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: "Folio Padre | "+response.totalInd,
                        data: response.totalInd,
                        borderColor: "rgb(255, 99, 132)",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                    },
                    {
                        label: "Folio Hijo | "+response.totalIndHijo,
                        data: response.totalIndHijo,
                        borderColor: "rgb(255, 159, 64)",
                        backgroundColor: "rgba(255, 159, 64, 0.2)",
                    },
                ],
            };
            const config = {
                type: "bar",
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        title: {
                            display: true,
                            text: "Folios generados / Padre vs Hijo",
                        },
                    },
                },
            };
            new Chart(ctx, config);
            tabExtra += `
            <span>Cancelados</span><br>
            <span>Padre : ${response.canceladoGen.length}</span><br>
            <span>Hijo : ${response.canceladoHijo.length}</span>
            `
        extra.innerHTML = tabExtra
        },
    });
}
function ordenServicioProceso() {
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");
    let extra = document.getElementById("divExtra")
    let tabExtra = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/ordenServicioProceso",
        data: {
            fechaInicio: $("#fechaInicio").val(),
            fechaFin: $("#fechaFin").val(),
            norma: $("#norma").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            const MONTHS = response.mes;
            const labels = MONTHS;
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: "En proceso | "+response.totalPendiente,
                        data: response.totalPendiente,
                        borderColor: "rgb(255, 99, 132)",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                    },
                    {
                        label: "Impresos | "+response.totalImpreso,
                        data: response.totalImpreso,
                        borderColor: "rgb(255, 159, 64)",
                        backgroundColor: "rgba(255, 159, 64, 0.2)",
                    },
                ],
            };
            const config = {
                type: "bar",
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        title: {
                            display: true,
                            text: "Folios generados / En proceso - Impresos",
                        },
                    },
                },
            };
            new Chart(ctx, config);
            tabExtra += `
            <span>Cancelados</span><br>
            <span>En proceso : ${response.canceladosPendiente.length}</span><br>
            <span>Impreso : ${response.canceladoImpreso.length}</span>
            `
        extra.innerHTML = tabExtra
        },
    });
}
function grafico1() {
    console.log("grafico1");
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");
    const data = {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
        ],
        datasets: [
            {
                label: "Looping tension",
                data: [65, 59, 80, 81, 26, 55, 40],
                fill: false,
                borderColor: "rgb(75, 192, 192)",
            },
        ],
    };
    const config = {
        type: "line",
        data: data,
        options: {
            animations: {
                tension: {
                    duration: 1000,
                    easing: "linear",
                    from: 1,
                    to: 0,
                    loop: true,
                },
            },
            scales: {
                y: {
                    // defining min and max so hiding the dataset does not change scale range
                    min: 0,
                    max: 100,
                },
            },
        },
    };
    new Chart(ctx, config);
}
function grafico2() {
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");

    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/indicadores",
        data: {
            fechaInicio: $("#fechaInicio").val(),
            fechaFin: $("#fechaFin").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            const MONTHS = response.mes;
            const labels = MONTHS;
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: "Orden de servicio padre",
                        data: response.totalInd,
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.2)",
                            "rgba(255, 159, 64, 0.2)",
                            "rgba(255, 205, 86, 0.2)",
                            "rgba(75, 192, 192, 0.2)",
                            "rgba(54, 162, 235, 0.2)",
                            "rgba(153, 102, 255, 0.2)",
                            "rgba(201, 203, 207, 0.2)",
                        ],
                        borderColor: [
                            "rgb(255, 99, 132)",
                            "rgb(255, 159, 64)",
                            "rgb(255, 205, 86)",
                            "rgb(75, 192, 192)",
                            "rgb(54, 162, 235)",
                            "rgb(153, 102, 255)",
                            "rgb(201, 203, 207)",
                        ],
                        borderWidth: 1,
                    },
                ],
            };
            const config = {
                type: "bar",
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            };

            new Chart(ctx, config);
        },
    });
}
function grafico3() {
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");
    const data = {
        labels: ["Red", "Blue", "Yellow"],
        datasets: [
            {
                label: "My First Dataset",
                data: [300, 50, 100],
                backgroundColor: [
                    "rgb(255, 99, 132)",
                    "rgb(54, 162, 235)",
                    "rgb(255, 205, 86)",
                ],
                hoverOffset: 4,
            },
        ],
    };
    const config = {
        type: "pie",
        data: data,
    };

    new Chart(ctx, config);
}

function getMuestrasPendientes() {
    console.log("getMuestrasPendientes");
    document.getElementById("divIndicador").innerHTML =
        '<div style="width: 100%; height: 500px"><canvas id="myChart"></canvas></div>';
    const ctx = document.getElementById("myChart");
    const folio = [];
    const fechas = [];

    $.ajax({
        type: "POST",
        url: base_url + "/admin/kpi/getMuestrasPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            // Supongamos que tienes un array de fechas en formato ISO 8601
            const fechasISO = ["2024-01-30", "2024-02-23", "2024-03-30"];

            // Convierte las fechas a objetos Date
            const fechas = fechasISO.map((fechaISO) => new Date(fechaISO));

            // Formatea las fechas como 'YYYY-MMM' (por ejemplo, '2020-May')
            const etiquetas = fechas.map((fecha) =>
                fecha.toLocaleString("en-US", {
                    year: "numeric",
                    month: "short",
                })
            );

            var clientes = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: etiquetas,
                    datasets: [
                        {
                            label: "Fechas",
                            data: [30, 15, 20], // Valores correspondientes a las fechas
                            // ...
                        },
                    ],
                },
                // ...
            });
        },
    });
}
