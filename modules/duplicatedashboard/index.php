<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(20); 
// Process button press
switch($_SESSION['button']):
	case 'Make Inactive':
		require_once('SQLUpdateFunctions.php');
		treatmentupdatestatus($_SESSION['id'], '900');
		break;

endswitch;
displaysitemessages();
require_once('dashboardForm.php');
?>