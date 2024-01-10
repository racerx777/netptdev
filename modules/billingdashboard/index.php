<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(20); 
// Process button press
$hidedashboard=0;
switch($_SESSION['button']):
	case 'To UR':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '100');
		unset($_SESSION['button']);
		break;

	case 'Billing Entered':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '700');
		unset($_SESSION['button']);
		break;

	case 'Build Billing File':
		require_once('billingCreateBatch.php');
		unset($_SESSION['button']);
		$hidedashboard=1;
		break;
	case 'Print Billing Summary':
		require_once('billingPrintBatch.php');
		unset($_SESSION['button']);
		$hidedashboard=1;
		break;
	case 'Export Billing to PTOS':
		require_once('billingExport.php');
		unset($_SESSION['button']);
		$hidedashboard=1;
		break;

endswitch;
displaysitemessages();
if(empty($hidedashboard)) 
	require_once('dashboardForm.php');
?>