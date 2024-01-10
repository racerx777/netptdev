<script language="JavaScript" type="text/JavaScript">
<!--
var type = "IE";	//Variable used to hold the browser name

DetectBrowser();

//detects the capabilities of the browser
function DetectBrowser() {
	if (navigator.userAgent.indexOf("Opera")!=-1 && document.getElementById) type="OP";		//Opera
	else if (document.all) type="IE";														//Internet Explorer e.g. IE4 upwards
	else if (document.layers) type="NN";													//Netscape Communicator 4
	else if (!document.all && document.getElementById) type="MO";							//Mozila e.g. Netscape 6 upwards
	else type = "IE";		//I assume it will not get here
}

//Puts the contents of str into the layer id
//id is the name of the layer
//str is the required content
//Works with all browsers except Opera
function SetInnerHTMLbyId(id, str) {
	if (type=="IE") {
		document.all[id].innerHTML = str;
	}
	if (type=="NN") { 
		document.layers[id].document.open();
		document.layers[id].document.write(str);
		document.layers[id].document.close();
	}
	if (type=="MO" || type=="OP") {
		document.getElementById(id).innerHTML = str;
	}
}

//Change the color of the layer background
//id is the name of the layer
//color is the required color
//Works with all browsers except NN4
function SetLayerBgColorById(id, color){
	if (type=="IE") document.all[id].style.backgroundColor=color;
	if (type=="NN") document.layer['id'].bgColor=color;
	if (type=="MO" || type=="OP") document.getElementById(id).style.backgroundColor=color;
}

//Show and hide a layer
//id is the name of the layer
//action is either hidden or visible
//Seems to work with all versions NN4 plus other browsers
function ShowLayer(id, action){
	if (type=="IE") eval("document.all." + id + ".style.visibility='" + action + "'");
	if (type=="NN") eval("document." + id + ".visibility='" + action + "'");
	if (type=="MO" || type=="OP") eval("document.getElementById('" + id + "').style.visibility='" + action + "'");
}
//-->
</script>
