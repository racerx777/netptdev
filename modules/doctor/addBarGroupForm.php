<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
$searchdoctorvars = array(
	"dmsname"=>array(
		"title"=>"Clinic Name", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"3", 
		"displayformat"=>"name", 
		"displaylength"=>"30", 
		"test"=>"EQUAL"), 
	"dmlname"=>array(
		"title"=>"Last Name", 
		"type"=>"text", 
		"dbformat"=>"name", 
		"dblength"=>"30", 
		"displayformat"=>"name", 
		"displaylength"=>"30", 
		"test"=>"LIKE"),
	"dmfname"=>array(
		"title"=>"First Name", 
		"type"=>"text", 
		"dbformat"=>"name", 
		"dblength"=>"30", 
		"displayformat"=>"name", 
		"displaylength"=>"30", 
		"test"=>"LIKE"),
	"dmdscode"=>array(
		"title"=>"Specialty Code", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"3", 
		"displayformat"=>"code", 
		"displaylength"=>"3", 
		"test"=>"EQUAL"),
	"dmdclass"=>array(
		"title"=>"MD Class", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"1", 
		"displayformat"=>"code", 
		"displaylength"=>"1", 
		"test"=>"EQUAL"),
	"dmestrefer"=>array(
		"title"=>"Estimated Referrals", 
		"type"=>"text", 
		"dbformat"=>"integer", 
		"dblength"=>"11", 
		"displayformat"=>"integer", 
		"displaylength"=>"11", 
		"test"=>"EQUAL"),
	"dmdob"=>array(
		"title"=>"DOB", 
		"type"=>"text", 
		"dbformat"=>"date", 
		"dblength"=>"8", 
		"displayformat"=>"date", 
		"displaylength"=>"10", 
		"test"=>"EQUAL")
);
if(!empty($_POST['buttonClearSearchDoctor'])) 
	clearformvars('doctor', 'searchdoctor');
// If Search then save search values
$disableclear = 'disabled="disabled"';
if(!empty($_POST['buttonSetSearchDoctor'])) {
	setformvars('doctor', 'searchdoctor', $_POST['searchdoctor']);
	$default = getformvars('doctor', 'searchdoctor');
	foreach($default as $field=>$value) {
		if(!empty($value)) {
			unset($disableadd);
			unset($disableclear);
		}
	}
}
// In any case retrieve search values
$default = getformvars('doctor', 'searchdoctor');

?>
<div class="containedBox">
	<fieldset>
	<legend>Search/Add Doctor Group Information</legend>
	<form method="post" name="searchDoctorForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">	
			<tr>
				<th>Short Name</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Specialty</th>
				<th>MD Class</th>
				<th>Est Referrals</th>
				<th>DOB</th>
			</tr>
			<tr>
				<td><input id="dmsname" name="searchdoctor[dmsname]" type="text" size="6" maxlength="6" value="<?php if(isset($default['dmsname'])) echo $default['dmsname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dmlname" name="searchdoctor[dmlname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['dmlname'])) echo $default['dmlname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dmfname" name="searchdoctor[dmfname]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dmfname'])) echo $default['dmfname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><select name="searchdoctor[dmdscode]" size="1" value="<?php if(isset($default['dmdscode'])) echo $default['dmdscode'];  ?>">
						<option label=""></option>
						<?php
						foreach($_SESSION['dscodes'] as $key=>$val) {
							if($default['dmdscode'] == $key)
								$selected = "selected";
							else
								unset($selected);
							echo "<option $selected value='" . $key . "'>" . $_SESSION['dscodes'][$key] . "</option>"; 
						}
					?>
					</select></td>
				<td><select name="searchdoctor[dmdclass]" size="1" value="<?php if(isset($default['dmdclass'])) echo $default['dmdclass'];  ?>">
						<option label=""></option>
						<?php
						foreach($_SESSION['dclasses'] as $key=>$val) {
							if($default['dmdclass'] == $key)
								$selected = "selected";
							else
								unset($selected);
							echo "<option $selected value='" . $key . "'>" . $_SESSION['dclasses'][$key] . "</option>"; 
						}
					?>
					</select></td>
				<td><input name="searchdoctor[dmestrefer]" type="text" size="6" maxlength="6"  value="<?php if(isset($default['dmestrefer'])) echo $default['dmestrefer'];  ?>"></td>
				<td><input id="dmdob" name="searchdoctor[dmdob]" type="text" size="10" maxlength="10"  value="<?php if(isset($default['dmdob'])) echo $default['dmdob'];  ?>"  onchange="validateDate(this.id)"></td>
			</tr>
			<tr>
				<td colspan="7"><div>
						<div style="float:left;">
							<input id="SearchButton" name="button[]" type="submit" value="Search Doctors"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearchAppt" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
						<div style="float:right;">
							<input id="AddButton" name="button[]" type="submit" value="Add Doctor" <?php if(isset($disableadd)) echo $disableadd ?> />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>