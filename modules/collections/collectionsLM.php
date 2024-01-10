<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
?>
<script>
document.title="Collection Notes"

function noteKeypress() {
	var note = document.getElementById("note");
	var submitbutton = document.getElementById("submitbutton");
	if(note.value.length === 0)
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
unset($patientname);
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
	error("001","Missing required identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum)");
	displaysitemessages(); 
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}

$_POST['note'] = strtoupper($_POST['note']);
$note = $_POST['note'];

if(isset($_POST['submitbutton']) && !empty($note)) {
// Format message fields and use notes system to insert note
	$type='SYS';
	$app='collections';
	$data=NULL;
	$note = strtoupper($note);
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
?>

<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
			<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th colspan="3">Left Message</th>
				</tr>
				<tr>
					<td colspan="3" nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="note" name="note" onchange="noteChange();" onkeypress="noteKeypress();"></textarea></td>
				</tr>
				<tr>
					<td><input id="submitbutton" name="submitbutton" type="submit" value="Add Note" disabled="disabled" /></td>
					<td><input name="noid" type="hidden" value="<?php echo $noid ?>" />
						<input name="app" type="hidden" value="<?php echo $app ?>" />
						<input name="appid" type="hidden" value="<?php echo $appid ?>" />
						<input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
						<input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
					<td><input name="close" type="button" value="Exit" onclick="window.close()" /></td>
				</tr>
				<tr>
					<td><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php'); ?></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php
}
?>
