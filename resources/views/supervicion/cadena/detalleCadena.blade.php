@extends('voyager::master')

@section('content')

<input type="text" id="idSol" value="{{$model->Id_solicitud}}" hidden>
<input type="text" id="idNorma" value="{{$model->Id_norma}}" hidden>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h6>Solicitud ID: <strong>{{$model->Id_solicitud}}</strong></h6>
                            <h6>Servicio: <strong>{{$model->Servicio}}</strong></h6>
                            <h6>Tipo descarga: <strong>{{$model->Descarga}}</strong></h6>
                            <h6>Norma: <strong>{{$model->Clave}}</strong></h6>
                            <h6>Cliente: <strong>{{$model->Empresa_suc}}</strong></h6>
                            <h6>Intermediario: <strong>{{@$intermediario->Nombres}}</strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Folio: <strong>{{$model->Folio_servicio}}</strong></h6>
                            <h6>Estado: <strong>Reporte</strong></h6>
                            <h6>Dirección: <strong>{{$direccion->Direccion}}</strong></h6>
                            <h6>Fecha muestro: <strong>{{$model->Fecha_muestreo}}</strong></h6>
                            <h6>Fecha recepcion: <strong>{{$model->Fecha_recepcion}}</strong></h6>
                            <h6>Fecha emisión: <input type="date" id="fechaEmision" value="{{$proceso->Emision_informe}}"> <span id="btnSetEmision" class="fas fa-edit bg-success"></span></h6>
                          
                        </div>
                        <div class="col-md-2">
                            
                            
                            <span id="mensaje" class="badge">
                            
                            </span>
                            <span id="mensaje2" class="badge">
                            
                            </span>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Supervisado</label>
                              <input class="form-check-input" id="ckSupervisado" type="checkbox" value="" id="defaultCheck1" @if (@$proceso->Supervicion == 1) checked = "true" @endif>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Liberado</label>
                              <input class="form-check-input" id="ckLiberado" type="checkbox" value="" id="defaultCheck1" @if (@$proceso->Liberado == 1) checked = "true" @endif>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Historial</label>
                              <input class="form-check-input" id="ckHistorial" type="checkbox" value="" id="defaultCheck1" @if (@$proceso->Historial_resultado == 1) checked = "true" @endif>
                            </div>

                        </div>
                     
                        <div class="col-md-4">
                            <p hidden id="extVersion"></p>
                            <p hidden id="nmhVersion"></p>
                            <p hidden id="sigplusVersion"></p>
                            <form action="https://sigplusweb.com/sign_chrome_ff_sigplusextlite.html#" name="FORM1" style="display: flex">
                                <p>
                                    <!-- <input > -->
                                    <button id="SignBtn" name="SignBtn" type="button" value="Sign" onclick="StartSign()">Firmar<i class="fas fa-pen-nib"></i></button>
                                    <button id="btnFirma" name="btnFirma" type="button">Guardar<i class="fas fa-floppy-disk"></i></button>
                                    <input type="HIDDEN" name="bioSigData">
                                    <input type="HIDDEN" name="sigImgData">
                                    <textarea hidden name="sigStringData" rows="20" cols="50">SigString: </textarea>
                                    <textarea hidden name="sigRawData" rows="20" cols="50"></textarea>
                                </p>
                            </form>
                            <table border="1" cellpadding="0" width="500">
                                <tbody><tr>
                                    <td height="100" width="500">
                                        <canvas id="cnv" name="cnv" width="500" height="100"></canvas>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                   
                            
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" id="btnCadena">Imprimir</button>
                            <button class="btn btn-success" id="btnCadenaVidrio">Imprimir V.</button>
                            <div id="fotos" style="display: flex;"></div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
    <div class="row">
        <div class=""></div>
        <div class="col-md-12">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <table id="tablePuntos" class="display compact cell-border " style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Punto muestreo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($puntos as $item)
                                        <tr>
                                            <td>{{$item->Id_solicitud}}</td>
                                            <td>{{$item->Punto}}</td>
                                        </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        </div>
                        <div class="col-md-6">
                            
                           <div id="divTableParametros">
                            <table id="tableParametros" class="display compact cell-border" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Parametro</th>
                                        <th>Tipo formula</th>
                                        <th>Resultado</th>
                                        <th>Liberado</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>    
                                <tbody>
                        
                                </tbody>
                            </table>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <button id="btnLiberar" class="btn-success"><i class="fas fa-square-root-alt" data-toggle="tooltip" data-placement="top" title="Liberar"></i></button>
                            @switch(Auth::user()->role->id)
                            @case(1)
                                <button id="btnRegresar" class="btn-info" onclick="regresarMuestra()" data-toggle="tooltip" data-placement="top" title="Regresar Resultado"><i class="voyager-double-left"></i></button>
                                <button id="btnReasignar" class="btn-warning" onclick="reasignarMuestra()" data-toggle="tooltip" data-placement="top" title="Cuadro de asignación"><i class="voyager-check"></i></button>
                                <button id="btnDesactivar" class="btn-danger" onclick="desactivarMuestra()" data-toggle="tooltip" data-placement="top" title="Ocultar"><i class="voyager-x"></i></button>
                                @break
                            @default
                            @if (Auth::user()->id == 14 || Auth::user()->id == 4 ||  Auth::user()->id == 12 || Auth::user()->id == 100 )
                                <button id="btnRegresar" class="btn-info" onclick="regresarMuestra()" data-toggle="tooltip" data-placement="top" title="Regresar Resultado"><i class="voyager-double-left"></i></button>
                                <button id="btnReasignar" class="btn-warning" onclick="reasignarMuestra()" data-toggle="tooltip" data-placement="top" title="Cuadro de asignación"><i class="voyager-check"></i></button>
                                <button id="btnDesactivar" class="btn-danger" onclick="desactivarMuestra()" data-toggle="tooltip" data-placement="top" title="Ocultar"><i class="voyager-x"></i></button>
                            @endif                           
                            @endswitch
                            <div id="divTabDescripcion">
                                <table id="tableResultado" class="display compact cell-border" style="width:100%">
                                    <thead>
                                        <tr>                                     
                                         <th>Descripcion</th>
                                         <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            
                                    </tbody>
                                </table>
                            </div>
                            <table>
                                <tr>
                                    <td>Resultado</td>
                                    <td><input type="text" style="font-size: 20px;width: 100px;color:red;" id="resDes" value="0.0"></td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                </div>
              </div>
        
        </div> 
    </div>
