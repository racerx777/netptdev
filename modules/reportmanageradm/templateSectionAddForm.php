<script language="javascript" src="./javascript/x_core.js"></script>
<script language="javascript" src="./javascript/ylib.js"></script>
<script language="javascript" src="./javascript/y_Tabs.js"></script>
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
// Required Value Report Template Id
if(empty($id))
	error("999","Missing Report Header Id.($id)");
else {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$rthid=$id;
// Retrieve Report Template Header Information
	$report_template_header_select="SELECT * FROM report_template_header WHERE rthid='$rthid'";
	if($report_template_header_result=mysqli_query($dbhandle,$report_template_header_select)) {
		if($report_template_header_row=mysqli_fetch_assoc($report_template_header_result)) {
// Retrieve Report Template Detail Information
			$report_template_detail_select="SELECT * FROM report_template_detail WHERE rthid='$rthid' ORDER BY rtdsequence";
			$report_template_detail_data=array();
			if($report_template_detail_result=mysqli_query($dbhandle,$report_template_detail_select)) {
				while($report_template_detail_row=mysqli_fetch_assoc($report_template_detail_result)) {
					$name=$report_template_detail_row["rtdname"];
					$sequence=$report_template_detail_row["rtdsequence"];
					$headername["$name"]="tab-header".$sequence;
					$contentname["$name"]="tab-content".$sequence;
					$report_template_detail_data["$sequence"]=$report_template_detail_row;
				}
			}
			if(count($headername)>0)
				$headernames=implode(",",$headername);
			if(count($contentname)>0)
				$contentnames=implode(",",$contentname);
		}
		else 
			error("999","Error FETCH report_template_header<br>$report_template_header_select<br>".mysqli_error($dbhandle));
	}
	else 
		error("999","Error SELECT report_template_header<br>$report_template_header_select<br>".mysqli_error($dbhandle));
?>
<script language="javascript">
	var arrayHeaders  = ["<?php echo "tab-headerNew".$headernames; ?>"];
	var arrayContents = ["<?php echo "tab-contentNew".$contentnames; ?>"]; 
	window.onload = function() {
		var tabRelationship = new ylib.widget.Tabs(arrayHeaders, arrayContents, 0);
	}
</script>
<form name="templateSectionAddForm" method="post">
	<div style="clear:both">
		<div> Template Status: <?php echo $report_template_header_row['rthinactive']; ?></div>
		<div> Business Unit/Provider Group/Clinic: <?php echo $report_template_header_row['rthbumcode'] ."/" . $report_template_header_row['rthpgmgroup'] . "/" . $report_template_header_row['rthcnum']; ?> </div>
		<div>Template Name/Description:<?php echo $report_template_header_row['rthname']. " " .$report_template_header_row['rthdescription']; ?> </div>
		<div>Create:<?php echo $report_template_header_row['rthcrtdate']." ".$report_template_header_row['rthcrtuser']." ".$report_template_header_row['rthcrtprog']; ?></div>
		<div>Update:<?php echo $report_template_header_row['rthupddate']." ".$report_template_header_row['rthupduser']." ".$report_template_header_row['rthupdprog']; ?></div>
		<ul class="tab-headers">
			<li id="tab-headerNew" class="active">New Section</li>
<?php
foreach($headername as $name=>$tabheadername) {
	echo('<li id="'.$tabheadername.'">'.$name.'</li>');
}
?>
		</ul>

		<div class="tab-content" id="tab-contentNew"> <br />
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
<?php
}
?>
