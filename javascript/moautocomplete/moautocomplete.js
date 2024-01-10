//
//  This script was created
//  by Mircho Mirev
//  mo /mo@momche.net/
//
//	:: feel free to use it BUT
//	:: if you want to use this code PLEASE send me a note
//	:: and please keep this disclaimer intact
//

var cAutocomplete =
{
	sDescription : 'autocomplete class'
}


cAutocomplete.complete = function( hEvent )
{
//	if( hEvent == null ) {
//		var hEvent = window.hEvent
//	}


	var hElement;
	if (!hEvent) 
		var hEvent = window.event;
	if (hEvent.target) 
		hElement = hEvent.target;
	else if (hEvent.srcElement) 
		hElement = hEvent.srcElement;
	if (hElement.nodeType == 3) // defeat Safari bug
		hElement = hElement.parentNode;

//	var hElement = ( hEvent.srcElement ) ? hEvent.srcElement : hEvent.originalTarget
	
	if( hEvent.keyCode == 27 ) { // escape
		hElement.value = '';
		return
	}
//	if( hEvent.keyCode == 9 ) { // tab
//		if(e.preventDefault) {
//			e.preventDefault();
//		}
//		return false;
//	}
//	if( hEvent.keyCode == 13 ) { // Enter
//		hElement.value = 'Enter';
//		return
//	}

	var sAA = hElement.getAttribute( 'autocompleteclass' ).toString()
// Array or List
	if( sAA.indexOf( 'array:' ) >= 0 ) {
		hArr = eval( sAA.substring( 6 ) )
	}
	else if(  sAA.indexOf( 'list:' ) >= 0 ) {
		hArr = sAA.substring( 5 ).split( '|' )
	}

// Enter Key
	if( hEvent.keyCode == 13 )  { // enter
		return
	}

// Shift Key
	if( hEvent.keyCode == 16 )  { // shift
		return
	}
// Get "Typed-In" Characters
	var sVal = hElement.value.toLowerCase()

	if( hEvent.keyCode == 8 ) { // backspace delete
		sVal = sVal.substring( 0, sVal.length - 1 )
		return
	}

	if( sVal.length < 1 ) {
		return
	}

	for( var nI = 0; nI < hArr.length; nI++ ) { // each array element
		sMonth = hArr[ nI ]
		nIdx = sMonth.toLowerCase().indexOf( sVal, 0 )
// If a match is found
		if( nIdx == 0 && sMonth.length > sVal.length ) {
			hElement.value = hArr[ nI ] // Assign value from array to text field
//			hElement.value = sVal+hArr[ nI ].substr(sVal.length) // Assign value from array to text field
// Set selection to remainder of value
			if( hElement.createTextRange ) {
				hRange = hElement.createTextRange()
				hRange.findText( hArr[ nI ].substr( sVal.length ) )
				hRange.select()
			}
			else {
				hElement.setSelectionRange( sVal.length, sMonth.length )
			}
			return
		}
	}

}

cAutocomplete.togglelist = function( hEvent ) {
	var hElement;
	if (!hEvent) 
		var hEvent = window.event;
	if (hEvent.target) 
		hElement = hEvent.target;
	else if (hEvent.srcElement) 
		hElement = hEvent.srcElement;
	if (hElement.nodeType == 3) // defeat Safari bug
		hElement = hElement.parentNode;

	var sAA = hElement.getAttribute( 'autocompleteclass' ).toString()
// Array or List
	if( sAA.indexOf( 'array:' ) >= 0 ) {
		hArr = eval( sAA.substring( 6 ) )
	}
	else if(  sAA.indexOf( 'list:' ) >= 0 ) {
		hArr = sAA.substring( 5 ).split( '|' )
	}

// Get "Typed-In" Characters
	var sVal = hElement.value.toLowerCase()

// Remove Div dropdown
// If Div dropdown is not showing, then create the element, then populate it, then display it.
// remove div from this element's parent, then add a div, then populate div
//	for( var nI = 0; nI < hArr.length; nI++ ) { // each array element
//		var div=document.createElement('DIV');
//		this.append(div);
//	}
}

cAutocomplete.init = function() {
	var nI = 0
	var aInputs = document.getElementsByTagName( 'INPUT' )
	for( var nI = 0; nI < aInputs.length; nI ++ ) {
		if( aInputs[ nI ].type.toLowerCase() == 'text' )
		{
		 	var sLangAtt = aInputs[ nI ].getAttribute( 'autocompleteclass' )
			if( sLangAtt ) {
				if( document.attachEvent ) {
					aInputs[ nI ].attachEvent( 'onkeyup', cAutocomplete.complete )
//					aInputs[ nI ].attachEvent( 'onclick', cAutocomplete.togglelist)
				}
				else if( document.addEventListener ) {
					aInputs[ nI ].addEventListener( 'keyup', cAutocomplete.complete, false )
//					aInputs[ nI ].addEventListener( 'click', cAutocomplete.togglelist, false )
				} 
			}
		}
	}
}

if( window.attachEvent ) {
	window.attachEvent( 'onload', cAutocomplete.init )
}
else if( window.addEventListener ) {
	window.addEventListener( 'load', cAutocomplete.init, false )
}