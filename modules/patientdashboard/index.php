<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(17); 
// Process button press
switch($_SESSION['button']):
	case 'To UR':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '100');
		unset($_SESSION['button']);
		break;

	case 'Patient Entered':
		require_once('SQLUpdateFunctions.php');
//		treatmentupdatestatus($_SESSION['id'], '150');
		updatepatientstatus($_SESSION['id'], '150');
		unset($_SESSION['button']);
		break;

	case 'Patient Entry List':
		require_once('dashboardForm.php');
		unset($_SESSION['button']);
		break;

	case 'PTOS Edit List':
		require_once('listBadPTOSRecords.php');
		unset($_SESSION['button']);
		break;

	case 'Patient List Report':
		require_once('printPatientListReportSelection.php');
		unset($_SESSION['button']);
		break;

endswitch;
displaysitemessages();
?>
<div style="clear:both;">&nbsp;</div>