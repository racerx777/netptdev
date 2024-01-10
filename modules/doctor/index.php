<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66);
//dumppost();
if(isset($_POST['buttonSearchDoctor']))
	$_SESSION['button']='Search Doctors';
if(isset($_POST['buttonSearchLocation']))
	$_SESSION['button']='Search Locations';
if(isset($_POST['buttonSearchGroup']))
	$_SESSION['button']='Search Groups';

//dump('button',$_SESSION['button']);
//dump('id',$_SESSION['id']);

if(!isset($_SESSION['button']))
	$_SESSION['button']=NULL;

// Process function
switch($_SESSION['button']):
	case 'Back':
	case 'Cancel':
		unset($_SESSION['button']);
		foreach($_POST as $key=>$val)
			unset($_POST["$key"]);
		break;
	case 'Make Active':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		doctorupdateinactivate($_SESSION['id']);
		break;
	case 'Make Inactive':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		doctorupdateinactivate($_SESSION['id']);
		break;

	case 'Make Location Active':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		locationupdateinactivate($_SESSION['id']);
		break;
	case 'Make Location Inactive':
		// Update Code - Update record and update status array
		require_once('SQLUpdateFunctions.php');
		locationupdateinactivate($_SESSION['id']);
		break;

	case 'Confirm Merge Locations':
		require_once('SQLUpdateFunctions.php');
		locationupdatemerge($_POST['mergefromid'], $_POST['dlid']);
		break;

	case 'Confirm Add Doctor':
		// Insert Code - Insert record and update status array
		require_once('SQLInsert.php');
		break;
	case 'Confirm Add Doctor Location':
		// Insert Code - Insert record and update status array
		require_once('SQLInsertLocation.php');
		break;
	case 'Confirm Add Group':
		// Insert Code - Insert record and update status array
		require_once('SQLInsertGroup.php');
		break;

	case 'Confirm Update Doctor':
		// Update Code - Update record and update status array
		require_once('SQLUpdate.php');
		break;
	case 'Confirm Update Doctor Location':
		// Update Code - Update record and update status array
		require_once('SQLUpdateLocation.php');
		break;
	case 'Confirm Update Group':
		// Update Code - Update record and update status array
		require_once('SQLUpdateGroup.php');
		break;
	case 'Confirm Merge Selected Doctors':
		require_once('SQLUpdateFunctions.php');
		mergeselecteddoctors($_POST['checkbox'], $_POST['todoctor']);
		break;

	case 'Confirm Merge Selected Doctor Locations':
		require_once('SQLUpdateFunctions.php');
		mergeselecteddoctorlocations($_POST['dmid'], $_POST['checkbox'], $_POST['todoctorlocation']);
		break;

	case 'Confirm UNDO Merge Selected Doctor Locations':
		require_once('SQLUpdateFunctions.php');
//		dump("button",$_SESSION['button']);
//		dump("id",$_SESSION['id']);
//		dumppost();
//		exit();
		mergeselecteddoctorlocationsUndo($_SESSION['id'], $_POST['checkbox']);
		break;

	case 'Remove Relationship':
		require_once('SQLUpdateFunctions.php');
		relationshipDelete($_POST['dmid'], $_SESSION['id']);
		$_SESSION['button']='Search Locations';
		break;

	case 'Add Location':
		if(isset($_POST['dlid'])) {
			require_once('SQLUpdateFunctions.php');
			$dlid=$_POST['dlid'];
			$dmid=$_POST['dmid'];
			if(!empty($dlid) && !empty($dmid)) {
				relationshipCreate($dmid, $dlid);
			}
			else {
				error("999","Cannot add relationship. Doctor and Location cannot be blank (dmid:$dmid dlid:$dlid).");
			}
		}
		$_SESSION['button']='Search Locations';
		break;
    case 'Update Doctor Territories':
        require_once('SQLUpdateFunctions.php');
        if (isset($_POST['territory'])) {
            updateTerritory($_POST['territory']);
        }
        $_SESSION['button'] = 'Search Locations';
        break;

endswitch;

displaysitemessages();

// Process action
switch($_SESSION['button']):
	case 'Add Doctor':
		// Edit code - get record $id and display in the add form
		$_POST = getformvars('doctor', 'searchdoctor');
		unset($_SESSION['id']);
		require_once('editDoctorForm.php');
		break;
	case 'Add Location':
// Save Doctor, Navigate to Locations app bound by Doctor
		$_POST = getformvars('doctor', 'searchlocation');
		unset($_SESSION['id']);
		require_once('editLocationForm.php');
		break;
	case 'Add Group':
		$_POST = getformvars('doctor', 'searchgroup');
		unset($_SESSION['id']);
		require_once('editGroupForm.php');
		break;

	case 'Edit Doctor':
		// Edit code - get record $id and display in the add form
		require_once('editDoctorForm.php');
		break;
	case 'Edit Location':
		// Edit code - get record $id and display in the add form
		require_once('editLocationForm.php');
		break;
	case 'Edit Group':
		// Edit code - get record $id and display in the add form
		require_once('editGroupForm.php');
		break;

	case 'UNDO Merge Selected Doctor Locations':
		require_once('SQLUpdateFunctions.php');
		locationMergeUndoForm($_SESSION['id']); // dmid
		break;

	case 'Merge Location':
		// Merge code - get record $id and display in the add form
		require_once('mergeLocationForm.php');
		break;

	case 'Search Doctors':
		// Edit code - get record $id and display in the add form
		require_once('addBarDoctorForm.php');
		require_once('searchResultsDoctorForm.php');
		break;

	case 'Search Locations':
		// Edit code - get record $id and display in the add form
		require_once('addBarLocationForm.php');
		require_once('searchResultsLocationForm.php');
		break;

	case 'Search Groups':
		// Edit code - get record $id and display in the add form
		require_once('addBarGroupForm.php');
		require_once('searchResultsGroupForm.php');
		break;

	case 'Merge Selected Doctors':
		if(count($_POST['checkbox']) > 1) {
			require_once('doctorMergeForm.php');
			break;
		}
		else {
			error("999","Please select at least 2 doctors to perform doctor merge.");
			displaysitemessages();
		}

	case 'Merge Selected Doctor Locations':
		if(count($_POST['checkbox']) > 1) {
			require_once('locationMergeForm.php');
			break;
		}
		else {
			error("999","Please select at least 2 locations to perform location merge.");
			displaysitemessages();
		}

	default:
		require_once('addBarDoctorForm.php');
		require_once('searchResultsDoctorForm.php');
		break;
endswitch;
?>