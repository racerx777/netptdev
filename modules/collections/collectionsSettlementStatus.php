<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);

document.title="Collection Notes"

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

function checkinput() {
	var note = document.getElementById("note");
	var date = document.getElementById("casettledate");
	var stat = document.getElementById("casettlestatus");
	var amnt = document.getElementById("casettleamount");
	var submitbutton = document.getElementById("submitbutton");
	var notestring = trim(note.value);
	var datestring = trim(date.value);
	var statstring = trim(stat.value);
	var amntstring = trim(amnt.value);
	if(notestring.length === 0 || datestring.length===0 || statstring.length===0 || amntstring.length===0)
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function noteChangeCase(e, obj)  {
		checkinput();
		if(!e)
			var key = window.event.keyCode;
		else
			var key = e.which;
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
unset($noid);
unset($app);
unset($appid);
unset($bnum);
unset($pnum);
unset($button);
if(!empty($_REQUEST['noid']))
	$noid=$_REQUEST['noid'];
if(!empty($_REQUEST['app']))
	$app=$_REQUEST['app'];
if(!empty($_REQUEST['appid']))
	$appid=$_REQUEST['appid'];
if(!empty($_REQUEST['bnum']))
	$bnum=$_REQUEST['bnum'];
if(!empty($_REQUEST['pnum']))
	$pnum=$_REQUEST['pnum'];
if(!empty($_REQUEST['button']))
	$button=$_REQUEST['button'];
unset($patientname);
if(!empty($_REQUEST['patientname']))
	$patientname=$_REQUEST['patientname'];

if( !empty($button) && (
	!empty( $noid) ||
	( !empty($app) && !empty($appid) ) ||
	( !empty($bnum) && !empty($pnum) )
	) ) {
//		ok
}
else {
	error("001","Missing required value/identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
	displaysitemessages();
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}
if(isset($_POST['submitbutton']) && !empty($note)) {
// Format message fields and use notes system to insert note
	$type='SYS';
	$app='collections';
	$button='SettleStatus';
	$status = $_POST['casettlestatus'];
	$date = $_POST['casettledate'];
	$amount = $_POST['casettleamount'];
	$note = strtoupper($_POST['note']);
	$dataarray[]='Status:'.$status;
	$dataarray[]='Date:'.$date;
	$dataarray[]='Amount:'.$amount;
	$data=implode(", ", $dataarray);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsSettleStatusFunctions.php');
	collectionsSettleStatusUpdate($appid, $status, $date, $amount);

	unset($_POST['note']);
//	$_SESSION['button']='Work Account';
	$_SESSION['navigation']=$app;
	$_SESSION['id']=$appid;
	$_REQUEST['app']=$app;
	$_REQUEST['appid']=$appid;
	$_REQUEST['caid']=$appid;
	$_REQUEST['pnum']=$pnum;
	$_REQUEST['bnum']=$bnum;
	echo("<script>");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("</script>");
}
else {

// retrieve basic account information
if(empty($app) || empty($appid)) {
	if(empty($bnum) || empty($pnum)) {
		error("999","Missing required value/identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
		displaysitemessages();
		echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
		exit();
	}
	else
		$where = "bnum='$bnum' and pnum='$pnum'";
}
else
	$where = "caid='$appid'";
$query = "
SELECT *
FROM PTOS_Patients p
LEFT JOIN collection_accounts ca
ON bnum=cabnum and pnum=capnum
WHERE $where
";
//dump("query",$query);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$result = mysqli_query($dbhandle,$query);
if($result) {
	$numrows=mysqli_num_rows($result);
	if($numrows==0) {
		echo("No Patients or Collections Accounts Found for $bnum $pnum. No Row Fetched.");
		exit();
	}
	if($numrows == 1)
//		echo("One Record Found for $bnum $pnum. Row Fetched.");
	if($numrows > 1)
		echo("Multiple Records Found for $bnum $pnum. First Row Fetched.");
	$row = mysqli_fetch_assoc($result);
	if($row) {
		foreach($row as $fieldname=>$fieldvalue) {
			if(!empty($fieldvalue))
				$_POST[$fieldname] = $fieldvalue;
		}
	}
	else
		error('011', "Row error.$query<br>".mysqli_error($dbhandle));
}
else
	error('011', "SELECT error.$query<br>".mysqli_error($dbhandle));
// Actions should be taken here.
if(empty($_POST['caqphone']))
	$_POST['caqphone']=$_POST['phone'];
if(empty($_POST['caqphoneext']))
	$_POST['caqphoneext']=$_POST['phoneext'];
?>

<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
			<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th colspan="3">Update Settlement Status</th>
				</tr>
				<tr>
					<td>Settlement Status</td>
					<td colspan="2" nowrap="nowrap" style="text-decoration:none"><select name="casettlestatus" id="casettlestatus" >
							<?php echo getSelectOptions($arrayofarrayitems = collectionsSettleStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['casettlestatus'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
						</select></td>
				</tr>
				<tr>
					<td>Settlement Action Date </td>
					<td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="casettledate" name="casettledate" type="text" size="10" maxlength="10" value="<?php echo $casettledate; ?>" onchange"validateDate(this.id);">
						<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.casettledate,'anchor1','MM/dd/yyyy'); return false;" /></td>
				</tr>
				<tr>
					<td nowrap="nowrap">Settlement Action Amount</td>
					<td colspan="2" nowrap="nowrap"><input id="casettleamount" name="casettleamount" type="text" size="10" maxlength="10" value="<?php echo $_POST['casettleamount']; ?>" /></td>
				</tr>
				<tr>
					<td colspan="3"><textarea wrap="soft" cols="115" rows="15" id="note" name="note" onkeypress="checkinput();"></textarea></td>
				</tr>
				<tr>
					<td nowrap="nowrap"><input id="submitbutton" name="submitbutton" type="submit" value="Update Settlement Status" disabled="disabled" /></td>
					<td nowrap="nowrap"><input name="noid" type="hidden" value="<?php echo $noid ?>" />
						<input name="app" type="hidden" value="<?php echo $app ?>" />
						<input name="appid" type="hidden" value="<?php echo $appid ?>" />
						<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
						<input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
					<td nowrap="nowrap"><input name="close" type="button" value="Exit" onclick="window.close()" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php } ?>
