<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>New Clinic Setup Request Form</title>
</head>
<body>
<?php
function validate() {
	$oops=array();
	if(empty($_POST['providergroup']))
		$oops['providergroup'] = 'OOPS! You must specify a provider group.';
	if(empty($_POST['cnum']))
		$oops['cnum'] = 'OOPS! You must specify a PTOS clinic number.';
	if(empty($_POST['name']))
		$oops['name'] = 'OOPS! You must enter the name/description of the clinic.';
	if(empty($_POST['email']))
		$oops['email'] = 'OOPS! You must enter an e-mail address for the clinic.';
	if(empty($_POST['phone']))
		$oops['phone'] = 'OOPS! You must enter the phone number for the clinic.';
	if(empty($_POST['fax']))
		$oops['fax'] = 'OOPS! You must enter the fax number for the clinic.';
	return($oops);
}

function sendrequest() {
	$oops=array();
	if(strtolower($_SERVER['SERVER_NAME'])=='netpt.wsptn.com')
		$to = 'NetPT Info <netpt.errors@freshims.com>';
	$from = 'JB Adam <jba@apmi.net>';
	$subject = 'NetPT Website Request';
	$headers = 'From: ' . $from . '
X-Mailer: PHP/' . phpversion();
	$data = "Please setup the following clinic:\n";
	foreach($_POST as $field=>$value)
		$data .= substr($field,0,1) . ":\t\t$value\n";
	$message = wordwrap($data, 70);
	if(!mail($to, $subject, $message, $headers))
		$oops['mail']='There was an error when trying to send data. [' . $data . ']';
	return($oops);
}

// Submitting Form
if(isset($_POST['submit'])) {
	if($_POST['submit']=='Send Request') {
		unset($_POST['submit']);
// Validate
		$errors=validate();
		if(count($errors) > 0) {
// Display Error Messages
			echo "<h1>There was an error validating the information (error count = " . count($errors) . ")</h1>";
			foreach($errors as $field=>$message)
				echo("$message ($field)<br>");
		}
		else {
// Send Message
			$senderrors=sendrequest();
			if(count($senderrors) > 0) {
// Display Error Sending Messages
			echo "There was an error sending the information<br>";
			foreach($senderrors as $field=>$message)
				echo("$field : $message");
			}
			else {
// Display Confirmation Message
				echo 'Request sent successfully.<br>';
				foreach($_POST as $field=>$value)
					unset($_POST["$field"]);
			}
		}
	}
}

?>
<br />
<form action="" method="post" name="NewClinicSetupRequest">
	<fieldset>
	<legend>New Clinic Setup Request Form</legend>
	<table border="1" cellspacing="3" cellpadding="3" summary="New Clinic Setup Request Form">
		<caption>
		Request Form
		</caption>
		<tr>
			<th colspan="2" scope="row">Clinic Information</th>
		</tr>
		<tr>
			<th scope="row">Business Unit</th>
			<td><select name="bu" id="bu">
					<option <?php if($_POST['bu']=='WestStar') echo 'selected';?>>WestStar</option>
					<option <?php if($_POST['bu']=='Network') echo 'selected';?>>Network</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">Provider Group</th>
			<td><input name="providergroup" type="text" size="20" maxlength="200" value="<?php if(!empty($_POST['providergroup'])) echo $_POST['providergroup']; else echo '*ALL'; ?>" /></td>
		</tr>
		<tr>
			<th scope="row">PTOS Clinic Number</th>
			<td><input name="cnum" type="text" size="3" maxlength="4" value="<?php if(!empty($_POST['cnum'])) echo $_POST['cnum']; else echo '*ALL'; ?>" /></td>
		</tr>
		<tr>
			<th scope="row">Description/Name</th>
			<td><input name="name" type="text" size="50" maxlength="50" value="<?php if(!empty($_POST['name'])) echo $_POST['name'];?>" /></td>
		</tr>
		<tr>
			<th scope="row">e-mail address</th>
			<td><input name="email" type="text" size="50" maxlength="64" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>" /></td>
		</tr>
		<tr>
			<th scope="row">Phone Number</th>
			<td><input name="phone" type="text" size="20" maxlength="20" value="<?php if(!empty($_POST['phone'])) echo $_POST['phone'];?>" /></td>
		</tr>
		<tr>
			<th scope="row">Fax Number</th>
			<td><input name="fax" type="text" size="20" maxlength="20" value="<?php if(!empty($_POST['fax'])) echo $_POST['fax'];?>" /></td>
		</tr>
		<tr>
			<th colspan="2" scope="row"><input name="submit" type="submit" value="Send Request" /></th>
		</tr>
	</table>
	</fieldset>
</form>
<br />
<a href="./newusersetuprequest.php">Click Here</a> to request setup of a new user.
</body>
</html>