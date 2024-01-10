<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
if(!empty($_POST['crfname']))
	$patient = $_POST['crlname'] . ", " . $_POST['crfname'];
else {
	if(!empty($_POST['crlname']))
		$patient = $_POST['crlname'];
	else
		$patient = $default;
}
if(!empty($_POST['crphone1'])) 	
	$phone = displayPhone($_POST['crphone1']);
else
	$phone = $default;
if(!empty($_POST['crphone2'])) 	
	$altphone = displayPhone($_POST['crphone2']);
else
	$altphone = $default;

if(!empty($_POST['crdob'])) 	
	$dob = displayDate($_POST['crdob']);
else
	$dob = $default;
if(!empty($_POST['crinjurydate'])) 	
	$doi = displayDate($_POST['crinjurydate']);
else
	$doi = $default;
?>
<div style="border:double; border-bottom-color:#000000">
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
</div>