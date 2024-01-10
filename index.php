<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// echo "We apologize, but netpt is currently unavailable. Please try back in about an hour.";

// exit();

date_default_timezone_set('America/Los_Angeles');
if(isset($_REQUEST['logout'])) {
	unset($_REQUEST['logout']);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	logout();
	exit();
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5);


if(isset($_SESSION['user']['passwordexpired']) && $_SESSION['user']['passwordexpired']==true) {
	if($_POST['button'][0]=='Update') {
		$_SESSION['user']['passwordexpired']==false;
	}
	else {
		unset($_POST);
		unset($_SESSION['button']);
		unset($_SESSION['navigation']);
		unset($_SESSION['application']);
		$_SESSION['application']='usersettings';
	//	if(getuser()=='SunniSpoon') {
	//		dumppost();
	//		dump("button",$_SESSION['button']);
	//		dump("navigation",$_SESSION['navigation']);
	//		dump("application",$_SESSION['application']);
	//	}
	}
}


if(!empty($_POST['imitateUser'])) {
	$userid=getuserid();
	getusersettings($_POST['imitateUser'],1);
	unset($_SESSION['navigation']);
	unset($_SESSION['application']);
	$_SESSION['user']['umid']=$userid;
	$_SESSION['user']['umuser']=getUserById($userid);
	$_SESSION['user']['umname']=getUserNameById($userid);
	unset($_POST['imitateUser']);
}
// NAVIGATION - button value contains Navigation, key contains parsable string keyword=keywordvalue parameters separated by & (ampersand)


if(isset($_POST['navigation']))  {
	foreach($_POST['navigation'] as $key=>$value)  {
		$_SESSION['navigationid'] = $key; // supporting legacy navigation calls
		$_SESSION['navigation'] = $value; // Application to navigate to
		parse_str(urldecode($key), $var);
		foreach($var as $k=>$v) {
			$_POST[$k] = $v;
		}
		if(isset($_POST['application'])) {
			$_SESSION['navigation'] = $_POST['application'];
			unset($_POST['application']);
		}
	}
	unset($_POST['navigation']);
	unset($_SESSION['button']);
}


if(isset($_POST['button']))  {
	foreach($_POST['button'] as $key=>$value)  {
		$_SESSION['id'] = $key;
		$_SESSION['button'] = $value;
	}
	unset($_POST['button']);
}
if(!isset($_SESSION['navigation']))
	$_SESSION['navigation']="";

//if($_SESSION['user']['umuser']=='Therapist')
//	dumpsession();
switch($_SESSION['navigation']):
	case 'Attorneys':
		$_SESSION['application'] = 'attorneys';
		break;
	case 'ICD Codes':
		$_SESSION['application'] = 'icdcodes';
		break;
		case 'Search Authorization Information':
			$_SESSION['application'] = 'authorization';
			break;
	case 'Clinics':
		$_SESSION['application'] = 'clinic';
		break;
	case 'Providers':
		$_SESSION['application'] = 'provider';
		break;
	case 'Business Units':
		$_SESSION['application'] = 'business';
		break;
	case 'User Settings':
		$_SESSION['application'] = 'usersettings';
		break;
	case 'Therapists':
		$_SESSION['application'] = 'therapist';
		break;
	case 'Users':
		$_SESSION['application'] = 'user';
		break;
	case 'User Audit List':
		$_SESSION['application'] = 'user';
		break;
	case 'Doctors':
	case 'Search Doctors':
	case 'Search Locations':
	case 'Search Groups':
		$_SESSION['application'] = 'doctor';
		break;
    case 'Doctor Locations':
        $_SESSION['application'] = 'doctor';
        $_SESSION['button'] = 'Search Locations';
        break;
	case 'Duplicate Dashboard':
		$_SESSION['application'] = 'duplicatedashboard';
		break;
	case 'Treatments':
		$_SESSION['user']['umclinic'] = $_SESSION['navigationid'];
		$_SESSION['application'] = 'treatment';
		break;
	case 'Add Treatments':
		unset($_SESSION['submissionDate']);
		$_SESSION['application'] = 'treatment';
		break;
	case 'Search Treatments':
		$_SESSION['application'] = 'treatmentsearch';
		break;
