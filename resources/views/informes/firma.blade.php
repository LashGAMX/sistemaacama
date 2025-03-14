
<!DOCTYPE html>
<!-- saved from url=(0057)https://sigplusweb.com/sign_chrome_ff_sigplusextlite.html -->
<html sigplusextliteextension-installed="true" sigwebext-installed="true"><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title></title>     
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
</head>
<body onload="ClearFormData();">
    <table border="1" cellpadding="0" width="500">
        <tbody><tr>
            <td height="100" width="500">
                <canvas id="cnv" name="cnv" width="500" height="100"></canvas>
            </td>
        </tr>
    </tbody></table>
    <br>	
	<p id="extVersion"></p>
	<p id="nmhVersion"></p>
	<p id="sigplusVersion"></p>
	<br>
    <form action="https://sigplusweb.com/sign_chrome_ff_sigplusextlite.html#" name="FORM1">
		<p>
			<input id="SignBtn" name="SignBtn" type="button" value="Sign" onclick="StartSign()">&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="HIDDEN" name="bioSigData">
			<input type="HIDDEN" name="sigImgData">
			<br>
			<br>
			<textarea name="sigStringData" rows="20" cols="50">SigString: </textarea>
			<textarea name="sigRawData" rows="20" cols="50">Base64 String: </textarea>
		</p>
	</form>
	<br><br>



</body></html>