<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$_SESSION['SERVER_NAME']='netpt.wsptn.com';
$dbhandle = dbconnect();


if(isset($_REQUEST['SESSIONID'])) {
	$sessionid=mysqli_real_escape_string($dbhandle,$_REQUEST['SESSIONID']);
	$select = "SELECT * FROM D2M_interface WHERE diSESSIONID='$sessionid'";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) {
			if($row['diappname']=='reportmanager') {
				$id=$row['diappdocid'];
				$archdate=$row['crtdate'];
				$archstatus=$row['diarchive_status'];
				$update="UPDATE report_header SET rharchdate='$archdate', rharchstatus='$archstatus' WHERE rhid='$id'";
				if($updateresult=mysqli_query($dbhandle,$update))
					echo "Updated Status for id=$id";
			}
		}
	}
}
?>