//	case 'Treatment Dashboard':
//		$_SESSION['application'] = 'treatmentdashboard';
//		break;
	case 'Billing Dashboard':
		$_SESSION['application'] = 'billingdashboard';
		$_SESSION['user']['umrole']=23;
		break;
	case 'Case Number':
	case 'Search Cases':
		$_SESSION['application'] = 'case';
		break;
	case 'Edit Patient':
	case 'Search Patients':
	case 'Customer Service':
		$_SESSION['application'] = 'customerservice';
		break;
	case 'Authorization':
		$_SESSION['application'] = 'authorization';
		break;
	case 'Authorization Processing':
		$_SESSION['application'] = 'authprocessing';
		break;
	case 'Patients':
	case 'New Patients':
	case 'Scheduled Patients':
		$_SESSION['application'] = 'patient';
		break;
	case 'Patient Entry List':
		$_SESSION['application'] = 'patientdashboard';
		break;
	case 'PTOS Edit List':
		$_SESSION['application'] = 'patientdashboard';
		break;
	case 'Cases':
		$_SESSION['application'] = 'case';
		break;
	case 'Contact Referral':
	case 'Scheduling Queue':
		$_SESSION['application'] = 'scheduling';
		break;
	case 'Attendance':
		$_SESSION['application'] = 'attendance';
		break;
	case 'Attendance Report':
		$_SESSION['application'] = 'attendance';
		$_SESSION['button'] = 'Attendance Report';
		break;
	case 'Patient Status Report':
		$_SESSION['application'] = 'customerservice';
		$_SESSION['button'] = 'Patient Status Report';
		break;
	case 'Scheduling Performance Report':
		$_SESSION['application'] = 'scheduling';
		$_SESSION['button'] = 'Scheduling Performance Report';
		break;
	case 'Cancel Reason Report':
		$_SESSION['application'] = 'case';
		$_SESSION['button'] = 'Cancel Reason Report';
		break;
	case 'PTOS NetPT Issues':
		$_SESSION['application'] = 'ptosnetptissues';
		break;
	case 'collections':
		$_SESSION['application'] = 'collections';
		break;
	case 'Collections Search':
		$_SESSION['application'] = 'Collections Search';
		break;
	case 'Touched Accounts':
		$_SESSION['application'] = 'Touched Accounts';
		break;
	case 'Notes Search':
		$_SESSION['application'] = 'notes';
		break;
	case 'Report Manager':
		$_SESSION['application'] = 'reportmanager';
		break;
	case 'Report Manager Admin':
		$_SESSION['application'] = 'reportmanageradm';
		break;
	case 'Document Manager':
		$_SESSION['application'] = 'documentmanager';
		break;
	case 'Patient List Report':
		$_SESSION['application'] = 'patientdashboard';
		break;
	case 'patientdashboard':
		$_SESSION['application'] = 'patientdashboard';
		break;
	case 'collectionassign':
		$_SESSION['application'] = 'collectionassign';
		break;
    case 'collectionmassmailing':
        $_SESSION['application'] = 'collectionsmassmailer';
        break;
    case 'Reports':
        $_SESSION['application'] = 'reports';
        break;
    case 'Sales Territory Assign':
        $_SESSION['application'] = 'salesassign';
        break;
    case 'Scheduled Queue List':
        $_SESSION['application'] = 'schedulingqueuelist';
        break;
endswitch;
unset($_SESSION['navigation']);

if(!isset($_SESSION['application']))  {
	if(isset($_SESSION['user']['umhomepage']))
		$_SESSION['application'] = $_SESSION['user']['umhomepage'];
	else
		$_SESSION['application'] = 'home';
}


// die;
configapplication($_SESSION['application']); 			// configure any pre output information for application
// Output begins here
	displaysiteheader(); 								// site header, css and javascript includes
	displaysitestatus(); 								// site status
	displaysitenavigation(); 							// site navigation depending on user class
	displaysitemessages(); 								// site notification messages
	displayapplication($_SESSION['application']); 		// run application display content
//dump("_SESSION['application']",$_SESSION['application']);
	displaysitefooter(); 								// site footer
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

?>