</div>

<!-- Modal historial-->
<div class="modal fade" id="modalHistorial" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-height: 200%;"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Historial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <span>Lista parametros </span>
                        <div id="divTablaHist">
                          <table id="tablaLoteModal" class="table table-sm">
                              <thead class="thead-dark">
                                  <tr>
                                      <th>Id lote</th>
                                      <th>Fecha lote</th>
                                      <th>Codigo</th>
                                      <th>Parametro</th>
                                      <th>Resultado</th>
                                      <th>His</th>
                                  </tr>
                              </thead>
                              <tbody>
                                      <td>Id lote</td>
                                      <td>Fecha lote</td>
                                      <td>Codigo</td>
                                      <td>Parametro</td>
                                      <td>Resultado</td>
                                      <td>Historial</td>
                              </tbody>
                        </table>
                        </div>
                    </div>
                </div>
     
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="modalImgFoto" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width: 80%">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Foto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="divImagen">

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>




@endsection
    @section('javascript')

    <script type="text/javascript">

	/**get the SigPlusExtLiteWrapperURL attribute from the content script to read the SigPlusExtLite wrapper javascript path*/
	try{
		var url = document.documentElement.getAttribute('SigPlusExtLiteWrapperURL');
		var script = document.createElement('script');
		script.onload = function () {
			ShowVersionInfo();
		};	
		script.onerror = function () {
			alert("Unable to load required SigPlusExtLite extension wrapper. Confirm extension is up-to-date, installed, and enabled, the NMH/SDK is installed, and SigPlus OCX is installed.");
		}
		script.src = url;

		document.head.appendChild(script);
	} catch(ex){
		alert("Unable to load required SigPlusExtLite extension wrapper. Confirm extension is up-to-date, installed, and enabled, the NMH/SDK is installed, and SigPlus OCX is installed.");
	}
		
	async function ShowVersionInfo(){
		try{
			let global = Topaz.Global;
			
			let extVersion = await global.GetSigPlusExtLiteVersion();
			if(extVersion != null){
				var extP = document.getElementById('extVersion');
				extP.innerHTML += "<b>Extension Version: </b>"+extVersion;
			} else {
				alert("Unable to get the extension version. Confirm it is installed and enabled.");
			}
			
			let nmhVersion = await global.GetSigPlusExtLiteNMHVersion();
			if(nmhVersion != null){
				var nmhP = document.getElementById('nmhVersion');
				nmhP.innerHTML += "<b>NMH/SDK Version: </b>"+ nmhVersion;
				
				//Get SigPlusOCX version if able to get nmh version info
				let sigplusVersion = await global.GetSigPlusActiveXVersion();
				if(sigplusVersion != null){
					var sigplusP = document.getElementById('sigplusVersion');
					sigplusP.innerHTML += "<b>SigPlus Version: </b>"+ sigplusVersion;
				} else {
					let lastError = await global.GetLastError();
					if(lastError != null){
						alert("Issue getting SigPlus OCX version information. "+lastError);
					} else {
						alert("Unable to get SigPlus OCX version information");
					}
				}
				
			} else {
				let lastError = await global.GetLastError();
				if(lastError != null){
					alert("Issue getting NMH/SDK version information. "+lastError);
				} else {
					alert("Unable to get NMH/SDK version information");
				}
			}
			
			
		} catch(ex){
			alert("Unable to get version information. Confirm extension is installed and enabled, the NMH/SDK is installed, and SigPlus OCX is installed.");
		}
	}

	var imgWidth;
	var imgHeight;
	async function StartSign()
	 {   
	    var isInstalled = document.documentElement.getAttribute('SigPlusExtLiteExtension-installed');  
	    if (!isInstalled) {
	        alert("SigPlusExtLite extension is either not installed or disabled. Please install or enable extension.");
			return;
	    }	
	    var canvasObj = document.getElementById('cnv');
		canvasObj.getContext('2d').clearRect(0, 0, canvasObj.width, canvasObj.height);
		document.FORM1.sigStringData.value = "SigString: ";
		document.FORM1.sigRawData.value = "Base64 String: ";
		imgWidth = canvasObj.width;
		imgHeight = canvasObj.height;
		try{
			let sign = Topaz.SignatureCaptureWindow.Sign;
			
			sign.SetImageDetails(1,imgWidth, imgHeight, false, false, 0.0);
			sign.SetPenDetails("Black", 1);
			sign.SetMinSigPoints(25);
			await sign.StartSign(false, 1, 0, "");	
			
			let lastError = await Topaz.Global.GetLastError();
			
			if(lastError != null && lastError != ""){
				if(lastError == "The signature does not have enough points to be valid."
					|| lastError == "User cancelled signing."){
					alert("SigPlusExtLite Info: " + lastError)
				} else{
					alert("SigPlusExtLite Error: On Signing - " + lastError);
				}
			} else {	
				var ctx = document.getElementById('cnv').getContext('2d');		
				if (await sign.IsSigned()) 
				{
					let imgData = await sign.GetSignatureImage();
					document.FORM1.sigRawData.value += imgData;
					document.FORM1.sigStringData.value += await sign.GetSigString();
					var img = new Image();
					img.onload = function () 
					{
						ctx.drawImage(img, 0, 0, imgWidth, imgHeight);
					}
					img.src = "data:image/png;base64," + imgData;
                    firma64 = imgData
				}
			}
		} catch(ex){
			console.log(ex);
		}		
    }
	
    function ClearFormData()
	{
	     document.FORM1.sigStringData.value = "SigString: ";
	     document.FORM1.sigRawData.value = "Base64 String: ";
	     document.getElementById('SignBtn').disabled = false;
    }

</script> 
    <script src="{{ asset('public/js/supervicion/cadena/detalleCadena.js') }}?v=1.2.5"></script>
    <script src="{{asset('/public/js/informes/firma.js')}}?v=1.0.0"></script>
@stop

