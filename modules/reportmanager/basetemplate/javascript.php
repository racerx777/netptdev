<script>var cal = new CalendarPopup();</script>
<script language="javascript" src="/javascript/x_core.js"></script>
<script language="javascript" src="/javascript/ylib.js"></script>
<script language="javascript" src="/javascript/y_Tabs.js"></script>
<script language="javascript" src="/javascript/autosave.js"></script>
<script src="/javascript/moautocomplete/moautocomplete.js"> </script>
<script language="javascript">
	var arrayHeaders  = ["tab-patient","tab-doctor","tab-subjective", "tab-objective", "tab-assessment", "tab-treatment", "tab-therapist"];
	var arrayContents = ["patient","doctor","subjective","objective","assessment","treatment","therapist"]; 
	var tracker = "tracker";
	window.onload = function() {
		var elem=document.getElementById(this.tracker);
		if(elem)
			var tabRelationship = new ylib.widget.Tabs(arrayHeaders, arrayContents, elem.value, tracker);
		else
			var tabRelationship = new ylib.widget.Tabs(arrayHeaders, arrayContents, null, tracker);
		AutoSaveInit(300000*3);
	}
</script>

<?php
// output javascript from config-include.php
echo implode('',$configjs);
?>
<script language=JavaScript>
function  SubmitFormToBeSaved() {
	var savebuttonform=document.getElementById('Save');
	var savebutton = document.createElement('input');
	savebutton.type='hidden';
	savebutton.name=savebuttonform.name;
	savebutton.value='Save';
	document.SoapForm.appendChild(savebutton);
	document.SoapForm.submit();
}

function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}
 
function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
 
function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
</script>

<script type="text/javascript">
function scrollX() {return window.pageXOffset ? window.pageXOffset : document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft;}
function scrollY() {return window.pageYOffset ? window.pageYOffset : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;}

function helperopen(id) {
	var helpers=document.getElementsByClassName('helper');
	for(i=0;i<helpers.length;i++) {
		var helperid=helpers[i].id+'_helper';
		var helperdiv = document.getElementById(helperid);
		var name1=helpers[i].id;
//		var name2=id.id;
		var name2=id;
//		if(helpers[i].id != id) {
		if(name1 != name2) {
			helperdiv.style.display="none";
		}
		else {
			if(helperdiv.style.display=="none") {
				helperdiv.style.display="block";
			}
			else {
				helperdiv.style.display="none";
			}
		}
	}
}

function helperclose(id) {
	var helperid=id+'_helper';
	var div = document.getElementById(helperid);
	div.style.display="none";
}

function helpercloseall(id) {
	var helpers=document.getElementsByClassName('helper');
	for(i=0;i<helpers.length;i++) {
		var helperid=helpers[i].id+'_helper';
		var helperdiv = document.getElementById(helperid);
		var name1=helpers[i].id;
//		var name2=id.id;
		var name2=id;
//		if(helpers[i].id != id) {
		if(name1 != name2) {
			helperdiv.style.display="none";
		}
//		else {
//			if(helperdiv.style.display=="none") {
//				helperdiv.style.display="block";
//			}
//			else {
//				helperdiv.style.display="none";
//			}
//		}
	}
}

function insertAtCursor(myField, myValue) {
  //IE support
  if (document.selection) {
    myField.focus();
    sel = document.selection.createRange();
    sel.text = myValue;
  }
  //MOZILLA/NETSCAPE support
  else if (myField.selectionStart || myField.selectionStart == '0') {
    var startPos = myField.selectionStart;
    var endPos = myField.selectionEnd;
    myField.value = myField.value.substring(0, startPos)
                  + myValue
                  + myField.value.substring(endPos, myField.value.length);
	myField.selectionStart+=myValue.length;
  } else {
    myField.value += myValue;
  }
  
}

function getIdFromHelperId(helperid) {
	return(helperid.substr(0,helperid.indexOf("_helper")));
}

function getElementFromHelperId(helperid) {
	return(document.getElementById(getIdFromHelperId(helperid)));
}

function updatetextbox(helperid) {
	var helper = document.getElementById(helperid);
	var myelement = getElementFromHelperId(helperid);
	insertAtCursor(myelement, helper.innerHTML);
	myelement.focus();
}

function cleartextbox(helperid) {
	var myelement = getElementFromHelperId(helperid);
	myelement.value="";
}

function autocompleteupdate(id) {
	var inputelem=document.getElementById(id);
	if(inputelem) {
		var popupelem=document.getElementById(id+'_popup');
		if(popupelem) {
			inputelem.value=popupelem.value;
		}
	}
	autocompletepopdown(id);
	return(true);
}

function autocompletepopdown(id) {
	var helperdiv=document.getElementById(id+'_popup');
	if(helperdiv)
		helperdiv.style.display="none";
	return(true);
}

function autocompletepopdownall() {
	var helperdivs=document.getElementsByTagName("select");
	if(helperdivs) {
		if(helperdivs.length > 0) {
			for(i=0; i<=helperdivs.length; i++) {
				if(helperdivs.className=='popup')
					helperdivs[i].style.display="none";
			}
		}
	}
	return(true);
}

function autocompletepopup(id) {
	var inputdiv=document.getElementById(id);
	var newwidth=Math.round(inputdiv.scrollWidth*1.2,0);
	if(inputdiv) {
		var helperdiv=document.getElementById(id+'_popup');
		if(helperdiv) {
			if(helperdiv.length > 0) {
				if(helperdiv.style.display!='block') {
					helperdiv.style.position="absolute";
					helperdiv.style.display="block";
					helperdiv.style.zIndex=9999;
					inputdiv.onblur="";
					helperdiv.focus();
				}
				else {
					helperdiv.style.position="absolute";
					helperdiv.style.display="none";
					helperdiv.style.zIndex=0;
					inputdiv.onblur="autocompletepopdown(this.id)";
					inputdiv.focus();
				}
			}
		}
	}
	return(true);
}
</script>