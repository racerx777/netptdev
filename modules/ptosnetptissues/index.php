<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(20); 
// Process button press
//if(count($_POST['button'])==1) {
//	foreach($_POST['button'] as $key=>$value) {
//		$button=$value;
//		$id=$key;
//	}
//}
switch($_SESSION['button']):
	case 'PTOS Is Correct - Update NetPT':
		require_once('SQLUpdateFunctions.php');
		updatenetptfromptos($_SESSION['id']);
		break;
endswitch;
displaysitemessages();
require_once('dashboardForm.php');
?>