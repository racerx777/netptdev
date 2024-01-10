<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
if(!isset($_SESSION['button']))
	$_SESSION['button']=NULL;


switch($_SESSION['button']):
	case 'Back':
	case 'Cancel':
		$searchapptSaved = getformvars('case', 'searchappt');
		$sortapptSaved = getformvars('case', 'searchapptResults');
		$_POST['formSubmit']="1";
		break;
	case 'Seen':
		require_once('SQLUpdateFunctions.php');
		caseseen($_SESSION['id']);
		unset($_SESSION['button']);
		break;
	case 'No Show':
		require_once('SQLUpdateFunctions.php');
		casenoshow($_SESSION['id']);
		unset($_SESSION['button']);
		break;
	case 'Confirm Schedule Referral':
		require_once('SQLUpdateFunctions.php');
		
		$_POST['appointment'] = dbDate($_POST['apptdate'] . " " . $_POST['appttime']);
		caseclinicrescheduled($_SESSION['id'], $_POST['crcnum'], $_POST['appointment']);
		unset($_SESSION['button']);
		break;
	case 'Confirm Cancel Referral':
		require_once('SQLUpdateFunctions.php');
		attendancecasecancel($_SESSION['id'], $_POST['crcancelreasoncode']);
		unset($_SESSION['button']);
		break;
endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):
	case 'Clinic Rescheduled':
		require_once('scheduleForm.php');
		break;
	case 'Cancel Case':
		require_once('cancelForm.php');
		break;
	case 'Attendance Report';
		require_once('printAttendanceReportSelection.php');
		break;
	default:
		require_once('attendanceSearchForm.php');
		require_once('attendanceSearchResultsForm.php');
		break;
endswitch;
?>