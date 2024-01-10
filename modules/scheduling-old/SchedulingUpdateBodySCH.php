<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>

<table>
<tr>
	<td>Dear Treating Physician,<br />
		<p><?php echo $patient?> has been scheduled on <?php echo $evaldate; ?> at <?php echo $clinicname; ?> for their physical therapy, acupuncture, or aquatic therapy evaluation and treatment.</p></td>
</tr>
<tr>
	<td align="center"><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/formats/patientinfo001.php'); ?>
	</td>
</tr>
<tr>
	<td align="center"><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/formats/referral.php'); ?>
	</td>
</tr>
<tr>
	<td align="center"><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/formats/doctor.php'); ?>
	</td>
</tr>
<table>
