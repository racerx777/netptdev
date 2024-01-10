<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<table>
<tr>
	<td>Dear Treating Physician,<br />
		<p>The prescription for <?php echo $patientname; ?> is currently pending authorization for their <?php echo $therapytype; ?> evaluation and treatment.</p></td>
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