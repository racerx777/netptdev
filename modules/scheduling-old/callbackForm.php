<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script type="text/javascript">
function updatephonenumber(index) {
	var phonetype=document.callbackForm.phonetype;
	var csqphone=document.callbackForm.csqphone;
	csqphone.value = phonetype.value;
}
</script>
<?php
$csqid=$_SESSION['id'];
if(!empty($csqid) && !isset($_POST['formLoaded'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	//if(mysqli_num_rows(mysqli_query($dbhandle,"SELECT * FROM case_scheduling_queue where csqid='$csqid'"))){
		$query = "
			SELECT csqid, csqcrid, csqpriority, csqschcalldate, csqphone , paphone1, paphone2, pacellphone
			FROM case_scheduling_queue 
			LEFT JOIN cases
			on csqcrid=crid
			LEFT JOIN patients
			on crpaid=paid
			WHERE csqid='$csqid'";
	// }else{
	// 	$query = "
	// 		SELECT csqid, csqcrid, csqpriority, csqschcalldate, csqphone , paphone1, paphone2, pacellphone
	// 		FROM case_scheduling_queue 
	// 		RIGHT JOIN cases
	// 		on csqcrid=crid
	// 		LEFT JOIN patients
	// 		on crpaid=paid
	// 		WHERE crid='$csqid'";
	// }

	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			foreach($row as $field=>$value)
				$_POST["$field"]=$value;
		}
		else
			error('001', "FETCH error. $query<br>".mysqli_error($dbhandle));	
	}
	else
		error('002', "SELECT error. $query<br>".mysqli_error($dbhandle));	
}
else
	error("003", "Queue Id not set error.$csqid");

if(errorcount()==0) {
	$csqid=$_POST['csqid'];
	$csqcrid=$_POST['csqcrid'];
	$csqpriority = $_POST['csqpriority'];
	if(!empty($_POST['csqschcalldate'])) 
		$datetime = $_POST['csqschcalldate'];
	else 
		$datetime=date('Y-m-d H:i:s',time());
	$csqschcalldate['date']=displayDate($datetime);
	$csqschcalldate['time']=displayTime($datetime);

	if(!empty($_POST['csqphone']))
		$csqphone=displayPhone($_POST['csqphone']);
	else
		unset($csqphone);

	$phonenumbers=array();
	if(!empty($_POST['paphone1']))
		$phonenumbers[]=array('value'=>displayPhone($_POST['paphone1']),'title'=>'Home');
	if(!empty($_POST['paphone2']))
		$phonenumbers[]=array('value'=>displayPhone($_POST['paphone2']),'title'=>'Work');
	if(!empty($_POST['pacellphone']))
		$phonenumbers[]=array('value'=>displayPhone($_POST['pacellphone']),'title'=>'Mobile');
	
	$phoneoptions=getSelectOptions($arrayofarrayitems=$phonenumbers, $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$csqphone, $addblankoption=TRUE, $arraykey="", $arrayofmatchvalues=array())
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="callbackForm"><input type="hidden" name="formLoaded" value="1" /><input type="hidden" name="csqid" value="<?php echo $csqid;?>" /><input type="hidden" name="csqcrid" value="<?php echo $csqcrid; ?>" /><input type="hidden" name="csqpriority" value="<?php echo $csqpriority; ?>" />
		<fieldset style="text-align:center;">
		<legend>Callback Queue Entry <?php echo $csqid; ?></legend>
		<table style="text-align:left;">
				<td>Callback Date </td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="csqschcalldate" name="csqschcalldate[date]" type="text" size="10" maxlength="10" value="<?php echo $csqschcalldate['date']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.callbackForm.csqschcalldate,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Callback Time </td>
				<td><select id="csqschcalldatetime" name="csqschcalldate[time]" />
					<?php echo getSelectOptions($arrayofarrayitems=timeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$csqschcalldate['time'], $addblankoption=FALSE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Callback Phone </td>
				<td><select id="phonetype" name="phonetype" onchange="updatephonenumber(this.selected)" ><?php echo $phoneoptions; ?></select>
				<input id="csqphone" name="csqphone" type="text" size="14" maxlength="14" value="<?php echo $_POST['csqphone']; ?>" onchange="formatphone(this.id)" />
				</td>
			</tr>
			<tr>
				<td valign="top">Callback Note/Reason </td>
				<td>
					<textarea id="cshdata" name="cshdata" onchange="uppercase(this.id)" cols="80" rows="10" ><?php echo $_POST['cshdata']; ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_POST['csqid']; ?>]" type="submit" value="Confirm Callback Referral" />
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
