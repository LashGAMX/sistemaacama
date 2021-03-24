//Componentes generales
var textLabel = (function (idInput = '',val = '',iconFa = 'plus'){
    let input = '<label for="'+idInput+'"><i class="nav-icon fas fa-'+iconFa+'"></i> '+val+'</label>';
    return input;
});
var inputText = (function (place = "", id = '', name = '',label = '',val='',attr = '',clss = 'form-control') {
    let input = '<label for="'+id+'">'+label+'</label><input type="text" class="'+clss+'" id="' + id + '" name="' + name + '" placeholder="' + place + '" value="'+val+'" '+attr+'>';
    return input;
});

var inputCheck = (function (id = '', name = '', chk = true, dis = false) {
    let input = '';
    if (chk == true && dis == false) {
        input = '<input type="checkbox" id="' + id + '" name="' + name + '" checked >';
    } else if (chk == true && dis == true) {
        input = '<input type="checkbox" id="' + id + '" name="' + name + '" checked disabled>';
    } else if (chk == false && dis == true) {
        input = '<input type="checkbox" id="' + id + '" name="' + name + '" disabled>';
    } else if (chk == false && dis == false) {
        input = '<input type="checkbox" id="' + id + '" name="' + name + '">';
    }
    return input;
});

var inputBtn = (function (id = '', name = '' , valor = "Boton", iconFa = 'plus', clss = "success", fn = "alert('boton');", clssExtra = '', type = "button") {
    let input = '<button type="' + type + '"  class="btn btn-' + clss + ' '+clssExtra+'" onClick="' + fn + '" name = "'+name+'" id="' + id + '"><i class="fa fa-' + iconFa + '"></i> ' + valor + '</button>';
    return input;
});

var inputBtnMdl = (function (id = '', valor = "Boton", iconFa = 'plus', cls = "success", fn = "alert('boton');", type = "button" ,componente = '') {
    let input = '<button type="' + type + '"  class="btn btn-' + cls + '" onClick="' + fn + ';" id="' + id + '" '+componente+'><i class="fa fa-' + iconFa + '"></i> ' + valor + '</button>';
    return input;
});

var inputType = (function (type = 'text', place = "", id = '', name = '', label = '',val = '',atrrExtra = '',clss = 'form-control') {
    let input = '<label for="'+id+'">'+label+'</label><input type="' + type + '" id="' + id + '" name="' + name + '" placeholder="' + place + '" class="' + clss + '" value="'+val+'" '+atrrExtra+'>';
    return input;
});

var inputSelect = (function (id = '',name = '',label = '',item,itemId,idchk = "",ext = '',clss = 'form-control') {
    let input = '';
    input += '<label>'+label+'</label>';
    input += '<select class="'+clss+'" name="'+name+'" id="'+id+'" '+ext+'>';
    for (let i = 0; i < item.length; i++) {
        if(itemId[i] == idchk){
            input += '<option value="'+itemId[i]+'" selected>'+item[i]+'</option>';
        }else{
            input += '<option value="'+itemId[i]+'">'+item[i]+'</option>';
        }
    }    
    input += '</select>';
    return input;
});
var inputMultiple = (function (id = '',name = '',label = '',item,itemId,place = 'Selecciona los datos',idchk = '',clss = 'select2') {
    let input = '';
    let sw = false;
    input += '<label>'+label+'</label>';
    input += '<select class="'+clss+'" name="'+name+'[]" id="'+id+'" multiple="multiple" data-placeholder="'+place+'" style="width: 100%;">';
    for (let i = 0; i < item.length; i++) {
        sw = false;
        for (let c = 0; c < idchk.length; c++) {
            if(itemId[i] == idchk[c]){
                sw = true;
            }
        }
        if(sw == true){
            input += '<option value="'+itemId[i]+'" selected>'+item[i]+'</option>';
        }else{
            input += '<option value="'+itemId[i]+'">'+item[i]+'</option>';
        }   
    }    
    input += '</select>';
    return input;
});

var inputSw = (function(value = 'Status',id = '', name = '',std = true,){
    let ch = "";
    if(std == true)
    {   
        ch = "checked";
    }else if(std == false){
        ch = "";
    }
    let input = '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success"><input type="checkbox" class="custom-control-input" id="'+id+'" name="'+name+'" '+ch+'><label class="custom-control-label" for="'+id+'">'+value+'</label></div>';
    return input;
});

