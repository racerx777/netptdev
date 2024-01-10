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
	var submitbutton = document.getElementById("submitbutton");
	var notestring = trim(note.value);
	if(notestring.length === 0)
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
function noteKeypress() {
	var note = document.getElementById("nonote");
	var submitbutton = document.getElementById("submitbutton");
	if(note.value.length === 0)
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function noteChange() {
	var note = document.getElementById("nonote");
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
	error("001","Missing required value/identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
	displaysitemessages(); 
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}

if(isset($_POST['submitbutton']) && !empty($_POST['nonote'])) {
// Format message fields and use notes system to insert note
	$type='USR';
	$app="collections";
	$data=NULL;
	$note = strtoupper($_POST['nonote']);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsQueueFunctions.php');
	collectionsQueueUpdate($appid, $button);

	unset($_POST['nonote']);
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
					<th colspan="3">Add Notes</th>
				</tr>
				<tr>
					<td colspan="3" nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="nonote" name="nonote" onchange="noteChange();" onkeypress="noteKeypress();"><?php echo $_POST['nonote']; ?></textarea></td>
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
					<td colspan="3"><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php'); ?></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php } ?>
