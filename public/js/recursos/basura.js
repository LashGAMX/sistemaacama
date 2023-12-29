var id = 0;

$(document).ready(function () {

});
function buscar() {
    let divTable = document.getElementById("tabla");
    let tab = "";
    let divTable2 = document.getElementById("tablaLote");
    let tab2 = "";
    $.ajax({
        type: "POST",
        url: base_url + "/admin/recursos/buscar",
        data: {
            folio:$("#folio").val(),
            parametro:$("#parametro").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
          console.log(response);
          var idCodigo = response.model.Id_codigo
          id = idCodigo
          console.log(id);

          tab += '<table class="table" id="tableCodigoParametro">';
          tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>ID</th>';
            tab += '          <th>Id_parametro</th> ';
            tab += '          <th>Resultado</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
           
                tab += '<tr>';
                tab += '<td>' + response.model.Id_codigo + '</td>';
                tab += '<td>' + response.model.Id_parametro + '</td>';
                tab += '<td>'+ response.model.Resultado2 + '</td>';
                
          tab += '</table>';
          divTable.innerHTML = tab;


          tab2 += '<table class="table" id="loteDetalle">';
          tab2 += '    <thead class="thead-dark">';
            tab2 += '        <tr>';
            tab2 += '          <th>Id_Codigo</th> ';
            tab2+= '          <th>parametro</th> ';
            tab2 += '          <th>Liberado</th> ';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.model2, function (key, item) {
                tab2 += '<tr>';
                  tab2 += '<td>' + item.Id_codigo + '</td>';
                  tab2 += '<td>' + item.Id_parametro + '</td>';
                  tab2 += '<td>'+ item.Liberado + '</td>';
                  tab2 += '</tr>';
                });
 
                tab2 += '    </tbody>'; 
          tab2 += '</table>';
          divTable2.innerHTML = tab2;

          
        }
    });
   
}
function eliminar(){
  $.ajax({
    type: "POST",
    url: base_url + "/admin/recursos/eliminar",
    data: {
        id:id,
        _token: $('input[name="_token"]').val()
    }, 
    dataType: "json",
    success: function (response) {            
      console.log(response);
      alert("Se elimino el lote: " + id)
    }
});
}
