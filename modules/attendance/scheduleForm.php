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

$crid = $_SESSION['id'];

if(isset($crid) && !empty($crid)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$callquery = "SELECT * FROM cases LEFT JOIN patients ON crpaid=paid WHERE crid='$crid'";
	if($callresult = mysqli_query($dbhandle,$callquery)) {
		if(mysqli_num_rows($callresult)==1) {
			$callrow = mysqli_fetch_assoc($callresult);
			foreach($callrow as $key=>$val) {
				$_POST["$key"] = $val;
			}
		}
		else
			error("002", "No Call Queue Entry or Non-unique field error (should never happen).");	
	}
	else
		error("001", mysqli_error($dbhandle));	
}
if(errorcount() == 0) {
$_POST['apptdate']=displayDate($_POST['crapptdate']);
$_POST['appttime']=displayTime($_POST['crapptdate']);
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Patient/Case Information:</legend>
		<table style="text-align:left;">
			<tr>
				<td>Patient Name</td>
				<td><?php echo($_POST['crfname'] . " " . $_POST['crmname'] . " " . $_POST['crlname']); ?></td>
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
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$_POST['crcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array(),TRUE ); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Appointment Date</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="apptdate" name="apptdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['apptdate'])) echo displayDate($_POST['apptdate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].apptdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Appointment Time</td>
				<td><select name="appttime" id="appttime" />
					<?php echo getSelectOptions($arrayofarrayitems=timeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$_POST['appttime'], $addblankoption=FALSE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo($crid); ?>]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo($crid); ?>]" type="submit" value="Confirm Schedule Referral" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
		<input type="hidden" name="crid" value="<?php echo $crid ?>" />
	</form>
</div>
<?php
}
?>
