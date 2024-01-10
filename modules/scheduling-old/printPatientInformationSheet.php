<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
function getcasetypes() {
	if(!isset($_SESSION['casetypes']) || (isset($_SESSION['casetypes']) && (count($_SESSION['casetypes'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT ctmcode, ctmdescription FROM master_casetypes ";
		if(!userisadmin())
			$query .= "WHERE ctminactive = 0 ";
		$query .= "ORDER BY ctmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);

		$casetypesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$casetypesarray[$result['ctmcode']] = $result['ctmdescription'];
		}
		return($casetypesarray);
	}
	else
		return($_SESSION['casetypes']);
}

$casetypes = getcasetypes();

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
	join master_clinics 
	on crcnum=cmcnum
	join patients
	on crpaid=paid
	left join doctor_relationships
	on crrefdmid = drdmid and crrefdlid=drdlid
	left join doctors
	on drdmid=dmid
	left join doctor_locations
	on drdlid=dlid
	left join doctor_locations_contacts
	on drdlsid=dlsid
	where crid='$crid'";
	$query = "
	select * 
	from cases 
	join master_clinics 
	on crcnum=cmcnum
	join patients
	on crpaid=paid
	left join doctors
	on crrefdmid=dmid
	left join doctor_locations
	on crrefdlid=dlid
	left join doctor_locations_contacts
	on crrefdlsid=dlsid
	where crid='$crid'";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			foreach($row as $field=>$value) {
				$_POST["$field"]=$value;
			}
		$icd9array=icd9CodeOptions();
		$treatmentdxarray=array();
		if(!empty($row['crdx1']))
			$treatmentdxarray[]=$icd9array[$row['crdx1']]['description'] . "(".$row['crdx1'].")";
		if(!empty($row['crdx2']))
			$treatmentdxarray[]=$icd9array[$row['crdx2']]['description'] . "(".$row['crdx2'].")";
		if(!empty($row['crdx3']))
			$treatmentdxarray[]=$icd9array[$row['crdx3']]['description'] . "(".$row['crdx3'].")";
		if(!empty($row['crdx4']))
			$treatmentdxarray[]=$icd9array[$row['crdx4']]['description'] . "(".$row['crdx4'].")";
		if(count($treatmentdxarray)>0) {
			$treatmentdx=implode("<br>", $treatmentdxarray);
		}
		else
			$treatmentdx="See Prescription";
		if(!empty($row['crfrequency']) && !empty($row['crduration']))
			$treatmentduration=$row['crfrequency']."x".$row['crduration'];
		else {
			if(!empty($row['crtotalvisits']) )
				$treatmentduration=$row['crtotalvisits'] . " visits";
			else
				$treatmentduration="See Prescription";
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Patient Information Cover Sheet : 
<?php 

echo $_SESSION['user']['umuser'];

?>
	
</title>
</head>
<script type="text/javascript">
 window.print();
 setTimeout(window.close, 1000);
</script>
<!--window.print();window.close();-->
<body onload="">
<table border="0" cellspacing="0" cellpadding="10" width="720px" >
	<tr>
		<td align="center"><img src="/img/wsptn logo bw outline.jpg" width="500px"> </td>
	<tr>
		<td><h1 align="center">Patient Information Sheet</h1></td>
	<tr>
		<td><div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="4">Appointment Information</th>
					<tr>
						<td width="190px">Clinic Referred to:</td>
						<td width="190px"><?php echo $_POST['cmname']; ?></td>
						<td width="190px">Referral Date</td>
						<td width="190px"><?php echo displayDate($_POST['crdate']); ?></td>
					</tr>
					<?php if($_POST['crcasestatuscode']=='SCH' || $_POST['crcasestatuscode']=='ACT') { ?>
					<tr>
						<td>Appointment Date:</td>
						<td colspan="3">
						<?php echo displayDate($_POST['crapptdate']); ?></td>
					</tr>
					<tr>
						<td>Appointment Time:</td>
						<td colspan="3"><?php echo displayTime($_POST['crapptdate']); ?></td>
					</tr>
					<?php }
						echo("<th>STATUS: " . $_POST['crcasestatuscode'] . "</th>");
					?>
					<tr>
					<td colspan="4" height="30px"><span style="font-size:small; color:#BBBBBB">(<?php echo $_POST['crid']; ?>)</span>
					</td>
					</tr>
				</table>
			</div></td>
	</tr>
	<tr>
		<td><div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="2">Patient Information</th>
					<tr>
						<td width="190px">Last Name:</td>
						<td><?php echo $_POST['palname']; ?></td>
					</tr>
					<tr>
						<td>First Name:</td>
						<td><?php echo $_POST['pafname']; ?></td>
					</tr>
					<tr>
						<td>Patient Ph#:</td>
						<td><?php echo displayPhone($_POST['paphone1']); ?></td>
					</tr>
					<tr>
						<td>DOB:</td>
						<td><?php echo displayDate($_POST['padob']); ?></td>
					</tr>
					<tr>
						<td>Date Of Injury:</td>
						<td><?php echo displayDate($_POST['crinjurydate']); ?></td>
					</tr>
					<tr>
						<td colspan="4" height="30px"><span style="font-size:small; color:#BBBBBB">(<?php echo $_POST['crpaid']; ?>)</span></td>
					</tr>
				</table>
			</div></td>
	</tr>
	<tr>
		<td><div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="5">Doctor Information</th>
					<tr>
						<td width="190px">Doctor:</td>
						<td colspan="3"><?php echo $_POST['dmlname'] . ", " . $_POST['dmfname']; ?></td>
					</tr>
					<tr>
						<td width="190px">Doctor Phone#:</td>
						<td width="190px"><?php echo displayPhone($_POST['dlphone']); ?></td>
						<td width="190px">Doctor Fax#:</td>
						<td width="190px"><?php echo displayPhone($_POST['dlfax']); ?></td>
					</tr>
					<tr>
						<td width="190px">Referral Phone#:</td>
						<td width="190px"><?php echo displayPhone($_POST['dlsphone']); ?></td>
						<td width="190px">Referral Fax#:</td>
						<td width="190px"><?php echo displayPhone($_POST['dlsfax']); ?></td>
					</tr>
					<tr>
						<td>Diagnosis:</td>
						<td><?php echo $treatmentdx; ?>&nbsp;</td>
						<td>Freq/Duration:</td>
						<td><?php echo $treatmentduration; ?>&nbsp;</td>
					</tr>
					<tr>
						<td>Type of Case</td>
						<td><?php echo($casetypes[$_POST['crcasetypecode']]); ?></td>
						<td>Type of Therapy</td>
						<td><?php echo $_POST['crtherapytypecode']; ?>&nbsp;</td>
					</tr>
					<tr>
						<td>Readmit</td>
						<td colspan="3"><?php if($_POST['crreadmit']==1) echo 'Y'; else echo 'N'; ?></td>
					</tr>
					<tr>
						<td>Relocate</td>
						<td colspan="3"><?php if($_POST['crrelocate']==1) echo 'Y'; else echo 'N'; ?></td>
					</tr>
					<tr>
						<td colspan="4" height="30px"><span style="font-size:small; color:#BBBBBB">(<?php echo $_POST['crrefdmid'] ."/" . $_POST['crrefdlid']; ?>)</span></td>
					</tr>
				</table>
			</div></td>
	</tr>
</table>
</body>
</html>
<?php
		}
		else 
			echo("1");
	}
	else 
		echo("2");
}
?>
