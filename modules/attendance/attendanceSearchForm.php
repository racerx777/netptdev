<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$searchapptvars = array(
	"cmcnum"=>array(
		"title"=>"Clinic Name", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"3", 
		"displayformat"=>"name", 
		"displaylength"=>"30", 
		"test"=>"EQUAL"), 
	"crcasestatuscode"=>array(
		"title"=>"Case Status", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"3", 
		"displayformat"=>"code", 
		"displaylength"=>"30", 
		"test"=>"EQUAL"),
	"crapptdate1"=>array(
		"title"=>"From Appt Date", 
		"type"=>"text", 
		"dbformat"=>"date", 
		"dblength"=>"8", 
		"displayformat"=>"date", 
		"displaylength"=>"10", 
		"test"=>"RANGE1",
		"rangefield"=>"crapptdate"),
	"crapptdate2"=>array(
		"title"=>"From Appt Date", 
		"type"=>"text", 
		"dbformat"=>"date", 
		"dblength"=>"8", 
		"displayformat"=>"date", 
		"displaylength"=>"10", 
		"test"=>"RANGE2",
		"rangefield"=>"crapptdate")
);
if(!empty($_POST['buttonClearSearchAppt'])) {
	clearformvars('case', 'searchappt');
}
else {
	if(empty($_POST['searchappt']['crcasestatuscode']))
		$_POST['searchappt']['crcasestatuscode']='SCH';
}

// If Search then save search values
if(!empty($_POST['buttonSetSearchAppt'])) {
	setformvars('case', 'searchappt', $_POST['searchappt']);
	$default = getformvars('case', 'searchappt');
	foreach($default as $field=>$value) {
		if(!empty($value)) {
			unset($disableclear);
		}
	}
}

// In any case retrieve search values
$default = getformvars('case', 'searchappt');
// If any field is populated then enable the Add button
if(empty($default['crapptdate1']))
	$default['crapptdate1']='01/01/1901';
if(empty($default['crapptdate2']))
	$default['crapptdate2']=displayDate(date("m/d/Y",(time()-86400)));
?>


<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Search Appointment Information</legend>
	<form method="post" name="searchapptForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Clinic</th>
				<th>Case Status</th>
				<th>From Appt Date</th>
				<th>To Appt Date</th>
			</tr>
			<tr>
				<td><select name="searchappt[crcnum]" id="crcnum">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$default['crcnum'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select></td>
				<td><select name="searchappt[crcasestatuscode]" id="crcasestatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = caseStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['crcasestatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
					</select></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crapptdate1" name="searchappt[crapptdate1]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crapptdate1'])) echo $default['crapptdate1']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchapptForm.crapptdate1,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crapptdate2" name="searchappt[crapptdate2]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crapptdate2'])) echo $default['crapptdate2']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.searchapptForm.crapptdate2,'anchor2','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td colspan="5"><div>
						<div style="float:left;">
							<input name="buttonSetSearchAppt" type="submit" value="Search"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearchAppt" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script>
	$(document).on('click','.page-link',function(e){
		e.preventDefault();
		$('.loader').show();
		var pageId = $(this).attr("data-pageid");
		if(pageId != '' || pageId != undefined){
			pageId == pageId;
		}else{
			pageId == 1;
		}
		$.ajax({
			type: 'post',
			url: 'modules/attendance/attendanceSearchResultsForm.php',
			data: {showall:'1',pageno:pageId},
			success: function (data) {
				// console.log(data);
				$('#append-table').html(data);
				if ( $('#append-table').length){
					$('#append-table').html(data);
					$('.loader').hide();
				}else{
					$('#append-table-new').html(data);
					$('.loader').hide();
				}
			}
		});
	});
</script>