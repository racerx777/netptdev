<?php
// Therapist Access Level 13
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(13); 
session_set_cookie_params(120*60);
$user=getuser();
//dumpsession();
//dumppost();
//$id='';
//$button='';
if(!empty($_SESSION['button']))  {
	if(is_array($_SESSION['button'])) {
		foreach($_SESSION['button'] as $key=>$value)  {
			$id = $key;
			$button = $value;
		}
	}
	else {
		$id = $_SESSION['id'];
		$button = $_SESSION['button'];
	}
}

if(!isset($_SESSION['useraccess']['patients']) || count($_SESSION['useraccess']['patients'])==0)
	$_SESSION['useraccess']['patients'] = getUserPatients();

//if(count($_SESSION['site']['homepages'])>1) {
//	$savehomepage=$_SESSION['site']['homepages'][$_SESSION['user']['umhomepage']];
//	unset($_SESSION['site']['homepages']);
//	$_SESSION['site']['homepages']=$_SESSION['site']['homepages'][$_SESSION['user']['umhomepage']];
//}

//if(count($_SESSION['site']['roles'])>1) {
//	$saverole=$_SESSION['site']['roles'][$_SESSION['user']['umrole']];
//	unset($_SESSION['site']['roles']);
//	$_SESSION['site']['roles']=$_SESSION['site']['roles'][$_SESSION['user']['umrole']];
//}

// handle magic quotes for the report arrays
if(get_magic_quotes_gpc()) {
//	if(isset($_POST['report'])) {
//		require_once('reportGeneratorFunctions.php');
//		$_POST['report']=stripslashes_deep($_POST['report']);
//	}
	if( isset($_POST) ) {
		if( count($_POST) > 0 ) {
			$_POST=stripslashes_deep($_POST);			
//			if($user=='Therapist') 
//				dumppost();
		}
	}

	if( isset($_SESSION) ) {
		if( count($_SESSION) > 0 ) {			
//			if($user=='Therapist') 
//				dump('site',$_SESSION['site']);
		}
	}
	
}

if(isset($_POST['buttonWalkIn'])) {
	$button='Add';
	$id="WalkIn";
	$rtid=NULL;
	$_SESSION['button']='Add';
	$_SESSION['id']="WalkIn";
	$crid="WalkIn";
	unset($_POST['buttonWalkIn']);
}

// ACTIONS
switch($button):
	case 'Cancel Add Body Parts':
		$button='Edit';
		$_SESSION['button']='Edit';
		break;
	case 'Cancel':
	case 'Exit':
		notify("000","Action canceled.");
		unset($id);	
		unset($_SESSION['id']);	
		parse_str(urldecode($_SESSION['navigationid']), $var);
		$button=$var['button'][0];
		$_SESSION['button']=$button;
		break;
	case 'Yes, Delete Report':
		require_once('reportGeneratorFunctions.php');
		deleteReport($button, $id); // rhid
		unset($id);	
		unset($_SESSION['id']);	
		parse_str(urldecode($_SESSION['navigationid']), $var);
		$button=$var['button'][0];
		$_SESSION['button']=$button;
		break;
	case 'Set Report Type':
		if(errorcount()==0) {
			if( !empty($_POST['rtid']) )
				$rtid=$_POST['rtid'];
			if( !empty($_POST['caller']) )
				$caller=$_POST['caller'];
			if( !empty($_POST['crid']) )
				$crid=$_POST['crid'];
	//		require_once('reportGeneratorFunctions.php');
			if( !empty($rtid) and !empty($caller) and !empty($crid) ) {
	//			notify("000","Report type set to $rtid for $caller to case $crid.");
				$id=$crid;
				$button=$caller;
				$_SESSION['button']=$caller;
			}
			else {
				error("999","Report type not set. $rtid : $caller : $crid");
				unset($id);	
				unset($button);	
				unset($_SESSION['id']);	
				unset($_SESSION['button']);	
			}
		}
		break;
	case 'Set Injury Template Type':
		if( !empty($_POST['rtid']) )
			$rtid=$_POST['rtid'];
		if( !empty($_POST['ritid']) )
			$ritid=$_POST['ritid'];
		if( !empty($_POST['caller']) )
			$caller=$_POST['caller'];
		if( !empty($_POST['crid']) )
			$crid=$_POST['crid'];
		if( (!empty($rtid) || !empty($ritid) ) and !empty($caller) and !empty($crid) ) {
			$id=$crid;
			$button=$caller;
			$_SESSION['button']=$caller;
		}
		else {
			error("999","Report Injury Type not set. $ritid : $caller : $crid");
			unset($id);	
			unset($button);	
			unset($_SESSION['id']);	
			unset($_SESSION['button']);	
		}
		break;
	case 'Save':
		require_once('reportGeneratorFunctions.php');
		saveReport($button, $id);
		break;
	case 'Save As Template':
		require_once('reportGeneratorFunctions.php');
		if(saveAsTemplate($button, $id))
			notify("000","User Template Saved.");
		$button='Edit';
		break;
	case 'Confirm Add Tests':
		require_once('reportGeneratorFunctions.php');
		confirmAddReportBodypartTest($button, $id); // Bodypart Record to add test to
		$id=$_POST['report']['header']['rhid'];
		break;
	case 'Confirm Add Body Parts':
		require_once('reportGeneratorFunctions.php');
		confirmAddReportBodypart($button, $id); // Report Record to add part to
		$id=$_POST['report']['header']['rhid'];
		break;
	case 'Confirm comparison report date':
		require_once('reportGeneratorFunctions.php');
		confirmComparisonReportDate($button, $id); // Update Comparison Fields then proceed to edit
