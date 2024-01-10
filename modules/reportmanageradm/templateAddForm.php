<script language="javascript" src="./javascript/x_core.js"></script>
<script language="javascript" src="./javascript/ylib.js"></script>
<script language="javascript" src="./javascript/y_Tabs.js"></script>
<script language="javascript">
	var arrayHeaders  = ["tab-header1"];
	var arrayContents = ["tab-content1"]; 
	window.onload = function() {
		var tabRelationship = new ylib.widget.Tabs(arrayHeaders, arrayContents, 0);
	}
</script>
<style>
ul.tab-headers, .tab-content {
	width:736px;
	height:400px;
}
ul.tab-headers {
	height: 20px;
	list-style-type: none;
	margin: 0 0 0 0;
	padding: 0;
}
ul.tab-headers li {
	float: left;
	padding: 3px 10px 3px 10px;
	border: solid 1px lightblue;
	border-bottom: 0;
	margin-left: 3px;
	cursor: pointer;
	color: gray;
}
ul.tab-headers li.active {
	background-color:lightblue;
	color: black;
}
.tab-content {
	margin: 0;
	padding: 0 5px 5px 5px;
	border: solid 1px lightblue;
	visibility: hidden;
	background-color: lightblue;
}
</style>
<?php

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

$inactivevalues=array("Active"=>array("value"=>"0", "name"=>"Active"), "Inactive"=>array("value"=>"1", "name"=>"Inactive"));

?>
<form name="templateAddForm" method="post">
<div style="clear:both">
	<ul class="tab-headers">
		<li id="tab-header1" class="active">Template Information</li>
	</ul>
	<div class="tab-content" id="tab-content1"> <br />
		<div style="position:relative; display:table; background-color:#FF0000;">
			<div style="display:table-row; padding:5px; background-color:#FFFF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">Template Active/Inactive:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<select name="rthinactive" id="rthinactive">
						<?php echo getSelectOptions($arrayofarrayitems=$inactivevalues, $optionvaluefield='value', $arrayofoptionfields=array('name'=>''), $defaultoption=$default['rthinactive'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#FFFF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">Business Unit:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<select name="rthbumcode" id="rthbumcode">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['businessunits'], $optionvaluefield='bumcode', $arrayofoptionfields=array('bumname'=>' (', 'bumcode'=>')'), $defaultoption=$default['rthbumcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#FFFF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">Provider Group:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<select name="rthpgmcode" id="rthpgmcode">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['providergroups'], $optionvaluefield='pgmcode', $arrayofoptionfields=array('pgmname'=>' (', 'pgmcode'=>')'), $defaultoption=$default['rthpgmcode'], $addblankoption=FALSE, $arraykey='pgmbumcode', $arrayofmatchvalues=array($default['rthbumcode'])); ?>
					</select>
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#0000FF;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">Clinic:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<select name="rthcnum" id="rthcnum">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$default['rthcnum'], $addblankoption=TRUE, $arraykey='cmpgmcode', $arrayofmatchvalues=array($default['rthpgmcode'])); ?>
					</select>
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#00FF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">Template Name:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<input type="text" name="rthname" size="16" value="<?php echo $_POST['name']; ?>" />
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#00FF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">Template Description:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<input type="text" name="rthdescription" size="50" maxlength="255"  value="<?php echo $_POST['description']; ?>" />
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#00FF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">List Sequence:</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<input type="text" name="rthsequence" size="5"  value="<?php echo $_POST['sequence']; ?>" />
				</div>
			</div>
			<div style="display:table-row; padding:5px; background-color:#00FF00;">
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<input type="submit" name="button[]" value="Cancel" />
				</div>
				<div style="display:table-cell; padding:5px; white-space:nowrap;">
					<input type="submit" name="button[]" value="Confirm Add Template" />
				</div>
			</div>			
		</div>
	</div>
</div>
</form>
