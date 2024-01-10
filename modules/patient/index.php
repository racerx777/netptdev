<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(12); 
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
// ACTIONS

switch($button):
	case 'Discharge w/Report':
//	Display Add Treatment form for Discharge Date and Therapy Type
		break;
	case 'Discharge w/o Report':
//	Display Add Treatment form for Discharge Date and Therapy Type
		break;
	case 'Print WC English':
		$id=$_SESSION['id'];
		require_once('patientPrintFormsFunctions.php');
		patientPrintIntakeForms($id, 'WC', 'English');
		break;
	case 'Print WC Spanish':
// Display Forms Available and Language : Spanish or English
// For each selected document:
// Generate PDF Documents for New Patient Chart
// Save it in database patients_forms (for future re-print)
		break;
	case 'Print PI English':
// Display Forms Available and Language : Spanish or English
// For each selected document:
// Generate PDF Documents for New Patient Chart
// Save it in database patients_forms (for future re-print)
		break;
	case 'Print PI Spanish':
// Display Forms Available and Language : Spanish or English
// For each selected document:
// Generate PDF Documents for New Patient Chart
// Save it in database patients_forms (for future re-print)
		break;
	case 'Print RA English':
// Display Forms Available and Language : Spanish or English
// For each selected document:
// Generate PDF Documents for New Patient Chart
// Save it in database patients_forms (for future re-print)
		break;
	case 'Print RA Spanish':
// Display Forms Available and Language : Spanish or English
// For each selected document:
// Generate PDF Documents for New Patient Chart
// Save it in database patients_forms (for future re-print)
		break;
endswitch;

// Output begins here
displaysitemessages();

switch($button):
	case 'View Chart':
		require_once('chartFunctions.php');
		viewChart($_SESSION['id']);
		break;
	default:
		if(userlevel()>=13) 
			require_once('searchBarForm.php');
		else {
// default is to list all scheduled patients
			$thisapplication="Patients";
			$thisform="searchBarForm";
			$_POST['search']=array("crcasestatuscode"=>"SCH");
			setformvars($thisapplication, $thisform, $_POST['search']);
			echo '<div style="clear:both;">&nbsp;</div>';
		}
		require_once('searchResultsForm.php');
		break;
endswitch;
?>
