<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'].'/common/javascript.js');
require_once($_SERVER['DOCUMENT_ROOT'].'/common/user.options.php');
?>
<script language="JavaScript">
function printPDFXLS(is_pdf=0) {
	var includedetails=false;
	var untouched=false;
	var userid='';
	$fromdate = document.printForm.fromdate;
	$thrudate = document.printForm.thrudate;
	if(document.printForm.includedetails.checked)
		includedetails=true;
	if(document.printForm.untouched.checked) {
		untouched=true;
		mintbal=document.printForm.mintbal;
	}
	if(document.printForm.userid==undefined)
		userid = '';
	else
		userid=document.printForm.userid.value;



	if($fromdate.value != ""){
		document.getElementById('errorMsg').innerHTML = "";
	}else{
		document.getElementById('errorMsg').innerHTML = "Please select date";
		return false;
	}

	if(is_pdf){
	
		$url = "/modules/collections/Reports/touched/printPdf.php?fromdate=" + $fromdate.value + "&thrudate=" + $thrudate.value + "&includedetails=" + includedetails + "&userid=" + userid + "&untouched=" + untouched + "&mintbal="+mintbal.value+"&printpdf="+is_pdf;
			window.open($url);
		
	}else{
		$url = "/modules/collections/Reports/touched/printXLS.php?fromdate=" + $fromdate.value + "&thrudate=" + $thrudate.value + "&includedetails=" + includedetails + "&userid=" + userid + "&untouched=" + untouched + "&mintbal="+mintbal.value;
		window.open($url);
	}
}


	var cal = new CalendarPopup();

function printReport() {
	var includedetails=false;
	var untouched=false;
	var userid='';
	$fromdate = document.printForm.fromdate;
	$thrudate = document.printForm.thrudate;
	if(document.printForm.includedetails.checked)
		includedetails=true;
	if(document.printForm.untouched.checked) {
		untouched=true;
		mintbal=document.printForm.mintbal;
	}
	if(document.printForm.userid==undefined)
		userid = '';
	else
		userid=document.printForm.userid.value;
	$url = "/modules/collections/collectionsTouchedAccountsReport.php?fromdate=" + $fromdate.value + "&thrudate=" + $thrudate.value + "&includedetails=" + includedetails + "&userid=" + userid + "&untouched=" + untouched + "&mintbal="+mintbal.value;
	window.open($url);
	return;
}
</script>
<style type="text/css">
	.acbtnrecords {
	    float: left;
	    vertical-align: middle;
	    padding: 2px 0 0 0;
	}

.acbtnrecords input {
	height:28px;
}
</style>
<?php
if(!isset($_POST['fromdate']))
	$_POST['fromdate']=today();

if(!isset($_POST['thrudate']))
	$_POST['thrudate']=$_POST['thrudate'];

$user=getuser();
$listoptions="";
if($list = getUserList()) {
	if(count($list) > 0) {
		$listoptions =  getSelectOptions(
			$arrayofarrayitems=$list,
			$optionvaluefield='umid',
			$arrayofoptionfields=array(
				'umuser'=>': ',
				'umname'=>''
				),
			$defaultoption=$_POST['userid'],
			$addblankoption=TRUE,
			$arraykey='umrole',
			$arrayofmatchvalues=array('33'=>'33','34'=>'34','73'=>'73'));
	}
	else
		error("999","User getSelectOptions() Error-No values in master table.");
}
else
	echo("Error-getUserList().");
?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Touched Accounts Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<p id="errorMsg" style="color:red;"></p>
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th> Touched Account Date Range (From-Thru)</th>
				<td nowrap="nowrap" style="text-decoration:none"><input id="fromdate" name="fromdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['fromdate'])) echo $_POST['fromdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="imgfrom" id="imgfrom" src="/img/calendar.gif" onclick="cal.select(document.printForm.fromdate,'imgfrom','MM/dd/yyyy'); return false;" />
					<input id="thrudate" name="thrudate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['thrudate'])) echo $_POST['thrudate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="imgthru" id="imgthru" src="/img/calendar.gif" onclick="cal.select(document.printForm.thrudate,'imgthru','MM/dd/yyyy'); return false;" /> </td>
			</tr>
<?php
if (isuserlevel(34)) {
?>
			<tr>
				<th>User (Default=ALL USERS)</th>
				<td nowrap="nowrap" style="text-decoration:none"><select id="userid" name="userid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['userid'])) echo $_POST['userid'];?>" />

					<?php echo $listoptions; ?>
					</select>
				</td>
			</tr>
<?php
}
//else
//	echo '<tr><td colspan="2"><input id="userid" name="userid" type="hidden" value=""></td></tr>';
?>
			<tr>
				<th>Print Detailed Actions</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="includedetails" id="includedetails" /></td>
			</tr>
			<tr>
				<th>Negate (show untouched in queue)</th>
				<td nowrap="nowrap" style="text-decoration:none"><input type="checkbox" name="untouched" id="untouched" /> Minimum Balance <input id="mintbal" name="mintbal" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['mintbal'])) echo $_POST['mintbal']; else echo '0'; ?>" ></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td nowrap="nowrap" style="text-decoration:none">
					<div class="acbtnrecords">
						<input type="button" name="DisplayTouchedAccountsReport" id="DisplayTouchedAccountsReport" value="Display Touched Accounts Report" onclick="printReport();" />
					</div>
				<div class="exporticons">
					<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="margin-left:5px;float: ;cursor: pointer;margin-right: 10px;padding-top: 3px;position: absolute;">&nbsp;&nbsp;
					<img src="/img/icon-xls.png" onClick="return printPDFXLS()" style="margin-left: 30px;cursor: pointer;margin-right: 15px;position: absolute;margin-top: 4px;" >
				</div>
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
