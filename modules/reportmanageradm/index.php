<?php
// Administrator Access Level 99
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(99); 

$id='';
$button='';
//$_SESSION['button']=$_POST['button']; // temporary

if(!empty($_SESSION['button']))  {
	if(is_array($_SESSION['button'])) {
		dump("SESSION button",$_SESSION['button']);
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

//dump("id",$id);
//dump("button",$button);

//// ACTIONS
switch($button):
	case 'Cancel':
		notify("000","No action taken.");
		unset($id);	
		unset($button);	
		unset($_SESSION['id']);	
		unset($_SESSION['button']);	
		break;
	case 'Make Active':
	case 'Make Inactive':
		require_once('templateGeneratorFunctions.php');
		toggleTemplateStatus($id); // rthid
		break;
	case 'Yes, Delete Template Section':
		require_once('templateGeneratorFunctions.php');
		deleteTemplateSection($button, $id) ; // rtdid
		break;
	case 'Confirm Add Template':
		require_once('templateGeneratorFunctions.php');
		writeTemplateAdd() ; 
		unset($id);	
		unset($button);	
		unset($_SESSION['id']);	
		unset($_SESSION['button']);	
		break;
endswitch;

// Output begins here

displaysitemessages();

switch($button):
	case 'Add Template':
		require_once('templateAddForm.php');
		break;
	case 'Add Section':
		require_once('templateSectionAddForm.php');
		break;
	case 'Edit Template':
		require_once('templateGeneratorFunctions.php');
		editTemplate($id); // rthid
		break;
	case 'Edit Section':
		require_once('templateGeneratorFunctions.php');
		editTemplateSection($id); // rtdid
		break;
	case 'Delete Section':
		require_once('templateGeneratorFunctions.php');
		deleteTemplateSection($button, $id) ; // rtdid
		break;
	default:
		require_once('searchBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
?>
