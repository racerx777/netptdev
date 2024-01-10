<?php
// Query
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
$q=$_GET["q"];
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "
SELECT dl.* 
FROM doctors d
LEFT JOIN doctor_relationships dr 
ON dmid=drdmid
LEFT JOIN doctor_locations dl 
ON dr.drdlid = dl.dlid 
WHERE dl.dlinactive='0' and dr.drdmid=". $q . "
ORDER BY dl.dlcity, dl.dlphone";
if($result = mysqli_query($dbhandle,$query)) {
	header('Content-Type: text/xml');
	header("Cache-Control: no-cache, must-revalidate");
	echo("<doctorlocations>");
	if(mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			echo("<location>");
				foreach($row as $field=>$value) {
					echo("<$field>");
					echo("$value");
					echo("</$field>");
				}
			echo("</location>");
//			if(!empty($row['dlcity']))
//				$city=$row['dlcity'];
//			else
//				$city="City not specified";
//			if(!empty($row['dlphone']))
//				$phone=$row['dlphone'];
//			else
//				$phone="Phone Number not specified";
//			$key = "$city - $phone";
//			$value=$row['dlid'];
//			echo "<option value='$value'>$key</option>";
		}
	}
	else {
		echo("");
	}
	echo("</doctorlocations>");
}
mysqli_close($dbhandle);
?> 