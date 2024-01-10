<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script type="text/javascript">

function printPDFXLS(is_pdf=0) {
	$from = document.printForm.from;
	$to = document.printForm.to;
	if($from.value == '' && $to.value == ''){
		document.getElementById('dateMsg').innerHTML = 'Please enter dates for report';
		document.getElementById('rtable').style.margin= "10px 0 0 0";
		return false;
	}else{
		document.getElementById('dateMsg').innerHTML = '';
		document.getElementById('rtable').style.margin= "0 0 0 0";
	}
	$detail = document.printForm.detail;
	$summary = document.printForm.summary;
	$refdmid = document.printForm.refdmid;
	$casestatuscode=document.printForm.casestatuscode;

	var a = new Date($from.value);
	var b = new Date($to.value);

	// Months between years.
	var months = (b.getFullYear() - a.getFullYear()) * 12;

	// Months between... months.
	months += b.getMonth() - a.getMonth();

	// Subtract one month if b's date is less that a's.
	if (b.getDate() < a.getDate())
	{
	    months--;
	}


	if(is_pdf){
		//$url = "/modules/customerservice/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value+"&casestatuscode="+$casestatuscode.value+"&printpdf="+is_pdf;
		if(months == 0){
			$url = "/modules/customerservice/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value+"&casestatuscode="+$casestatuscode.value+"&printpdf="+is_pdf;
			window.open($url);
		}else{
			alert("Only one month range allowed");
		}
	}else{
		$url = "/modules/customerservice/printXLS.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value+"&casestatuscode="+$casestatuscode.value;
		window.open($url);
	}
}
function doGetCaretPosition (ctrl) {
	var CaretPos = 0;	// IE Support
	if (document.selection) {
		ctrl.focus ();
		var Sel = document.selection.createRange ();
		Sel.moveStart ('character', -ctrl.value.length);
		CaretPos = Sel.text.length;
	}
	// Firefox support
	else if (ctrl.selectionStart || ctrl.selectionStart == '0')
		CaretPos = ctrl.selectionStart;
	return (CaretPos);
}

function setCaretPosition(ctrl, pos){
	if(ctrl.setSelectionRange) {
		ctrl.focus();
		ctrl.setSelectionRange(pos,pos);
	}
	else if (ctrl.createTextRange) {
		var range = ctrl.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
}

function querydoctornames(id,keyupevent) {
// *key 91	CMD
// *key 93	CMD
// *key 9	TAB
// *key 16	SHIFT
// *key 17	CTRL
// *key 18	ALT
// *key 37	arrow left
// *key 38	arrow up
// *key 39	arrow right
// *key 40	arrow down
	if(keyupevent.keyCode!=9 && keyupevent.keyCode!=16 && keyupevent.keyCode!=17 && keyupevent.keyCode!=18 && keyupevent.keyCode!=37 && keyupevent.keyCode!=38 && keyupevent.keyCode!=39 && keyupevent.keyCode!=40  && keyupevent.keyCode!=91 && keyupevent.keyCode!=93) {
//		alert(keyupevent.keyCode);
		var e=document.getElementById(id);
		var e_name=document.getElementById(e.id+"_name");
		var e_autocomplete=document.getElementById(e.id+"_autocomplete");
		if(e_name.value.length==0) {
			e.options.selectedIndex=0;
			e_name.value="";
			e_autocomplete.value='Type search values here.';
		}
		else {
			if(keyupevent.keyCode==13) {
				e_name.value=e_autocomplete.value;
			}
			else {
				pos=doGetCaretPosition(e_name);
				e_name.value=e_name.value.toUpperCase();
				e_autocomplete.value=e_name.value;
				for(i=0;i<e.length;i++) { 
					e_index = e.options[i].text.indexOf(e_name.value);
					e.options.selectedIndex = 0;
					if(e_index == 0 ) {
						e.options.selectedIndex = i;
						e_autocomplete.value=e.options[i].text;
						break;
					}
				}
				setCaretPosition(e_name,pos);
			}
		}
	}
}

function setdoctorname(id) {
	var e=document.getElementById(id);
	var e_name=document.getElementById(e.id+"_name");
	var e_autocomplete=document.getElementById(e.id+"_autocomplete");
	e_index=e.options.selectedIndex;
	e_name.value=e.options[e_index].text;
	if(e_index==0)
		e_autocomplete.value='Type search values here.';
	else
		e_autocomplete.value=e_name.value;
}
</script>
<?php
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

	if($from.value == '' && $to.value == ''){
		document.getElementById('dateMsg').innerHTML = 'Please enter dates for report';
		document.getElementById('rtable').style.margin= "10px 0 0 0";
		return false;
	}else{
		document.getElementById('dateMsg').innerHTML = '';
		document.getElementById('rtable').style.margin= "0 0 0 0";
	}

	$detail = document.printForm.detail;
	$summary = document.printForm.summary;
	$refdmid = document.printForm.refdmid;
	$casestatuscode=document.printForm.casestatuscode;
	$url = "/modules/customerservice/printPatientStatusReport.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+ "&refdmid=" + $refdmid.value+"&casestatuscode="+$casestatuscode.value;
	window.open($url);
	return;
}

</script>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Patient Status Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<span id="dateMsg" style="margin-left:312px;color:red;"></span>
		<table width="50%" border="1" cellspacing="0" cellpadding="3" id="rtable" >
			<tr>
				<th>From Date</th>
				<td nowrap="nowrap" style="text-decoration:none">
				<input id="from" name="from" type="text" size="10" maxlength="10" value="<?php if(isset($default['from'])) echo $default['from']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.printForm.from,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Thru Date</th>
				<td nowrap="nowrap" style="text-decoration:none">
				<span id="toDateMsg"></span>
				<input id="to" name="to" type="text" size="10" maxlength="10" value="<?php if(isset($default['to'])) echo $default['to']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.printForm.to,'anchor2','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Referring Doctor</th>
				<td nowrap="nowrap" style="text-decoration:none"><div id="inputfields" style="position:relative; height:25px;">
						<input id="refdmid_name" style="position:absolute; top:0; left:0; z-index:11; background:transparent;" type="text" size="32" onkeyup="querydoctornames('refdmid',event);" onblur="setdoctorname('refdmid')" />
						<input id="refdmid_autocomplete" style="position:absolute; top:0; left:0; z-index:10; background:transparent;" type="text" size="32" value="Type search values here." disabled="disabled" /><br />
						<select style="display:none;" id="refdmid" name="refdmid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['refdmid'])) echo $_POST['refdmid'];?>" onblur="setdoctorname('refdmid')" />
						<?php echo $doctorlistoptions; ?>
						</select>
					</div>
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
				<th>Case Status</th>
				<td nowrap="nowrap" style="text-decoration:none"><select name="casestatuscode" id="casestatuscode">
						<option value="" selected="selected">All Status Codes (default)</option>
						<option value="PEN">Pending Scheduling</option>
						<option value="PEA">Pending Prior Authorization</option>
						<option value="SCH">Scheduled not seen</option>
						<option value="ACT">Active/Seen</option>
						<option value="CAN">Canceled</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" name="Print Patient Status Report" id="Print Patient Status Report" value="Print Patient Status Report" onclick="printReport();" />&nbsp;&nbsp;<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
