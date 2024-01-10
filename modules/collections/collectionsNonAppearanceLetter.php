<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);
document.title="Collection Non-Appearance Letter"
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
	var scheduledactiondate = document.getElementById("scheduledactiondate");
	var scheduledaction = document.getElementById("scheduledaction");
	var venuename = document.getElementById("venuename");
	var venueaddress1 = document.getElementById("venueaddress1");
	var venueaddress2 = document.getElementById("venueaddress2");
	var venueaddress3 = document.getElementById("venueaddress3");
	var patient = document.getElementById("patient");
	var employer = document.getElementById("employer");
	var wcabnumber = document.getElementById("wcabnumber");
	var cicstatus = document.getElementById("cicstatus");
	var lienamount = document.getElementById("lienamount");

	var datestring = trim(scheduledactiondate.value);
	var scheduledaction = trim(scheduledaction.value);
	var venuenamestring = trim(venuename.value);
	var venueaddressstring = trim(venueaddress1.value) + trim(venueaddress2.value) + trim(venueaddress3.value);
	var patientstring = trim(patient.value);
	var employerstring = trim(employer.value);
	var wcabnumberstring = trim(wcabnumber.value);
	var cicstatusstring = trim(cicstatus.value);
	var lienamountstring = trim(lienamount.value);

	var submitbutton = document.getElementById("CreateNonAppearanceLetter");

	if(
		datestring.length === 0 ||
		scheduledaction.length === 0 ||
		venuenamestring.length === 0 ||
		venueaddressstring.length === 0 ||
		patientstring.length === 0 ||
		employerstring.length === 0 ||
		wcabnumberstring.length === 0 ||
		cicstatusstring.length === 0 ||
		lienamountstring.length === 0
	)
	{
		submitbutton.disabled=true;
	}
	else
		submitbutton.disabled=false;
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

if(isset($_POST['CreateNonAppearanceLetter'])) {
// Format message fields and use notes system to insert note
	$app='collections';
	$type='SYS';
	$date=$_POST['scheduledactiondate'];
	$action=$_POST['scheduledaction'];
	$note="Non-Appearance Letter Created for $action on $date.";
	foreach($_POST as $key=>$value)
		$dataarray[$key]=mysqli_real_escape_string($dbhandle,$value);
	$data=serialize($dataarray);
//	$data="$date $amount $sendvia $insadjuster $insname $insaddress $inscity $insstate $inszip";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsQueueFunctions.php');
	collectionsQueueUpdate($appid, $button);

	unset($_POST['note']);
	$_SESSION['navigation']=$app;
	$_SESSION['id']=$appid;
	$_REQUEST['app']=$app;
	$_REQUEST['appid']=$appid;
	$_REQUEST['caid']=$appid;
	$_REQUEST['pnum']=$pnum;
	$_REQUEST['bnum']=$bnum;
	foreach($_POST as $key=>$val)
		$req.="&$key=" . urlencode($val);
	echo("<script>");
	echo("window.open('/modules/collections/collectionsPrintForms.php?$req','CreateNonAppearanceLetter');");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("</script>");
}
else {
// Actions should be taken here.
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

	if(empty($courtoptions)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/court.options.php');
		$courtoptions=getCourtOptions($_POST['venuecode']);
	}

	if(empty($_POST['scheduledactiondate']))
		$_POST['scheduledactiondate']=today();
	if(empty($_POST['scheduledaction']))
		$_POST['scheduledaction']="enter scheduled action here";

	if(empty($_POST['venuename']))
		$_POST['venuename']='Judge';
	if(isset($_POST['venueselect'])) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/court.options.php');
		$thiscourt=getCourtInformation($_POST['venueselect']);
		$_POST['venueaddress1']=$thiscourt['cname'];
		$_POST['venueaddress2']=$thiscourt['caddress1'];
		$_POST['venueaddress3']=$thiscourt['caddress2'];
		$_POST['venueaddress4']=$thiscourt['caddress3'];
	}
	else {
		if(empty($_POST['venueaddress1']))
			$_POST['venueaddress1']='Court Address 1';
		if(empty($_POST['venueaddress1']))
			$_POST['venueaddress2']='Court Address 2';
		if(empty($_POST['venueaddress1']))
			$_POST['venueaddress3']='Court Address 3';
	}

	if(empty($_POST['first']))
		$_POST['first']=$_POST['fname'];
	if(empty($_POST['last']))
		$_POST['last']=$_POST['lname'];

	if(empty($_POST['employer']))
		$_POST['employer']=$_POST['emp'];

	if(empty($_POST['wcab']))
		$_POST['wcab']=$_POST['cawcab1'];

	if(empty($_POST['cicstatus']))
		$_POST['cicstatus']='enter CIC status here';

	if(empty($_POST['lienamount']))
		$_POST['lienamount']=$_POST['calienamount'];
	if(empty($_POST['lienamount']))
		$_POST['lienamount']='enter lien amount here';

	if(empty($_POST['collector']))
		$_POST['collector']=getusername();
