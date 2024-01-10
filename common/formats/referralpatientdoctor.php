<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 

$seerx = 'See Rx';

if(!empty($_POST['crdate']))
	$referraldate = displayDate($_POST['crdate']);
else
	$referraldate = $seerx;

if(!empty($_POST['cmname']))
	$clinicname = strtoupper($_POST['cmname']);
else
	$clinicname = $seerx;

if(!empty($_POST['crapptdate'])) 	
	$evaldate = displayDate($_POST['crapptdate']) . " at " . displayTime($_POST['crapptdate']);
else
	$evaldate = $seerx;

if(!empty($_POST['crdx'])) 	
	$dx = $_POST['crdx'];
else
	$dx = $seerx;

if(!empty($_POST['crfreqduration'])) 	
	$freqanddur = $_POST['crfreqduration'];
else
	$freqanddur = $seerx;

if(!empty($_POST['crtherapytypecode'])) 	
	$therapytype = $_POST['crtherapytypecode'];
else
	$therapytype = $seerx;

if(!empty($_POST['crphone1'])) 	
	$phone = displayPhone($_POST['crphone1']);
else
	$phone = $seerx;

if(!empty($_POST['crphone2'])) 	
	$altphone = displayPhone($_POST['crphone2']);
else
	$altphone = $seerx;

if(!empty($_POST['crdob'])) 	
	$dob = displayDate($_POST['crdob']);
else
	$dob = $seerx;

if(!empty($_POST['crinjurydate'])) 	
	$doi = displayDate($_POST['crinjurydate']);
else
	$doi = $seerx;

if(!empty($_POST['crfname']))
	$patient = $_POST['crlname'] . ", " . $_POST['crfname'];
else {
	if(!empty($_POST['crlname']))
		$patient = $_POST['crlname'];
	else
		$patient = $seerx;
}
if(!empty($_POST['dmfname']))
	$dr = $_POST['dmlname'] . ", " . $_POST['dmfname'];
else {
	if(!empty($_POST['dmlname']))
		$dr = $_POST['dmlname'];
	else
		$dr = $seerx;
}

if(!empty($_POST['dmphone'])) 	
	$drphone = displayPhone($_POST['dmphone']);
else
	$drphone = $seerx;

if(!empty($_POST['dmfax'])) 	
	$drfax = displayPhone($_POST['dmfax']);
else
	$drfax = $seerx;
?>
<table width="576px">
	<tr>
		<td><div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="4">Referral Information</th>
					<tr>
						<td>Referral Date</td>
						<td colspan="3"><?php echo $referraldate; ?></td>
					</tr>
					<?php if($_POST['crcasestatuscode']=='SCH' || $_POST['crcasestatuscode']=='ACT') { ?>
					<tr>
						<td>To Clinic:</td>
						<td><?php echo $clinicname; ?></td>
						<td>Eval Date:</td>
						<td><?php echo $evaldate; ?></td>
					</tr>
					<?php }
						echo("<th>STATUS: " . $_POST['crcasestatuscode'] . "</th>");
					?>
					<tr>
						<td>Therapy:</td>
						<td><?php echo $therapytype; ?>&nbsp;</td>
						<td>ReAd/Relo:</td>
						<td><?php if($_POST['crreadmit']==1) echo 'Y'; else echo 'N'; ?></td>
					</tr>
				</table>
			</div></td>
	</tr>
	<tr>
		<td><div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="4">Patient Information</th>
					<tr>
						<td>Patient Name:</td>
						<td colspan="3"><?php echo $patient; ?></td>
					</tr>
					<tr>
						<td>Patient Ph#:</td>
						<td><?php echo $phone; ?></td>
						<td>Alt. Ph#:</td>
						<td><?php echo $altphone; ?></td>
					</tr>
					<tr>
						<td>DOB:</td>
						<td><?php echo $dob; ?></td>
						<td>DOI:</td>
						<td><?php echo $doi; ?></td>
					</tr>
				</table>
			</div></td>
	</tr>
	<tr>
		<td><div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="5">Doctor Information</th>
					<tr>
						<td>Doctor Name:</td>
						<td colspan="3"><?php echo $dr; ?></td>
					</tr>
					<tr>
						<td>Doctor Phone#:</td>
						<td><?php echo $drphone; ?></td>
						<td>Fax#:</td>
						<td><?php echo $drfax; ?></td>
					</tr>
				</table>
			</div></td>
	</tr>
</table>
