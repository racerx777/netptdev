<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
if(!empty($_POST['crid']))
	$crid=$_POST['crid'];

if(!empty($_GET['crid']))
	$crid=$_GET['crid'];

if(!empty($crid)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	select * 
	from cases 
		left join master_clinics 
		on crcnum=cmcnum
		left join patients
		on crpaid = paid
		left join doctor_relationships
		on crrefdmid = drdmid and crrefdlid=drdlid
			left join doctors
			on drdmid=dmid
			left join doctor_locations
			on drdlid=dlid
	where crid='$crid'";

	if($result = mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			foreach($row as $field=>$value) 
				$_POST["$field"]=$value;
			$status = $_POST['crcasestatuscode'];
			$clinicname = $_POST['cmname'];
			$referraldate = displayDate($_POST['crdate']);
			$evaldate = displayDate($_POST['crapptdate']) . " at " . displayTime($_POST['crapptdate']);
			$therapytype = $_POST['crtherapytypecode'];
			$patientname = properCase($_POST['pafname']. " " . $_POST['palname']);
			$patientaddress = properCase(trim($_POST['paaddress1']). " " . trim($_POST['paaddress2']));
			$patientcitystatezip = properCase($_POST['pacity']) . ", " . strtoupper($_POST['pastate']) . " " . displayZip($_POST['pazip']);
			if(!empty($_POST['dmfname']))
				$dr = $_POST['dmlname'] . ", " . $_POST['dmfname'];
			else
				$dr = $_POST['dmlname'];
			$drphone = displayPhone($_POST['dlphone']);
			$drfax = displayPhone($_POST['dlfax']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Scheduling Update Letter</title>
</head>
<body style="width:576px" onload="window.print();window.close();">
<table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td><div>
				<div style="float:left"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
				<div style="float:right">
					<h3>Scheduling Update</h3>
				</div>
			</div></td>
	</tr>
	<tr>
		<td><? // if CAN or SCH select content
				require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/scheduling/SchedulingUpdateBody$status.php");
			?>
		</td>
	<tr>
	<tr>
		<td><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/formats/contactblock_scheduling.php'); ?>
		</td>
	</tr>
</table>
</body>
</html>
<?php
			if($status=='PEA')
				$historydata = "Print Pending Auth letter. Dr. $dr.";
			if($status=='PEN')
				$historydata = "Print Call Us letter. $patientaddress $patientcitystatezip.";
			if($status=='SCH')
				$historydata = "Print Scheduled letter. Dr $dr.";
			if($status=='ACT')
				$historydata = "Print Seen letter. Dr $dr.";
			if($status=='CAN')
				$historydata = "Print Cancelled letter. Dr $dr.";
			require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/scheduling/SQLUpdateFunctions.php');
			caseschedulinghistoryadd($crid, $historydata);
		}
		else 
			echo("Error: Fetch Failed. QUERY: $query");
	}
	else 
		echo("Error: Query Failed. QUERY: $query");
}
else 
	echo("Error: Case number not passed to program. CRID: $crid");
?>
