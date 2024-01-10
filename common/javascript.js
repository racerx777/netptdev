<script type="text/javascript">
// popup picker
function sendValue(s){
	var selvalue = s.options[s.selectedIndex].value;
	window.opener.document.popupform.choice.value = selvalue;
	window.close();
}

function highlightelement(x) {
	document.getElementById(x).style.background = "red";
}

function highlightelementclear(x) {
	document.getElementById(x).style.background = "";
}

function toProperCase(s) {
  return s.toLowerCase().replace(/^(.)|\s(.)/g, 
          function($1) { return $1.toUpperCase(); });
}

function upperCase(x) {
	var y=document.getElementById(x).value;
	document.getElementById(x).value=y.toUpperCase();
}

function lowerCase(x) {
	var y=document.getElementById(x).value;
	document.getElementById(x).value=y.toLowerCase();
}

function properCase(x) {
	var y=document.getElementById(x).value;
	document.getElementById(x).value=toProperCase(y);
}

function displayzip(x) {
	var y=stripChars(document.getElementById(x).value);
	if(y.length == 5 || y.length == 9) {
		highlightelementclear(x);
		if(y.length == 5) {
			y = y.substr(0,5);
		}
		else {
			y = y.substr(0,5) + "-" + y.substr(5,4);
		}
	}
	else {
		if(y.length == 0) {
			if(document.getElementById(x).value != "") {
				y=document.getElementById(x).value;
				highlightelement(x);
			}
			else
				highlightelementclear(x);
		} 
	}
	document.getElementById(x).value=y;
}

function displayphone(x) {
	var y=stripChars(document.getElementById(x).value);
	if(y.length == 7 || y.length == 10) {
		highlightelementclear(x);
		if(y.length == 7) {
			y = "(714) " + y.substr(0,3) + "-" + y.substr(3,4);
		}
		else {
			if(y.length == 10) {
				y = "(" + y.substr(0,3) + ") " + y.substr(3,3) + "-" + y.substr(6,4);
			}
		}
	}
	else {
		if(y.length == 0) {
			if(document.getElementById(x).value != "") {
				y=document.getElementById(x).value;
				highlightelement(x);
			}
			else
				highlightelementclear(x);
		} 
	}
	document.getElementById(x).value=y;
}

function displayssn(x) {
	var y=stripChars(document.getElementById(x).value);
	if(y.length == 9) {
		highlightelementclear(x);
		y = y.substr(0,3) + "-" + y.substr(3,2) + "-" + y.substr(5,4);
	}
	else {
		if(y.length == 0) {
			if(document.getElementById(x).value != "") {
				y=document.getElementById(x).value;
				highlightelement(x);
			}
			else
				highlightelementclear(x);
		} 
	}
	document.getElementById(x).value=y;
}

function stripChars(s){
	var i;
	var returnString = "";
	for (i = 0; i < s.length; i++) {
		var c = s.charAt(i);
		if ( (c >= "0") && (c <= "9") ) 
			returnString += c;
	}
	return returnString;
}

