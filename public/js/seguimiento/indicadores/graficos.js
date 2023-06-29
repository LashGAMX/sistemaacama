var idSol;
$(document).ready(function () {
    $('#btnBuscar').click(function () {
        switch (parseInt($("#grafica").val())) {
            case 1:
                grafica1()
                break;
            case 2:
                grafica2()
                break;
            case 3:
                grafica3()
                break;
            case 4:
                grafica4()
                break;
            default:
                alert("No hay una grafica seleccionada")
                break;
        }
    });
 
});
var canva = document.getElementById("newCanva")
function grafica1() {
    canva.innerHTML = '<canvas id="myChart"></canvas>'
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['123-1/23', '121-1/23', '122-1/23', '124-1/23', '125-1/23', '126-1/23'],
            datasets: [{
                label: 'Muestras ingresadas',
                data: [12, 20, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
function grafica2() {
    canva.innerHTML = '<canvas id="myChart"></canvas>'
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['123-1/23', '121-1/23', '122-1/23', '124-1/23', '125-1/23', '126-1/23'],
            datasets: [{
                label: 'Muestras ingresadas',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
function grafica3() {
    canva.innerHTML = '<canvas id="myChart"></canvas>'
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['123-1/23', '121-1/23', '122-1/23', '124-1/23', '125-1/23', '126-1/23'],
            datasets: [{
                label: '',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
function grafica4() {
    canva.innerHTML = '<canvas id="myChart"></canvas>'
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['123-1/23', '121-1/23', '122-1/23', '124-1/23', '125-1/23', '126-1/23'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}