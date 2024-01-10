<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15);
if(empty($_POST['crid']))
	$_POST['crid']=$_SESSION['id'];
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);
document.title="Collection Notes"
// Removes leading whitespaces
function noteKeypress() {
	var note = document.getElementById("note");
	var submitbutton = document.getElementById("submitbutton");
	if(note.value.length === 0)
		submitbutton.disabled=true;
	else {
		note.value = note.value.toUpperCase();
		submitbutton.disabled=false;
	}
}
</script>
<?php
if(isset($_POST['submitbutton']) && !empty($_POST['note'])) {
// Format message fields 
	$crid=$_POST['crid'];
	$data=strtoupper($_POST['note']);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/scheduling/SQLUpdateFunctions.php');
	caseschedulinghistoryadd($crid, $data);
	displaysitemessages();
}
else {
?>
<div class="centerFieldset">
	<form method="post" name="noteAddForm" onKeyPress="noteKeypress()">
		<fieldset style="text-align:center;">
		<legend>Add Scheduling History Note for <?php echo "$bnum $pnum - $patientname" ?></legend>
		<table cellpadding="5" cellspacing="0">
			<tr>
				<th>Add Scheduling History Note</th>
			</tr>
			<tr>
				<td nowrap="nowrap"><textarea wrap="soft" cols="115" rows="15" id="note" name="note" /><?php echo $_POST['note']; ?></textarea></td>
			</tr>
			<tr>
				<td><input id="submitbutton" name="submitbutton" type="submit" value="Confirm Add Note" disabled="disabled" />
				<input id="crid" name="crid" type="hidden" value"<?php echo $_POST['crid']; ?>" ></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php } ?>
