<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
/*dumppost();
exit();
*/

$thisuser=getuser();

switch($_SESSION['button']):
	case 'Please wait adding...':
		require_once('SQLInsert.php');
		break;

	case 'Update':
		require_once('SQLUpdate.php');
		break;

	case 'Make Active':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '100');
		break;

	case 'To UR':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '100');
		break;

	case 'Patient Entered':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '150');
		break;

	case 'To Patient Entry':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '300');
		break;

	case 'To Billing Entry':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '500');
		break;

	case 'Billing Entered':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '700');
		break;

	case 'Make Inactive':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '900');
		break;

	case 'Delete':
		require_once('SQLDelete.php');
		break;

endswitch;
// Process button press
switch($_SESSION['button']):
	case 'Edit':
		displaysitemessages();
		require_once('editForm.php');

//if($thisuser=='SunniSpoon') {
//		require_once('addFormNew.php');
//}
//else {
		// Edit code - get record $_SESSION['id'] and display in the add form
		require_once('addForm.php');
//}

		break;

	case 'Confirm Submission':
		require_once('confirmSubmission.php');
		break;
		
	case 'Submit treatment list to WestStar':
		require_once('submitConfirm.php');
		break;
			
	case 'Clear':
	case 'Cancel/Return':
		foreach($_POST as $key=>$val) {
			if(substr($key,0,6) != 'search')
				unset($_POST["$key"]);
		}
		break;

	case 'Set Working Date':
		$_SESSION['notify'][]='Working submission date was changed.';
		break;

	case 'Set Clinic':
		if(isuserlevel(23)) 
			$_SESSION['user']['umclinic'] = $_POST['selectedclinic'];
		unset($_POST['selectedclinic']);
		$_SESSION['notify'][]='Working clinic was changed.';
		break;

endswitch;
displaysitemessages();

?>
<form action="" method="post" name="addForm" id="addForm" target="_self" onsubmit="return checkfield()">
<?php

//if($thisuser=='SunniSpoon') {
//	if($_SESSION['button'] !='Edit' && $_SESSION['button'] !='Submit treatment list to WestStar' && $_SESSION['button'] !='Confirm Submission' ) {
//		if(userlevel() == '10' || userlevel() == '12' || userlevel() == '23' || userlevel()==99)
//			require_once('addBarFormNew.php');
//		require_once('searchResultsFormNew.php');
//	}
//}
//else {
	if($_SESSION['button'] !='Edit' && $_SESSION['button'] !='Submit treatment list to WestStar' && $_SESSION['button'] !='Confirm Submission' ) {
		if(userlevel() == '10' || userlevel() == '12' || userlevel() == '23' || userlevel()==99)
			require_once('addBarForm.php');
		require_once('searchResultsForm.php');
	}
//}
?>
</form>
<?php
unset($_SESSION['button']);
unset($_SESSION['id']);
unset($_SESSION['navigation']);
unset($_SESSION['navigationid']);
?>