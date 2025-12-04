@extends('voyager::master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
               
                <div class="col-md-6">
                  

                    <div class="form-group">
                        <input type="month" id="monthInput" class="form-control form-control-sm">
                        <button id="searchButton" class="btn btn-sm btn-success">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>

                </div>
                <div class="col-md-6">
                    <p hidden id="extVersion"></p>
                    <p hidden id="nmhVersion"></p>
                    <p hidden id="sigplusVersion"></p>
                    <form action="https://sigplusweb.com/sign_chrome_ff_sigplusextlite.html#" name="FORM1"
                        style="display: flex">
                        <p>
                            <!-- <input > -->
                            <button id="SignBtn" name="SignBtn" type="button" value="Sign"
                                onclick="StartSign()">Firmar<i class="fas fa-pen-nib"></i></button>
                            <button id="btnFirma" name="btnFirma" type="button">Guardar<i
                                    class="fas fa-save"></i></button>
                            <input type="HIDDEN" name="bioSigData">
                            <input type="HIDDEN" name="sigImgData">
                            <textarea hidden name="sigStringData" rows="20" cols="50">SigString: </textarea>
                            <textarea hidden name="sigRawData" rows="20" cols="50"></textarea>
                        </p>
                    </form>
                    <table border="1" cellpadding="0" width="500">
                        <tbody>
                            <tr>
                                <td height="100" width="500">
                                    <canvas id="cnv" name="cnv" width="500" height="100"></canvas>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
              
                <div class="col-md-12">
                    <div class="row">
                        <!-- Primera sección de tablas -->
                        <div class="col-md-4">
                            <div style="width: 100%; overflow:scroll">
                                <table id="tableServicios" class="table" style="width: 100%; font-size: 10px;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>Norma</th>
                                            <th>Tipo servicio</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ContenidoServicios">
                                        @foreach ($model as $item)
                                        <tr onclick="getPuntoMuestro({{$item->Id_solicitud}})">
                                            <td>{{$item->Id_solicitud}}</td>
                                            <td>{{$item->Folio}}</td>
                                            <td>{{$item->Empresa}}</td>
                                            <td>{{$item->Clave_norma}}</td>
                                            <td>{{$item->Servicio}}</td>
                                            <td>{{$item->Obs_proceso}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Segunda seccion de tablas -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="puntoMuestreo">Punto de muestreo</label>
                                        <div id="selPuntos">
                                            <select class="form-control" id="puntoMuestreo">
                                                <option value="">Puntos Muestreo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipoReporte">Tipo de reporte</label>
                                        <select class="form-control" id="tipoReporte">
                                            <option value="2">Sin Comparación</option>
                                            <option value="1">Con comparación</option>
                                            <option value="11">Nuevo informe</option>
                                            <option value="4">Cadena de custodia</option>
                                            <option value="3">Campo</option>
                                            <option value="5">Vibrio fischeri</option>
                                            <option value="6">Informe Add</option>
                                            <option value="7">Hoja Campo Add</option>
                                            <option value="8">Ebenhoch Sin</option>
                                            <option value="9">Ebenhoch Solo</option>
                                            <option value="10">Custodia Interna Vidrio</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="nota">Nota 4</label>
                                    <input type="checkbox" id="nota">
                                    <button id="btnNota" style="border: none;background: none;"><i
                                            class="text-danger voyager-exclamation"></i></button>
                                    <button class="btn btn-info" id="btnImprimir"><i class="voyager-cloud-download"></i>
                                        Descargar</button>
                                </div>
                            </div>

                            <div style="width: 100%; overflow:scroll">
                                <div id="divServicios">
                                    <table id="tablaParametro" class="table" style="width: 100%; font-size: 10px">
                                        <thead>
                                            <tr>
                                                <th>Norma</th>
                                                <th style="width: 30%;">Parametro</th>
                                                <th>Unidad</th>
                                                <th>Resultado</th>
                                                <th>Incertidumbre</th>
                                                <th>Límite Pd</th>
                                                <th>Opc</th>
                                                <!-- Iniciales de quien lo analizó -->
                                            </tr>
                                        </thead>
                                        <tbody id="datosTablaParametro">
                                            <!-- <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
<script src="{{asset('/public/js/informes/firma.js')}}?v=1.0.0"></script>

<script src="{{asset('/public/js/informes/informes.js')}}?v=1.0.6"></script>


@stop