<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php

$thisuser=getuser();

switch($_SESSION['button']):
	case 'Add':
		require_once('SQLInsert.php');
		break;

	case 'Update':
		require_once('SQLUpdate.php');
		break;

	case 'Make Active':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '100');
		$_SESSION['button']='Search';
		break;

	case 'Rollback Billing':
		require_once('SQLUpdateFunctions.php');
		treatmentrollbackbilling($_SESSION['id']);
		$_SESSION['button']='Search';
		break;

	case 'To UR':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '100');
		$_SESSION['button']='Search';
		break;

	case 'Patient Entered':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '150');
		$_SESSION['button']='Search';
		break;

	case 'To Patient Entry':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '300');
		$_SESSION['button']='Search';
		break;

	case 'To Billing Entry':
		require_once('SQLUpdateFunctions.php');
		treatmentbilltreatment($_SESSION['id']);
//		treatmentupdatestatus($_SESSION['id'], '500');
		$_SESSION['button']='Search';
		break;

	case 'Billing Entered':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '700');
		$_SESSION['button']='Search';
		break;

	case 'Selected To Billing Entry':
		require_once('SQLUpdateFunctions.php');
		foreach($_POST['checkbox'] as $key=>$val) {
			treatmentbilltreatment($val);
		}
//		treatmentupdatestatus($_SESSION['id'], '500');
		$_SESSION['button']='Search';
		break;

	case 'Make Inactive':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '900');
		$_SESSION['button']='Search';
		break;

	case 'Delete':
		require_once('SQLDelete.php');
		break;

endswitch;

// Process button press
switch($_SESSION['button']):
	case 'Edit':
		// Edit code - get record $_SESSION['id'] and display in the add form
		displaysitemessages();
		require_once('editForm.php');
//if($thisuser=='SunniSpoon') {
//		require_once('addFormNew.php');
//}
//else {
		// Edit code - get record $_SESSION['id'] and display in the add form
		require_once('addForm.php');
//}

		exit();
		break;

	case 'Confirm Submission':
		require_once('confirmSubmission.php');
		break;
		
	case 'Submit treatment list to WestStar':
		require_once('submitConfirm.php');
		break;
			
	case 'Reset Search':
		cleartreatmentsearchvalues();
		break;

	case 'Cancel/Return':
	case 'Update':
		gettreatmentsearchvalues();
		gettreatmentsortvalues();
		$_SESSION['button']='Search';
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
<form action="" method="post" name="searchForm"  id="searchform" target="_self">

<?php

//if($thisuser=='SunniSpoon') {
//	if($_SESSION['button'] !='Edit' && $_SESSION['button'] !='Submit treatment list to WestStar' && $_SESSION['button'] !='Confirm Submission' ) {
//		if(userlevel() == '10' || userlevel() == '12' || userlevel() == '23' || userlevel()==99)
//			require_once('addFormNew.php');
//		require_once('searchBarForm.php');
//		require_once('searchBarFormNew.php');
//		require_once('searchResultsFormNew.php');
//	}
//}
//else {
	if($_SESSION['button'] !='Edit' && $_SESSION['button'] !='Submit treatment list to WestStar' && $_SESSION['button'] !='Confirm Submission' ) {
		require_once('searchBarForm.php');
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