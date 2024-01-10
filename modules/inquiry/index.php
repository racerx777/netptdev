<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(5); 
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
switch($button):
	case 'Search Cases':
		$thisapplication="Inquiry";
		$thisform="searchBarForm";
		clearformvars($thisapplication, $thisform);
		$search=array('paid'=>$_SESSION['id']);
		setformvars($thisapplication, $thisform, $search);
		break;
endswitch;
// Output begins here
displaysitemessages();

switch($button):
	case 'View Patient':
		require_once('viewPatient.php');
		break;
	case 'View Case':
		require_once('viewCase.php');
		break;
	default:
		require_once('searchBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
?>
