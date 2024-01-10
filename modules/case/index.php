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
	case 'Back':
	case 'Cancel':
		$searchcaseSaved = getformvars('customerservice', 'searchcase');
		$sortcaseSaved = getformvars('customerservice', 'searchcaseResults');
		$_POST['formSubmit']="1";
		break;
	case 'Add Case':
		// Insert Code - Insert record and update status array
		require_once('SQLInsert.php');
		break;
	case 'Update Case':
		// Update Code - Update record and update status array
		require_once('SQLUpdate.php');
		break;
	case 'Reschedule':
		require_once('SQLUpdateFunctions.php');
//		caseupdatestatuscode($_SESSION['id'], 'NEW');
//		schedulingaddcall($_SESSION['id']);
		casereschedule($_SESSION['id']);
		break;
	case 'Requires Authorization':
		require_once('SQLUpdateFunctions.php');
		caserequiresauthorization($_SESSION['id']);
//		schedulingaddcall($_SESSION['id']);
//		caseupdatestatuscode($_SESSION['id'], 'PEA');
// Write out a new Prescription Record and copy default values and set prescription status to NEW
//		prescriptionadd($_SESSION['id']);
		unset($_SESSION['button']);
		break;
	case 'To Scheduling':
		require_once('SQLUpdateFunctions.php');
		schedulingaddcall($_SESSION['id']);
		caseupdatestatuscode($_SESSION['id'], 'PEN');
// Write out a new Prescription Record and copy default values and set prescription status to NEW
//		prescriptionadd($_SESSION['id']);
		unset($_SESSION['button']);
		break;
	case 'Seen':
		require_once('SQLUpdateFunctions.php');
		caseupdatestatuscode($_SESSION['id'], 'ACT');
		schedulingaddcall($_SESSION['id']);
		break;
	case 'Confirm Cancel Referral':
		require_once('SQLUpdateFunctions.php');
		casecancel($_SESSION['id'], 'CAN', $_POST['crcancelreasoncode']);
		break;
	case 'Delete Referral':
		// Delete Code delete record update status array
		require_once('SQLUpdateFunctions.php');
		casedelete($_SESSION['id']);
		break;
	case 'Confirm Add Note':
		require_once('caseSchedulingHistoryAddNoteForm.php');
		break;
endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):
	case 'Edit Case':
	case 'Add':
		// Edit code - get record $id and display in the add form
		require_once('editForm.php');
		break;
	case 'Cancel Referral':
		require_once('cancelForm.php');
		break;
	case 'Cancel Reason Report';
		require_once('printCancelReasonReportSelection.php');
		break;
	case 'Add Note':
		require_once('caseSchedulingHistoryAddNoteForm.php');
		break;
	default:
		require_once('addBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
?>