?>
<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
			<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th colspan="3">Create Non-Appearance Letter</th>
				</tr>
				<tr>
					<td>Scheduled Action Date</td>
					<td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="scheduledactiondate" name="scheduledactiondate" type="text" size="10" maxlength="10" value="<?php echo $_POST['scheduledactiondate']; ?>" onchange="checkinput(); validateDate(this.id);">
						<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.scheduledactiondate,'anchor1','MM/dd/yyyy'); return false;" /></td>
				</tr>
				<tr>
					<td>Scheduled Action</td>
					<td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="scheduledaction" name="scheduledaction" type="text" size="32" maxlength="64" value="<?php echo $_POST['scheduledaction']; ?>" onchange="checkinput();"></td>
				</tr>
				<tr>
					<td>Select Venue</td>
					<td colspan="2"><select name="venueselect" id="venueselect" onchange="javascript:submit();">
							<?php echo $courtoptions; ?>
						</select></td>
				</tr>
				<tr>
					<td>To Name </td>
					<td colspan="2"><input size="35" name="venuename" id="venuename" maxlength="64" type="text" value="<?php echo $_POST['venuename']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>To Address</td>
					<td colspan="2"><input name="venueaddress1" id="venueaddress1" type="text" size="35" value="<?php echo $_POST['venueaddress1']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input name="venueaddress2" id="venueaddress2" size="35" type="text" value="<?php echo $_POST['venueaddress2']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input name="venueaddress3" id="venueaddress3" size="35" type="text" value="<?php echo $_POST['venueaddress3']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>Patient</td>
					<td><input name="first" id="first" size="35" type="text" value="<?php echo $_POST['first']; ?>" onchange="checkinput();" /></td>
					<td><input name="last" id="last" size="35" type="text" value="<?php echo $_POST['last']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>Employer</td>
					<td colspan="2"><input name="employer" id="employer" size="35" type="text" value="<?php echo $_POST['employer']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>WCAB Number</td>
					<td colspan="2"><input name="wcab" id="wcab" size="35" type="text" value="<?php echo $_POST['wcab']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>CIC Status</td>
					<td colspan="2"><input name="cicstatus" id="cicstatus" size="35" type="text" value="<?php echo $_POST['cicstatus']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>Lien Amount</td>
					<td colspan="2"><input name="lienamount" id="lienamount" size="35" type="text" value="<?php echo $_POST['lienamount']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td>Collector</td>
					<td colspan="2"><input name="collector" id="collector" type="text" size="35" maxlength="30"value="<?php echo $_POST['collector']; ?>" onchange="checkinput();" /></td>
				</tr>
				<tr>
					<td colspan="3"><input id="CreateNonAppearanceLetter" name="CreateNonAppearanceLetter" type="submit" value="Create Non-Appearance Letter"/>
						<input name="close" type="button" value="Exit" onclick="window.close()" />
						<input name="noid" type="hidden" value="<?php echo $noid ?>" />
						<input name="app" type="hidden" value="<?php echo $app ?>" />
						<input name="appid" type="hidden" value="<?php echo $appid ?>" />
						<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
						<input name="pnum" type="hidden" value="<?php echo $pnum ?>" />
						<input name="button" type="hidden" value="<?php echo $button ?>" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php } ?>