var itemModal = new Array();
var newArray = (function (item){
    let ar = item;
    return ar;
});
var newModal = (function(idDiv = '',idModal = '',titulo = "Modal",sizeModal = 'xl',rowX = 1,rowY = 1,arr = 0,btn = inputBtn()){
    let div = document.getElementById(idDiv);
    let com = '';
    let resX = (12 / rowX);
    let countInput = 0;
    let br = false;
    com += '<div class="modal fade" id="'+idModal+'">';
    com += '    <div class="modal-dialog modal-'+sizeModal+'">';
    com += '        <div class="modal-content">';
    com += '            <div class="modal-header">';
    com += '                <h4 class="modal-title">'+titulo+'</h4>';
    com += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    com += '            </div>';
    com += '            <div class="modal-body">';
                            for (let x = 0; x < rowY; x++) {
                                com += '                <div class="row">';
                               for (let y = 0; y < rowX; y++) {
                                   if((countInput) < itemModal[arr].length)
                                   {
                                    com += '<div class="col-'+resX+'">';
                                    com += '<div class="form-group">';
                                    com += ''+itemModal[arr][countInput];
                                    com += '</div>';
                                    com += '</div>';
                                   }
                                  countInput++;
                               }
                               com += '                </div>';
                            }                          
    com += '            </div>';
    com += '            <div class="modal-footer justify-content-between">';
    com += '                <button type="button" class="btn btn-danger" class="btn btn-info" data-dismiss="modal">Cerrar</button>';
    com += '                '+btn;
    com += '            </div>';
    com += '        </div>';
    com += '    </div>';
    com += '</div>';
    div.innerHTML = com;
    
    $('#'+idModal).modal('show');
});
var newModal2 = (function(idDiv = '',idModal = '',titulo = "Modal",sizeModal = 'xl',rowX = 1,rowY = 1,arr = 0,btn = '<button>Boton</button>',mod=''){
    let div = document.getElementById(idDiv);
    let com = '';
    let resX = (12 / rowX);
    let countInput = 0;
    let br = false;
    com += '<div class="modal fade" id="'+idModal+'">';
    com += '    <div class="modal-dialog modal-'+sizeModal+'">';
    com += '        <div class="modal-content">';
    com += '            <div class="modal-header">';
    com += '                <h4 class="modal-title">'+titulo+'</h4>';
    com += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    com += '            </div>';
    com += '            <div class="modal-body">';
                            for (let x = 0; x < rowY; x++) {
                                com += '                <div class="row">';
                               for (let y = 0; y < rowX; y++) {
                                   if((countInput) < itemModal[arr].length)
                                   {
                                    com += '<div class="col-'+resX+'">';
                                    com += '<div class="form-group">';
                                    com += ''+itemModal[arr][countInput];
                                    com += '</div>';
                                    com += '</div>';
                                   }
                                  countInput++;
                               }
                               com += '                </div>';
                            }
    com += '<div class="border border-info"></div>';
    com += '                <div class="row">';
    com += '                    <div class="col-12">';
    com += '                        <div class="form-group">';
    com += '                           '+ mod;
    com +=                          '</div>';
    com += '                    </div>';
    com += '                </div>';                            
    com += '            </div>';
    com += '            <div class="modal-footer justify-content-between">';
    com += '                <button type="button" class="btn btn-danger" class="btn btn-info" data-dismiss="modal">Cerrar</button>';
    com += '                '+btn;
    com += '            </div>';
    com += '        </div>';
    com += '    </div>';
    com += '</div>';
    div.innerHTML = com;
    
    $('#'+idModal).modal('show');
});


var modalType = (function (idDiv = '',idModal = '',titulo = "Modal",sizeModal = 'lg',body,btn = '<button>Boton</button>'){
    let div = document.getElementById(idDiv);
    let content = body;
    let com = '';
    com += '<div class="modal fade" id="'+idModal+'">';
    com += '    <div class="modal-dialog modal-'+sizeModal+'">';
    com += '        <div class="modal-content">';
    com += '            <div class="modal-header">';
    com += '                <h4 class="modal-title">'+titulo+'</h4>';
    com += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    com += '            </div>';
    com += '            <div class="modal-body" id="idBody">';
    com += ''+content;                    
    com += '            </div>';
    com += '            <div class="modal-footer justify-content-between">';
    com += '                <button type="button" class="btn btn-danger" class="btn btn-info" data-dismiss="modal">Cerrar</button>';
    com += '                '+btn;
    com += '            </div>';
    com += '        </div>';
    com += '    </div>';
    com += '</div>';
    div.innerHTML = com;
    
    $('#'+idModal).modal('show');
});

var formGroup = (function (lbl = '',item){
    let input = '';
    input += '<div class="form-group">';
    input += '<div class="row">';
    input += ''+lbl+'<br>';
    input += '</div>';
    input  = '  <div class="input-group">';
    for (let i = 0; i < item.length; i++) {
        input += item[i];
    }
    input += '  </div>';
    input += '</div>';
    return input;
});

var inputFiltroFecha = (function (idF1 = 'fInicio',idF2 = 'fFin',grupo ='filtroFecha',btn = '<botton>Boton</botton>'){
    let input = '';
    input +='                <div class="form-group">';
    input +='                   <label for="'+idF1+'"><i class="nav-icon fas fa-calendar-alt"></i> Busqueda periodo</label>';
    input +='                    <div class="input-group">';
    input +='                         '+inputType('date','Fecha inicio',idF1,grupo);
    input +='                         '+inputType('date','Fecha final',idF2,grupo);
    input +='                        &nbsp'+btn;
    input +='                    </div>';
    input +='                </div>';
    return input;
});
