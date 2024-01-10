<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>

<table>
	<tr>
		<td>Dear Treating Physician,<br />
			<p><?php echo $patientname; ?> has NOT been scheduled at the listed time for their physical therapy, acupuncture, or aquatic therapy evaluation and treatment due to:</p></td>
	</tr>
	<tr>
		<td><input type="checkbox" />
			The Patient did not show up for ___ scheduled initial evaluation appointments.<br />
			<input type="checkbox" />
			We have made ___ attempts to schedule this patient, but they have not responded. We have also sent a letter to the patient's address. There has been no response.<br />
			<input type="checkbox" />
			Patient chooses to attend therapy at another facility.<br />
			<input type="checkbox" />
			Patient states that our facilities are too far.<br />
			<input type="checkbox" />
			Patient does not have transportation to therapy, our company does not offer transportation.<br />
			<input type="checkbox" />
			Patient no longer feels therapy intervention is necessary.<br />
			<br />
			Additional information:<br />
			<textarea name="other" cols="65" rows="5"></textarea>
		</td>
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
</table>
