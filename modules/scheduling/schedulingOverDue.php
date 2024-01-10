<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

// select patients that are overdue in the scheduling queue
$select="SELECT crid, crlname, crfname, DATE_FORMAT(crinjurydate,'%m/%d/%Y'), DATE_FORMAT(crdate,'%m/%d/%Y'), DATE_FORMAT(c.crtdate,'%m/%d/%Y') created, NOW(), time_to_sec(timediff( NOW(), crdate ))/3600 age
FROM case_scheduling_queue csq LEFT JOIN cases c ON csqcrid=crid WHERE csqresult is NULL AND time_to_sec(timediff( NOW(), crdate ))/3600 > 48";
if($result=mysqli_query($dbhandle,$select)) {
?>
	<table>
<?php
	while($row=mysqli_fetch_assoc($result)) {
?>
		<tr>
			<td>
				<?php echo $row['crlname']; ?>
			</td>
			<td>
				<?php echo $row['crfname']; ?>
			</td>
			<td>
				<?php echo $row['crinjurydate']; ?>
			</td>
			<td>
				<?php echo $row['age']; ?>
			</td>
		</tr>
<?php
	}
?>
	</table>
<?php
}
?>