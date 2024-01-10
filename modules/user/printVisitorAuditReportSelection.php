<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/user.options.php');
$listoptions="";
$list = getUserList();
if($list) {
	if(count($list) > 0) {
		$listoptions =  getSelectOptions(
			$arrayofarrayitems=$list, 
			$optionvaluefield='umid', 
			$arrayofoptionfields=array(
				'umname'=>', ', 
				'umuser'=>'' 
				), 
			$defaultoption=$_POST['user'], 
			$addblankoption=TRUE, 
			$arraykey='', 
			$arrayofmatchvalues=array()); 
	}
	else
		error("999","User getSelectOptions() Error-No values in master table.");
}
else
	echo("Error-getUserList().");
?>
<script type="text/javascript">
function printReport() {
	$from = document.printForm.from;
	$thru = document.printForm.thru;
	$fromtime = document.printForm.fromtime;
	$thrutime = document.printForm.thrutime;
	$outside = document.printForm.outside.checked;
	$includecorporateusers = document.printForm.includecorporateusers;
	$includemobileusers = document.printForm.includemobileusers;
	$user = document.printForm.user;
	$url = "/modules/user/printVisitorAuditReport.php?from=" + $from.value + "&thru=" + $thru.value + "&fromtime=" + $fromtime.value + "&thrutime=" + $thrutime.value +"&outside="+$outside+ "&includecorporateusers=" + $includecorporateusers.value + "&includemobileusers=" + $includemobileusers.value+ "&user=" + $user.value;
	window.open($url);
	return;
}
</script>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Visitor Audit Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th> Audit Date Range (From-Thru)</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="from" name="from" type="text" size="10" maxlength="10" value="<?php if(isset($default['from'])) echo $default['from']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="imgfrom" id="imgfrom" src="/img/calendar.gif" onclick="cal.select(document.printForm.from,'imgfrom','MM/dd/yyyy'); return false;" />
					<input id="thru" name="thru" type="text" size="10" maxlength="10" value="<?php if(isset($default['thru'])) echo $default['thru']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="imgthru" id="imgthru" src="/img/calendar.gif" onclick="cal.select(document.printForm.thru,'imgthru','MM/dd/yyyy'); return false;" /> </td>
			</tr>
			<tr>
				<th>Access Time Range (From-Thru)</th>
				<td nowrap="nowrap" style="text-decoration:none">
					<input id="fromtime" name="fromtime" type="text" size="10" maxlength="10" value="<?php if(isset($default['fromtime'])) echo $default['fromtime']; ?>"  onchange="validateTime(this.id)">
					<input id="thrutime" name="thrutime" type="text" size="10" maxlength="10" value="<?php if(isset($default['thrutime'])) echo $default['thrutime']; ?>"  onchange="validateTime(this.id)" />
					</td>
			</tr>
			<tr>
				<th>Access Outside Time</th>
				<td nowrap="nowrap" style="text-decoration:none">
					<input id="outside" name="outside" type="checkbox" value="<?php if(isset($default['outside'])) echo $default['outside']; ?>"  onchange="validateTime(this.id)">
					</td>
			</tr>
			<tr>
				<th>User (Default=ALL USERS)</th>
				<td nowrap="nowrap" style="text-decoration:none"><select id="user" name="user" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['user'])) echo $_POST['user'];?>" />
					<?php echo $listoptions; ?>
					</select>
					<a href="" style="text-decoration:none" onclick="window.open(somewindow)">&nbsp;+&nbsp;</a></td>
			</tr>
			<tr>
				<th>Include Corporate Sources</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="includecorporateusers" id="includecorporateusers" checked /></td>
			</tr>
			<tr>
				<th> Include Mobile Sources</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="includemobileusers" id="includemobileusers" checked /></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" name="Print Visitor Audit Report" id="Print Vistor Audit Report" value="Print Visitor Audit Report" onclick="printReport();" /></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
