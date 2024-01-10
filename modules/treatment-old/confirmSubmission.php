<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

//declare the SQL statement that will query the database
$query  = "SELECT * FROM treatment_header WHERE thsbmStatus=0 and thcnum IN " . getUserClinicsList() . " ";
//execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if(!$result)
	echo mysqli_error($dbhandle);	
$totalRows = mysqli_num_rows($result);
$sbmDate = date('Y-m-d H:i:s');
?>
<div class="containedBox">
	<fieldset>
	<legend class="boldLarger">Treatment List Submission</legend>
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
			<th>Submit Status</th>
		</tr>
		<?php
$numRows=0;
$cliniclistarray=array();
$clinics = $_SESSION['useraccess']['clinics'];
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
	$lnamefname=strtoupper(trim($row['thlname']) . trim($row['thfname']));
	$cliniclistarray[$row['thcnum']] = $row['thcnum'] . '-' . $clinics[$row['thcnum']]['cmname'];
	$updatequery = "UPDATE treatment_header set thsbmStatus='100', thsbmDate='" . $sbmDate . "', thsbmUser='" . $_SESSION['user']['umuser'] . "' WHERE thid = '" . $row["thid"] . "'";
	$update = mysqli_query($dbhandle,$updatequery);
	if(!$update)
		$sbm=mysqli_error($dbhandle);
	else
		$sbm="SUBMITTED";

		$casetypetext = $_SESSION['casetypes'][$row['thctmcode']];
		$visittypetext = $_SESSION['visittypes'][$row['thvtmcode']];
		$treatmenttypetext = $_SESSION['treatmenttypes'][$row['thttmcode']];

		$procmodarray = array();
		$queryproc  = "SELECT * FROM treatment_procedure_groups WHERE thid='" . $row['thid'] . "' and gmcode not in ('A','P') ORDER BY thid, gmcode";
		$resultproc = mysqli_query($dbhandle,$queryproc);
		if(!$resultproc)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsproc = mysqli_num_rows($resultproc);
			if($numRowsproc != NULL) {
				while($rowproc = mysqli_fetch_array($resultproc,MYSQLI_ASSOC)) {
					$procmodarray[] = $_SESSION['procedures'][$row['thttmcode']][$rowproc['gmcode']];
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
					$procmodarray[] = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];
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
			<td><?php echo $sbm; ?></td>
		</tr>
		<?php
	$numRows++;
	addheaderhistory($row["thid"], $sbmDate, $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'SUBMITTED', 'Treatment Submitted to Weststar.', $updatequery);
}
?>
		<tr>
			<td colspan="8" class="boldLarger"><?php echo $numRows . " of " . $totalRows . " treatment(s) submitted on " . $sbmDate . "."; ?> </td>
		</tr>
	</table>
	<?php
//close the connection
mysqli_close($dbhandle);
$userclinic = implode(", ", $cliniclistarray);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/mail.php'); 
sendSubmissionNotification($userclinic);
?>
	</form>
	<form action="" method="post" name="treatmentSubmit">
		<div	id="container">
			<div style="float:left">
				<input name="button[]" type="submit" value="Return to treatment list">
			</div>
			<div style="float:right">
				<input name="print" type="button" value="Print submitted list for your records" onclick="window.print();">
			</div>
		</div>
	</form>
	</fieldset>
</div>