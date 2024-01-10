<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5); 
// Process button press
switch($_SESSION['button']):
	case 'Cancel/Exit':
		unset($_POST);
		$_SESSION['application'] = $_SESSION['user']['umhomepage'];
		configapplication($_SESSION['application']); 			// configure any pre output information for application
		displaysitemessages(); 								// site notification messages
		displayapplication($_SESSION['application']); 		// run application display content
		displaysitefooter(); 								// site footer
		exit;
		break;
	case 'Update':
		// Update Code - Update record and update status array
		require_once('SQLUpdate.php');
		unset($_POST);
		break;
endswitch;
displaysitemessages();
require_once('editForm.php');
?>