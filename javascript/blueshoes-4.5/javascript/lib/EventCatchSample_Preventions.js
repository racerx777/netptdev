<!--  
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!! Remove This Text if this code is in use !!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!! This is a *sample* on how to catch events in IE and Mozilla
!! It prevents (or makes it difficult to) copy/print the contens of the 
!! page by trying to prevent you from
!!  - using the control keys
!!  - using the right mouse buton
!!  - selecting text
!!  - printing (?)
  
var isNetscape = (navigator.appName.indexOf("Netscape") !=-1);

/**
* Control-Key prevention
*/
function preventControlKeys(e) {
	var keyHit = (isNetscape) ? e.which : event.keyCode;
	switch(keyHit) {
		case 0: { // Modifier
			window.location="";
			return false;
			}
		case 17: { // Control Key
			window.location="";
			return false;
			}
		case 91: { // Command
			window.location="";
			return false;
			}
		case 93: { // Context Menu 
			window.location="";
			return false;
			}
		}
}

function printdetection(e) {
	switch (e) {
		case 1: alert("onPrint");
		case 2: alert("window.print");
		case 3: alert("document.print");
	}
}
	

/**
* Right mouse click prevention
*/
function preventRightMouse(e) {
  if (document.all) {
    if (event.button == 2) { 
		  window.location="";
      return false;
    }
  }
  if (document.layers) {
    if (e.which == 3) {
		  window.location="";
      return false;
    }
  }
  return false;
}

/**
* Prevent Text selection
*/
function preventTextSelection() {
	var txt = ""
	if (document.getSelection) {
		txt = document.getSelection();
	}
	else if (document.selection) {
		txt = document.selection.createRange().text;
	}
	else return;

	if (txt!="") {
		window.location=window.location;
	}
	return;
}

/**
* In Netscape we have to turn on the events we intend to capture
*/
if (document.layers) {
  document.captureEvents(Event.MOUSEDOWN);
  document.captureEvents(Event.MOUSEUP);
  document.captureEvents(Event.BEFOREPRINT);
  document.captureEvents(Event.KEYDOWN);
}

document.onclick=preventTextSelection;
document.onmousedown=preventRightMouse;
document.onmouseup=preventRightMouse;preventTextSelection;  // interessting assignment: 2 functions.
document.onkeydown=preventControlKeys;
// --> 