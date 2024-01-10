<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
if($_POST['sex'] == 'M')
	$greeting = 'Mr. ' . properCase($_POST['palname']);
else
	if($_POST['sex'] == 'F')
		$greeting = 'Ms. ' . properCase($_POST['palname']);	
	else
		$greeting = 'Mr./Ms. ' . properCase($_POST['palname']);
?>

<div style=" margin-left:72px; font:Verdana, Arial, Helvetica, sans-serif; font-style:italic; font-size:20px;">
	<div style="float:right">
	<table>
		<tr>
			<td align="right"><?php echo properCase($_POST['pafname'] . " " . $_POST['palname']); ?> </td>
		</tr>
		<tr>
			<td align="right"><?php echo properCase($_POST['paaddress1']); ?> </td>
		</tr>
		<tr>
			<td align="right"><?php echo properCase($_POST['paaddress2']); ?> </td>
		</tr>
		<tr>
			<td align="right"><?php echo properCase($_POST['pacity'] . ", ") . strtoupper($_POST['pastate']) . " " . displayZip($_POST['pazip']); ?> </td>
		</tr>
	</table>
	</div>
	<div style="clear: both"></div>
	</p>
	<p>&nbsp;</p>
	<p><? echo date('F d, Y', time())?></p>
	<p>Dear <?php echo $greeting?>,</p>
	<p>Please call our office to setup an appointment for your physical therapy, occupational therapy, aquatic therapy, or acupuncture as prescribed by your doctor.</p>
	<p>&nbsp;</p>
	<p>Favor de llamar a nuestra officina para hacerle una cita para su terapia fisica, terapia occupacional, terapia aquatica, o acupuntura que su doctor ordeno.</p>
	<p>&nbsp;</p>
	<p>Thank You,</p>
	<p>&nbsp;</p>
	<p><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/formats/contactblock_scheduling.php'); ?></p>
</div>