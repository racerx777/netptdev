<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);
document.title="Collection Notes"

function togglelienfields(toggle) {
	if(toggle) {
		document.getElementById("charges").blur();
		document.getElementById("charges").hidden=true;
		document.getElementById("payments").blur();
		document.getElementById("payments").hidden=true;
		document.getElementById("lienamount").blur();
		document.getElementById("lienamount").hidden=true;
	}
	else {
		document.getElementById("charges").hidden=false;
		document.getElementById("payments").hidden=false;
		document.getElementById("lienamount").hidden=false;
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

if(!empty($button) && (
	!empty( $noid) ||
	( !empty($app) && !empty($appid) ) ||
	( !empty($bnum) && !empty($pnum) )
	) ) {
//		ok
}
else {
//	error("001","Missing required value/identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
//	displaysitemessages();
//	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
//	exit();
}

$button='LienFiled';
$noid='';
$app='collections';
$appid='';
$bnum='WS';
$pnum='1917';

if(isset($_POST['submitbutton'])) {
// Add Functionality to Cancel Lien Request

// Format message fields and use notes system to insert note
	$app="collections";
	$type='SYS';
	$date=displayDate(today());
	if(isset($_POST['liendate']))
		$date=displayDate($_POST['liendate']);
	$datetime=dbDate($date);
	$lienuser=getuser();
	$caid=$appid;

	$note=strtoupper($note);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	if($_POST['lienaction']=='filed') {
		$charges=$_POST['charges'];
		$payments=$_POST['payments'];
		$lienamount=$_POST['lienamount'];
		$note=strtoupper("Lien Filed Date:$date Charges:$charges Payments:$payments Lien Amount:$lienamount $note");
		$data="$date:$charges:$payments:$lienamount:$note";
		$query = "
			UPDATE collection_accounts
			SET calienstatus='L', calienamount='$lienamount', caliendate='$datetime', calienuser='$lienuser'
			WHERE caid='$caid' and calienstatus='RL'
		";
		$notebutton=$button;
	}
	else {
		if($_POST['lienaction']=='canceled') {
			$note=strtoupper("Lien Request Canceled Date:$date Charges:$charges Payments:$payments Lien Amount:$lienamount $note");
			$data="$date::::$note:Canceled Lien Request";
			$query = "
				UPDATE collection_accounts
				SET calienstatus=NULL
				WHERE caid='$caid' and calienstatus='RL'
			";
			$notebutton='LienRequestCanceled';
		}
	}

	if(mysqli_query($dbhandle,$query)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
		noteAddSimple($type, $app, $appid, $bnum, $pnum, $notebutton, $note, $data);

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
		error("999","Error Updating Collection Account Lien Status.<br>$query<br>".mysqli_error($dbhandle));
		displaysitemessages();
	}
}



else {
// Actions should be taken here.
	$charges=0;
	$payments=0;
	$lientamount=0;
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT charges, payments
		FROM PTOS_Patients p
		WHERE pnum='$pnum'
	";
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$numrows=mysqli_num_rows($result);
		if($numrows==1) {
	// ok
			if($row=mysqli_fetch_assoc($result)) {
				$charges=displayCurrency($row['charges']);
				$payments=displayCurrency($row['payments']);
				$lienamount=displayCurrency($charges-$payments);
				$liendate=displayDate(today());
?>

<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
		<legend>Display/Update Notes</legend>
		<table cellpadding="5" cellspacing="0">
			<tr>
				<th colspan="3"><?php echo $bnum; ?> Lien Filed/Canceled Information</th>
			</tr>
			<tr>
				<td>Action</td>
				<td nowrap="nowrap" style="text-decoration:none">
					<label>
					<input type="radio" name="lienaction" value="filed" id="filed" onclick="togglelienfields(false)" checked="checked" />
					Lien Filed</label>
					&nbsp;or&nbsp;
					<label>
						<input type="radio" name="lienaction" value="canceled" id="canceled" onclick="togglelienfields(true)" />Canceled Lien Request
					</label>
					</td>
			</tr>
			<tr>
				<td>Action Date</td>
				<td nowrap="nowrap" style="text-decoration:none">
<input id="liendate" name="liendate" type="text" size="10" maxlength="10" value="<?php echo $liendate; ?>" onchange="checkinput(); validateDate(this.id);">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.liendate,'anchor1','MM/dd/yyyy'); return false;" />
					</td>
			<tr id="charges">
				<td> Charges </td>
				<td colspan="2"><input style="text-align:right" name="charges" type="text" value="<?php echo($charges); ?>"  /></td>
			</tr>
			<tr id="payments">
				<td> Payments </td>
				<td colspan="2"><input style="text-align:right" name="payments" type="text" value="<?php echo($payments); ?>"  /></td>
			</tr>
			<tr id="lienamount">
				<td>Lien Amount </td>
				<td colspan="2"><input style="text-align:right" name="lienamount" type="text" value="<?php echo($lienamount); ?>"  /></td>
			</tr>
			<tr>
				<td colspan="1" valign="top" nowrap="nowrap" >Action Note:</td>
				<td nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="note" name="note"></textarea></td>
			</tr>
			<tr>
				<td colspan="3"><input id="submitbutton" name="submitbutton" type="submit" value="Confirm Lien Done" />
					<input name="close" type="button" value="Exit" onclick="window.close()" />
					<input name="noid" type="hidden" value="<?php echo $noid ?>" />
					<input name="app" type="hidden" value="<?php echo $app ?>" />
					<input name="appid" type="hidden" value="<?php echo $appid ?>" />
					<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
					<input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
			}
			else {
// error on fetch
			}
		}
		else {
// error not == 1 row
		}
	}
	else {
// error result
	}
displaysitemessages();
}
?>
