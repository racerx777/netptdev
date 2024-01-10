<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
echo('<div style="clear:both;">&nbsp;</div>');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query  = "
	SELECT * 
	FROM ptos_pnums 
	WHERE bnum='' or bnum IS NULL or cnum='' or cnum IS NULL or acctype='' or acctype IS NULL or lname='' or lname IS NULL or fname='' or fname IS NULL 
	";
if($result = mysqli_query($dbhandle,$query)) {
	$numRows = mysqli_num_rows($result);
	echo('<table border="1" cellpadding="3" cellspacing="0" width="100%">');
	while($row=mysqli_fetch_assoc($result)) {
		if(!isset($headerdone)) {
			echo "<tr>";
			foreach($row as $field=>$value)
				echo "<th>$field</th>";
			echo "<tr>";
			$headerdone=1;
		}
		echo "<tr>";
		foreach($row as $field=>$value)
			echo "<td>$value</td>";
		echo "<tr>";
	}
	echo("</table>");
}
else 
	error("001","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
?>