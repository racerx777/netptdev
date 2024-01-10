<script type="text/javascript">
function printPDFXLS(is_pdf=0) {
	var $from;
	var $to;
	var $detail;
	var $summary;
	$from = document.printForm.from;
	$to = document.printForm.to;
	if(document.printForm.detail.checked)
		$detail = '1';
	else
		$detail = '0';
	if(document.printForm.summary.checked)
		$summary = '1';
	else
		$summary = '0';



	if($from.value != ""){
		document.getElementById('errorMsg').innerHTML = "";
	}else{
		document.getElementById('errorMsg').innerHTML = "Please select date";
		return false;
	}

	if(is_pdf){
		//$url = "/modules/customerservice/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value+"&casestatuscode="+$casestatuscode.value+"&printpdf="+is_pdf;
		//if(months == 0){
			$url = "/modules/scheduling/reports/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary + "&detail=" + $detail+"&printpdf="+is_pdf;
			window.open($url);
		//}else{
		   // alert("Only one month range allowed");
		//}
	}else{
		$url = "/modules/scheduling/reports/printXLS.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary + "&detail=" + $detail;
		window.open($url);
	}
}
function printReport() {
	var $from;
	var $to;
	var $detail;
	var $summary;
	$from = document.printForm.from;
	$to = document.printForm.to;
	if(document.printForm.detail.checked)
		$detail = '1';
	else
		$detail = '0';
	if(document.printForm.summary.checked)
		$summary = '1';
	else
		$summary = '0';
	$url = "/modules/scheduling/printSchedulingPerformanceReport.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary + "&detail=" + $detail;
	window.open($url);
	return;
}
</script>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Scheduling Performance Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<p id="errorMsg" style="color:red;"></p>
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
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" name="Print Report" id="Print Report" value="Print Scheduling Performance Report" onclick="printReport();">
				<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="margin-left:5px;float:;cursor: pointer;margin-bottom: -6px;position: relative;">&nbsp;&nbsp;
				<img src="/img/icon-xls.png" onClick="return printPDFXLS()" style="cursor: pointer;position: relative;margin-bottom: -6px;" >
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