function validateDate(x) {
	var strDate=document.getElementById(x).value;
	var parsedDate = new Array();

	highlightelementclear(x);
	if (strDate.search("/") > 0) {
		parsedDate = strDate.split("/");
	}
	else {
		if (strDate.search("-") > 0) {
			parsedDate = strDate.split("-");
		}
		else {
			if (strDate.search(".") > 0) {
			parsedDate = strDate.split(".");
			}
			else { // No Separators 
				   // could be 
				   // 		length 4 : mdyy
				   // 		length 5 : mmdyy, mddyy
				   // 		length 6 : mmddyy,  mdyyyy
				   // 		length 7 : mmdyyyy, mddyyyy
				   //		length 8 : mmddyyyy
				   //
				   //  Assume 4 is mdyy, 5 is invalid, 6 is mmddyy, 7 is invalid, 8 is mmddyyyy
				strDate = stripChars(strDate);
				if(strDate.length == 4) {
					parsedDate = Array(strDate.substr(0,1), strDate.substr(1,1), '20'+strDate.substr(2,2));
				}
				else {
					if(strDate.length == 6) { // mmddyy
						parsedDate = Array(strDate.substr(0,2), strDate.substr(2,2), '20'+strDate.substr(4,2));
					}
					else {
						if(strDate.length == 8) { // mmddyyyy
							parsedDate = Array(strDate.substr(0,2), strDate.substr(2,2), strDate.substr(4,4));
						}
					}
				}
			}
		}
	}
	if (parsedDate.length == 3) {
		var day, month, year;
		var l0, l1, l2;
		l0 = stripChars(parsedDate[0]);
		l1 = stripChars(parsedDate[1]);
		l2 = stripChars(parsedDate[2]);
		if(l0.length == 4) {
			year = +parsedDate[0];
			month = +parsedDate[1];
			day = +parsedDate[2];
		}
		else {
			month = +parsedDate[0];
			day = +parsedDate[1];
			year = +parsedDate[2];
		}
		newDate = month + "/" + day + "/" + year;
		var objDate = new Date (newDate);
		var m = objDate.getMonth()+1;
		var d = objDate.getDate();
		var y = objDate.getFullYear();
		if (month==m && day == d) {
			highlightelementclear(x);
			document.getElementById(x).value = objDate.getMonth()+1 + "/" + objDate.getDate() + "/" + objDate.getFullYear();
			return(true);
		}
	}
	else {
		if(parsedDate.length == 0) {
			if(document.getElementById(x).value == "") {
				highlightelementclear(x);
				return(true);
			}
		}
	}
	highlightelement(x);
	return false;
}

function phoneFormat(x){
	var Phone=document.getElementById(x);
	if ((Phone.value==null)||(Phone.value=="")){
		alert("Please Enter your Phone Number");
		Phone.value="";
		return false;
	}
	PhoneStr = stripChars(Phone.value);
	if (PhoneStr.length < 10) {
		alert("Phone Number must be at least 10 digits");
		return false;
	}
	else {
		var areacode = PhoneStr.substr(0,3);
		var prefix = PhoneStr.substr(3,3);
		var exchange = PhoneStr.substr(6,4);
		var extension = "";
		if(PhoneStr.length > 10) {
			extension = " Ext." + PhoneStr.substring(11);
		}
		document.getElementById(x).value = "(" + areacode + ") " + prefix + "-" + exchange + extension;
	}
	return true
 }

function emailFormat(x){
	var email=document.getElementById(x);
	if ( (email.value.indexOf(".") > 2) && (email.value.indexOf("@") > 0) )	{
		document.getElementById(x).value = email.value.toLowerCase();
		return true;
	}
	else {
		alert("Please enter a valid email address.");
		document.getElementById(x).value="";
		email.focus()
		return false
	}
 }

function trim(s) {
   return s.replace(/^\s+|\s+$/g,'');
}

function isEmail(str) {
   var regex = /^[-_.a-z0-9]+@(([-_a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i;
	return regex.test(str);
}

function checkAddBarForm() {
	var cuser, cname, cemail, cclinic, chomepage, crole;
	with(window.document.addBarForm) {
		cuser		= user;
		cname		= name;
		cemail		= email;
		cclinic		= clinic;
		chomepage	= homepage;
		crole		= role;

		cuser.value		= trim(user.value);
		cname.value		= trim(name.value);
		cemail.value	= trim(email.value);
		cclinic.value	= trim(clinic.value);
		chomepage.value	= trim(homepage.value);
		crole.value		= trim(role.value);
   }

   if(cuser.value == '') {
	   alert('Please enter a value for user');
	   cuser.focus();
      return false;
   }
   else if(cname.value == '') {
	   alert('Please enter a value for name');
	   cname.focus();
      return false;
   }
   else if(cemail.value == '') {
      alert('Please enter a value for email');
      cemail.focus();
      return false;
   }
   else if(!isEmail(cemail.value)) {
      alert('Please enter a valid E-mail address');
      cemail.focus();
      return false;
   }
   else if(cclinic.value == '') {
      alert('Please select a value for clinic');
      cclinic.focus();
      return false;
   }
   else if(chomepage.value == '') {
      alert('Please select a value for homepage');
      chomepage.focus();
      return false;
   }
   else if(crole.value == '') {
      alert('Please select a value for role');
      crole.focus();
      return false;
   }
   else {
	   return true;
   }
}
</script>
<script type="text/javascript" src="/javascript/calendarpopup/combinedcompact/CalendarPopup.js">
</script> 
<script type="text/javascript" src="/common/sorttable.js">
</script> 