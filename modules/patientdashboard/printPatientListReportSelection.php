<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/clinic.options.php');
securitylevel(15); 
?>
<script type="text/javascript">
var cal=new CalendarPopup();


function printPDFXLS(is_pdf=0) {
	var form=document.forms["printApptForm"];
	var request='';
	for (var i = 0; i<form.elements.length; i++) {
		var field=form.elements[i];
		if(field.value!='') {
			if (
				field.name=='bnum' ||
				field.name=='cnum' ||
				field.name=='doifrom' ||
				field.name=='doithru' ||
				field.name=='reffrom' ||
				field.name=='refthru' ||
				field.name=='apptfrom' ||
				field.name=='apptthru' ||
				field.name=='detail' ||
				field.name=='summary' ||
				field.name=='nopnum' ||
				field.name=='casestatus'
			)
				request+=field.name+"="+field.value+"&";
		}
    }

    var apptfrom = document.getElementById('apptfrom').value;
    var doifrom = document.getElementById('doifrom').value;
    var reffrom = document.getElementById('reffrom').value;


    if(apptfrom != "" || doifrom != "" || reffrom != ""){
    	document.getElementById('errorMsg').innerHTML = "";
    }else{
    	document.getElementById('errorMsg').innerHTML = "Please select date";
    	return false;
    }


	if(is_pdf){
			$url = "/modules/patientdashboard/reports/printPdf.php?"+request+"&"+is_pdf;
			window.open($url);
	}else{
		$url = "/modules/patientdashboard/reports/printXLS.php?fromref="+request+"&"+is_pdf;
		window.open($url);
	}
}

function printPatientListReport() {
// The form
	var form=document.forms["printApptForm"];
	var request='';
	for (var i = 0; i<form.elements.length; i++) {
		var field=form.elements[i];
		if(field.value!='') {
			if (
				field.name=='bnum' ||
				field.name=='cnum' ||
				field.name=='doifrom' ||
				field.name=='doithru' ||
				field.name=='reffrom' ||
				field.name=='refthru' ||
				field.name=='apptfrom' ||
				field.name=='apptthru' ||
				field.name=='detail' ||
				field.name=='summary' ||
				field.name=='nopnum' ||
				field.name=='casestatus'
			)
				request+=field.name+"="+field.value+"&";
		}
    }
	$url = "/modules/patientdashboard/printPatientListReport.php?"+request;
	window.open($url);
	return;
}
</script>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Print Patient List Report Criteria</legend>
	<form method="post" name="printApptForm" onsubmit="return formValidator()">
		<p id="errorMsg" style="color:red;"></p>
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Business Unit</th>
				<td><select name="bnum" id="bnum"><option value=""></option><option value="WS">Weststar</option><option value="NET">Network</option></select></td>
			</tr>
			<tr>
				<th>Treating Clinic</th>
				<td><select name="cnum" id="cnum">
						<?php echo getSelectOptions($arrayofarrayitems=getClinicTypeOptions($_POST['crtherapytypecode']), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_REQUEST['cnum'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Injury From Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="doifrom" name="doifrom" type="text" size="10" maxlength="10" value="<?php if(isset($default['doifrom'])) echo $default['doifrom']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="doifrom1" id="doifrom1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.doifrom,'doifrom1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Injury Thru Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="doithru" name="doithru" type="text" size="10" maxlength="10" value="<?php if(isset($default['doithru'])) echo $default['doithru']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="doithru1" id="doithru1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.doithru,'doithru1','MM/dd/yyyy'); return false;" /></td>
			</tr>

			<tr>
				<th>Referral From Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="reffrom" name="reffrom" type="text" size="10" maxlength="10" value="<?php if(isset($default['reffrom'])) echo $default['reffrom']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="reffrom1" id="reffrom1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.reffrom,'reffrom1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Referral Thru Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="refthru" name="refthru" type="text" size="10" maxlength="10" value="<?php if(isset($default['refthru'])) echo $default['refthru']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="refthru1" id="refthru1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.refthru,'refthru1','MM/dd/yyyy'); return false;" /></td>
			</tr>

			<tr>
				<th>Appointment From Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="apptfrom" name="apptfrom" type="text" size="10" maxlength="10" value="<?php if(isset($default['apptfrom'])) echo $default['apptfrom']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="apptfrom1" id="apptfrom1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.apptfrom,'apptfrom1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<th>Appointment Thru Date</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="apptthru" name="apptthru" type="text" size="10" maxlength="10" value="<?php if(isset($default['apptthru'])) echo $default['apptthru']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="apptthru1" id="apptthru1" src="/img/calendar.gif" onclick="cal.select(document.printApptForm.apptthru,'apptthru1','MM/dd/yyyy'); return false;" /></td>
			</tr>

			<tr>
				<th>Print Detail</th>
				<td nowrap="nowrap" style="text-decoration:none"><label>
					<input type="checkbox" name="detail" id="detail" checked>
					</label></td>
			</tr>
			<tr>
				<th>Print Summary</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="summary" id="summary" ></td>
			</tr>
			<tr>
				<th>Print Only Patients without PNUMS</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="nopnum" id="nopnum" checked></td>
			</tr>
			<tr>
				<th>Case Status Code</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="text" name="casestatus" id="casestatus" checked></td>
			</tr>
			<tr>
				<th><!--<input type="submit" name="Print Attendance Report2" id="Print Attendance Report2" value="Back">--></th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="button" value="Print Patient List Report" onclick="printPatientListReport();">
				<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="margin-left:5px;float: ;cursor: pointer;padding-top: 3px;position: relative;margin-bottom: -6px;">&nbsp;&nbsp;
				<img src="/img/icon-xls.png" onClick="return printPDFXLS()" style="cursor: pointer;position: relative;margin-top: 4px;margin-bottom: -6px;" >
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
