<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
require_once('config.php');
if($_REQUEST['searchfunction']=='Search') {
	$_POST['button'][0]='Search';
}
if($_POST['button'][0]=='Search') {
	$_REQUEST['searchfunction']='Search';
}
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
if(isset($_REQUEST['searchfromtreatmentdate']) && !empty($_REQUEST['searchfromtreatmentdate']))
	$_POST['searchfromtreatmentdate'] = $_REQUEST['searchfromtreatmentdate'];
if(isset($_REQUEST['searchtotreatmentdate']) && !empty($_REQUEST['searchtotreatmentdate']))
	$_POST['searchtotreatmentdate'] = $_REQUEST['searchtotreatmentdate'];
if(isset($_REQUEST['searchlname']) && !empty($_REQUEST['searchlname']))
	$_POST['searchlname'] = $_REQUEST['searchlname'];
if(isset($_REQUEST['searchfname']) && !empty($_REQUEST['searchfname']))
	$_POST['searchfname'] = $_REQUEST['searchfname'];
if(isset($_REQUEST['searchcnum']) && !empty($_REQUEST['searchcnum']))
	$_POST['searchcnum'] = $_REQUEST['searchcnum'];
// Process button press
switch($_REQUEST['searchfunction']):
	case 'Reset Search':
		cleartreatmentsearchvalues();
		break;

	case 'Cancel/Return':
endswitch;

displaysitemessages();
?>
<form action="" method="post" name="searchForm" target="_self">
<?php
require_once('searchBarForm.php');
require_once('searchResultsForm.php');
?>
</form>