function getclientes(){
    let color= "";
    let tabla=document.getElementById('divclientesGen');
    let tab="";
    $.ajax({
         type: 'POST',
         url: base_url+'/admin/clientes/getClientesGen',
         data:{
            Empresa:Empresa,
            Id_cliente:Id_cliente,
            Id_intermediario:Id_intermediario,
            Nombres:Nombres,
            created_at:created_at,
            Id_user_c:Id_user_c,
            updated_at:updated_at,
            Id_user_m:Id_user_m,
         },
         dataType:"json",
         async:false,
         success:function(response){
            tab += '<table id="tablaClientesGen" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Id</th>';
            tab += '          <th>Cliente</th>';
            tab += '          <th>Id inter</th>';
            tab += '          <th>Intermediario</th> ';
            tab += '          <th>Creación</th> ';
            tab += '          <th>Creó</th> ';
            tab += '          <th>Modificación</th> ';
            tab += '          <th>Modificó</th> ';
            tab += '          <th>Acción</th> ';
            tab += '         <tr> ';
            tab += '      <thead> ';
            tab += '      <tbody> ';
          $.each(response.data, function(item){
            if(data.deleted_at == null ){
                color="danger";
            }else{
                color="";
            }
            tab +=     '<tr>';
            tab += '<tr class="bg-'+color+'>'+data.Id_cliente+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.Empresa+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.Id_intermediario+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.Nombres+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.created_at+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.Id_user_c+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.updated_at+'</td>';
            tab += '<tr class="bg-'+color+'>'+data.Id_user_m+'</td>';
            tab += '<td>';
            tab +=  '<button type="button" class="btn btn-warning boton-editar" activo="no" data-toggle="modal" data-target="#modalEditar"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>';
            tab += '<button type="button" class="btn btn-primary boton-ver"><i class="voyager-external"></i><span hidden-sm hidden-xs>Ver</span></button> ';
            tab += '</td>'; 
            tab +=     '</tr>';
        });
            tab +=   '</tbody>';
            tab += '</table>';
      tabla.innerHTML=tab;
      let tablaClientesGen=$('#tablaClientesGen').DataTable({
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY": "300px",
        "scrollCollapse":true,
        "paging": false,
       
      });
     

         }

    });
}
