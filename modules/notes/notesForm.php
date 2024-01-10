<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(12);
?>
<script>
document.title="Display note"

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

function noteChange() {
	var note = document.getElementById("note");
	var submitbutton = document.getElementById("submitbutton");
	var notestring = trim(note.value);
	if(notestring.length === 0)
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function noteChangeCase(e, obj)  {
        var key = e.which || window.event.keyCode;
		noteChange();
        if ((key >= 65) && (key <= 90)) {
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
if(!empty($_REQUEST['noteid']))
	$noid=$_REQUEST['noteid'];

if(empty($noid)) {
	error("001","No Note identifier ($noid)");
	displaysitemessages(); 
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}

$_POST['note'] = strtoupper($_POST['note']);
$note = $_POST['note'];

if(isset($_POST['submitbutton']) && !empty($note)) {
	require_once('noteSQLFunctions.php');
	noteAddSimple($noid, $note, $application);
	unset($_POST['note']);
}

// Actions should be taken here.
$notehtmlrows=array();
$notenumrows=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(!empty($noid)) {
	$notequery = "
		SELECT * 
		FROM notes
		WHERE noid='$noid'
		ORDER BY crtdate desc
	";
	if($noteresult = mysqli_query($dbhandle,$notequery)) {
		$notenumrows=mysqli_num_rows($noteresult);
		if($notenumrows > 0) {
			$notehtmlrows[]="<tr><th>Date Added</th><th>Notes</th><th>Added by User</th></tr>";
			while($noterow=mysqli_fetch_assoc($noteresult)) {
				$notedate=displayDate($noterow['crtdate']) . " " . displayTime($noterow['crtdate']);
				$notedescription=strtoupper($noterow['nonote']);
				$noteuser=strtoupper($noterow['crtuser']);
				$notehtmlrows[]="<tr><td>$notedate</td><td>$notedescription</td><td>$noteuser</td></tr>";
			}
		}
		$notehtmlrows[]="<tr><th colspan='3'>$notenumrows notes found.</th></tr>";
	}
	else 
		error("001","notes: SELECT Error. $notequery<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
else
	error("002","notes: No Note Identifier.");

if(count($notehtmlrows) > 0) 
	$notehtml=implode("", $notehtmlrows);

displaysitemessages();
?>
<div class="centerFieldset">
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
		<legend>Display/Update Note</legend>
		<table cellpadding="5" cellspacing="0">
			<tr>
				<th colspan="3">Note</th>
			</tr>
			<tr>
				<td colspan="3">
					<input id="note" name="note" type="text" size="64" maxlength="64" value="<?php echo $_POST['note']; ?>" onkeypress="noteChangeCase(event, this);" />
					<input id="submitbutton" name="submitbutton" type="submit" value="Add note" disabled="disabled" />
					<input name="close" type="button" value="Close" onclick="window.close()" />
					<input name="noid" type="hidden" value="<?php echo $noid ?>" />
					<input name="noapp" type="hidden" value="<?php echo $noapp ?>" />
					<input name="noappid" type="hidden" value="<?php echo $noappid ?>" />
					<input name="nobnum" type="hidden" value="<?php echo $nobnum ?>" />
					<input name="nopnum" type="hidden" value="<?php echo $nopnum ?>" />
				</td>
			</tr>
			<?php echo $notehtml; ?>
		</table>
		</fieldset>
	</form>
</div>