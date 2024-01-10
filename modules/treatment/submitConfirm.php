<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
errorclear();
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query  = "SELECT * FROM treatment_header WHERE thsbmStatus=0 and thcnum IN " . getUserClinicsList() . " ";
 //execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if(!$result)
	error("001", mysqli_error($dbhandle));
$numRows = mysqli_num_rows($result);
?>
<div class="containedBox">
<fieldset>
<legend class="boldLarger">Confirm Treatment List</legend>
<?php
if($numRows > 0) {
?>
<form method="post" name="searchlist">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Treatment Date</th>
			<th>Number</th>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Case Type</th>
			<th>Visit Type</th>
			<th>Treatment Type</th>
			<th>Procedures/Modalities</th>
		</tr>
		<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
		$searchRowStyle = "";
		if($workingToday==true) {
			if( datediff('d', $row['thdate'], $nowdate) >= 14) {
				$searchRowStyle = ' style="background-color:#FFFF99;"';
				if(datediff('d', $row['thdate'], $nowdate) >= 30) {
					$searchRowStyle = '  style="background-color:#FF9999;"';
				}
			}
		}
		$casetypetext = $_SESSION['casetypes'][$row['thctmcode']];
		$visittypetext = $_SESSION['visittypes'][$row['thvtmcode']];
		$treatmenttypetext = $_SESSION['treatmenttypes'][$row['thttmcode']];

		$procmodarray = array();
		$queryproc  = "SELECT * FROM treatment_procedures WHERE thid='" . $row['thid'] . "' AND pmcode not in ('A','P') ORDER BY thid, pmcode";
		//$queryproc  = "SELECT * FROM treatment_procedure_groups WHERE thid='" . $row['thid'] . "' and gmcode not in ('A','P') ORDER BY thid, gmcode";
		$resultproc = mysqli_query($dbhandle,$queryproc);
		if(!$resultproc)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsproc = mysqli_num_rows($resultproc);
			if($numRowsproc != NULL) {
				while($rowproc = mysqli_fetch_array($resultproc,MYSQLI_ASSOC)) {
					//$procmodarray[] = $_SESSION['procedures'][$row['thttmcode']][$rowproc['gmcode']];
					if(!empty($_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']]))
						$procmodarray[] = $_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']];

							$selectBox = "<select onchange='return addProcedureModalityQty(\"treatment_procedures\",$rowproc[thid],this.value,\"$rowproc[pmcode]\",\"pmcode\")'>";
							for ($i=0; $i < 6 ; $i++) { 
								if($rowproc['qty'] == $i)
									$selectBox .= "<option value='".$i."' selected>".$i."</option>";
								else
									$selectBox .= "<option value='".$i."'>".$i."</option>";
							}
							$selectBox .= "</select>";
							$procmodarray[] = $str."  ".$selectBox;
				}
			}
		}

//declare the SQL statement that will query the database
		$querymodality  = "SELECT * FROM treatment_modalities WHERE thid='" .  $row['thid'] . "' and mmcode not in ('15P') ORDER BY thid, mmcode";
		$resultmodality = mysqli_query($dbhandle,$querymodality);
		if(!$resultmodality)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsmodality = mysqli_num_rows($resultmodality);
			if($numRowsmodality != NULL) {
				while($rowmodality = mysqli_fetch_array($resultmodality,MYSQLI_ASSOC)) {
					if(!empty($_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']]))
						$procmodarray[] = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];
					if(!empty($_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']]))
						$procmodarray[] = $_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']];

						$selectBox = "<select name='".$row['thid'].'_'.$str."' onchange='return addProcedureModalityQty(\"treatment_modalities\",$rowmodality[thid],this.value,\"$rowmodality[mmcode]\",\"mmcode\")'>";
						for ($i=0; $i < 6 ; $i++) { 
							if($rowmodality['qty'] == $i)
								$selectBox .= "<option value='".$i."' selected>".$i."</option>";
							else
								$selectBox .= "<option value='".$i."'>".$i."</option>";
						}
						$selectBox .= "</select>";
						$procmodarray[] = $str."  ".$selectBox;
				}
			}
		}
		$proceduretext = implode(', ', $procmodarray);
?>
		<tr <?php echo $searchRowStyle; ?> >
			<td><?php echo date('m/d/Y', strtotime($row["thdate"])); ?></td>
			<td><?php echo $row["thpnum"]; ?></td>
			<td><?php echo $row["thlname"]; ?></td>
			<td><?php echo $row["thfname"]; ?></td>
			<td><?php echo $casetypetext; ?></td>
			<td><?php echo $visittypetext; ?></td>
			<td><?php echo $treatmenttypetext; ?></td>
			<td><?php echo $proceduretext; ?></td>
		</tr>
		<?php
	}
?>
		<tr>
			<td colspan="8" class="boldLarger">
				<?php echo $numRows . " unposted treatment(s) as of " . date('m/d/Y H:i:s')?>
			</td>
		</tr>
		<tr>
			<td colspan="8" class="boldLarger"> By clicking the Confirm Submission button below, I hereby certify that all information on this form is correct and complete to the best of my knowledge and it is consistent with all treatment information in the patient file or records. </td>
		</tr>
	</table>
</form>
<form action="" method="post" name="treatmentSubmit">
	<div id="container" style="clear:both; margin:10px;">
		<div style="float:left">
			<input name="button[]" type="submit" value="Return to treatment list">
		</div>
		<div style="float:right">
			<input name="button[]" type="submit" value="Confirm Submission">
		</div>
	</div>
</form>
<?php
}
else {
	echo('No unposted treatments.');
}
//close the connection
mysqli_close($dbhandle);
// 	Select unposted records for current clinic
?>
</fieldset>
</div>
<?php
exit;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
 function addProcedureModalityQty(table,thid,qty,pmcode,codekey){
 	$.post("/modules/treatment/addProcedureModalityQty.php",{'table':table,'thid':thid,'qty':qty,'pmcode':pmcode,'codekey':codekey,'addProcedureModalityQty':1},function(res){
 		console.log(res.status)
 	})
 }
</script>