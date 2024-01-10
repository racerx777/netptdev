<script type="text/javascript">
function printAttendanceReport() {
	$from = document.printApptForm.from;
	$to = document.printApptForm.to;
	$detail = document.printApptForm.detail;
	$summary = document.printApptForm.summary;
	$url = "/modules/attendance/printAttendanceReport.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value;
	window.open($url);
	return;
}
function printPDFXLS(is_pdf=0) {
	$from = document.printApptForm.from;
	$to = document.printApptForm.to;
	$detail = document.printApptForm.detail;
	$summary = document.printApptForm.summary;
	if(is_pdf){
		$url = "/modules/attendance/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+"&printpdf="+is_pdf;
		window.open($url);
	}else{
		$url = "/modules/attendance/printXLS.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value;
		window.open($url);
	}
}
</script>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Attendance Report Criteria</legend>
	<form method="post" name="printApptForm" onsubmit="return formValidator()">
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>From Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="from" name="from" type="text" size="10" maxlength="10" value="<?php if(isset($default['from'])) echo $default['from']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.from,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Thru Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="to" name="to" type="text" size="10" maxlength="10" value="<?php if(isset($default['to'])) echo $default['to']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.to,'anchor2','MM/dd/yyyy'); return false;" /></td>
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
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" name="Print Attendance Report" id="Print Attendance Report" value="Print Attendance Report" onClick="printAttendanceReport();">&nbsp;&nbsp;<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
