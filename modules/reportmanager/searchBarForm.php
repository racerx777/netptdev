<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
?>
<script type="text/javascript">
function updatePatientList() {
	var obj = document.searchForm;
//	obj.palname='';
//	obj.pafname='';
//	obj.padob='';
//	obj.paphone1='';
//	obj.passn='';
	var elem = document.createElement('input');
	elem.setAttribute("type", "hidden");
	elem.setAttribute("name", "buttonSetSearch");
	elem.setAttribute("value", "buttonSetSearch");
	obj.appendChild(elem);
	obj.submit();
}

function updatePatientInformation(crpnum) {
	var obj = document.getElementById(crpnum);
	if(obj!=null) {
		if(obj.value == '') {
			document.searchForm.palname.disabled = false;
			document.searchForm.pafname.disabled = false;
		}
		else {
			document.searchForm.palname.value = "";
			document.searchForm.pafname.value = "";
			document.searchForm.palname.disabled = true;
			document.searchForm.pafname.disabled = true;
		}
	}
}
</script>
<script>
var cal = new CalendarPopup();
</script>
<?
$disableclear = 'disabled="disabled"';
// Search Variable Array
$searchvars = array(
	"crpnum"=>array("title"=>"Patient Number", "type"=>"text", "dbformat"=>"int", "dblength"=>"6", "displayformat"=>"numeric", "displaylength"=>"6", "test"=>"LIKE"), 
	"crinjurydate"=>array("title"=>"Injury Date", "type"=>"text", "dbformat"=>"date", "dblength"=>"8", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL"), 
	"crcnum"=>array("title"=>"Clinic", "type"=>"text", "dbformat"=>"char", "dblength"=>"2", "displayformat"=>"name", "displaylength"=>"2", "test"=>"EQUAL"), 
	"crcasestatuscode"=>array("title"=>"Case Status", "type"=>"text", "dbformat"=>"char", "dblength"=>"3", "displayformat"=>"code", "displaylength"=>"3", "test"=>"EQUAL"), 
	"crtherapytypecode"=>array("title"=>"Therapy Type", "type"=>"text", "dbformat"=>"char", "dblength"=>"3", "displayformat"=>"code", "displaylength"=>"3", "test"=>"EQUAL"), 
	"crcasetypecode"=>array("title"=>"Case Type", "type"=>"text", "dbformat"=>"char", "dblength"=>"3", "displayformat"=>"char", "displaylength"=>"3", "test"=>"EQUAL"), 
	"paid"=>array("title"=>"Id", "type"=>"text", "dbformat"=>"int", "dblength"=>"11", "displayformat"=>"numeric", "displaylength"=>"11", "test"=>"EQUAL"), 
	"palname"=>array("title"=>"Last Name", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"30", "displayformat"=>"name", "displaylength"=>"30", "test"=>"LIKE"), 
	"pafname"=>array("title"=>"First Name", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"30", "displayformat"=>"name", "displaylength"=>"30", "test"=>"LIKE"),  
	"padob"=>array("title"=>"Birth Date", "type"=>"text", "dbformat"=>"date", "dblength"=>"8", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL"),  
	"paphone1"=>array("title"=>"Phone Number", "type"=>"text", "dbformat"=>"phone", "dblength"=>"18", "displayformat"=>"phone", "displaylength"=>"22", "test"=>"LIKELIKE"),  
	"passn"=>array("title"=>"Social Security Number", "type"=>"text", "dbformat"=>"ssn", "dblength"=>"9", "displayformat"=>"ssn", "displaylength"=>"11", "test"=>"LIKELIKE")
);

// If Clear Pressed
if(!empty($_POST['buttonClearSearch'])) {
	clearformvars('reportmanager', 'search');
}
else {
	if(!empty($_POST['buttonSetSearch'])) {	
		// Reformat User Search Values to database search format

		// If Search then save search values
			setformvars('reportmanager', 'search', $_POST['search']);	
	}
}

// Retrieve search values
$default = getformvars('reportmanager', 'search');

if(empty($default['crcnum'])) {
	if(empty($_POST['search']['crcnum'])) {
		$clinics=$_SESSION['useraccess']['clinics'];
		$key=key($clinics);
		$default=array("crcnum"=>$clinics["$key"]["cmcnum"]);
	}
	else
		$default=array("crcnum"=>$_POST['search']['crcnum']);
}
// If any search field is populated then enable the Clear button
foreach($default as $field=>$value) {
	if(!empty($value)) {
		unset($disableclear);
	}
}

// ReFormat User Search Values for display format in HTML area

?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Search Patient Report Information</legend>
	<form method="post" name="searchForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Clinic</th>
				<th>Existing Patient</th>
<!--				<th>Patient Number</th>
-->				<th>Last Name</th>
				<th>First Name</th>
				<th>DOB</th>
				<th>DOI</th>
				<th>Phone</th>
				<th>SSN</th>
			</tr>
			<tr>
				<td><select name="search[crcnum]" id="crcnum" onchange="updatePatientList();">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$default['crcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
				<td><select name="search[crpnum]" id="crpnum" onchange="updatePatientInformation(this.id)">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['patients'], $optionvaluefield='pnum', $arrayofoptionfields=array('lname'=>', ', 'fname'=>' (', 'pnum'=>') ', 'cnum'=>''), $defaultoption=$default['crpnum'], $addblankoption=TRUE, $arraykey="cnum", $arrayofmatchvalues=array($default['crcnum'])); ?>
					</select></td>
<!--				<td><input id="crpnum" name="search[crpnum]" type="text" size="10" maxlength="30" value="<?php if(isset($default['crpnum'])) echo strtoupper($default['crpnum']);  ?>"></td>
-->				<td><input id="palname" name="search[palname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['palname'])) echo strtoupper($default['palname']);  ?>"></td>
				<td><input id="pafname" name="search[pafname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['pafname'])) echo strtoupper($default['pafname']);  ?>"></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="search[padob]" type="text" size="10" maxlength="10" value="<?php if(isset($default['padob'])) echo displayDate($default['padob']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crinjurydate" name="search[crinjurydate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crinjurydate'])) echo displayDate($default['crinjurydate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.searchForm.crinjurydate,'anchor2','MM/dd/yyyy'); return false;" /></td>
				<td><input id="paphone1" name="search[paphone1]" type="text" size="11" maxlength="14" value="<?php if(isset($default['paphone1'])) echo displayPhone($default['paphone1']);  ?>" onchange="displayphone(this.id)"></td>
				<td><input id="passn" name="search[passn]" type="text" size="11" maxlength="11" value="<?php if(isset($default['passn'])) echo displaySsnAll($default['passn']);  ?>"></td>
			</tr>
			<tr>
				<td colspan="8"><div>
						<div style="float:left;">
							<input id="buttonSetSearch" name="buttonSetSearch" type="submit" value="Search"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearch" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>