//		$button='Edit';
		$button='Add';
		$_SESSION['button']=$button;
		$crid=$_POST['crid'];
		$rtid=$_POST['rtid'];
		$compreportdate=$_POST['report']['header']['rhcompreportdate'];
//		dumppost();
		$id=$_POST['crid'];
		$_SESSION['id']=$id;
		break;
	case 'Assign Report to Selected Case':
		$rhid=$id;
		$crid=$_POST['crid'];
		require_once('reportGeneratorFunctions.php');
		if(updateReportCase($rhid, $crid))
			notify('000',"Report $rhid assigned to case $crid.");
		else
			error('999',"Error assigning report $rhid.");
		unset($id);	
		unset($button);	
		unset($_SESSION['id']);	
		unset($_SESSION['button']);	
		break;
	case 'Generate':
		require_once('generateReport.php');
		break;
	case 'View':
//		is a javascript popup window for viewing report pdf files.
		break;
	case 'Remove Tests':
		require_once('reportGeneratorFunctions.php');
		removeReportBodypartTest($button, $id); // Bodypart Record id
		$id=$_POST['report']['header']['rhid'];
		break;
	case 'Remove Body Parts':
		require_once('reportGeneratorFunctions.php');
		removeReportBodypart($button, $id); // Report id
		$id=$_POST['report']['header']['rhid'];
		break;

	case 'Add Using Dx':
		require_once('reportGeneratorFunctions.php');
		addTestsUsingDx(); 
		break;

	case 'Add Tests Using Template':
		require_once('reportGeneratorFunctions.php');
		addTestsUsingTemplate($id); // Record id
		$id=$_POST['report']['header']['rhid'];
		break;

	case 'Save Tests Template':
		require_once('reportGeneratorFunctions.php');
		saveTestsTemplate($id); // Record id
		$id=$_POST['report']['header']['rhid'];
		break;

	case 'File':
		require_once('reportGeneratorFunctions.php');
		fileReport($id); // Report id
		break;

//	Display Add Treatment form for Discharge Date and Therapy Type
//		break;
//	case 'Confirm Delete Report':
//	Display Add Treatment form for Discharge Date and Therapy Type
//		break;
endswitch;

// Output begins here

displaysitemessages();
switch($button):
	case 'Add':
		require_once('reportGeneratorFunctions.php');
//		if(!empty($id)) 
//dump('id', $id);
//dump('rtid', $rtid);
//dump('ritid', $ritid);
//dump('rhid', $rhid);
			addReport($id, $rtid, $ritid, $rhid); // case id, report template id
		break;
	case 'Edit':
	case 'Save':
	case 'Confirm Add Tests':
	case 'Confirm Add Body Parts':
	case 'Remove Tests':
	case 'Remove Body Parts':
	case 'Add Using Dx':
	case 'Add Tests Using Template':
	case 'Save Tests Template':
	case 'Confirm comparison report date':
		require_once('reportGeneratorFunctions.php');
		editReport($id); // rhid
		break;

	case 'Edit Template':
		require_once('reportGeneratorFunctions.php');
		editReport(0, 0, $id); // rhid
		break;	
	case 'Delete':
		require_once('reportGeneratorFunctions.php');
		deleteReport($button, $id); // rhid
		break;
	case 'Assign':
		require_once('reportGeneratorFunctions.php');
		assignReport($id); // rhid
		break;
	case 'Add Test':
		require_once('reportGeneratorFunctions.php');
		addReportBodypartTest($id); // id=Bodypart id to add test to
		break;
	case 'Add Body Part':
		require_once('reportGeneratorFunctions.php');
		addReportBodypart($id); // id=Report ID to add bodypart to
		break;
	case 'Search Reports':
		require_once('searchBarForm.php');
		break;
	case 'New Reports':
		require_once('searchResultsFormNew.php');
		break;
	case 'Walkin Reports':
		require_once('searchResultsFormWalkIns.php');
		break;
	case 'Reports To File':
		require_once('searchResultsFormUnfiled.php');
		break;
	case 'Report Manager Templates':
		require_once('searchResultsFormInjuryTemplates.php');
		break;
	default:
		require_once('searchBarForm.php');
		require_once('searchResultsForm.php');
		break;
endswitch;

//if(empty($_SESSION['button'])) {
if(empty($button)) {
	displaysitemessages();
	require_once('searchBarForm.php');
	require_once('searchResultsForm.php');
}
?>
