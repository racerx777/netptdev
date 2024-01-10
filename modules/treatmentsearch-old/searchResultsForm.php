<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
?>
<script type="text/javascript">
function selectallcheckboxes() {
// written by Daniel P 3/21/07
// toggle all checkboxes found on the page
	var inputlist = document.getElementsByTagName("input");
	for (i = 0; i < inputlist.length; i++) {
	if ( inputlist[i].getAttribute("type") == 'checkbox' ) { // look only at input elements that are checkboxes
		if (inputlist[i].checked) inputlist[i].checked = false
		else inputlist[i].checked = true;
		}
	}
}
</script>
<?php

//if($_SESSION['button']=='Search') 
if(!empty($_SESSION['id']) && $_SESSION['button']!='Search') {
	cleartreatmentsearchvalues();
	$_POST['searchpnum']=$_SESSION['id'];
	$_SESSION['button']='Search';
}
//dumppost();
puttreatmentsearchvalues();

if($_SESSION['button']=='Reset Sort') 
	cleartreatmentsortvalues();

if( ($_POST['button'][0]=='Search' || (!empty($_SESSION['button']) && ($_SESSION['button']=='Search' || $_SESSION['button']=='Reset Sort') ) || !empty($_POST['sort']) )) {
	$search=FALSE;
	gettreatmentsearchvalues();
	foreach($_POST as $key=>$val) {
		if(!empty($val)) 
			$search = TRUE;
	}

	if($search) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		
		// Declare the SQL statement that will query the database
		$query  = "SELECT treatment_header.* FROM treatment_header";
		$where = array();
		
		// Set minimum Clinic Filter
		$where[] = "thcnum IN " . getUserClinicsList() . " ";
		
		// Determine Clinic Filter
		if(!empty($_POST['searchcnum']))
			$where[] = "thcnum IN ( '" . implode("','", $_POST['searchcnum']) . "' )";
		
		if(isset($_POST['searchfromtreatmentdate']) && !empty($_POST['searchfromtreatmentdate'])) 
			$where[] = "DATE_FORMAT( thdate, '%Y%m%d' ) >= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle,$_POST['searchfromtreatmentdate']))) . "', '%Y%m%d') ";
		
		if(isset($_POST['searchtotreatmentdate']) && !empty($_POST['searchtotreatmentdate'])) 
			$where[] = "DATE_FORMAT( thdate, '%Y%m%d' ) <= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle,$_POST['searchtotreatmentdate']))) . "', '%Y%m%d') ";
				
		if(isset($_POST['searchpnum']) && !empty($_POST['searchpnum'])) 
			$where[] = "thpnum= '" . mysqli_real_escape_string($dbhandle,$_POST['searchpnum']) . "'";
        
		if(isset($_POST['searchbnum']) && !empty($_POST['searchbnum'])) {
            $query .= " LEFT JOIN PTOS_Patients ON pnum = thpnum ";
			$where[] = "bnum = '" . mysqli_real_escape_string($dbhandle,$_POST['searchbnum']) . "'";
        }
		
		if(isset($_POST['searchlname']) && !empty($_POST['searchlname'])) 
			$where[] = "thlname LIKE '" . mysqli_real_escape_string($dbhandle,$_POST['searchlname']) . "%'";
		
		if(isset($_POST['searchfname']) && !empty($_POST['searchfname'])) 
			$where[] = "thfname LIKE '" . mysqli_real_escape_string($dbhandle,$_POST['searchfname']) . "%'";
		
		if(isset($_POST['searchctmcode']) && !empty($_POST['searchctmcode'])) 
			$where[] = "thctmcode= '" . mysqli_real_escape_string($dbhandle,$_POST['searchctmcode']) . "'";
		
		if(isset($_POST['searchvtmcode']) && !empty($_POST['searchvtmcode'])) 
			$where[] = "thvtmcode= '" . mysqli_real_escape_string($dbhandle,$_POST['searchvtmcode']) . "'";
		
		if(isset($_POST['searchttmcode']) && !empty($_POST['searchttmcode'])) 
			$where[] = "thttmcode= '" . mysqli_real_escape_string($dbhandle,$_POST['searchttmcode']) . "'";
		
		if(isset($_POST['searchfromsubmitdate']) && !empty($_POST['searchfromsubmitdate'])) 
			$where[] = "DATE_FORMAT( thsbmdate, '%Y%m%d' ) >= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle,$_POST['searchfromsubmitdate']))) . "', '%Y%m%d') ";
		
		if(isset($_POST['searchtosubmitdate']) && !empty($_POST['searchtosubmitdate'])) 
			$where[] = "DATE_FORMAT( thsbmdate, '%Y%m%d' ) <= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle,$_POST['searchtosubmitdate']))) . "', '%Y%m%d') ";

		if(isset($_POST['searchsbmstatus']) && !empty($_POST['searchsbmstatus'])) 
			$where[] = "thsbmstatus " . mysqli_real_escape_string($dbhandle,$_POST['searchsbmstatus']) . " ";	

		if(count($where) > 0) 
			$query .= " WHERE " . implode(" and ", $where) . " ";

// Implement Sort
// build sortfields array from saved form values
//gettreatmentsortvalues();
gettreatmentsortvalues();
if(!empty($_POST['sortfields'])) 
	foreach($_POST['sortfields'] as $field=>$data) {
		list($title, $collation) = explode("|", $data);
		$sortfields["$field"] = array("title"=>$title, "collation"=>$collation);
	}
else
	$sortfields = array();

// URL Encoded Parameters need to be re-packaged
if(is_array($_POST['sort'])) {
	if(!empty($_POST['sort'])) {
		$sortfield = key($_POST['sort']);
		if(array_key_exists($sortfield, $sortfields)) {
			if($sortfields[$sortfield]['collation'] == 'desc')
				$sortfields[$sortfield]['collation'] = '';
			else
				$sortfields[$sortfield]['collation'] = 'desc';
		}
		else
			$sortfields[$sortfield]=array("title"=>$_POST['sort']["$sortfield"], "collation"=>'');
		foreach($sortfields as $key=>$val) 
			$_POST['sortfields'][$key]=$val['title'] . "|" . $val['collation'];
		puttreatmentsortvalues();
	}
}

$orderby=array();
$sortfieldtitles='';
foreach($sortfields as $key=>$val) {
	$orderby[] = $key . " " . $val['collation'];
	if($val['collation'] == 'desc')
		$sortfieldtitles .= $val['title'] . ' (descending), ';
	else
		$sortfieldtitles .= $val['title'] . ', ';
}
if(count($orderby) > 0) {
	$sortfieldtitles = substr($sortfieldtitles,0,-2);
	$query .= " ORDER BY " . implode(", ", $orderby) . " ";
}

if(userlevel()==10 || userlevel()==99) 
	$query .= " LIMIT 100";

//dump('query', $query);
$result = mysqli_query($dbhandle,$query);
	if(!$result)
		error("001", "MySql[searchresults]:" . mysqli_error($dbhandle));	

$numRows = mysqli_num_rows($result); 

function echosearchlink($pnum) {
	if(userlevel()>=23 && empty($_POST['searchpnum'])) {
		echo('<input type="submit" name="button[' . $pnum . ']" value="'.$pnum.'" />'); 
	}
	else
		echo("$pnum");
}
?>

<fieldset class="containedBox">
<legend class="boldLarger">Search Results New
<?php if(count($orderby)>0) echo " sorted by " . $sortfieldtitles; else echo " unsorted (click column titles to add/toggle sort)"; ?>
</legend>
<?php
			if($numRows>0) {
			?>
<?php 
			foreach($sortfields as $key=>$val) {
		?>
<input name="sortfields[<?php echo $key; ?>]" type="hidden" value="<?php echo $val['title'] . "|" . $val['collation']; ?>" />
<?php
			}
		?>
<table border="1" cellpadding="3" cellspacing="0" width="100%">
	<tr>
<?php 
if(userlevel()==23) {
?>
		<th nowrap="nowrap"><input name="selectall" type="checkbox" value="Sel" onclick="selectallcheckboxes();" /></th>
<?php
}
?>
		<th><input name="sort[thcnum]" type="submit" value="Clinic" /></th>
		<th><input name="sort[thdate]" type="submit" value="Treatment Date" /></th>
		<th><input name="sort[thpnum]" type="submit" value="Number" /></th>
		<th><input name="sort[thlname]" type="submit" value="Last Name" /></th>
		<th><input name="sort[thfname]" type="submit" value="First Name" /></th>
		<th><input name="sort[thctmcode]" type="submit" value="Case Type" /></th>
		<th><input name="sort[thvtmcode]" type="submit" value="Visit Type" /></th>
		<th><input name="sort[thttmcode]" type="submit" value="Treatment Type" /></th>
		<th>Procedures/Modalities</th>
		<th><input name="sort[thnadate]" type="submit" value="Next Action Date" /></th>
		<th><input name="button[]" type="submit" value="Reset Sort"></th>
	</tr>
	<?php
				$billablerows=0;
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
					$thid=$row['thid'];
					if(isset($_POST['checkbox'][$thid]) && $_POST['checkbox'][$thid]==1)
						$_POST['checkbox'][$thid]='checked';
					else
						$_POST['checkbox'][$thid]='';
					$pnum=$row['thpnum'];
					$casetypestyle="";
					if(!empty($pnum)) {
						if(userlevel() >= 23) {
							$casetypequery="
								SELECT count(*) as casetypecount FROM (
									SELECT DISTINCT thctmcode from treatment_header where thpnum='$pnum'
								) as a";
							if($casetyperesult = mysqli_query($dbhandle,$casetypequery)) {
								if($casetyperow=mysqli_fetch_assoc($casetyperesult)) {
									if($casetyperow['casetypecount']>1)
										$casetypestyle='style="background-color:#FFFF00"';
								}
							}
						}
					}
					else
						unset($pnum);
					$casetypetext = $_SESSION['casetypes'][$row['thctmcode']];
					$visittypetext = $_SESSION['visittypes'][$row['thvtmcode']];
					$treatmenttypetext = $_SESSION['treatmenttypes'][$row['thttmcode']];
					$procmodarray = array();
					
					$queryproc  = "SELECT * FROM treatment_procedures WHERE thid='" . $row['thid'] . "' AND pmcode not in ('A','P') ORDER BY thid, pmcode";
					$resultproc = mysqli_query($dbhandle,$queryproc);
				
					if(!$resultproc)
						error("001", mysqli_error($dbhandle));
					else {
						$numRowsproc = mysqli_num_rows($resultproc);
						if($numRowsproc != NULL) {
							while($rowproc = mysqli_fetch_array($resultproc)) {
								if(!empty($_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']])) {
									$str = $_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']];
									$selectBox = " (".$rowproc['qty'].")";
									$procmodarray[] = $str.$selectBox;
								}else{
									$querymaster  = "SELECT * FROM master_procedures WHERE pmcode='" . $rowproc['pmcode'] . "'";
									$resultmaster = mysqli_query($dbhandle,$querymaster);
				
									if(!$resultmaster){
										error("001", mysqli_error($dbhandle));
									}else {
										$numRowsmaster = mysqli_num_rows($resultmaster);
										if($numRowsmaster != NULL) {
											while($rowmaster = mysqli_fetch_array($resultmaster)) {
												$str = $rowmaster['pmdescription'];
												$selectBox = " (".$rowproc['qty'].")";
												$procmodarray[] = $str.$selectBox;
											}
										}
									}
								}
							}
						}
					}
				if(!empty($procmodarray))
					$proceduretext = "<p><span style='color:#4b7fb4'>P |</span> ".implode(', ', $procmodarray)."</p>";
				$procmodarray = array();


			//declare the SQL statement that will query the database
					$querymodality  = "SELECT * FROM treatment_modalities WHERE thid='" .  $row['thid'] . "' and mmcode not in ('15P') ORDER BY thid, mmcode";
					$resultmodality = mysqli_query($dbhandle,$querymodality);
					if(!$resultmodality)
						error("001", mysqli_error($dbhandle));
					else {
						$numRowsmodality = mysqli_num_rows($resultmodality);
						if($numRowsmodality != NULL) {
							while($rowmodality = mysqli_fetch_array($resultmodality)) {
								if(!empty($_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']]))
									$procmodarray[] = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];
								if(!empty($_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']]))
									$procmodarray[] = $_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']];

								/*$selectBox = "<select name='".$row['thid'].'_'.$str."' onchange='return addProcedureModalityQty(\"treatment_modalities\",$rowmodality[thid],this.value,\"$rowmodality[mmcode]\",\"mmcode\")'>";
								for ($i=0; $i < 6 ; $i++) { 
									if($rowmodality['qty'] == $i)
										$selectBox .= "<option value='".$i."' selected>".$i."</option>";
									else
										$selectBox .= "<option value='".$i."'>".$i."</option>";
								}
								$selectBox .= "</select>";*/
								
							}
						}
					}


					$modulitytext = "";
					///$modulitytext = implode(', ', $procmodarray);
					if(!empty($procmodarray))
						$modulitytext = "<p><span style='color:#4b7fb4'>M | </span>".implode(', ', $procmodarray)."</p>";
		?>
	<tr>
<?php
if(userlevel()==23) {
	if($row['thsbmstatus']>=100 && $row['thsbmstatus'] < 500 && !empty($pnum)) {
		$billablerows++;
?>
		<td><input name="checkbox[<?php echo $thid; ?>]" type="checkbox" value="<?php echo $row['thid']; ?>" <?php if($_POST['checkbox'][$row['thid']]==1) echo "checked"; ?>/></td>
<?php
	}
	else {
?>
		<td>&nbsp;</td>
<?php
	}
} 
?>
		<td><?php echo $row["thcnum"]; ?>&nbsp;</td>
		<td <?php echo $dateStyle; ?>><?php echo date('m/d/Y', strtotime($row["thdate"])); ?>&nbsp;</td>
		<td><?php echosearchlink($pnum); ?>&nbsp;</td>
		<td><?php echo $row["thlname"]; ?>&nbsp;</td>
		<td><?php echo $row["thfname"]; ?>&nbsp;</td>
		<td <?php echo $casetypestyle; ?>><?php echo $casetypetext; ?>&nbsp;</td>
		<td><?php echo $visittypetext; ?>&nbsp;</td>
		<td><?php echo $treatmenttypetext; ?> &nbsp;</td>
		<td><?php echo $proceduretext; ?><?php echo $modulitytext; ?>&nbsp;</td>
		<td><?php if($row['thnadate']<='2012-08-01 00:00:00.000') echo "(none)"; else echo date('m/d/Y', strtotime($row["thnadate"])); ?>&nbsp;</td>
		<td style="min-width:100px;"><?php 
					if($row['thsbmstatus'] == 0) {
						if(isuserlevel(20)) 
							echo('Not yet submitted by clinic.');
						else {
							echo('
							<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />
							<input name="button[' . $row["thid"] . ']" type="submit" value="Delete" />
							');
						}
					}
					if($row['thsbmstatus'] > 0) { 
						if(isuserlevel(99)) {
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To Patient Entry" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Patient Entered" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To Billing Entry" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
							echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Active" />');
						}
						else {
							if(isuserlevel(20)) {
								if(($row['thsbmstatus']>=100 && $row['thsbmstatus']<=199) || $row['thsbmstatus']==510) {
									if(userlevel()==23) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To Patient Entry" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To Billing Entry" />');
									}
									if($row['thsbmstatus']==100)
										echo ("Treatment&nbsp;is&nbsp;in&nbsp;UR.<br>");
									if($row['thsbmstatus']==150)
										echo ("Treatment&nbsp;is&nbsp;in&nbsp;UR&nbsp;and&nbsp;Patient&nbsp;has&nbsp;been&nbsp;entered.<br>");							
								}
								if($row['thsbmstatus']==300) {
									if(userlevel()==21) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Patient Entered" />');
									}
									echo ("Treatment&nbsp;is&nbsp;in&nbsp;patient&nbsp;entry.<br>");
								}
								if($row['thsbmstatus']==500) {
									if(userlevel()==22) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />');
									}
									echo ("Treatment&nbsp;is&nbsp;in&nbsp;billing.<br>");
								}
								if($row['thsbmstatus']==700) {
									if(userlevel()==23) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
									}
									echo ("Treatment&nbsp;has&nbsp;been&nbsp;billed.<br>");
								}
								if($row['thsbmstatus']==710) {
									if(userlevel()==23) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Rollback Billing" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
									}
									echo ("Treatment&nbsp;has&nbsp;been&nbsp;auto-billed.<br>");
								}
								if($row['thsbmstatus']==800) {
									if(userlevel()==23) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
									}
									echo ("Treatment&nbsp;is&nbsp;completed.<br>");
								}
								if($row['thsbmstatus']==900) {
									if(userlevel()==23) {
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
										echo('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Active" />');
									}
									echo ('<div style="background-color:yellow";>Treatment&nbsp;is&nbsp;cancelled/inactive.</div>');
								}
							}
							else {
								if($row['thsbmstatus'] >= 900) 
									echo ("Treatment&nbsp;is&nbsp;cancelled/inactive.<br>");
							}
						}
						echo ("Submitted&nbsp;on&nbsp;" . date('m/d/Y', strtotime($row['thsbmdate'])));
					}
			?>
		</td>
	</tr>
	<?php
				}
			?>
