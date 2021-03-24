//Variables gobales
var tabla = [];
// Funcion para crear tablas con jquery
var crearTabla = (function(idTabla,idGrupo){
  setBotones(idGrupo);
    tabla[idTabla] = $("#"+idTabla).DataTable({
        "responsive": true,
        "autoWidth": true,
        stateSave: true,
        "language": {
          "lengthMenu": "Mostrar _MENU_ por pagina",
          "zeroRecords": "Datos no encontrados",
          "info": "Mostrando _PAGE_ de _PAGES_",
          "infoEmpty": "No hay datos",
          "infoFiltered": "(filtered from _MAX_ total records)",
        }
      });
    
      $('a.toggle-vis').on( 'click', function (e) {
          e.preventDefault();
   
          // Get the column API object
          var column = tabla[idTabla].column( $(this).attr('data-column') );
          // Toggle the visibility
          column.visible( ! column.visible() );
      } );

      // $("#"+idTabla)
      // .on( 'page.dt',   function () { console.log("Cambio de pagina"); } )
      // .DataTable();
});
var setBotones = function(idGrupo)
{
    let btnGrupo;
    let i = 0;
    console.log("Tamaño de filtas:"+idGrupo.length);
    for(i = 0; i < idGrupo.length;i++)
    {
      btnGrupo = document.getElementsByName(idGrupo[i]);
      //btnGrupo[btnGrupo.length - 1].hidden = true;
      btnGrupo[btnGrupo.length - 2].hidden = true;
      btnGrupo[btnGrupo.length - 3].hidden = true;
    }
}
var crearTabla2 = (function(idTabla){
  tabla[idTabla] = $("#"+idTabla).DataTable({
      "responsive": true,
      "autoWidth": false,
      stateSave: true,
      "language": {
        "lengthMenu": "# _MENU_ por pagina",
        "zeroRecords": "Datos no encontrados",
        "info": "Mostrando _PAGE_ de _PAGES_",
        "infoEmpty": "No hay datos para mostrar",
        "infoFiltered": "(filtered from _MAX_ total records)"
      }
    });
  
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = tabla[idTabla].column( $(this).attr('data-column') );
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );

   
});
var customTable = (function(idTabla,config){

//  tabla[idTabla] = $("#"+idTabla).DataTable(config);
tabla[idTabla] = $("#"+idTabla).DataTable(config);
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        // Get the column API object
        var column = tabla[idTabla].column( $(this).attr('data-column') );
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
});
//var crearFila()
var habilitarInput = (function(idGrupo){
  let grupo = document.getElementsByName(idGrupo);
  let btnTabla = [];
  // btnTabla[0] = grupo.length - 1; btnEditar
  // btnTabla[1] = grupo.length - 2; btnCancelar
  // btnTabla[2] = grupo.length - 3; btnConfirmar
  btnTabla[0] = grupo.length - 1;
  btnTabla[1] = grupo.length - 2;
  btnTabla[2] = grupo.length - 3;

  for(let i = 0; i < grupo.length - 1;i++)
  {
    if(grupo[i].disabled == true){
      if(i < grupo.length - 3)
      {
        grupo[i].disabled = false;
      }
    }else if(grupo[i].disabled == false){
      if(i < grupo.length - 3)
      {
        grupo[i].disabled = true;
      }
    }
  }

  if(grupo[0].disabled == true)
  {
    //grupo[btnTabla[0]].hidden = false;
    grupo[btnTabla[0]].hidden = false;
    grupo[btnTabla[1]].hidden = true;
    grupo[btnTabla[2]].hidden = true;

  }else if(grupo[0].disabled == false)
  {
    //grupo[btnTabla[0]].hidden = true;
    grupo[btnTabla[0]].hidden = true;
    grupo[btnTabla[1]].hidden = false;
    grupo[btnTabla[2]].hidden = false;
  }

});

