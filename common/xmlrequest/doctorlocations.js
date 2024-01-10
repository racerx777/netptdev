var xmlhttp;

function showDoctor(str) {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null) {
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="/common/xmlresponse/doctorlocations.php";
	url=url+"?q="+str;
	url=url+"&sid="+Math.random();
	xmlhttp.onreadystatechange=stateChanged;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}

function stateChanged() {
	if (xmlhttp.readyState==4) {
		xmlDoc=xmlhttp.responseXML;
		foreach(xmlhttp.responseXML);
			document.getElementById("dmid").innerHTML=xmlDoc.getElementsByTagName("dmid")[0].childNodes[0].nodeValue;
			document.getElementById("dmlname").innerHTML=xmlDoc.getElementsByTagName("dmlname")[0].childNodes[0].nodeValue;
			document.getElementById("dmfname").innerHTML=xmlDoc.getElementsByTagName("dmfname")[0].childNodes[0].nodeValue;
			document.getElementById("dlid").innerHTML=xmlDoc.getElementsByTagName("dlid")[0].childNodes[0].nodeValue;
		if(1!=1)
			document.getElementById("dlcity").innerHTML=xmlDoc.getElementsByTagName("dlcity")[0].childNodes[0].nodeValue;
		if(xmlDoc.getElementsByTagName("dlphone")[0].childNodes[0].length != 0)
			document.getElementById("dlphone").innerHTML=xmlDoc.getElementsByTagName("dlphone")[0].childNodes[0].nodeValue;
	  }
}

function GetXmlHttpObject() {
	if (window.XMLHttpRequest) {
// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject) {
// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}