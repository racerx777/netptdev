<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


// List accounts that I touched today
$thisuser=getuser();
$fromdate=date("Y-m-d", time()).' 00:00:00';
$thrudate=date("Y-m-d", time()).' 23:59:59';

if(isset($_REQUEST['user'])) 
	$thisuser=$_REQUEST['user'];

if(isset($_REQUEST['fromdate'])) 
	$fromdate=$_REQUEST['fromdate'];

if(isset($_REQUEST['thrudate'])) 
	$thrudate=$_REQUEST['thrudate'];

if($thisuser=='SunniSpoon') {
	$select="
		SELECT crtuser, nopnum, count(*) as count, max(crtdate) as crtdate
		FROM notes
		WHERE crtdate between '$fromdate' and '$thrudate'
		GROUP BY crtuser, nopnum
		ORDER BY crtuser, nopnum
	";
	$result=mysqli_query($dbhandle,$select);
	echo('<table border="1">');
	echo("<tr><th>User</th><th>#</th><th>Account</th><th>Date</th></tr>");
	while($row=mysqli_fetch_assoc($result)) {
		echo("<tr>");
		if($saved<>$row['crtuser']) {
			echo "<td>".$row['crtuser']."</td>";
			$saved=$row['crtuser'];
			$savedcount=0;
		}
		else
			echo "<td>&nbsp</td>";
		$savedcount++;
		echo("<td>$savedcount</td><td>".$row['nopnum']."</td><td>".$row['crtdate']."</td>");
		echo("</tr>");
	}
	echo("</table>");
}
else {
	echo("From: $fromdate Thru: $thrudate User: $thisuser<br>");
	$select="
		SELECT DISTINCT nopnum
		FROM notes
		WHERE crtuser='$thisuser' and crtdate between '$fromdate' and '$thrudate'
	";
	$result=mysqli_query($dbhandle,$select);
	while($row=mysqli_fetch_assoc($result)) {
		echo($row['nopnum']."<br>");
	}
	
	echo("End User: $thisuser<br>");
}
mysqli_close($dbhandle);
?>