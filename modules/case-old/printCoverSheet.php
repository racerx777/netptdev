<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Patient Information Cover Sheet</title>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="13" width="760px" >
	<tr>
		<td align="center"><img src="/img/wsptn logo bw outline.jpg" width="500px"> </td>
	<tr>
		<td><h1 align="center">New Patient Information</h1></td>
	<tr>
		<td><table border="1" cellspacing="1" cellpadding="3" width="100%">
				<th style="font-size:14pt" align="left" colspan="4">Appointment Information</th>
				<tr>
					<td width="190px">Clinic Referred to:</td>
					<td width="190px"><?php echo $_POST['cmname']; ?></td>
					<td width="190px">Referral Date</td>
					<td width="190px"><?php echo $_POST['crdate']; ?></td>
				</tr>
				<tr>
					<td>Appointment Date:</td>
					<td colspan="3"><?php echo $_POST['crapptdate']; ?></td>
				</tr>
				<tr>
					<td>Appointment Time:</td>
					<td colspan="3"><?php echo $_POST['crapptdate']; ?></td>
				</tr>
			</table></td>
	</tr>
	<tr>
		<td><table border="1" cellspacing="1" cellpadding="3" width="100%">
				<th style="font-size:14pt" align="left" colspan="2">Patient Information</th>
				<tr>
					<td width="190px">Last Name:</td>
					<td><?php echo $_POST['crlname']; ?></td>
				</tr>
				<tr>
					<td>First Name:</td>
					<td><?php echo $_POST['crfname']; ?></td>
				</tr>
				<tr>
					<td>Patient Ph#:</td>
					<td><?php echo $_POST['crphone1']; ?></td>
				</tr>
				<tr>
					<td>DOB:</td>
					<td><?php echo $_POST['crdob']; ?></td>
				</tr>
				<tr>
					<td>Date Of Injury:</td>
					<td><?php echo $_POST['crdoi']; ?></td>
				</tr>
				<tr>
					<td colspan="4" height="30px"></td>
				</tr>
			</table></td>
	</tr>
	<tr>
		<td><table border="1" cellspacing="1" cellpadding="3" width="100%">
				<th style="font-size:14pt" align="left" colspan="5">Doctor Information</th>
				<tr>
					<td width="190px">Doctor:</td>
					<td colspan="3"><?php echo $_POST['dmlname'] . ", " . $_POST['dmfname']; ?></td>
				</tr>
				<tr>
					<td width="190px">Doctor Phone#:</td>
					<td width="190px"><?php echo $_POST['dmphone']; ?></td>
					<td width="190px">Doctor Fax#:</td>
					<td width="190px"><?php echo $_POST['dmfax']; ?></td>
				</tr>
				<tr>
					<td>Diagnosis:</td>
					<td><?php echo $_POST['crdx']; ?></td>
					<td>Freq/Duration:</td>
					<td><?php echo $_POST['crfreqduration']; ?></td>
				</tr>
				<tr>
					<td>Type of Case</td>
					<td><?php echo $_SESSION['casetypes'][$_POST['crcasetypecode']; ?></td>
					<td>Type of Therapy</td>
					<td><?php echo $_POST['crtherapytype']; ?></td>
				</tr>
				<tr>
					<td>Readmit/Relocated</td>
					<td colspan="3"><?php echo $_POST['crreadmit']; ?></td>
				</tr>
				<tr>
					<td colspan="4" height="30px"></td>
				</tr>
			</table></td>
	</tr>
</table>
</body>
</html>
