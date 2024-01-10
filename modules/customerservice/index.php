<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
// ACTIONS
switch($_SESSION['button']):
	case 'Cancel':
	case 'Clear':
		$searchSaved = getformvars('customerservice', 'search');
		$sortSaved = getformvars('customerservice', 'searchResults');
		$_POST['formSubmit']="1";
		break;
	case 'Add Patient':
		// Insert Code - Insert record and update status array
		require_once('SQLInsert.php');
		break;
	case 'Make Inactive':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		patientupdateinactivate($_SESSION['id']);
		break;
	case 'Make Active':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		patientupdateinactivate($_SESSION['id']);
		break;
	case 'Update Patient':
		// Update Code - Update record and update status array
		require_once('SQLUpdate.php');
		break;
	case 'Delete Patient':
		// Delete Code delete record update status array
		require_once('SQLUpdateFunctions.php');
		patientdelete($_SESSION['id']);
		break;

endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):
	case 'Edit Patient':
		// Edit code - get record $id and display in the add form
		require_once('editForm.php');
		break;
	case 'Add':
		// Add code - display in the add form
		$_POST = getformvars('customerservice', 'search');
		unset($_SESSION['id']);
		require_once('editForm.php');
		break;
	case 'Patient Status Report';
		require_once('printPatientStatusReportSelection.php');
		break;
	default:
		require_once('addBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
?>