<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Confirm Add Therapist</title>
</head>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if(isset($_POST['add'])) {
	if(isset($_POST['ctcnum'])) {
		if(isset($_POST['ctttmcode'])) {
			if(isset($_POST['cttherap'])) {
				$insertmctt="INSERT INTO master_clinics_treatmenttypes (cttmcnum, cttmttmcode) VALUES('".$_POST['ctcnum']."','".$_POST['ctttmcode']."') ";
				if($result=mysqli_query($dbhandle,$insertmctt)) {
					notify("000","Therapy Type ".$_POST['ctttmcode']." inserted.");
					$insertmct="INSERT INTO master_clinics_therapists (ctcnum, ctttmcode, cttherap) VALUES('".$_POST['ctcnum']."','".$_POST['ctttmcode']."','".$_POST['cttherap']."') ";
					if($result=mysqli_query($dbhandle,$insertmct)) {
						notify("000","Therapist ".$_POST['cttherap']." inserted.");
					} // Insert
					else
						error("999","INSERT error".$insertmct);
				} // Insert
				else
					error("999","INSERT error".$insertmctt);
			} // Therapist
			else
				error("999","Therapist not specified.");
		} // Treatment Type
		else
			error("999","Treatment Type not specified.");
	} // Clinic
	else
		error("999","Clinic not specified.");
?>
<script>
window.opener.location.reload(true);
// Close this window
window.close();
</script>
<?php
}
?>
<body>
<p>Are you sure you want to add this therapist to this clinic?</p>
<form id="AddTherapist" name="AddTherapist" method="post" action="">
	Clinic: <?php echo $_REQUEST['cnum']; ?><br />
	Therapy Type: <?php echo $_REQUEST['therapytype']; ?><br />
	Therapist Code: <?php echo $_REQUEST['ttherap']; ?><br />
	Therapist Name: <?php echo $_REQUEST['tname']; ?><br />
	<input type="hidden" name="ctcnum" value="<?php echo $_REQUEST['cnum']; ?>" />
	<input type="hidden" name="ctttmcode" value="<?php echo $_REQUEST['therapytype']; ?>" />
	<input type="hidden" name="cttherap" value="<?php echo $_REQUEST['ttherap']; ?>" />

	<input type="button" name="Cancel" id="Cancel" value="Cancel" onclick="window.close()" />
	<input type="submit" name="add" id="add" value="Yes, Add this" />
</form>
</body>
</html>
