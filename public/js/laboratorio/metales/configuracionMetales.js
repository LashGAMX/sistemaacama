var table
var id 
$(document).ready(function () {

    _initTable()
});

function _initTable()
{
    table = $('#tabParametros').DataTable({
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        "scrollY": 450,
        "scrollCollapse": true
    });
    $('#tabParametros tbody').on('dblclick', 'tr', function () {
        id = $(this).children(':first').html();
        // getMuestraSinAsignar()
        // alert("id"+id)
        getConfiguraciones(id)
    });
    $("#btnGuardar").click(function (){
        setConfiguraciones(id)
    });
}
function getConfiguraciones(id)
{ 
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getConfiguraciones",
        data: {
            id:id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            $("#parametro").val(response.parametro.Parametro)
            $("#equipo").val(response.model.Equipo)
            $("#corrienteLampara").val(response.model.Lampara)
            $("#NoInventario").val(response.model.No_Inventario)
            $("#energiaLampara").val(response.model.Energia)
            $("#concentracion").val(response.model.Concentracion)
            $("#inventarioLamapra").val(response.model.No_lampara)
            $("#longitudOnda").val(response.model.Longitud_onda)
            $("#slit").val(response.model.Slit)
            $("#acetileno").val(response.model.Acetileno)
            $("#aire").val(response.model.Aire)
            $("#oxidoNitroso").val(response.model.Oxido_nitroso)
            $("#hidruros").val(response.model.Hidruros)
            $("#curva").val(response.model.Bitacora_curva)
            $("#supStd1").val(response.model.Sup_std1)
            $("#supStd2").val(response.model.Sup_std2)
            $("#supStd3").val(response.model.Sup_std3)
            $("#supStd4").val(response.model.Sup_std4)
            $("#supStd5").val(response.model.Sup_std5)
            $("#absStd1").val(response.model.Abs_std1)
            $("#absStd2").val(response.model.Abs_std2)
            $("#absStd3").val(response.model.Abs_std3)
            $("#absStd4").val(response.model.Abs_std4)
            $("#absStd5").val(response.model.Abs_std5)
            $("#infStd1").val(response.model.Inf_std1)
            $("#infStd2").val(response.model.Inf_std2)
            $("#infStd3").val(response.model.Inf_std3)
            $("#infStd4").val(response.model.Inf_std4)
            $("#infStd5").val(response.model.Inf_std5)
        }
    });
}
function setConfiguraciones(id)
{ 
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/setConfiguraciones",
        data: {
            id:id,
            Equipo:$("#equipo").val(),
            No_inventario:$("#NoInventario").val(),
            Energia:$("#energiaLampara").val(),
            Lampara:$("#corrienteLampara").val(),
            No_lampara:$("#inventarioLamapra").val(),
            Concentracion:$("#concentracion").val(),
            Longitud_onda:$("#longitudOnda").val(),
            Slit:$("#slit").val(),
            Acetileno:$("#acetileno").val(),
            Aire:$("#aire").val(),
            Oxido_nitroso:$("#oxidoNitroso").val(),
            Hidruros:$("#hidruros").val(),
            Bitacora_curva:$("#curva").val(),
            Sup_std1:$("#supStd1").val(),
            Sup_std2:$("#supStd2").val(),
            Sup_std3:$("#supStd3").val(),
            Sup_std4:$("#supStd4").val(),
            Sup_std5:$("#supStd5").val(),
            Abs_std1:$("#absStd1").val(),
            Abs_std2:$("#absStd2").val(),
            Abs_std3:$("#absStd3").val(),
            Abs_std4:$("#absStd4").val(),
            Abs_std5:$("#absStd5").val(),
            Inf_std1:$("#infStd1").val(),
            Inf_std2:$("#infStd2").val(),
            Inf_std3:$("#infStd3").val(),
            Inf_std4:$("#infStd4").val(),
            Inf_std5:$("#infStd5").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            alert("Datos guardados")
        }
    });
}