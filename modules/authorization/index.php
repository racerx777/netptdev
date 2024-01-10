<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
// Pre-Output actions
if (isset($_POST['insurance1'])) {
	$crid = $_SESSION['id'];
	$icseq = 1;
	require_once('case/insuranceEditForm.php');
	exit();
}
if (isset($_POST['insurance2'])) {
	$crid = $_SESSION['id'];
	$icseq = 2;
	require_once('case/insuranceEditForm.php');
	exit();
}
switch ($_SESSION['button']):
	case 'Update Patient':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/customerservice/SQLUpdate.php');
		unset($_SESSION['button']);
		break;
	case 'Update Case':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/case/SQLUpdate.php');
		unset($_SESSION['button']);
		break;
	case 'Insert Prescription':
		$crid = $_SESSION['id'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/prescription/prescriptionSQLInsert.php');
		unset($_SESSION['button']);
		break;
	// case 'Update Prescription':
	// 	$cpid=$_POST['cpid'];
	// 	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/prescription/prescriptionSQLUpdate.php');
	// 	unset($_SESSION['button']);
	case 'Save and Close':
		$cpid = $_POST['cpid'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/prescription/prescriptionSQLUpdate.php');
		unset($_SESSION['button']);
		break;
	case 'Send to PTOS':
		$crid = $_SESSION['id'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/case/caseSendToPtos.php');
		unset($_SESSION['button']);
		break;
	case 'Export to PTOS':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/case/caseExport.php');
		unset($_SESSION['button']);
		break;
	case 'Send to Authorization':
		$cpid = $_SESSION['id'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/prescription/prescriptionSendToAuthorization.php');
		unset($_SESSION['button']);
		break;
	case 'Update Insurance 1':
		$crid = $_SESSION['id'];
		$icseq = '1';
		require_once('case/insuranceSQLUpdate.php');
		break;
	case 'Update Insurance 2':
		$crid = $_SESSION['id'];
		$icseq = '2';
		require_once('case/insuranceSQLUpdate.php');
		break;
endswitch;

// Output begins here
displaysitemessages();

//$_POST['search']['crptosstatus']='(EMPTY)';

switch ($_SESSION['button']):
	case 'Duplicate PNUM List':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/case/caseDuplicatePnums.php');
		unset($_SESSION['button']);
		exit();
		break;
	case 'Prior Auth (PEA)':
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = 'PEA';
		$_POST['search']['cpstatuscode'] = 'NEW';
		$_POST['search']['cpauthstatuscode'] = '(EMPTY)';
		//unset($_POST['search']['crptosstatus']);
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'New Prescriptions':
		clearformvars('authorization', 'search');
		$_POST['search']['cpstatuscode'] = 'NEW';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Review/Update':
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = '(ACTIVE)'; // Active Case
		$_POST['search']['cpstatuscode'] = 'NEW';
		$_POST['search']['cpauthstatuscode'] = '(EMPTY)';
		$_POST['search']['crptosstatus'] = '(EMPTY)';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'In Authorization':
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = array('ACT', 'PEA'); // Active or Preauth Case
		$_POST['search']['cpstatuscode'] = 'ACT';
		$_POST['search']['cpauthstatuscode'] = 'NEW';
		$_POST['search']['crptosstatus'] = '(EMPTY)';
		$_POST['search']['cricid1'] = '(EMPTY)';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Final Review':
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = 'ACT'; // Active case
		$_POST['search']['crptosstatus'] = '(EMPTY)'; // not in PTOS
		$_POST['search']['cricid1'] = '(NOT_EMPTY)'; // has primary insurance
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Waiting for NetPT Export':
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = 'ACT';
		$_POST['search']['crptosstatus'] = 'RQS';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Not In PTOS':
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = 'ACT';
		$_POST['search']['crptosstatus'] = '(NOT_IN_PTOS)';
		//$_POST['search']['crpnum'] = '(NOT_IN_PTOS)';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Waiting for PTOS Import':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authorization/case/caseExportVerify.php');
		updatePatientExportStatus();
		clearformvars('authorization', 'search');
		$_POST['search']['crcasestatuscode'] = 'ACT';
		$_POST['search']['crptosstatus'] = 'EXP';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authorization', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	//	case 'Patients In PTOS':
//		clearformvars('authorization', 'search');
//		$_POST['search']['crcasestatuscode']='ACT';
//		$_POST['search']['crptosstatus']='IN';
//		$dbvalues = valuestodb($_POST['search'], $searchvars);
//		setformvars('authorization', 'search', $dbvalues);
//		break;
	case 'Edit Patient':
		//		require_once('patient/patientEditForm.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/customerservice/editForm.php');
		exit();
		break;
	case 'Edit Case':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/case/editForm.php');
		exit();
		break;
	case 'Edit Prescription':
		$cpid = $_SESSION['id'];
		require_once('prescription/prescriptionEditForm.php');
		exit();
		break;
	case 'Add Prescription':
		$crid = $_SESSION['id'];
		require_once('prescription/prescriptionEditForm.php');
		exit();
		break;

		case 'Search Authorization Information':

			require_once('searchBarForm.php');
			require_once('searchResultsForm.php');

			exit();
			break;
endswitch;
require_once('searchBarForm.php');
require_once('searchResultsForm.php');
?>