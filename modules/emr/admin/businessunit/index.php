<?php
// Administrator Access Level 90
// Handles Add/Update Business Unit Preferences
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(90); 
session_set_cookie_params(120*60);
require_once('businessunitfunctions.php');

$id='';
$button='';

if(!empty($_SESSION['button']))  {
	if(is_array($_SESSION['button'])) {
		foreach($_SESSION['button'] as $key=>$value)  {
			$id = $key;
			$button = $value;
		}
	}
	else {
		$id = $_SESSION['id'];
		$button = $_SESSION['button'];
	}
}

// handle magic quotes for the report arrays
if(get_magic_quotes_gpc) {
	if(isset($_POST['report'])) {
//		notify("000","Stripping slashes.");
		require_once('reportGeneratorFunctions.php');
		$_POST['report']=stripslashes_deep($_POST['report']);
	}
}

// PRE-DISPLAY ACTIONS
switch($button):
	case 'Cancel':
	case 'Exit':
		notify("000","Action canceled.");
		unset($id);	
		unset($button);	
		unset($_SESSION['id']);	
		unset($_SESSION['button']);	
		break;
endswitch;

// Output begins here
displaysitemessages();

switch($button):
	case 'Add':
		emrAddBusinessUnit();
		break;
	case 'Edit':
		emrEditBusinessUnit($id);
		break;
	case 'Delete':
		emrDeleteBusinessUnit($id);
		break;
	default:
		require_once('searchBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
?>
