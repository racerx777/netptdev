<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
?>
<style>
ul.tab-headers, .tab-content {
	width:736px;
	height:auto;
}
ul.tab-headers {
	height: 20px;
	list-style-type: none;
	margin: 0 0 0 0;
	padding: 3px;
}
ul.tab-headers li {
	float: left;
	padding: 3px 10px 3px 10px;
	border: solid 1px #4682B4;
	border-bottom: 0;
	margin-left: 3px;
	cursor: pointer;
	color: gray;
}
ul.tab-headers li.active {
	background-color:#4682B4;
	color: white;
}
.tab-content {
	margin: 0;
	padding: 0 5px 5px 5px;
	border: solid 1px #4682B4;
	visibility: hidden;
	background-color: #4682B4;
	color:white;
}
.clearboth {
 clear:both;
}
.position_absolute {
	position:absolute;
}
.position_relative {
	position:relative;
	padding-top:3px;
	padding-bottom:3px;
}
.bodypartnav {
	width:20%;
	left:0px;
	white-space:nowrap;
	clear:left;
}

.bodypartnav {
	width:20%;
	left:0px;
	white-space:nowrap;
	clear:left;
}

.bodypartnav a:link {
	text-decoration:none;
	color:#000000;
}

.bodypartnav a:visited {
	text-decoration:none;
	color:#000000;
}

.bodypartnav a:hover {
	color:#000000;
	background-color:#0099CC;
}

.bodypartnav a:active {
	text-decoration:none;
	color:#000000;
	background-color:#0099CC;
}

.col10pct {
	width:10%;
	float:left;
	white-space:nowrap;
}
.col15pct {
	width:15%;
	float:left;
	white-space:nowrap;
}
.col20pct {
	width:20%;
	float:left;
	white-space:nowrap;
}
.col30pct {
	width:30%;
	float:left;
	white-space:nowrap;
}
.col40pct {
	width:40%;
	float:left;
	white-space:nowrap;
}
.col50pct {
	width:50%;
	float:left;
	white-space:nowrap;
}
.col60pct {
	width:60%;
	float:left;
	white-space:nowrap;
}
.col70pct {
	width:70%;
	float:left;
	white-space:nowrap;
}
.col80pct {
	width:80%;
	float:left;
	white-space:nowrap;
}
.col90pct {
	width:90%;
	float:left;
	white-space:nowrap;
}
.col100pct {
	width:100%;
	float:left;
	white-space:nowrap;
}
</style>
<script>
// fields set/passed in by parent
var dxdiv;
var destfield_icd9value;
var destfield_icd9text;
var destfield_injuryvalue;
var destfield_injurytext;
var destfield_bodypartvalue;
var destfield_bodyparttext;
var destfield_descriptorvalue;
var destfield_descriptortext;
function returnSelection(){
	var formicd9 = document.getElementById("formicd9");
	var forminjury = document.getElementById("forminjury");
	var formbodypart = document.getElementById("formbodypart");
	var formdescriptor = document.getElementById("formdescriptor");

	var icd9value=formicd9.options[formicd9.selectedIndex].value;
	var icd9text=formicd9.options[formicd9.selectedIndex].text;

	var injuryvalue=forminjury.options[forminjury.selectedIndex].value;
	var injurytext=forminjury.options[forminjury.selectedIndex].text;

	var bodypartvalue=formbodypart.options[formbodypart.selectedIndex].value;
	var bodyparttext=formbodypart.options[formbodypart.selectedIndex].text;

	var descriptorvalue=formdescriptor.options[formdescriptor.selectedIndex].value;
	var descriptortext=formdescriptor.options[formdescriptor.selectedIndex].text;
	
	var dxtext=icd9text+"<br>"+injurytext+"<br>"+descriptortext+" "+bodyparttext;
	if (opener && !opener.closed && opener.updateDxValues){
		opener.updateDxValues(dxdiv, dxtext, destfield_icd9value, icd9value, destfield_icd9text, icd9text, destfield_injuryvalue, injuryvalue,destfield_injurytext, injurytext, destfield_bodypartvalue, bodypartvalue, destfield_bodyparttext, bodyparttext, destfield_descriptorvalue, descriptorvalue, destfield_descriptortext, descriptortext);
	}
	window.close();
}
</script>
<?php
$icd9codearray = icd9CodeOptions(); // contains icd9, injurytype, bodypart, descriptor
$injurynaturecodearray = injurynatureCodeOptions(); // contains injurytype code, description
$bodypartcodearray = bodypartCodeOptions(); // contains bodypart code, description
$bodypartdescriptorcodearray = bodypartdescriptorCodeOptions(); // contains descriptor code, description

$icd9s = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$icd9, $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

$injurys = getSelectOptions($arrayofarrayitems=$injurynaturecodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$injury, $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

$bodyparts = getSelectOptions($arrayofarrayitems=$bodypartcodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$bodypart, $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

$descriptors = getSelectOptions($arrayofarrayitems=$bodypartdescriptorcodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$descriptor, $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());
?>

<form id="ICD9Picker">
	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col10pct">ICD9:</div>
		<div class="col90pct">
			<select name="formicd9" id="formicd9">
				<?php echo $icd9s; ?>
			</select>
		</div>
	</div>
	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col10pct">Injury Nature:</div>
		<div class="col90pct">
			<select name="forminjury" id="forminjury">
				<?php echo $injurys; ?>
			</select>
		</div>
	</div>
	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col10pct">Descriptor:</div>
		<div class="col90pct">
			<select name="formdescriptor" id="formdescriptor">
				<?php echo $descriptors; ?>
			</select>
		</div>
	</div>
	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col10pct">Bodypart:</div>
		<div class="col90pct">
			<select name="formbodypart" id="formbodypart">
				<?php echo $bodyparts; ?>
			</select>
		</div>
	</div>
	<div>
		<input type="button" value="Update" onclick="returnSelection();">
	</div>
</form>
<script type="text/javascript">
document.getElementById("formicd9").value = destfield_icd9value.value
document.getElementById("forminjury").value = destfield_injuryvalue.value
document.getElementById("formbodypart").value = destfield_bodypartvalue.value
document.getElementById("formdescriptor").value = destfield_descriptorvalue.value
</script>
