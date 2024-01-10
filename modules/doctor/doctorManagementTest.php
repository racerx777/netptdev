<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15);
$crid='35542';
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$selectquery="SELECT * FROM cases JOIN doctor_relationships ON crrefdmid=drdmid and crrefdlid=drdlid LEFT JOIN doctor_locations_contacts ON drdlsid=dlsid WHERE crid='$crid'";
//echo "$selectquery<br>";
if($selectresult=mysqli_query($dbhandle,$selectquery)) {
	if($selectrow=mysqli_fetch_assoc($selectresult)) {
		echo "case doctor, location, contact:<br>";
		echo $selectrow['crrefdmid']."<br>";
		echo $selectrow['crrefdlid']."<br>";
		echo $selectrow['drdlsid']."<br>";
	}
	else
		echo "No fetch";
}
else
	echo "No select";
?>
<form>
<input id="button" name="button" type="button" value="Click" onclick="window.open('/modules/doctor/doctorManagement.php?crid=<?php echo $crid; ?>','DoctorManagement','width=window.screen.availWidth,scrollbars=yes');">
</form>
<?php
displaysitemessages();
?>