<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');

$dbhandle = dbconnect();

$select="
SELECT source, crid, cnum, pnum, lname, fname, doi, dob
FROM (
	SELECT 'NETPT' as source, crid, crcnum as cnum, crpnum as pnum, crlname as lname, crfname as fname, crinjurydate as doi, crdob as dob, crcasestatuscode as status
	FROM cases
	LEFT JOIN PTOS_Patients
	ON crpnum=pnum
	WHERE
		crpnum IS NOT NULL AND crpnum <> '' AND
		pnum IS NULL and DATE_FORMAT(crptosupdated, '%Y-%m-%d') != CURDATE()
	UNION
	SELECT 'PTOS', ' ' as crid, cnum, pnum, lname, fname, injury, birth, acctype as status
	FROM PTOS_Patients
	WHERE fvisit >= '20100401' AND
	pnum NOT IN (
		SELECT crpnum
		FROM cases
		WHERE crcasestatuscode in ('SCH','ACT')
		)
	) as u
ORDER BY source, pnum, lname, fname, doi, dob
";
if($result = mysqli_query($dbhandle,$select)) {
	$numRows = mysqli_num_rows($result);
	if($numRows>0) {
			echo '<table border="1" cellpadding="3" cellspacing="0">';
		while($row = mysqli_fetch_assoc($result)) {
			if(!isset($saved)) {
// output header rows
				echo "<tr>";
//				echo '<th nowrap="nowrap">REF DOCTOR</th>';
				echo '<th nowrap="nowrap">SOURCE</th>';
				echo '<th nowrap="nowrap">CASE</th>';
				echo '<th nowrap="nowrap">CNUM</th>';
				echo '<th nowrap="nowrap">PNUM</th>';
				echo '<th nowrap="nowrap">LNAME</th>';
				echo '<th nowrap="nowrap">FNAME</th>';
				echo '<th nowrap="nowrap">DOI</th>';
				echo '<th nowrap="nowrap">DOB</th>';
				echo "</tr>";
			}
			if($saved != $row['source']) {
				echo('<tr height="50px">
	<th valign="bottom" align="left" colspan="17">
'.$row['source'].'
	</th>
</tr>');
				$saved=$row['source'];
			}

			echo("<tr>");
// output detail rows
				echo '<td nowrap="nowrap"><input type="checkbox"></td>';
				echo '<td nowrap="nowrap">'.$row['crid'].'</td>';
				echo '<td nowrap="nowrap">'.$row['cnum'].'</td>';
				echo '<td nowrap="nowrap">'.$row['pnum'].'</td>';
				echo '<td nowrap="nowrap">'.$row['lname'].'</td>';
				echo '<td nowrap="nowrap">'.$row['fname'].'</td>';
				echo '<td nowrap="nowrap">'.displayDate($row['doi']).'</td>';
				echo '<td nowrap="nowrap">'.displayDate($row['dob']).'</td>';
			echo("</tr>");

		}
		echo("</table>");
	}
}
mysqli_close($dbhandle);
?>