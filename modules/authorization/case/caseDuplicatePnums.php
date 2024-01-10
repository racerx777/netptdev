<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(21); 
errorclear();
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$pnumquery="
	SELECT c.crpnum, p.palname, p.pafname, count(*)
	FROM cases c
	JOIN
	(
	
		SELECT crpnum, count(*) from cases 
		WHERE crpnum IS NOT NULL and crpnum <> ''
		GROUP BY crpnum
		HAVING count(*) > 1
	
	) as a
	ON c.crpnum=a.crpnum
	LEFT JOIN patients p
	ON c.crpaid=paid
	GROUP BY c.crpnum, p.palname, p.pafname
	HAVING count(*)<2
	ORDER BY c.crpnum, p.palname, p.pafname
";
//dump("pnumquery",$pnumquery);
if($pnumresult = mysqli_query($dbhandle,$pnumquery)) {
	$numrows=mysqli_num_rows($pnumresult);
	notify("000","$numrows records found.");
	displaysitemessages();
	if(!empty($numrows)) {
		echo('<table cellpadding="3" cellspacing="0" style="border: 2px solid rgb(0,0,0);">');
		echo("<tr>");
		echo("<th>Row #</th>");
		echo("<th>Patient Number</th>");
		echo("<th>Last Name</th>");
		echo("<th>First Name</th>");
		echo("</tr>
		");
		while($pnumrow = mysqli_fetch_assoc($pnumresult)) {
			$rownum++;
			$pnum=$pnumrow['crpnum'];
			$lname=$pnumrow['palname'];
			$fname=$pnumrow['pafname'];
			if($savedpnum != $pnum) {
				$savedpnum = $pnum;
				if($rowcolor == "#FFFFFF") 
					$rowcolor = "#CCCCCC";
				else 
					$rowcolor = "#FFFFFF";
			}
			echo('<tr style="background-color:' . $rowcolor . '">
			');
			echo("<td>$rownum</td>");
			echo("<td>$pnum</td>");
			echo("<td>$lname</td>");
			echo("<td>$fname</td>
		");
			echo("</tr>
			");
		}
		echo("</table>");
	}
}
else
	error("999","SELECT Error<br>$query<br>".mysqli_error($dbhandle));
mysqli_close($dbhandle);
?>