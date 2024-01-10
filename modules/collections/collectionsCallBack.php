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
	var note = document.getElementById("nonote");
	var date = document.getElementById("caqschcalldate");
	var time = document.getElementById("caqschcalldatetime");
	var phone = document.getElementById("caqphone");
	var submitbutton = document.getElementById("submitbutton");
	var notestring = trim(note.value);
	var datestring = trim(date.value);
	var timestring = trim(time.value);
	var phonestring = trim(phone.value);
	if(notestring.length === 0 || datestring.length===0 || timestring.length===0 || phonestring.length===0)
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function noteKeypress() {
	var note = document.getElementById("nonote");
	var submitbutton = document.getElementById("submitbutton");
	if(note.value.length === 0)
		submitbutton.disabled=true;
	else
		checkinput();
}

function noteChange() {
	var note = document.getElementById("nonote");
	note.value = note.value.toUpperCase();
	checkinput();
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

if(isset($_POST['submitbutton'])) {
	$callbackdatetime = dbDate($_POST['caqschcalldate']['date']." ".$_POST['caqschcalldate']['time']);
	$now = dbDate(date("Y-m-d H:i:s", time()));
	if($callbackdatetime<=$now)
		error("","Callback date cannot be before current date/time. $callbackdatetime < $now");
}

if(errorcount()==0 && isset($_POST['submitbutton']) && !empty($_POST['nonote'])) {
// Format message fields and use notes system to insert note
	$type='SYS';
	$app='collections';
	$button='Callback';

	$date1 = $_POST['caqschcalldate']['date'];
	$time1 = $_POST['caqschcalldate']['time'];
	$dataarray[]='Date:'.$date1;
	$dataarray[]='Time:'.$time1;
	$dataarray[]='Phone:'.$_POST['caqphone'];
	$data=implode(", ", $dataarray);

	$note = 'CALLBACK DATE/TIME:' .  $date1 . '/' . $time1 . ', ' . strtoupper($_POST['nonote']);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsQueueFunctions.php');
	$datetime="$date1 $time1";
	collectionsQueueUpdate($appid, $button, $datetime);

	unset($_POST['nonote']);
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
// default date to next week
$today=date("m/d/Y H:i:s", time());
$nextweek=date("m/d/Y", strtotime($today . " +7 days"));
$caqschcalldate['date']=$nextweek;

$thishour=date("H:i:s", time());
$nexthour=date("H:00:00", strtotime($thishour . " +1 Hour"));
$caqschcalldate['time']=$nexthour;

if(empty($_POST['upddate'])) { //use ptos
	if(empty($_POST['caqphone']))
		$_POST['caqphone']=$_POST['pphone'];
	if(empty($_POST['caqphoneext']))
		$_POST['caqphoneext']="";
}
else { // use ca
	if(empty($_POST['caqphone']))
		$_POST['caqphone']=$_POST['caadjuster1phone'];
	if(empty($_POST['caqphoneext']))
		$_POST['caqphoneext']=$_POST['caadjuster1ext'];
}
?>

<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
			<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th colspan="3">Schedule Call Back </th>
				</tr>
				<tr>
					<td>Callback Date </td>
					<td colspan="2" nowrap="nowrap" style="text-decoration:none">
					<input id="caqschcalldate" name="caqschcalldate[date]" type="text" size="10" maxlength="10" value="<?php echo $caqschcalldate['date']; ?>" onchange="checkinput();">
						<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.caqschcalldate,'anchor1','MM/dd/yyyy'); return false;" /></td>
				</tr>
				<tr>
					<td>Callback Time </td>
					<td colspan="2"><select id="caqschcalldatetime" name="caqschcalldate[time]" onchange="checkinput();" />

						<?php echo getSelectOptions($arrayofarrayitems=timeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$caqschcalldate['time'], $addblankoption=FALSE, $arraykey="", $arrayofmatchvalues=array()); ?>
						</select></td>
				</tr>
				<tr>
					<td>Callback Phone </td>
					<td><input id="caqphone" name="caqphone" type="text" size="14" maxlength="14" value="<?php echo $_POST['caqphone']; ?>" onchange="checkinput();" /></td>
					<td>Extension
						<input id="caqphoneext" name="caqphoneext" type="text" size="10" maxlength="10" value="<?php echo $_POST['caqphoneext']; ?>" onchange="checkinput();"/></td>
				</tr>

				<tr>
					<td colspan="3" nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="nonote" name="nonote" onchange="noteChange();" onkeypress="noteKeypress();"></textarea></td>
				</tr>
				<tr>
					<td><input id="submitbutton" name="submitbutton" type="submit" value="Add Call Back" disabled="disabled" /></td>
					<td><input name="noid" type="hidden" value="<?php echo $noid ?>" />
						<input name="app" type="hidden" value="<?php echo $app ?>" />
						<input name="appid" type="hidden" value="<?php echo $appid ?>" />
						<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
						<input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
					<td><input name="close" type="button" value="Exit" onclick="window.close()" /></td>
				</tr>
				<tr>
					<td colspan="3"><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php'); ?></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php } ?>
