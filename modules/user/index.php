<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 

$user=getuser();
if($user=='NancyVilla') {
		$application = 'Billing Dashboard';
		$button = 'Back to UR';
		$vars=urlencode("application=$application&button[]=$button");
		echo('<form name="navBarForm" method="POST"><div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div></form>'); 
}

?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
// Process button press
if(isset($_SESSION['button'])) {
	switch($_SESSION['button']):
		case 'User Audit List':
			require_once('printVisitorAuditReportSelection.php');
			exit();
			break;
	
		case 'Cancel':
			unset($_POST);
			break;
	
		case 'Edit':
			// Edit code - get record $id and display in the add form
			require_once('editForm.php');
			break;
	
		case 'Edit Access':
			// Edit code - get record $id and display in the add form
			require_once('editFormAccess.php');
			break;
	
		case 'Add':
			// Insert Code - Insert record and update status array
			require_once('SQLInsert.php');
			break;
	
		case 'Add Access':
			// Insert Code - Insert record and update status array
			require_once('SQLInsertAccess.php');
			break;
	
		case 'Make Inactive':
			// Update Code - Update record and update status array
			require_once('SQLUpdateFunctions.php');
			userupdateinactivate($_SESSION['id']);
			break;
	
		case 'Make Active':
			// Update Code - Update record and update status array
			require_once('SQLUpdateFunctions.php');
			userupdateinactivate($_SESSION['id']);
			break;
	
		case 'Update':
			// Update Code - Update record and update status array
			require_once('SQLUpdate.php');
			break;
			
		case 'Delete':
			// Delete Code delete record update status array
			require_once('SQLDelete.php');
			break;
	
		case 'Delete Access':
			// Delete Code delete record update status array
			require_once('SQLDeleteAccess.php');
			break;

		case 'Reset Password':
			// Update Code - Update record and update status array
			require_once('SQLUpdateFunctions.php');
			userupdatereset($_SESSION['id']);
			break;
	
	endswitch;
}

displaysitemessages();


//https://netpt.wsptn.com/modules/user/printVisitorAuditReportSelection.php

// Search List Code
if($_SESSION['button'] != 'Edit' && $_SESSION['button'] != 'Edit Access') {
	require_once('addBarForm.php');
	require_once('searchResultsForm.php');
}
//else
//	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/redirect.php');
?>