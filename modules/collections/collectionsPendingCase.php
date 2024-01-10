<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
//require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
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

function noteKeypress() {
	var pendingdays = document.getElementById("pendingdays");
	var note = document.getElementById("note");
	var submitbutton = document.getElementById("submitbutton");
	var pendingdaysstring = trim(pendingdays.value);
	if(note.value.length === 0 || pendingdaysstring.length === 0)
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function noteChange() {
	var note = document.getElementById("note");
	note.value = note.value.toUpperCase();
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
// Format message fields and use notes system to insert note
	$type='SYS';
	$app='collections';
	$pendingdays=$_POST['pendingdays'];
	$today=date("Y-m-d H:i:s", time());
	$date=date("Y-m-d H:i:s", strtotime($today . "+ $pendingdays days"));
	$note="Case is Pending. Followup after $pendingdays days ($date). ".$_POST['note'];
	$data="$pendingdays:$date";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsQueueFunctions.php');
	collectionsQueueUpdate($appid, $button, $date);

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
?>

<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
			<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th colspan="3">Set <?php echo $bnum; ?> Pending Case</th>
				</tr>
				<tr>
					<td>Pending Case Follow-up</td>
					<td colspan="2" nowrap="nowrap" style="text-decoration:none"><select name="pendingdays" id="pendingdays" onchange="noteKeypress();">
							<option value="" selected="selected"></option>
							<option value="30">30 Days</option>
							<option value="60">60 Days</option>
							<option value="90">90 Days</option>
							<option value="120">120 Days</option>
						</select></td>
				</tr>
				<tr>
					<td colspan="3" nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="note" name="note" onchange="noteChange();" onkeypress="noteKeypress();"></textarea></td>
				</tr>
				<tr>
					<td><input id="submitbutton" name="submitbutton" type="submit" value="Set Pending Case Follow-up" disabled="disabled" /></td>
					<td><input name="noid" type="hidden" value="<?php echo $noid ?>" />
						<input name="app" type="hidden" value="<?php echo $app ?>" />
						<input name="appid" type="hidden" value="<?php echo $appid ?>" />
						<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
						<input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
					<td><input name="close" type="button" value="Exit" onclick="window.close()" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php } ?>
