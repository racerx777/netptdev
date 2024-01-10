<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);
document.title="Collection Resubmit Unpaid Bills Notes"
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
function noteKeypress() {
	var note = document.getElementById("note");
	note.value = note.value.toUpperCase();
}
function noteChange() {
	var note = document.getElementById("note");
	note.value = note.value.toUpperCase();
}
function checkinput() {
	var resubmitdate = document.getElementById("resubmitdate");
	var resubmitamount = document.getElementById("resubmitamount");
	var viausmail = document.getElementById("viausmail");
	var viafax = document.getElementById("viafax");
	var viaemail = document.getElementById("viaemail");
	var insname = document.getElementById("insname");
	var insaddress1 = document.getElementById("insaddress1");
	var insaddress2 = document.getElementById("insaddress2");
	var insaddress3 = document.getElementById("insaddress3");
	var insadjusterfax = document.getElementById("insadjusterfax");
	var insadjusteremail = document.getElementById("insadjusteremail");

	var datestring = trim(resubmitdate.value);
	var amountstring = trim(resubmitamount.value);
	if(viausmail.checked) {
		var viausmailstring = trim(viausmail.value);
		var insaddressstring = trim(insaddress1.value) + trim(insaddress2.value) + trim(insaddress3.value);
	}
	else {
		var viausmailstring="";
		var insaddressstring="";
	}
	if(viafax.checked) {
		var viafaxstring = trim(viafax.value);
		var insadjusterfaxstring = trim(insadjusterfax.value);
	}
	else {
		var viafaxstring = "";
		var insadjusterfaxstring = trim(insadjusterfax.value);
	}
	if(viaemail.checked) {
		var viaemailstring = trim(viaemail.value);
		var insadjusteremailstring = trim(insadjusteremail.value);
	}
	else {
		var viaemailstring="";
		var insadjusteremailstring="";
	}
	var insnamestring = trim(insname.value);
	var insaddressstring = trim(insaddress1.value) + trim(insaddress2.value) + trim(insaddress3.value);

	var submitbutton = document.getElementById("ResubmitBills");

	if(
		datestring.length === 0 ||
		amountstring.length === 0 ||
		(viausmailstring.length!==0 && insaddressstring.length === 0) ||
		(viafaxstring.length !== 0 && insadjusterfaxstring.length===0) ||
		(viaemailstring.length !== 0 && insadjusteremailstring.length===0) ||
		(viausmailstring.length === 0 && viafaxstring.length === 0 && viaemailstring.length === 0 ) ||
		insnamestring.length === 0
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

if(isset($_POST['ResubmitBills'])) {
// Format message fields and use notes system to insert note
	$app="collections";
	$type='SYS';
	$date=$_POST['resubmitdate'];
	$amount=$_POST['resubmitamount'];
	$note="Resubmitted unpaid bills Amount:$amount. ".strtoupper($_POST['note']);
	foreach($_POST as $key=>$value)
		$dataarray[$key]=$value;
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
	echo("<script>");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("</script>");
}
else {
// Actions should be taken here.
if(!isset($_POST['init'])) {
	$_POST['resubmitdate']=today();


	$query = "
		SELECT pinsurance, padjust, tbal, cainsname1, caadjuster1, 	caadjuster1fax, caadjuster1email, ca.upddate
		FROM PTOS_Patients p
		LEFT JOIN collection_accounts ca
		ON bnum=cabnum and pnum=capnum
		WHERE pnum='$pnum'
	";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if($result = mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {

			$upddate=$row['upddate'];
			if(!empty($upddate)) {
				$insurance=$row['cainsname1'];
				$adjuster=$row['caadjuster1'];
			}
			else {
				$insurance=$row['pinsurance'];
				$adjuster=$row['padjust'];
				$_POST['resubmitamount']=$row['tbal'];
			}

			require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
			if(!empty($insurance)) {
				if($insuranceinfo=getPTOSInsuranceCompanyInformation($bnum, $insurance)) {
					$_POST['insname']=$insuranceinfo['iname'];
					$_POST['insaddress1']=$insuranceinfo['iadd1'];
					$_POST['insaddress2']=$insuranceinfo['iadd2'];
					$_POST['insaddress3']=$insuranceinfo['iadd3'];
				}
			}

			$_POST['insadjuster']=$djuster;
			$_POST['insadjusterfax']=$row['caadjuster1fax'];
			$_POST['insadjusteremail']=$row['caadjuster1fax'];
		}
	}


}
?>

<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
		<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
		<table cellpadding="5" cellspacing="0">
			<tr>
				<th colspan="2">Resubmit bill for payment</th>
			</tr>
			<tr>
				<td>Re-Submit Date</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="resubmitdate" name="resubmitdate" type="text" size="10" maxlength="10" value="<?php echo $_POST['resubmitdate']; ?>" onchange="checkinput(); validateDate(this.id);">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.resubmitdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Re-Submit Amount</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="resubmitamount" name="resubmitamount" type="text" size="13" maxlength="13" value="<?php echo $_POST['resubmitamount']; ?>" onchange="checkinput();"></td>
			</tr>
			<tr>
				<td>Insurance Company</td>
				<td><input size="35" name="insname" id="insname" maxlength="64" type="text" value="<?php echo $_POST['insname']; ?>" onchange="checkinput();" /></td>
			</tr>
			<tr>
				<td>Send Via:</td>
				<td><table>
						<tr>
							<td><label>
								<input type="checkbox" name="viausmail" value="USMAIL" id="viausmail" onchange="checkinput();" />
								US Mail</label></td>
							<td><input name="insaddress1" id="insaddress1" type="text" size="35" value="<?php echo $_POST['insaddress1']; ?>" onchange="checkinput();" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input name="insaddress2" id="insaddress2" size="35" type="text" value="<?php echo $_POST['insaddress2']; ?>" onchange="checkinput();" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input name="insaddress3" id="insaddress3" size="35" type="text" value="<?php echo $_POST['insaddress3']; ?>" onchange="checkinput();" /></td>
						</tr>
						<tr>
							<td><label>
								<input type="checkbox" name="viafax" value="FAX" id="viafax" onchange="checkinput();" />
								Fax</label></td>
							<td><input type="text" name="insadjusterfax" id="insadjusterfax" size="20" maxlength="20" value="<?php echo $_POST['insadjusterfax']; ?>" onchange="checkinput();"></td>
						</tr>
						<tr>
							<td><label>
								<input type="checkbox" name="viaemail" value="EMAIL" id="viaemail" onchange="checkinput();" />
								E-mail</label></td>
							<td><input type="text" name="insadjusteremail" id="insadjusteremail" size="20" maxlength="64" value="<?php echo $_POST['insadjusteremail']; ?>" onchange="checkinput();"></td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<td colspan="2" nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="note" name="note" onchange="noteChange();" onkeypress="noteKeypress();"></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><input id="ResubmitBills" name="ResubmitBills" type="submit" value="Account Resubmitted for Payment" disabled="disabled" />
					<input name="close" type="button" value="Exit" onclick="window.close()" />
					<input name="noid" type="hidden" value="<?php echo $noid ?>" />
					<input name="app" type="hidden" value="<?php echo $app ?>" />
					<input name="appid" type="hidden" value="<?php echo $appid ?>" />
					<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
					<input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $notehtml; ?></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php } ?>
