
$(document).ready(function () {
    $(".select2").select2()
    $("#btnBuscar").click(function () {
        getLotes()
    });
});

function getLotes()
{
    let table = document.getElementById("divLote")
    let tab = ''
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/analisis/getLotes",
        data: {
            parametro:$("#parametro").val(),
            tipo:$("#tipo").val(),
            mes:$("#mes").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response) 
        } 
    }); 
}