<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);
//dumppost();
// Connect to database
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


//declare the SQL statement that will query the database
$query  = "SELECT * FROM treatment_header ";
$where = array();
$where[] = "thcnum IN " . getUserClinicsList() . " ";
$limit = 25;
if (isset($_GET["page"])) {  
    $pn  = $_GET["page"];  
}  
else {  
    $pn=1;  
}
  

$start_from = ($pn-1) * $limit;   
// If Corporate User or better
if(isuserlevel(20)) {
	if($_SESSION['button']=='Show active in UR') {
			$where[] = "(thsbmstatus between '100' and '199')";
		}
	else if($_SESSION['button']=='Show active in Patient Entry') {
			$where[] = "(thsbmstatus between '300' and '399')";
		}
	else if($_SESSION['button']=='Show active in Billing Entry') {
			$where[] = "(thsbmstatus between '500' and '599')";
		}
	else if($_SESSION['button']=='Show all Active') {
			$where[] = "(thsbmstatus between '100' and '599')";
		}
	else if($_SESSION['button']=='Show all Inactive') {
			$where[] = "(thsbmstatus between '900' and '999')";
		}
	else if($_SESSION['button']!= 'Search') {
		if(userlevel()==21)
			$where[] = "(thsbmstatus between '300' and '399') ";
		if(userlevel()==22)
			$where[] = "(thsbmstatus between '500' and '599') ";
		if(userlevel()==23)
			$where[] = "(thsbmstatus between '100' and '199') ";
	}
}
else {
//		$where[] = "(thsbmstatus between '0' and '99')";
	$where[] = "( thsbmdate IS NULL OR DATE_FORMAT(thsbmdate, '%Y-%m-%d') = CURDATE() )";
}

if(count($where) > 0)
	$query .= " WHERE " . implode(" and ", $where) . " ";

// Implement Sort
if($_SESSION['button'] == 'Reset Sort') {
	unset($_POST['sortfields']);
}

// build sortfields array from saved form values
if(!empty($_POST['sortfields']))
	foreach($_POST['sortfields'] as $field=>$data) {
		list($title, $collation) = explode("|", $data);
		$sortfields["$field"] = array("title"=>$title, "collation"=>$collation);
	}
else
	$sortfields = array();

