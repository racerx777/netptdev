<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
$doctorlistoptions="";
$doctorlist = getDoctorList();
if($doctorlist) {
	if(count($doctorlist) > 0) {
		$doctorlistoptions =  getSelectOptions(
			$arrayofarrayitems=$doctorlist, 
			$optionvaluefield='dmid', 
			$arrayofoptionfields=array(
				'dmlname'=>', ', 
				'dmfname'=>'' 
				), 
			$defaultoption=$_POST['refdmid'], 
			$addblankoption=TRUE, 
			$arraykey='', 
			$arrayofmatchvalues=array()); 
	}
	else
		echo("Error-No Doctors in Doctor Master.");
}
else
	echo("Error-getDoctorList.");
?>
<script type="text/javascript">
function printReport() {
	$from = document.printForm.from;
	$to = document.printForm.to;
	$detail = document.printForm.detail;
	$summary = document.printForm.summary;
	$refdmid = document.printForm.refdmid;
	$url = "/modules/customerservice/printPatientStatusReport.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value;
	window.open($url);
	return;
}
</script>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Patient Status Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>From Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="from" name="from" type="text" size="10" maxlength="10" value="<?php if(isset($default['from'])) echo $default['from']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.printForm.from,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Thru Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="to" name="to" type="text" size="10" maxlength="10" value="<?php if(isset($default['to'])) echo $default['to']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.printForm.to,'anchor2','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Referring Doctor</th>
				<td nowrap="nowrap" style="text-decoration:none"><select id="refdmid" name="refdmid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['refdmid'])) echo $_POST['refdmid'];?>" /><?php echo $doctorlistoptions; ?>
					</select>
					<a href="" style="text-decoration:none" onclick="window.open(somewindow)">&nbsp;+&nbsp;</a></td>
			</tr>
			<tr>
				<th>Print Detail</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="detail" id="detail" checked /></td>
			</tr>
			<tr>
				<th><!--<input type="submit" name="Print Attendance Report2" id="Print Attendance Report2" value="Back">-->
				Print Summary</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="summary" id="summary" checked /></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" name="Print Patient Status Report" id="Print Patient Status Report" value="Print Patient Status Report" onclick="printReport();" /></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
