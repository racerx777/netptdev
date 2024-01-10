<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');

$dbhandle = dbconnect();

$select="
SELECT *
FROM cases
JOIN PTOS_Patients
ON crpnum=pnum
WHERE
	crpnum IS NOT NULL AND
	crpnum <> '' AND
	crcasestatuscode in ('SCH', 'ACT') AND
	crtprog <> '/conversion/04-5' AND
	(cricid1 IS NULL OR cricid1=0) AND (pinsurance IS NULL OR pinsurance=0) AND
	tbal>0 AND
	acctype <> '15' AND
	acctype not like '4%' AND
	acctype not like '5%' AND
	acctype not like '8%' AND
	acctype not like '9%'
ORDER BY crrefdmid, crrefdlid, crpaid, lname, fname
";
if($result = mysqli_query($dbhandle,$select)) {
	$numRows = mysqli_num_rows($result);
	if($numRows>0) {
// Load list of Doctors and locations
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
$doctors=getDoctorList();
$locations=getDoctorLocationList();
			echo '<h1>Work Comp Accounts with NetPT Insurance Issues</h1>';
			echo '<table border="1" cellpadding="3" cellspacing="0">';
		while($row = mysqli_fetch_assoc($result)) {
			if(!isset($saved)) {
// output header rows
				echo "<tr>";
//				echo '<th nowrap="nowrap">REF DOCTOR</th>';
				echo '<th colspan="2" nowrap="nowrap">REF DATE</th>';
				echo '<th nowrap="nowrap">RE/LOC</th>';
				echo '<th nowrap="nowrap">THERAPY</th>';
				echo '<th nowrap="nowrap">STATUS</th>';
				echo '<th nowrap="nowrap">CLINIC</th>';
				echo '<th nowrap="nowrap">FVISIT</th>';
				echo '<th nowrap="nowrap">PNUM</th>';
				echo '<th nowrap="nowrap">PATIENT</th>';
				echo '<th nowrap="nowrap">PHONE</th>';
				echo '<th nowrap="nowrap">EMPLOYER</th>';
				echo "</tr>";
			}
			if($saved != ($row['crrefdmid'].$row['crrefdlid'])) {
				$thisdoctor=$doctors[$row['crrefdmid']];
				$thislocation=$locations[$row['crrefdlid']];
				echo('<tr height="50px">
	<th valign="bottom" align="left" colspan="17">
'.$thisdoctor['dmlname'].', '.$thisdoctor['dmfname'].' in '.$thislocation['dlcity'].', Phone:'.displayPhone($thislocation['dlphone']).'
	</th>
</tr>');
				$saved=$row['crrefdmid'].$row['crrefdlid'];
			}

			if($row['crreadmit']=='1' || $row['crrelocate']=='1') {
				$relocate='YES';
			}
			else {
				$relocate='NO';
			}
			echo("<tr>");
// output detail rows
				echo '<td nowrap="nowrap"><input type="checkbox"></td>';
				echo '<td nowrap="nowrap">'.displayDate($row['crdate']).'</td>';
				echo '<td nowrap="nowrap">'.$relocate.'</td>';
				echo '<td nowrap="nowrap">'.$row['crtherapytypecode'].'</td>';
				echo '<td nowrap="nowrap">'.$row['crcasestatuscode'].'</td>';
				echo '<td nowrap="nowrap">'.$row['crcnum'].'</td>';
				echo '<td nowrap="nowrap">'.displayDate($row['fvisit']).'</td>';
				echo '<td nowrap="nowrap">'.$row['crpnum'].'</td>';
				echo '<td nowrap="nowrap">'.$row['crlname'].', '.$row['crfname'].'</td>';
				echo '<td nowrap="nowrap">'.trim(displayPhone($row['crphone1']).' '.displayPhone($row['crphone2']).' '.displayPhone($row['crphone3'])).'</td>';
				echo '<td nowrap="nowrap">'.$row['crempname'].displayPhone($row['crempphone']).'</td>';
//				foreach($row as $field=>$value)
//					echo '<td nowrap="nowrap">'.$value.'</td>';
			echo("</tr>");

		}
		echo("</table>");
	}
}
mysqli_close($dbhandle);
?>