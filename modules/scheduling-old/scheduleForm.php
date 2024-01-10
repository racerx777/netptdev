<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
function phoneformat($str) {
	$mystr = preg_replace("/[^0-9]/", "", $str);
	if(strlen($mystr)==10) {
		$area = substr($mystr,0,3);
		$exch = substr($mystr,3,3);
		$numb = substr($mystr,6,4);
		$msg = "";
	}
	else {
		$area = "???";
		$exch = "???";
		$numb = "???";
		$msg = "Invalid phone format!";
	}
	return("($area) $exch-$numb $msg");
}
$id = $_SESSION['id'];
if(isset($id) && !empty($id)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	//if(mysqli_num_rows(mysqli_query($dbhandle,"SELECT * FROM case_scheduling_queue where csqid='$id'"))){
		$callquery = "SELECT * FROM case_scheduling_queue LEFT JOIN cases ON csqcrid=crid LEFT JOIN patients on crpaid=paid WHERE csqid='$id'";
	//}else{
		//$callquery = "SELECT * FROM case_scheduling_queue RIGHT JOIN cases ON csqcrid=crid LEFT JOIN patients on crpaid=paid WHERE crid='$id'";
	//}
	if($callresult = mysqli_query($dbhandle,$callquery)) {
		if(mysqli_num_rows($callresult)==1) {
			$callrow = mysqli_fetch_assoc($callresult);
			foreach($callrow as $key=>$val) {
				$_POST["$key"] = $val;
			}
		}
		else
			error("002", "Non-unique field error (should never happen).");	
	}
	else
		error("001", mysqli_error($dbhandle));	
}
if(errorcount() == 0) {
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Patient/Case Information:</legend>
		<table style="text-align:left;">
			<tr>
				<td>Patient Name</td>
				<td><?php echo($_POST['pafname'] . " " . $_POST['pamname'] . " " . $_POST['palname']); ?></td>
			</tr>
			<tr>
				<td>Address </td>
				<td><?php echo($_POST['paaddress']); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo($_POST['pacity'] . ", " . $_POST['pastate'] . " " . $_POST['pazip']); ?></td>
			</tr>
			<tr>
			<tr>
				<td>Clinic</td>
				<td><select name="crcnum" id="crcnum">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$_POST['crcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Appointment Date</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crfvisitdate" name="crfvisitdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['crfvisitdate'])) echo displayDate($_POST['crfvisitdate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].crfvisitdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Appointment Time</td>
				<td><select name="crfvisitdatetime" id="crfvisitdatetime" />
					<?php echo getSelectOptions($arrayofarrayitems=timeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$_POST['crfvisitdatetime'], $addblankoption=FALSE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo($id); ?>]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo($id); ?>]" type="submit" value="Confirm Schedule Referral" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
}
?>
