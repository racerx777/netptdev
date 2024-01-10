<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if(isset($_POST['remove'])) {
	if(isset($_POST['ctcnum'])) {
		if(isset($_POST['ctttmcode'])) {
			if(isset($_POST['cttherap'])) {
				// Delete therapist
				$delete="DELETE FROM master_clinics_therapists WHERE ctcnum='".$_POST['ctcnum']."' and ctttmcode='".$_POST['ctttmcode']."' and cttherap='".$_POST['cttherap']."'";
				if($result=mysqli_query($dbhandle,$delete)) {
					notify("000","Therapist ".$_POST['cttherap']." deleted.");
				
					$select = "SELECT count(*) therapistcount FROM master_clinics_therapists WHERE ctcnum='".$_POST['ctcnum']."' and ctttmcode='".$_POST['ctttmcode']."'";
					if($result=mysqli_query($dbhandle,$select)) {
						$count=mysqli_fetch_assoc($result);
						if($count['therapistcount']==0) {
							$deletetreatmenttype = "DELETE FROM master_clinics_treatmenttypes WHERE cttmcnum ='".$_POST['ctcnum']."' and cttmttmcode='".$_POST['ctttmcode']."'";
							if($result=mysqli_query($dbhandle,$deletetreatmenttype)) {
								notify("000","Treatment type ".$_POST['ctttmcode']." deleted from clinic ".$_POST['ctcnum'].".");
							} // Delete Treatment Type
							else
								error("999","DELETE error.".$deletetreatmenttype);
						} // Therapist Count
						else
							notify("000","There are ".$count['therapistcount']." therapists performing ".$_POST['ctttmcode']." at ".$_POST['cttherap'].".");
					} // Select
					else
						error("999","SELECT error".$select);
				} // Delete
				else
					error("999","DELETE error".$delete);
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
<p>Are you sure you want to remove this therapist from this clinic?</p>
<form id="RemoveTherapist" name="RemoveTherapist" method="post" action="">
	Clinic: <?php echo $_REQUEST['cnum']; ?><br />
	Therapy Type: <?php echo $_REQUEST['therapytype']; ?><br />
	Therapist Code: <?php echo $_REQUEST['ttherap']; ?><br />
	Therapist Name: <?php echo $_REQUEST['tname']; ?><br />
	<input type="hidden" name="ctcnum" value="<?php echo $_REQUEST['cnum']; ?>" />
	<input type="hidden" name="ctttmcode" value="<?php echo $_REQUEST['therapytype']; ?>" />
	<input type="hidden" name="cttherap" value="<?php echo $_REQUEST['ttherap']; ?>" />

	<input type="button" name="Cancel" id="Cancel" value="Cancel" onclick="window.close()" />
	<input type="submit" name="remove" id="remove" value="Yes, Remove this" />
</form>
</body>
</html>