if(!empty($_POST['sort'])) {
	$sortfield = key($_POST['sort']);
	if(array_key_exists($sortfield, $sortfields)) {
		if($sortfields[$sortfield]['collation'] == 'desc')
			$sortfields[$sortfield]['collation'] = '';
		else
			$sortfields[$sortfield]['collation'] = 'desc';
	}
	else
		$sortfields[$sortfield]= array("title"=>$_POST['sort']["$sortfield"], "collation"=>'');
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
$result1 = mysqli_query($dbhandle,$query);
$totalRows = mysqli_num_rows($result1);
$query .= "limit $start_from, $limit";
//dump("query",$query);
$result = mysqli_query($dbhandle,$query);
	if(!$result)
		error("001", "MySql[searchresults]:" . mysqli_error($dbhandle));

$numRows = mysqli_num_rows($result);
$pagLink = ""; 

if($totalRows > $limit){
	// Number of pages required. 
	$total_pages = ceil($totalRows / $limit);   
	$k = (($pn+4>$total_pages)?$total_pages-4:(($pn-4<1)?5:$pn));         
	if($pn>=2){ 
	    $pagLink .= "<a href='index.php?page=1'> << </a>"; 
	    $pagLink .= "<a href='index.php?page=".($pn-1)."'> < </a>"; 
	} 
	for ($i=-4; $i<=4; $i++) { 
	  if($k+$i==$pn) 
	    $pagLink .= "<a class='active' href='index.php?page=".($k+$i)."'>".($k+$i)."</a>"; 
	  else
	    $pagLink .= "<a href='index.php?page=".($k+$i)."'>".($k+$i)."</a>";   
	};   
	if($pn<$total_pages){ 
	    $pagLink .= "<a href='index.php?page=".($pn+1)."'> > </a>"; 
	    $pagLink .= "<a href='index.php?page=".$total_pages."'> >> </a>"; 
	}     
}
?>

<fieldset class="containedBox">
<legend class="boldLarger">Treatment List
<?php if(count($orderby)>0) echo " sorted by " . $sortfieldtitles; else echo " unsorted (click column titles to add/toggle sort)"; ?>
</legend>
<?php
if($numRows>0) {
// save hidden sort fields
	foreach($sortfields as $key=>$val) {
		$value = $val['title'] . '|' . $val['collation'];
		echo('<input name="sortfields[' . $key . ']" type="hidden" value="' . $value . '" />');
	}
?>
<table border="1" cellpadding="3" cellspacing="0" width="100%">
	<tr>
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
	$nowdate = date('Y/m/d', time());
	$numRowsDisplayed=0;
// By default enable submission
	unset($disabled);
	while($row = mysqli_fetch_assoc($result)) {
	//while(0){
// Row Processing
		$dateStyle = "";
		if( datediff('d', $row['thdate'], $nowdate) >= 14) {
			$dateStyle = ' style="background-color:#FFFF99;"';
			if(datediff('d', $row['thdate'], $nowdate) >= 30) {
				$dateStyle = '  style="background-color:#FF9999;"';
			}
		}
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
				while($rowproc = mysqli_fetch_array($resultproc,MYSQLI_ASSOC)) {
					if(!empty($_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']])){
						$str = $_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']];
						//if(userlevel() == 23){
							/*$selectBox = "<select  onchange='return addProcedureModalityQty(\"treatment_procedures\",$rowproc[thid],this.value,\"$rowproc[pmcode]\",\"pmcode\")'>";
							for ($i=0; $i < 6 ; $i++) { 
								if($rowproc['qty'] == $i)
									$selectBox .= "<option value='".$i."' selected>".$i."</option>";
								else
									$selectBox .= "<option value='".$i."'>".$i."</option>";
							}
							$selectBox .= "</select>";*/
							$selectBox = "(".$rowproc['qty'].")";
							$procmodarray[] = $str."  ".$selectBox;
						// }else{
						// 	$procmodarray[] = $str;
						// }
					}
				}
			}
		}
		$proceduretext = "";
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
				while($rowmodality = mysqli_fetch_array($resultmodality,MYSQLI_ASSOC)) {
					$str = "";
					if(!empty($_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']])){
						$str = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];
					}
					if(!empty($_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']])){
						$str = $_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']];
					}
					//if(userlevel() == 23){
					/*	$selectBox = "<select name='".$row['thid'].'_'.$str."' onchange='return addProcedureModalityQty(\"treatment_modalities\",$rowmodality[thid],this.value,\"$rowmodality[mmcode]\",\"mmcode\")'>";
						for ($i=0; $i < 6 ; $i++) { 
							if($rowmodality['qty'] == $i)
								$selectBox .= "<option value='".$i."' selected>".$i."</option>";
							else
								$selectBox .= "<option value='".$i."'>".$i."</option>";
						}
						$selectBox .= "</select>";
						$procmodarray[] = $str."  ".$selectBox;*/
						$procmodarray[] = $str;
					// }else{
					// 	$procmodarray[] = $str;
					// }
				}
			}
		}
		$modulitytext = "";
		if(!empty($procmodarray))
			$modulitytext = "<p><span style='color:#4b7fb4'>M | </span>".implode(', ', $procmodarray)."</p>";


		$rowstatus='';
		$rowfunctions='';
// determine status and function buttons
		switch($row['thsbmstatus']):
// Clinic
			case 0:
				if(isuserlevel(13))
					$rowstatus="Not yet submitted to WestStar.";
				else {
					$rowfunctions=
						'<input name="button[' . $row["thid"] . ']" type="submit" id="editbutton" value="Edit" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" id="deletebutton" value="Delete" />';
				}
				break;
// UR
			case 100:
			case 150:
			case 510:
				if(userlevel()==23) {
					if($row['thsbmstatus']==100)
						$rowstatus="Treatment&nbsp;is&nbsp;in&nbsp;UR.";
					if($row['thsbmstatus']==150)
						$rowstatus="Treatment&nbsp;is&nbsp;in&nbsp;UR&nbsp;and&nbsp;Patient&nbsp;has&nbsp;been&nbsp;entered.";
					if($row['thsbmstatus']==510)
						$rowstatus="Treatment&nbsp;Billing&nbsp;Error.";
					$rowfunctions=
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="To Patient Entry" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="To Billing Entry" />';
				}
				break;
// Patient Entry
			case 300:
				$rowstatus="Treatment&nbsp;is&nbsp;in&nbsp;patient&nbsp;entry.";
				if(userlevel()==21) {
					$rowfunctions=
						'<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Patient Entered" />';
				}
				break;
// Billing
			case 500:
				$rowstatus="Treatment&nbsp;is&nbsp;in&nbsp;billing.";
				if(userlevel()==22) {
					$rowfunctions=
						'<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />';
				}
				break;
// Billed
			case 700:
				$rowstatus="Treatment&nbsp;has&nbsp;been&nbsp;billed.";
				if(userlevel()==23) {
					$rowfunctions=
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />';
				}
				break;
// PTOS Billed
			case 800:
				$rowstatus="Treatment&nbsp;complete.";
				break;
// Inactive/Cancelled
			case 900:
				$rowstatus='<div style="background-color:yellow";>Treatment&nbsp;is&nbsp;cancelled/inactive.</div>';
				if(userlevel()==23) {
					$rowfunctions=
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />' .
						'<input name="button[' . $row["thid"] . ']" type="submit" value="Make Active" />';
				}
				break;
		endswitch;

// Admin Override Functions
		if(isuserlevel(99)) {
			$rowfunctions=
				'<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="To Patient Entry" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="Patient Entered" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="To Billing Entry" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />' .
				'<input name="button[' . $row["thid"] . ']" type="submit" value="Make Active" />';
		}

		if(!empty($rowfunctions))
			$rowfunctions.="<br>";
		if(!empty($rowstatus))
			$rowstatus.="<br>";
		if(!empty($row['thsbmstatus']))
			$rowstatus .= "Submitted&nbsp;on&nbsp;" . date('m/d/Y', strtotime($row['thsbmdate'])) . ".<br>";

		if(userlevel()>12 || $row['thsbmstatus'] < 100) {
//		if($row['thsbmstatus'] < 100) {
//			$isuserlevel=isuserlevel(11);
//			($thsbmstatus=($row['thsbmstatus']<100);
//			dump("values","$isuserlevel : $thsbmstatus");
			$numRowsDisplayed++;
?>
	<tr>
		<td><?php echo $row['thid']; $row["thcnum"]; ?>&nbsp;</td>
		<td <?php echo $dateStyle; ?>><?php echo date('m/d/Y', strtotime($row["thdate"])); ?>&nbsp;</td>
		<td><?php echo $row["thpnum"]; ?>&nbsp;</td>
		<td><?php echo $row["thlname"]; ?>&nbsp;</td>
		<td><?php echo $row["thfname"]; ?>&nbsp;</td>
		<td><?php echo $casetypetext; ?>&nbsp;</td>
		<td><?php echo $visittypetext; ?>&nbsp;</td>
		<td><?php echo $treatmenttypetext; ?>&nbsp;</td>
		<td><?php echo $proceduretext; ?><?php echo $modulitytext; ?></td>
		<td><?php if($row['thnadate']<='2012-08-01 00:00:00.000') echo "(none)"; else echo date('m/d/Y', strtotime($row["thnadate"])); ?>&nbsp;</td>
		<td style="min-width:100px;"><?php echo("$rowfunctions $rowstatus"); ?></td>
	</tr>
<?php
		}
		else {
			if($row['thsbmuser']!='NoNetPT') {
				//$disabled = 'disabled="disabled"';
			}
		}
//		dump("crtuser",$row['crtuser']);
	} // end while

