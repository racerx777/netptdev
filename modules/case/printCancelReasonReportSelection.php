<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script type="text/javascript">

function printPDFXLS(is_pdf=0) {
	var $fromref = document.printForm.fromref;
	var $toref = document.printForm.toref;
	var $fromcan = document.printForm.fromcan;
	var $tocan = document.printForm.tocan;
	var $detail = 0;
	var $summary = 0;
	if(document.printForm.detail.checked) 
		$detail = 1;
	else
		$detail = 0;
	if(document.printForm.summary.checked) 
		$summary = 1;
	else
		$summary = 0;

	var a = new Date($fromcan.value);
	var b = new Date($tocan.value);

	// Months between years.
	var months = (b.getFullYear() - a.getFullYear()) * 12;

	// Months between... months.
	months += b.getMonth() - a.getMonth();

	// Subtract one month if b's date is less that a's.
	if (b.getDate() < a.getDate())
	{
	    months--;
	}

	if($fromcan.value != "" || $fromref.value != ""){
		document.getElementById('errorMsg').innerHTML = "";
	}else{
		document.getElementById('errorMsg').innerHTML = "Please select date";
		return false;
	}

	if(is_pdf){
		//$url = "/modules/customerservice/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value+"&casestatuscode="+$casestatuscode.value+"&printpdf="+is_pdf;
		//if(months == 0){
			$url = "/modules/case/reports/printPdf.php?fromref=" + $fromref.value + "&toref=" + $toref.value + "&fromcan=" + $fromcan.value + "&tocan=" + $tocan.value +"&summary=" + $summary + "&detail=" + $detail+ "&printpdf="+is_pdf;
			window.open($url);
		//}else{
		   // alert("Only one month range allowed");
		//}
	}else{
		$url = "/modules/case/reports/printXLS.php?fromref=" + $fromref.value + "&toref=" + $toref.value + "&fromcan=" + $fromcan.value + "&tocan=" + $tocan.value +"&summary=" + $summary + "&detail=" + $detail;
		window.open($url);
	}
}
function printReport() {
	var $fromref = document.printForm.fromref;
	var $toref = document.printForm.toref;
	var $fromcan = document.printForm.fromcan;
	var $tocan = document.printForm.tocan;
	var $detail = 0;
	var $summary = 0;
	if(document.printForm.detail.checked) 
		$detail = 1;
	else
		$detail = 0;
	if(document.printForm.summary.checked) 
		$summary = 1;
	else
		$summary = 0;
	$url = "/modules/case/printCancelReasonReport.php?fromref=" + $fromref.value + "&toref=" + $toref.value + "&fromcan=" + $fromcan.value + "&tocan=" + $tocan.value +"&summary=" + $summary + "&detail=" + $detail;
	window.open($url);
	return;
}
</script>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Cancel Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<p id="errorMsg" style="color:red;"></p>
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>From Referral Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="fromref" name="fromref" type="text" size="10" maxlength="10" value="<?php if(isset($default['fromref'])) echo $default['fromref']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.printForm.fromref,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Thru Referral Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="toref" name="toref" type="text" size="10" maxlength="10" value="<?php if(isset($default['toref'])) echo $default['toref']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.printForm.toref,'anchor2','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>From Cancel Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="fromcan" name="fromcan" type="text" size="10" maxlength="10" value="<?php if(isset($default['fromcan'])) echo $default['fromcan']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor3" id="anchor3" src="/img/calendar.gif" onclick="cal.select(document.printForm.fromcan,'anchor3','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Thru Cancel Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="tocan" name="tocan" type="text" size="10" maxlength="10" value="<?php if(isset($default['tocan'])) echo $default['tocan']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor4" id="anchor4" src="/img/calendar.gif" onclick="cal.select(document.printForm.tocan,'anchor4','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Print Detail</th>
				<td nowrap="nowrap" style="text-decoration:none"><label>
					<input type="checkbox" name="detail" id="detail" checked>
					</label></td>
			</tr>
			<tr>
				<th>Print Summary</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="summary" id="summary" checked></td>
			</tr>
			<tr>
				<th><!--<input type="submit" name="Print Attendance Report2" id="Print Attendance Report2" value="Back">--></th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" name="Print Cancel Reason Report" id="Print Cancel Reason Report" value="Print Cancel Reason Report" onclick="printReport();">
				<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="margin-left:5px;float:;cursor: pointer;padding-top: 3px;position: relative;margin-bottom: -6px;">&nbsp;&nbsp;
				<img src="/img/icon-xls.png" onClick="return printPDFXLS()" style="cursor: pointer;position: relative;margin-top: 4px;margin-bottom: -6px;" >
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
