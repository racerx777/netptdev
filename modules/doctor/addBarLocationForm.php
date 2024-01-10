<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
$searchlocationvars = array(
	"dmid"=>array(
		"title"=>"Doctor", 
		"type"=>"text", 
		"dbformat"=>"id", 
		"dblength"=>"11", 
		"displayformat"=>"id", 
		"displaylength"=>"11", 
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
	"dlsname"=>array(
		"title"=>"Location Name", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"6", 
		"displayformat"=>"name", 
		"displaylength"=>"6", 
		"test"=>"EQUAL"), 
	"dlname"=>array(
		"title"=>"Location Name", 
		"type"=>"text", 
		"dbformat"=>"name", 
		"dblength"=>"50", 
		"displayformat"=>"name", 
		"displaylength"=>"50", 
		"test"=>"LIKE"),
	"dlphone"=>array(
		"title"=>"Location Phone", 
		"type"=>"text", 
		"dbformat"=>"phone", 
		"dblength"=>"20", 
		"displayformat"=>"phone", 
		"displaylength"=>"20", 
		"test"=>"LIKE"),
	"dlemail"=>array(
		"title"=>"email address", 
		"type"=>"text", 
		"dbformat"=>"email", 
		"dblength"=>"64", 
		"displayformat"=>"email", 
		"displaylength"=>"64", 
		"test"=>"EQUAL"),
	"dlfax"=>array(
		"title"=>"Fax Number", 
		"type"=>"text", 
		"dbformat"=>"phone", 
		"dblength"=>"20", 
		"displayformat"=>"phone", 
		"displaylength"=>"20", 
		"test"=>"LIKE"),
	"dladdress"=>array(
		"title"=>"Address", 
		"type"=>"text", 
		"dbformat"=>"char", 
		"dblength"=>"30", 
		"displayformat"=>"char", 
		"displaylength"=>"30", 
		"test"=>"LIKE"),
	"dlcity"=>array(
		"title"=>"City", 
		"type"=>"text", 
		"dbformat"=>"char", 
		"dblength"=>"20", 
		"displayformat"=>"char", 
		"displaylength"=>"20", 
		"test"=>"LIKE"),
	"dlzip"=>array(
		"title"=>"Zip", 
		"type"=>"text", 
		"dbformat"=>"zip", 
		"dblength"=>"10", 
		"displayformat"=>"zip", 
		"displaylength"=>"10", 
		"test"=>"EQUAL"),
	"dlterritory"=>array(
		"title"=>"Territory", 
		"type"=>"text", 
		"dbformat"=>"id", 
		"dblength"=>"11", 
		"displayformat"=>"id", 
		"displaylength"=>"11", 
		"test"=>"EQUAL"),
	"dgname"=>array(
		"title"=>"Group", 
		"type"=>"text", 
		"dbformat"=>"id", 
		"dblength"=>"11", 
		"displayformat"=>"id", 
		"displaylength"=>"11", 
		"test"=>"EQUAL") 
);

// if id has dmid or dgid then use add it to search criteria

if(!empty($_POST['buttonClearSearchLocation'])) 
	clearformvars('doctor', 'searchlocation');

$disableclear = 'disabled="disabled"';

if(!empty($_POST['buttonSearchLocation'])) {
	setformvars('doctor', 'searchlocation', $_POST['searchlocation']);
	$default = getformvars('doctor', 'searchlocation');
	foreach($default as $field=>$value) {
		if(!empty($value)) {
			unset($disableadd);
			unset($disableclear);
		}
	}
}
// In any case retrieve search values
$default = getformvars('doctor', 'searchlocation');
$searchdoctorSaved = getformvars('doctor', 'searchdoctor');
if(isset($searchdoctorSaved))
//	dump("searchdoctorSaved", $searchdoctorSaved);
//	$doctorname = 'doctorname';
$searchgroupSaved = getformvars('doctor', 'searchgroup');
if(isset($searchgroupSaved))
//	dump("searchgroupSaved",$searchgroupSaved);
//	$groupname = 'groupname';

//dumpsession();
?>
<div class="containedBox">
	<fieldset>
	<legend>Search/Add Doctor Location Information <?php echo "$doctorname $groupname"; ?></legend>
	<form method="post" name="searchDoctorForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">	
			<tr>
				<th>Dr Id</th>
				<th>Short Name</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Fax</th>
				<th>Address</th>
				<th>City</th>
				<th>Zip</th>
				<th>Territory</th>
			</tr>
			<tr>
				<td>
					<input id="dmid" name="searchlocation[dmid]" type="text" size="11" maxlength="11" value="<?php if(isset($default['dmid'])) echo $default['dmid'];  ?>">
					<input id="dmlname" name="searchlocation[dmlname]" type="hidden" value="<?php if(isset($default['dmlname'])) echo $default['dmlname'];  ?>" />
					<input id="dmfname" name="searchlocation[dmfname]" type="hidden" value="<?php if(isset($default['dmfname'])) echo $default['dmfname'];  ?>" />
				</td>
				<td><input id="dlsname" name="searchlocation[dlsname]" type="text" size="6" maxlength="6" value="<?php if(isset($default['dlsname'])) echo $default['dlsname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlname" name="searchlocation[dlname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['dlname'])) echo $default['dlname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlphone" name="searchlocation[dlphone]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dlphone'])) echo $default['dlphone'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlemail" name="searchlocation[dlemail]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dlemail'])) echo $default['dlemail'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlfax" name="searchlocation[dlfax]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dlfax'])) echo $default['dlfax'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dladdress" name="searchlocation[dladdress]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dladdress'])) echo $default['dladdress'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlcity" name="searchlocation[dlcity]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dlcity'])) echo $default['dlcity'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlzip" name="searchlocation[dlzip]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dlzip'])) echo $default['dlzip'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="dlterritory" name="searchlocation[dlterritory]" type="text" size="10" maxlength="30"  value="<?php if(isset($default['dlterritory'])) echo $default['dlterritory'];  ?>" onchange="upperCase(this.id)"></td>
			</tr>
			<tr>
				<td colspan="10"><div>
						<div style="float:left;">
							<input type="hidden" name="dmid" value="<?php echo $default['dmid'] ?>" /><input id="buttonSearchLocation" name="buttonSearchLocation" type="submit" value="Search Locations"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearchLocation" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
						<div style="float:right;">
							<input id="AddButton" name="button[]" type="submit" value="Add Location" <?php if(isset($disableadd)) echo $disableadd ?> />
						</div>
						<div style="float:right;">
							Add Relationship by Location ID:<input type="text" id="dlid" name="dlid" size="4" />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>