?>
</table>
<div style="margin:10px;">
	<div class="boldLarger"><?php
}
if($numRowsDisplayed > 0) {
	if($numRowsDisplayed == 1){
		echo "$numRowsDisplayed treatment found.";
		
	}
	else {
		if($numRowsDisplayed < 100)
			echo "$totalRows treatments found.";
		else
			echo "Over $numRowsDisplayed treatments found. Did not display all treatments.";
	}
}
else {
	$disabled = 'disabled="disabled"';
	echo('No treatments found.');
}
if($_SESSION['user']['umuser']=='NoNetPT') {
	$disabled = '';
}

// Provide Print or Submit Button
if(isuserlevel(13))
	$tablebutton = '<input name="print" type="button" value="Print" onclick="window.print();">';
else
	$tablebutton = '<input ' . $disabled . ' name="button[]" type="submit" value="Submit treatment list to WestStar">';

echo '<div class="pagination" style="float:right;margin-left:10px;">'.$pagLink.'</div>';
mysqli_close($dbhandle);
?>
		<div style="float:right;margin-top: 7px;"><?php echo $tablebutton; ?> </div>
<?php
if($numRows>0) {
?>
	</div>
</div>
<?php
}
?>
</fieldset>
<fieldset class="containedBox">
<legend>Date Color Indicator Definitions</legend>
<div style="float:left; width:510px;">
	<div style="border:solid 1px; background-color:#FF9999; float:left; min-width:250px; width:250px;">&nbsp; Treatment date older than 30 days </div>
	<div style="border:solid 1px; background-color:#FFFF99; float:right; min-width:250px; width:250px;">&nbsp; Treatment date older than 14 days </div>
</div>
</fieldset>
</div>
<style type="text/css" media="screen">
.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}
.pagination a.active {
  background-color: #FFFF99;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
 function addProcedureModalityQty(table,thid,qty,pmcode,codekey){
 	$.post("/modules/treatment/addProcedureModalityQty.php",{'table':table,'thid':thid,'qty':qty,'pmcode':pmcode,'codekey':codekey,'addProcedureModalityQty':1},function(res){
 		console.log(res.status)
 	})
 }
</script>