// Crear columna para agregar dastos
var addCol = (function (idBtn,idTabla,item){
  let t = $('#'+idTabla).DataTable();
  var cLab = false;

    $('#'+idBtn).on( 'click', function () {
      if(cLab == false)
      {
        t.row.add( item
          ).draw( false );
      cLab = true;
      }else if(cLab == true){
        swal("Error!", "Ya tienes generado una fila para registro!", "error");
      }
    } );
});


//Componentes de tabla
var verCol = (function(item){
  let btn = '';
  btn += '<div class="dropdown">';
  btn += '<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver</a>';
  btn += '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
  for (let i = 0; i < item.length; i++) {
    //console.log(item [i]);
    btn += '<a class="dropdown-item toggle-vis" data-column="'+i+'">'+item[i]+'</a>';
  }
  btn += '</div>&nbsp;</div>';
  return btn;

});
// var crearColumna = (function (idBtn,idTabla){
//   let t = $('#'+idTabla).DataTable();
//   let counter = 1;
//   var countCol = false;

//     $('#'+idBtn).on( 'click', function () {
//       if(countCol == false)
//       {
//         t.row.add( [
//           couter+'',
//           '<input>'
//       ] ).draw( false );
//       countCol = true;
//       }else if(countCol == true){
//         swal("Error!", "Ya tienes generado una fila para registro!", "error");
//       }
//     } );
// });

// //Generar botons para las tablas
// var btnTabla = (function (grupo){
//   let btnGroup = "'"+grupo+"'";
//   console.log(btnGroup);
//   //let btn = '<button type="button" class="btn bg-success btn-sm" onclick="actualizarLab('+"'l"+item.Id_laboratorio+"'"+');" name="l'+item.Id_laboratorio+'"><i class="nav-icon fas fa-check"></i> </button>&nbsp;<button type="button" class="btn bg-danger btn-sm" onclick="habilitarInput('+"'l"+item.Id_laboratorio+"'"+');" name="l'+item.Id_laboratorio+'"><i class="nav-icon fas fa-times-circle"></i> </button>&nbsp;<button type="button" onclick="habilitarInput('+"'l"+item.Id_laboratorio+"'"+');" name="l'+item.Id_laboratorio+'" class="btn bg-warning btn-sm"><i class="nav-icon fas fa-pen"></i> </button>&nbsp;<button type="button" class="btn bg-danger btn-sm" name="l'+item.Id_laboratorio+'"><i class="nav-icon fas fa-trash"></i> </button>';
//  //  let btn = '<button type="button" class="btn bg-success btn-sm" onclick="actualizarLab('+grupo+');" name="'+grupo+'"><i class="nav-icon fas fa-check"></i> </button>&nbsp;<button type="button" class="btn bg-danger btn-sm" onclick="habilitarInput('+grupo+');" name="'+grupo+'"><i class="nav-icon fas fa-times-circle"></i> </button>&nbsp;<button type="button" onclick="habilitarInput('+grupo+');" name="'+grupo+'" class="btn bg-warning btn-sm"><i class="nav-icon fas fa-pen"></i> </button>&nbsp;<button type="button" class="btn bg-danger btn-sm" name="'+grupo+'"><i class="nav-icon fas fa-trash"></i> </button>';
//   let btn = '<button type="button" class="btn bg-success btn-sm" onclick="actualizarLab('+btnGroup+');" name="'+grupo+'"><i class="nav-icon fas fa-check"></i> </button>&nbsp;<button type="button" class="btn bg-danger btn-sm" onclick="habilitarInput('+grupo+');" name="'+grupo+'"><i class="nav-icon fas fa-times-circle"></i> </button>&nbsp;<button type="button" onclick="habilitarInput('+grupo+');" name="'+grupo+'" class="btn bg-warning btn-sm"><i class="nav-icon fas fa-pen"></i> </button>';
//   return btn;
//  });
// Asigna el estado a un input para guardar el estado en la DB
var setStdSw = (function(sw){
    var setSw = document.getElementById(sw);
    var setSw2 = document.getElementById(sw + "2");
    if (setSw.checked == true) {
      setSw2.value = "true";
    } else if (setSw.checked == false) {
      setSw2.value = "false";
    }
});