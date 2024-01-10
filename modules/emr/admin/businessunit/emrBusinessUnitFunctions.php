<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$dbselect = dbselect($dbhandle);



function cleanArray($array) {
	if(is_array($array)) {

		foreach($array as $field=>$value) {
			if(is_array($value)) { 
				$array["$field"]=cleanArray($value);

			}
			else {
				$array["$field"]=mysql_real_escape_string(htmlspecialchars($value));
			}
		}


		return($array);
	}
	else {// return the cleaned string as an array
		return(array(0=>mysql_real_escape_string(htmlspecialchars($array))));
	}
}




$test1='Regular String';
$test2='String with html <html><head></head><body><a href="test">Test</a></body></html>';
$test3=array(1,2,3,4,5,6,'&','"',"'",'<','>');
$test4=array('A','B','C','D','E','F');
$test5=array('1,2,3','1-2-3','1$2$3','1/2/3','1\2\3','<>?":{}|_+,./;[]\-==');
$test6=array('test1'=>$test1,'test2'=>$test2,'test3'=>$test3,'test4'=>$test4,'test5'=>$test5);
$cleanedarray=cleanArray($test6);
dumpcode('cleanedarray',$cleanedarray);

function emrAddBusinessUnitAction($buarray) { // normally $_POSTed data
	$cleanbuarray=cleanArray($buarray);
// Validation
// check to make sure both fields are entered
	if ($firstname == '' || $lastname == '') 
		error('999','ERROR: Please fill in all required fields!');

	if(errorcount()==0) {
// save the data to the database
		if($result=mysql_query("INSERT players SET firstname='$firstname', lastname='$lastname'") )
			notify('000','ok');
		else
			error("999","Error inserting business unit preference"); 
	}
}

function emrAddBusinessUnitForm() {
// Display Add Business Unit Preferences Form
// Allow Cancel/Confirm Add
}

function emrEditBusinessUnitAction($bumcode) {
}

function emrEditBusinessUnitForm($bumcode) {
}

function emrDeleteBusinessUnitAction($bumcode) {
}

function emrDeleteBusinessUnitForm($bumcode) {
}
