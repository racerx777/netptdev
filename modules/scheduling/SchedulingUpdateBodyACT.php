<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<table>
<tr>
	<td>Dear Treating Physician,<br />
		<p><?php echo $patientname; ?> was initially seen on <?php echo $evaldate; ?> at <?php echo $clinicname; ?> for their <?php echo $therapytype; ?> evaluation and treatment.</p></td>
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