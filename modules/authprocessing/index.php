<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);
//dumppost();
?>

<script language="JavaScript">
	var cal = new CalendarPopup();
</script>

<?php
$rx=$_SESSION['id'];
$button=$_SESSION['button'];
//dump("button",$button);
// Pre-Output actions
switch($button):
	case 'Push Auth':
		$cpid=$_SESSION['id'];
// Update the status' to push a converted person
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		require_once('authprocessingSQLUpdate.php');
		rxExport($cpid);
		break;
	case 'Push Conversion':
		$cpid=$_SESSION['id'];
// Update the status' to push a converted person
		require_once('authprocessingSQLUpdate.php');
		rxConvert($cpid);
		break;
	case 'Authorization Dashboard':
		break;
	case 'No Insurance':
		clearformvars('authprocessing', 'search');
		$_POST['criclid1NULL']='1';
		$_POST['formSubmit']='1';
//		$dbvalues = valuestodb($_POST['search'], $searchvars);
//		setformvars('authprocessing', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Prior Auth (PEA)':
		clearformvars('authprocessing', 'search');
		$_POST['search']['crcasestatuscode']='PEA';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authprocessing', 'search', $dbvalues);
		unset($_SESSION['button']);
		break;
	case 'Print RFAs': // List AuthSts=NEW records, allow print if LtrSts=NULL/ Sent if LtrSts=PRT
		// change name of display list to "RFA Processing List" set search to AuthSts=NEW
		clearformvars('authprocessing', 'search');
		$_POST['search']['cpstatuscode']='ACT';
		$_POST['search']['cpauthstatuscode']='NEW';
		$_POST['search']['cprfastatuscode']='NEW';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authprocessing', 'search', $dbvalues);
		break;
	case 'Send RFAs': // List AuthSts=NEW records, allow print if LtrSts=NULL/ Sent if LtrSts=PRT
		// change name of display list to "RFA Processing List" set search to AuthSts=NEW
		clearformvars('authprocessing', 'search');
		$_POST['search']['cpstatuscode']='ACT';
		$_POST['search']['cpauthstatuscode']='NEW';
		$_POST['search']['cprfastatuscode']='PRT';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authprocessing', 'search', $dbvalues);
		break;
	case 'Send Doc/Info Requests': // List ALL records with DocSts=RQS. allow Sent Docs/Info
		// set search to DocSts=RQS
		clearformvars('authprocessing', 'search');
		$_POST['search']['cpstatuscode']='ACT';
		$_POST['search']['cpdocstatuscode']='RQS';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authprocessing', 'search', $dbvalues);
		break;
	case 'Process Responses': // List AuthSts=PEN records, Allow Auth, Denied, Request for Docs/Info
		// set search to AuthSts=PEN
		clearformvars('authprocessing', 'search');
		$_POST['search']['cpstatuscode']='ACT';
		$_POST['search']['cpauthstatuscode']='NEW';
		$_POST['search']['cprfastatuscode']='SNT';
		$dbvalues = valuestodb($_POST['search'], $searchvars);
		setformvars('authprocessing', 'search', $dbvalues);
		break;
	case 'Sent RFA': // Updates LtrSts to SNT
		require_once('authprocessingRfa.php');
		rxSentRfa($rx);
		break;
	case 'Confirm Requested Docs': // Updates DocSts to RQS
	case 'Confirm Requested Docs Late': // Updates DocSts to RQS
		require_once('authprocessingDoc.php');
		rxRequestedDocs($rx);
		break;
	case 'Confirm Sent Docs/Info': // Updates DocSts to SNT
		require_once('authprocessingDoc.php');
		rxSentDocs($rx);
		break;
	case 'Confirm Authorized': // Updates AuthSts to AUT
		require_once('authprocessingSQLUpdate.php');
		rxAuthorized($rx);
		break;
	case 'Confirm Denied': // Updates AuthSts to DEN if not AuthSts!=ASU otherwise notes account as Late Denial
	case 'Confirm Denied Late': // Updates AuthSts to DEN if not AuthSts!=ASU otherwise notes account as Late Denial
		require_once('authprocessingSQLUpdate.php');
		rxDenied($rx);
		break;
	case 'Confirm Process No Responses (Daily)': // Updates AuthSts to ASU if 5 days or 14 days rule
		require_once('authprocessingDailyUpdate.php');
		rxDaily();
		break;
endswitch;

// Output begins here
displaysitemessages();
switch($button):
    case 'No Insurance Queue':
        require_once 'noInsQueue.php';
        break;
	case 'Process No Responses (Daily)': // Allow Process 5day and 14day limits on response.
		// show list of eligible records and button to process them
		// Confirm Button
		require_once('authprocessingDailyUpdate.php');
		rxDaily();
		break;
	case 'Requested Docs': // Updates DocSts to RQS
	case 'Requested Docs Late': // Updates DocSts to RQS
		$_POST['cpid']=$_SESSION['id'];
		require_once('authprocessingDocReqForm.php');
		break;
	case 'Sent Docs/Info': // Updates DocSts to SNT
		$_POST['cpid']=$_SESSION['id'];
		require_once('authprocessingDocSntForm.php');
		break;
	case 'Authorized': // Updates AuthSts to AUT
		$_POST['cpid']=$_SESSION['id'];
		require_once('authprocessingAuthorizedForm.php');
		break;
	case 'Denied': // Updates AuthSts to DEN if not AuthSts!=ASU otherwise notes account as Late Denial
	case 'Denied Late': // Updates AuthSts to DEN if not AuthSts!=ASU otherwise notes account as Late Denial
		$_POST['cpid']=$_SESSION['id'];
		require_once('authprocessingDeniedForm.php');
		break;
	default:
		require_once('searchBarForm.php');
		require_once('searchResultsForm.php');
endswitch;
?>