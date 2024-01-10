<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
// ACTIONS
switch($_SESSION['button']):
	case 'Cancel':
		clearpost();
		break;
	case 'Add':
		// Insert Code - Insert record and update status array
		require_once('SQLInsert.php');
		break;
	case 'Make Inactive':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		clinicupdateinactivate($_SESSION['id']);
		break;
	case 'Make Active':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		clinicupdateinactivate($_SESSION['id']);
		break;
	case 'Update':
		// Update Code - Update record and update status array
		require_once('SQLUpdate.php');
		break;
	case 'Delete':
		// Delete Code delete record update status array
		require_once('SQLDelete.php');
		break;

	case 'Add Therapist':
		// Delete Code delete record update status array
		require_once('SQLInsertTherapist.php');
		break;

endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):
	case 'Edit':
		// Edit code - get record id and display in the add form
		require_once('editForm.php');
		break;
	default:
		require_once('addBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;
unset($_SESSION['button']);
?>