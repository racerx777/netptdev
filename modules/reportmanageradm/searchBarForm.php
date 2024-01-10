<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(99); 

$disableclear = 'disabled="disabled"';

// Search Variable Array
$searchvars = array(
	"rthinactive"=>array("title"=>"Inactive", "type"=>"checkbox", "dbformat"=>"boolean", "dblength"=>"1", "displayformat"=>"checked", "displaylength"=>"1", "test"=>"EQUAL"), 
	"rthbumcode"=>array("title"=>"Business", "type"=>"text", "dbformat"=>"char", "dblength"=>"3", "displayformat"=>"name", "displaylength"=>"3", "test"=>"EQUAL"), 
	"rthpgmcode"=>array("title"=>"Provider", "type"=>"text", "dbformat"=>"char", "dblength"=>"3", "displayformat"=>"name", "displaylength"=>"3", "test"=>"EQUAL"), 
	"rthcnum"=>array("title"=>"Clinic", "type"=>"text", "dbformat"=>"char", "dblength"=>"2", "displayformat"=>"name", "displaylength"=>"2", "test"=>"EQUAL"), 
	"rthname"=>array("title"=>"Name", "type"=>"text", "dbformat"=>"char", "dblength"=>"16", "displayformat"=>"name", "displaylength"=>"16", "test"=>"LIKE"), 
	"rthdescription"=>array("title"=>"Description", "type"=>"text", "dbformat"=>"char", "dblength"=>"255", "displayformat"=>"name", "displaylength"=>"255", "test"=>"LIKE"), 
	"crtdate"=>array("title"=>"Report Create Date", "type"=>"text", "dbformat"=>"date", "dblength"=>"10", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL"),
	"upddate"=>array("title"=>"Report Update Date", "type"=>"text", "dbformat"=>"date", "dblength"=>"10", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL")
);

// If Clear Pressed
if(!empty($_POST['buttonClearSearch'])) {
	clearformvars('reportmanageradm', 'search');
}
else {
	if(!empty($_POST['buttonSetSearch'])) {	
		// Reformat User Search Values to database search format

		// If Search then save search values
			setformvars('reportmanageradm', 'search', $_POST['search']);	
	}
}



// Retrieve search values
$default = getformvars('reportmanageradm', 'search');

if(empty($default['rthbumcode'])) {
	if(empty($_POST['search']['rthbumcode'])) {
		$businessunits=$_SESSION['useraccess']['businessunits'];
		$key=key($businessunits);
		$default["rthbumcode"]=$businessunits["$key"]["bumcode"];
	}
	else
		$default["rthbumcode"]=$_POST['search']['rthbumcode'];
}
$bumcode=$default["rthbumcode"];

if(empty($default['rthpgmcode'])) {
	if(empty($_POST['search']['rthpgmcode'])) {
		$providers=$_SESSION['useraccess']['providergroups'];
		$key=key($providers["$bumcode"]);
		$default["rthpgmcode"]=$providers["$bumcode"]["pgmbumcode"];
	}
	else
		$default["rthpgmcode"]=$_POST['search']['rthpgmcode'];
}
$pgmcode=$default["rthpgmcode"];

if(empty($default['rthcnum'])) {
	if(empty($_POST['search']['rthcnum'])) {
		$clinics=$_SESSION['useraccess']['clinics'];
		$key=key($clinics);
		$default["rthcnum"]=$clinics["$key"]["cmcnum"];
	}
	else
		$default["rthcnum"]=$_POST['search']['rthcnum'];
}
$cnum=$default["rthcnum"];

// If any search field is populated then enable the Clear button
foreach($default as $field=>$value) {
	if(!empty($value)) {
		unset($disableclear);
	}
}

$inactivevalues=array("Active"=>array("value"=>"0", "name"=>"Active"), "Inactive"=>array("value"=>"1", "name"=>"Inactive"));
$default["rthinactive"]=$_POST['search']['rthinactive'];
$inactive=$default["rthinactive"];


// ReFormat User Search Values for display format in HTML area

?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Search Template Information</legend>
	<form method="post" name="searchForm">
		<table width="100%"  border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Inactive</th>
				<th>Business</th>
				<th>Provider</th>
				<th>Clinic</th>
				<th>Template Name</th>
				<th>Description</th>
				<th>Create</th>
				<th>Update</th>
			</tr>
			<tr>
				<td><select name="search[rthinactive]" id="rthinactive">
						<?php echo getSelectOptions($arrayofarrayitems=$inactivevalues, $optionvaluefield='value', $arrayofoptionfields=array('name'=>''), $defaultoption=$default['rthinactive'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
				<td><select name="search[rthbumcode]" id="rthbumcode">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['businessunits'], $optionvaluefield='bumcode', $arrayofoptionfields=array('bumname'=>' (', 'bumcode'=>')'), $defaultoption=$default['rthbumcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
				<td><select name="search[rthpgmcode]" id="rthpgmcode">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['providergroups'], $optionvaluefield='pgmcode', $arrayofoptionfields=array('pgmname'=>' (', 'pgmcode'=>')'), $defaultoption=$default['rthpgmcode'], $addblankoption=FALSE, $arraykey='pgmbumcode', $arrayofmatchvalues=array($default['rthbumcode'])); ?>
					</select>
				</td>
				<td><select name="search[rthcnum]" id="rthcnum">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$default['rthcnum'], $addblankoption=TRUE, $arraykey='cmpgmcode', $arrayofmatchvalues=array($default['rthpgmcode'])); ?>
					</select>
				</td>
				<td><input id="rthname" name="search[rthname]" type="text" size="16" maxlength="16" value="<?php if(isset($default['rthname'])) echo strtoupper($default['rthname']);  ?>"></td>
				<td><input id="rthdescription" name="search[rthdescription]" type="text" size="25" maxlength="255" value="<?php if(isset($default['rthdescription'])) echo strtoupper($default['rthdescription']);  ?>"></td>

				<td nowrap="nowrap" style="text-decoration:none"><input id="crtdate" name="search[crtdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crtdate'])) echo displayDate($default['crtdate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchForm.crtdate,'anchor1','MM/dd/yyyy'); return false;" /></td>

				<td nowrap="nowrap" style="text-decoration:none"><input id="upddate" name="search[upddate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['upddate'])) echo displayDate($default['upddate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.searchForm.upddate,'anchor2','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td colspan="8"><div>
						<div style="float:left;">
							<input id="buttonSetSearch" name="buttonSetSearch" type="submit" value="Search"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearch" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
						<div style="float:right;">
							<input id="button[0]" name="button[0]" type="submit" value="Add Template" />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