</table>
<div style="margin:10px;">
<?php
if( userlevel()==23 && !empty($billablerows) ){
?>
	<div style="float:left">
		<input name="button[]" type="submit" value="Selected To Billing Entry">
	</div>
<?php
}
if($_REQUEST['searchfunction']=='Search') {
	$onclick="window.close()";
	$title="Close";
}
else {
	$onclick="window.print();";
	$title="Print";
}
?>
	<div style="float:right">
		<input name="print" type="button" value="<?php echo $title; ?>" onclick="<?php echo $onclick; ?>">
	</div>
	<div class="boldLarger" style="clear:both">
		<?php
				echo $numRows . " treatment(s) found ";
			//		require_once('treatmentSubmitTreatmentsForm.php');
			}
			else {
				echo('No treatments found ');
			}
			//close the connection
			mysqli_close($dbhandle);
			// 	Select unposted records for current clinic
			?>
		for <?php echo $_SESSION['workingDate']; ?> as of <?php echo date('m/d/Y H:i:s'); ?>. </div>
</div>
</fieldset>
</div>
<?php 
	}
	else {
?>
<fieldset class="containedBox">
<legend class="boldLarger">Search Results</legend>
<div>At least one search value must be entered above.</div>
</fieldset>
<?php	}
} 
?>
