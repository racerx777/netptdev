<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
// ACTIONS
$phone1=$_POST['phone1'];
$phone2=$_POST['phone2'];
$phone3=$_POST['phone3'];
$phonedoc=$_POST['phonedoc'];

//if(count($phone3)>0) {
//	$_SESSION['button']=$phone3[$_SESSION['id']];
//}
//if(count($phone2)>0) {
//	$_SESSION['button']=$phone2[$_SESSION['id']];
//}
$result=array();
if(count($phone1)>0) {
	if(!empty($phone1[$_SESSION['id']]))
		$result[]=$phone1[$_SESSION['id']];
}
if(count($phone2)>0) {
	if(!empty($phone2[$_SESSION['id']]))
		$result[]=$phone2[$_SESSION['id']];
}
if(count($phone3)>0) {
	if(!empty($phone3[$_SESSION['id']]))
		$result[]=$phone3[$_SESSION['id']];
}
if(count($result)>0) {
	$_SESSION['button']=$result[0];
}

switch($_SESSION['button']):
	case 'Cancel':
	case 'Clear':
		$searchSaved = getformvars('scheduling', 'search');
		$sortSaved = getformvars('scheduling', 'searchResults');
		$_POST['formSubmit']="1";
		break;
	case 'Add':
		// Insert Code - Insert record and update status array
		require_once('SQLInsert.php');
		break;
	case 'Make Inactive':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		appointmentupdateinactivate($_SESSION['id']);
		break;
	case 'Make Active':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		appointmentupdateinactivate($_SESSION['id']);
		break;
	case 'Update':
		// Update Code - Update record and update status array
		require_once('SQLUpdate.php');
		break;
	case 'Update Patient':
		// Update Code - Update record and update status array
		require_once('SQLUpdatePatient.php');
		break;
	case 'Busy':
	case 'No Answer':
	case 'Ans Mach':
	case 'Confirm Callback Referral':
	case 'Confirm Schedule Referral':
	case 'Confirm Cancel Referral':
		require_once('SQLUpdateFunctions.php');
		casebuttonaction($_SESSION['id'], $_SESSION['button']);
		break;
	case 'Delete':
		// Delete Code delete record update status array
		require_once('SQLUpdateFunctions.php');
		appointmentdelete($_SESSION['id']);
		break;
endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):
	case 'Edit Patient':
		// Edit code - get record $id and display in the add form
		require_once('editPatientForm.php');
		break;
	case 'Cancel':
		require_once('cancelForm.php');
		break;
	case 'Callback':
		require_once('callbackForm.php');
		break;
	case 'Schedule':
		require_once('scheduleForm.php');
		break;
	case 'Add Appointment':
		// Add code - display in the add form
		$_POST = getformvars('scheduling', 'search');
		unset($_SESSION['id']);
		require_once('editForm.php');
		break;
	case 'Scheduling Performance Report';
		require_once('printSchedulingPerformanceReportSelection.php');
		break;

//	case 'Place Call':
//		require_once('callForm.php');
//		break;

	case 'Schedule patient':
		require_once('scheduleForm.php');
		break;

	default:
//		require_once('addBarForm.php');
//		scheduling defaults to absolute queue sort order
//		list cases where status is in Scheduling and Not Cancelled
//		order by priority - Haven't Contacted
//				- Contacted but not scheduled
//		$defaultqueuesearch=array(
//							"crinactive"=>0,
//							"crcasestatuscode"=>'Pen'
//							);
//		$defaultqueuesort=array();
//		$searchSaved = setformvars('scheduling', 'search', $defaultqueuesearch);
//		$sortSaved = setformvars('scheduling', 'searchResults', $defaultqueuesort);
//		require_once('searchResultsForm.php');
		require_once('callForm.php');
		break;
endswitch;
?>