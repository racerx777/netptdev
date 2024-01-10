<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(99); 
?>
<script>
var cal = new CalendarPopup();
</script>
<?
$disableclear = 'disabled="disabled"';
// Search Variable Array
$searchvars = array(
"diid"=>array(				"title"=>"ID", 					"type"=>"text", "dbformat"=>"int", 		"dblength"=>"11", "displayformat"=>"numeric", "displaylength"=>"11", "test"=>"EQUAL"), 
"distatus"=>array(			"title"=>"Status", 				"type"=>"text", "dbformat"=>"varchar", 	"dblength"=>"16", "displayformat"=>"code", "displaylength"=>"16", "test"=>"EQUAL"), 
"difile_status"=>array(		"title"=>"File Status", 		"type"=>"text", "dbformat"=>"varchar", 	"dblength"=>"16", "displayformat"=>"code", "displaylength"=>"16", "test"=>"EQUAL"), 
"diarchive_status"=>array(	"title"=>"Archive Status", 		"type"=>"text", "dbformat"=>"varchar", 	"dblength"=>"16", "displayformat"=>"code", "displaylength"=>"16", "test"=>"EQUAL"), 
"diappname"=>array(			"title"=>"Application Name",	"type"=>"text", "dbformat"=>"varchar", 	"dblength"=>"16", "displayformat"=>"varchar", "displaylength"=>"16", "test"=>"EQUAL"), 
"diappdocname"=>array(		"title"=>"Document Name", 		"type"=>"text", "dbformat"=>"varchar", 	"dblength"=>"255","displayformat"=>"varchar", "displaylength"=>"16", "test"=>"LIKELIKE"), 
"diappdocdate"=>array(		"title"=>"Document Date", 		"type"=>"text", "dbformat"=>"datetime",	"dblength"=>"19", "displayformat"=>"date", "displaylength"=>"19", "test"=>"LIKE"), 
"diappdocid"=>array(		"title"=>"Document ID", 		"type"=>"text", "dbformat"=>"int", 		"dblength"=>"11", "displayformat"=>"numeric", "displaylength"=>"11", "test"=>"EQUAL"), 
"diDOCENTITY"=>array(		"title"=>"Document Type", 		"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"50", "displayformat"=>"code", "displaylength"=>"50", "test"=>"LIKE"),  
"diDOCFOLDER"=>array(		"title"=>"Last Name", 			"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"50", "displayformat"=>"name", "displaylength"=>"50", "test"=>"LIKE"),  
"diDOCSOURCE"=>array(		"title"=>"First Name", 			"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"50", "displayformat"=>"name", "displaylength"=>"50", "test"=>"LIKE"),  
"diDOCTYPE"=>array(			"title"=>"Patient Number", 		"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"50", "displayformat"=>"numeric", "displaylength"=>"11", "test"=>"LIKE"),  
"diIMPORTANCE"=>array(		"title"=>"Clinic", 				"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"50", "displayformat"=>"code", "displaylength"=>"50", "test"=>"LIKE"),  
"crtdate"=>array(			"title"=>"Create Date", 		"type"=>"text", "dbformat"=>"date",		"dblength"=>"19", "displayformat"=>"date", "displaylength"=>"19", "test"=>"LIKE"), 
"crtuser"=>array(			"title"=>"Create User", 		"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"16", "displayformat"=>"name", "displaylength"=>"16", "test"=>"LIKE"), 
"upddate"=>array(			"title"=>"Update Date", 		"type"=>"text", "dbformat"=>"date",		"dblength"=>"19", "displayformat"=>"date", "displaylength"=>"19", "test"=>"LIKE"), 
"upduser"=>array(			"title"=>"Update User", 		"type"=>"text", "dbformat"=>"varchar",	"dblength"=>"16", "displayformat"=>"name", "displaylength"=>"16", "test"=>"LIKE")
);
// If Clear Pressed
if(!empty($_POST['buttonClearSearch'])) {
	clearformvars('documentmanager', 'search');
}
else {
	if(!empty($_POST['buttonSetSearch'])) {	
		// If Search then save search values
			setformvars('documentmanager', 'search', $_POST['search']);	
	}
}

// Retrieve search values
$default = getformvars('documentmanager', 'search');

//if(empty($default['crcnum'])) {
//	if(empty($_POST['search']['crcnum'])) {
//		$clinics=$_SESSION['useraccess']['clinics'];
//		$key=key($clinics);
//		$default=array("crcnum"=>$clinics["$key"]["cmcnum"]);
//	}
//	else
//		$default=array("crcnum"=>$_POST['search']['crcnum']);
//}
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
	<legend style="font-size:large">Search Document Report Information</legend>
	<form method="post" name="searchForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>ID</th>
				<th>Status</th>
				<th>File Status</th>
				<th>Archive Status</th>
				<th>Application</th>
				<th>Doc Name</th>
				<th>Doc ID</th>
				<th>Doc Date</th>
				<th>Document Type</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Patient Number</th>
				<th>Clinic</th>
				<th>Create Date</th>
				<th>Create User</th>
				<th>Update Date</th>
				<th>Update User</th>
			</tr>
			<tr>
				<td><input type="text" name="search[diid]" id="diid" size="5" value="<?php echo $default['diid']; ?>"></td>
				<td><select name="search[distatus]" id="distatus" >
						<option value="" ></option>
						<option value="REQUESTED" <?php if($default['distatus']=='REQUESTED') echo 'selected="selected"'; ?> >Requested</option>
						<option value="PROCESSING" <?php if($default['distatus']=='PROCESSING') echo 'selected="selected"'; ?> >Processing</option>
						<option value="COMPLETED" <?php if($default['distatus']=='COMPLETED') echo 'selected="selected"'; ?> >Completed</option>
						<option value="ERROR" <?php if($default['distatus']=='ERROR') echo 'selected="selected"'; ?> >Error</option>
					</select></td>
				<td><select name="search[difile_status]" id="difile_status" >
						<option value=""></option>
						<option value="NOT-TRANSFERRED" <?php if($default['difile_status']=='NOT-TRANSFERRED') echo 'selected="selected"'; ?> >Not Transferred</option>
						<option value="TRANSFERRED" <?php if($default['difile_status']=='TRANSFERRED') echo 'selected="selected"'; ?> >Transferred</option>
						<option value="ERROR" <?php if($default['difile_status']=='ERROR') echo 'selected="selected"'; ?> >Error</option>
					</select></td>
				<td><select name="search[diarchive_status]" id="diarchive_status" >
						<option value=""></option>
						<option value="NOT-ARCHIVED" <?php if($default['diarchive_status']=='NOT-ARCHIVED') echo 'selected="selected"'; ?> >Not-Archived</option>
						<option value="ARCHIVED" <?php if($default['diarchive_status']=='ARCHIVED') echo 'selected="selected"'; ?> >Archived</option>
						<option value="ERROR" <?php if($default['diarchive_status']=='ERROR') echo 'selected="selected"'; ?> >Error</option>
					</select></td>
				<td><select name="search[diappname]" id="diappname" >
						<option value=""></option>
						<option value="reportmanager"  <?php if($default['diappname']=='reportmanager') echo 'selected="selected"'; ?> >Report Manager</option>
					</select></td>
				<td><input type="text" name="search[diappdocname]" id="diappdocname" size="20" value="<?php echo $default['diappdocname']; ?>" ></td>
				<td><input type="text" name="search[diappdocid]" id="diappdocid" size="5" value="<?php echo $default['diappdocid']; ?>"></td>
				<td nowrap="nowrap" style="text-decoration:none"><input name="search[diappdocdate]" id="diappdocdate" type="text" size="10" maxlength="10" value="<?php if(isset($default['diappdocdate'])) echo displayDate($default['diappdocdate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="diappdocdate_cal" id="diappdocdate_cal" src="/img/calendar.gif" onclick="cal.select(document.searchForm.diappdocdate,'diappdocdate_cal','MM/dd/yyyy'); return false;" /></td>
				<td><input type="text" name="search[diDOCENTITY]" id="diDOCENTITY" size="20" value="<?php echo $default['diDOCENTITY']; ?>" ></td>
				<td><input type="text" name="search[diDOCFOLDER]" id="diDOCFOLDER" size="10" maxlength="30" value="<?php if(isset($default['diDOCFOLDER'])) echo strtoupper($default['diDOCFOLDER']);  ?>"></td>
				<td><input type="text" name="search[diDOCSOURCE]" id="diDOCSOURCE" size="10" maxlength="30" value="<?php if(isset($default['diDOCSOURCE'])) echo strtoupper($default['diDOCSOURCE']);  ?>"></td>
				<td><input type="text" name="search[diDOCTYPE]" id="diDOCTYPE" size="10" maxlength="6" value="<?php if(isset($default['diDOCTYPE'])) echo strtoupper($default['diDOCTYPE']);  ?>"></td>
				<td><input type="text" name="search[diIMPORTANCE]" id="diIMPORTANCE" size="10" maxlength="50" value="<?php if(isset($default['diIMPORTANCE'])) echo strtoupper($default['diIMPORTANCE']);  ?>"></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crtdate" name="search[crtdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crtdate'])) echo displayDate($default['crtdate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="crtdate_cal" id="crtdate_cal" src="/img/calendar.gif" onclick="cal.select(document.searchForm.crtdate,'crtdate_cal','MM/dd/yyyy'); return false;" /></td>
				<td><input type="text" name="search[crtuser]" id="crtuser" size="10" maxlength="50" value="<?php if(isset($default['crtuser'])) echo strtoupper($default['crtuser']);  ?>" ></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="upddate" name="search[upddate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['upddate'])) echo displayDate($default['upddate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="upddate_cal" id="upddate_cal" src="/img/calendar.gif" onclick="cal.select(document.searchForm.upddate,'upddate_cal','MM/dd/yyyy'); return false;" /></td>
				<td><input type="text" name="search[upduser]" id="upduser" size="10" maxlength="50" value="<?php if(isset($default['upduser'])) echo strtoupper($default['upduser']);  ?>" ></td>
			</tr>
			<tr>
				<td colspan="21"><div>
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
