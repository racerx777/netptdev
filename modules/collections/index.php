<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
if(!isset($_POST['exit']))
	$_POST['exit']='';

if($_POST['exit']=='Exit') {
	$_SESSION['button']='Collections Search';
}
if($_POST['exit']=='Next') { 
	$_SESSION['button']='Collections Queue';
	if(!empty($_POST['caid'])) {
		$caid=$_POST['caid'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		unlockrow($dbhandle, 'collection_queue', 'cqcaid', $caid);
		unset($_SESSION['id']);
	}
	else
		dumppost();
}
if(isset($_SESSION['button']))
	$button = $_SESSION['button'];
else
	$button="";
//dump("button",$button);
// dumppost();
if(isset($_SESSION['id']))
	$id = $_SESSION['id'];
else
	$id="";

if(!empty($_POST['crpnum'])) {
	$idkey='crpnum';
	$id=$_POST['crpnum'];
}
if(!empty($_POST['caid'])) {
	$idkey='caid';
	$id=$_POST['caid'];
}
//dump("$idkey id",$id);

switch($button):
	case 'Back':
	case 'Cancel':
		$searchCollectionsSaved = getformvars('collections', 'searchCollection');
		$sortCollectionsSaved = getformvars('collections', 'searchCollectionsResults');
		$_POST['formSubmit']="1";
		break;
	case 'collectionsFunction':
		require_once('collectionsScript.php');
		collectionsFunction($id);
		unset($_SESSION['button']);
		break;
endswitch;

// Output begins here
displaysitemessages();
//dump("button",$_SESSION['button']);
if(!isset($_SESSION['button']))
	$_SESSION['button']='';

switch($_SESSION['button']):
	case 'collectionsDisplayFunction':
		require_once('collectionsDisplayForm.php');
		break;
	case 'Collections Queue':
		require_once('collectionsQueue.php');
		break;
	case 'Collections Search':
		require_once('collectionsSearchForm.php');
		require_once('collectionsSearchResultsForm.php');
		break;
	case 'Work Account':
		require_once('collectionsWorkAccount.php');
//		collectionsWorkAccount($idkey, $id);
		break;
        case 'Work Account New':
                require_once('collectionsWorkAccountNew.php');
                break;
	case 'Cancel Case':
		require_once('cancelForm.php');
		break;
	case 'Touched Accounts':
		require_once('collectionsTouchedAccountsSelection.php');
		break;
	case 'UnTouched Accounts':
		require_once('collectionsUnTouchedAccountsSelection.php');
		break;
	case 'Update Queue Assignment':
		dumppost();
		break;
	default:
		require_once('collectionsQueue.php');
		break;
endswitch;
?>