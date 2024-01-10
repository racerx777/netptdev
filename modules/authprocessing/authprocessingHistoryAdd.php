<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15);
?>
<script>
document.title="Display Prescription History"

// Removes leading whitespaces
function LTrim( value ) {
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}

// Removes ending whitespaces
function RTrim( value ) {
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}

// Removes leading and ending whitespaces
function trim( value ) {
	return LTrim(RTrim(value));
}

function historyChange() {
	var history = document.getElementById("history");
	var submitbutton = document.getElementById("submitbutton");
	var historystring = trim(history.value);
	if(historystring.length === 0)
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function historyChangeCase(e, obj)  {
        var key = e.which || window.event.keyCode;
		historyChange();
        if ((key >= 65) && (key <= 90))  {
                    obj.value+=String.fromCharCode(key).toLowerCase(); 
					if (e.preventDefault)
						e.preventDefault();	
					e.returnValue = false; 	
                }
        if ((key >= 97) && (key <= 122)) {    	
					obj.value+=String.fromCharCode(key).toUpperCase(); 
					if (e.preventDefault)
						e.preventDefault();	
					e.returnValue = false; 			
        }
} 

</script>
<?php
// handle request parameters
unset($cpid);
if(!empty($_REQUEST['cpid']))
	$cpid=$_REQUEST['cpid'];

if(empty($cpid)) {
	error("001","No Case identifier ($crid) or prescription ($cpid)");
	displaysitemessages(); 
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}

$_POST['history'] = strtoupper($_POST['history']);
$history = $_POST['history'];

if(isset($_POST['submitbutton']) && !empty($history)) {
	require_once('historySQLFunctions.php');
	addPrescriptionHistorySimple($cpid, $history, 'authprocessing');
	unset($_POST['history']);
}

// Actions should be taken here.
$historyhtmlrows=array();
$historynumrows=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(!empty($cpid)) {
	$historyquery = "
		SELECT cphdate, cphhistory, cphuser 
		FROM case_prescriptions_history
		WHERE cphcpid='$cpid'
		ORDER BY cphdate desc
	";
	if($historyresult = mysqli_query($dbhandle,$historyquery)) {
		$historynumrows=mysqli_num_rows($historyresult);
		if($historynumrows > 0) {
			$historyhtmlrows[]="<tr><th>Date Added</th><th>History Notes</th><th>Added by User</th></tr>";
			while($historyrow=mysqli_fetch_assoc($historyresult)) {
				$historydate=displayDate($historyrow['cphdate']) . " " . displayTime($historyrow['cphdate']);
				$historydescription=strtoupper($historyrow['cphhistory']);
				$historyuser=strtoupper($historyrow['cphuser']);
				$historyhtmlrows[]="<tr><td>$historydate</td><td>$historydescription</td><td>$historyuser</td></tr>";
			}
		}
		$historyhtmlrows[]="<tr><th colspan='3'>$historynumrows History records found.</th></tr>";
	}
	else 
		error("001","authprocessingHistory: SELECT Error. $query<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
else
	error("002","authprocessingHistory: No Case Prescription Identifier.");

if(count($historyhtmlrows) > 0) 
	$historyhtml=implode("", $historyhtmlrows);

displaysitemessages();
?>

<div class="centerFieldset">
	<form method="post" name="historyEditForm">
		<fieldset style="text-align:center;">
			<legend>Display/Update History</legend>
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th colspan="3">History Note</th>
				</tr>
				<tr>
					<td colspan="3"><textarea wrap="soft" cols="64" rows="15" id="history" name="history" onkeypress="historyChange();"></textarea></td>
				</tr>
				<tr>
					<td><input id="submitbutton" name="submitbutton" type="submit" value="Add History" disabled="disabled" /></td>
					<td><input name="cpid" type="hidden" value="<?php echo $cpid ?>" /></td>
					<td align="right"><input name="close" type="button" value="Close" onclick="window.close()" /></td>
				</tr>
				<?php echo $historyhtml; ?>
			</table>
		</fieldset>
	</form>
</div>
