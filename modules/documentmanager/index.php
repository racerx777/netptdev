<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(99); 
session_set_cookie_params(60*60);

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

//// ACTIONS
switch($button):
	case 'Cancel':
	case 'Exit':
		notify("000","Action canceled.");
		unset($id);	
		unset($button);	
		unset($_SESSION['id']);	
		unset($_SESSION['button']);	
		break;
	case 'View':
//		is a javascript popup window for viewing report pdf files.
		break;
	case 'Confirm Add Document Record':
		require_once('documentmanagerFunctions.php');
		addDocumentRecord($id); // document id
		break;
	case 'Confirm Update Document Record':
		require_once('documentmanagerFunctions.php');
		updateDocumentRecord($id); // document id
		break;
	case 'Confirm Delete Document Record':
		require_once('documentmanagerFunctions.php');
		deleteDocumentRecord($id); // document id
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
		require_once('documentmanagerFunctions.php');
		addDocument($id); // document id
		break;
	case 'Edit':
		require_once('documentmanagerFunctions.php');
		editReport($id); // document id
		break;
	case 'Delete':
		require_once('documentmanagerFunctions.php');
		deleteReport($id); // document id
		break;
	default:
		require_once('searchBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
?>
