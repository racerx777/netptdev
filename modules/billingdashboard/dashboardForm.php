<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(22); 

// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

//
// MAIN LOOP
//
$usercliniclist = getUserClinicsList();

// Genesis sort vs default sort
if( array_key_exists('WS', $_SESSION['useraccess']['businessunits']))
$inbillingquery  = "
	SELECT DATE_FORMAT(thdate, '%Y-%m-%d') as groupvalue, DATE_FORMAT(thdate, '%Y-%M-%d') as grouptitle, s.* 
	FROM (
		SELECT thcnum, thctmcode, thsbmdate, upddate, th.thid, 1 as seq, thdate, thpnum, thlname, thfname, billcode, billdescription 
		FROM treatment_header th
		JOIN (
			SELECT thid, pmbillcode as billcode, pmdescription as billdescription 
			FROM treatment_procedures tp
				LEFT JOIN master_procedures mp
				on tp.pmcode=mp.pmcode
			) as j1
		ON th.thid=j1.thid
		WHERE thcnum IN " . $usercliniclist . " and thsbmstatus between '500' and '599'
UNION
		SELECT thcnum, thctmcode, thsbmdate, upddate, th.thid, 2 as seq, thdate, thpnum, thlname, thfname, billcode, billdescription 
		FROM treatment_header th
		JOIN (
			SELECT thid, mmbillcode as billcode, mmdescription as billdescription  
			FROM treatment_modalities tm
				LEFT JOIN master_modalities mm
				on tm.mmcode=mm.mmcode
			) as j2
		ON th.thid=j2.thid
		WHERE thcnum in " . $usercliniclist . " and thsbmstatus between '500' and '599'
	) as s 
	ORDER BY thdate, thcnum, thpnum, thlname, thfname, thid, seq, billcode";

else {
$inbillingquery  = "
	SELECT DATE_FORMAT(thdate,'%Y-%m') as groupvalue, MONTHNAME(thdate) as grouptitle, s.* 
	FROM (
		SELECT thcnum, thctmcode, thsbmdate, upddate, th.thid, 1 as seq, thdate, thpnum, thlname, thfname, billcode, billdescription 
		FROM treatment_header th
		JOIN (
			SELECT thid, pmbillcode as billcode, pmdescription as billdescription 
			FROM treatment_procedures tp
				LEFT JOIN master_procedures mp
				on tp.pmcode=mp.pmcode
			) as j1
		ON th.thid=j1.thid
		WHERE thcnum IN " . $usercliniclist . " and thsbmstatus between '500' and '599'
UNION
		SELECT thcnum, thctmcode, thsbmdate, upddate, th.thid, 2 as seq, thdate, thpnum, thlname, thfname, billcode, billdescription 
		FROM treatment_header th
		JOIN (
			SELECT thid, mmbillcode as billcode, mmdescription as billdescription  
			FROM treatment_modalities tm
				LEFT JOIN master_modalities mm
				on tm.mmcode=mm.mmcode
			) as j2
		ON th.thid=j2.thid
		WHERE thcnum in " . $usercliniclist . " and thsbmstatus between '500' and '599'
	) as s 
	ORDER BY YEAR(thdate), MONTH(thdate), thpnum, thcnum, thdate, thlname, thfname, thid, seq, billcode";
$inbillingquery  = "
SELECT DATE_FORMAT(thdate,'%Y-%m') as groupvalue, MONTHNAME(thdate) as grouptitle, thcnum, thctmcode, thsbmdate, th.upddate, th.thid, 1 as seq, thdate, thpnum, thlname, thfname, pmbillcode as billcode, pmdescription as billdescription
FROM treatment_header th
	JOIN treatment_procedures tp
	ON th.thid = tp.thid
		LEFT JOIN master_procedures mp
		ON tp.pmcode=mp.pmcode
WHERE thcnum IN " . $usercliniclist . " and thsbmstatus between '500' and '599'
UNION
SELECT DATE_FORMAT(thdate,'%Y-%m') as groupvalue, MONTHNAME(thdate) as grouptitle, thcnum, thctmcode, thsbmdate, th.upddate, th.thid, 2 as seq, thdate, thpnum, thlname, thfname, mmbillcode as billcode, mmdescription as billdescription 
FROM treatment_header th
	JOIN treatment_modalities tm
	ON th.thid = tm.thid
		LEFT JOIN master_modalities mm
		ON tm.mmcode=mm.mmcode
WHERE thcnum IN " . $usercliniclist . " and thsbmstatus between '500' and '599'
ORDER BY YEAR(thdate), MONTH(thdate), thpnum, thcnum, thdate, thlname, thfname, thid, seq, billcode
";
}
$inbillingresult = mysqli_query($dbhandle,$inbillingquery);
if(!$inbillingresult) {
		error("002","MySql[inbillingresult]: $inbillingquery " . mysqli_error($dbhandle));	
		$numRows = 0;
}
else 
	$numRows = mysqli_num_rows($inbillingresult);
?>
<div id="billingdashboard" class="containedBox">
	<fieldset>
	<legend style="font-size:large">Billing Dashboard</legend>
	<?php
if($numRows>0) {
?>
	<div class="containedBox">
		<form method="post" name="searchlist">
			<table border="1" cellpadding="3" cellspacing="0" width="100%">
				<tr style="color:#FFFFFF; background-color:#4682B4;">
					<th>Clinic</th>
					<th>Treatment Date</th>
					<th>Patient Number</th>
					<th>Billing Code</th>
					<th>Description</th>
					<th>Patient Last Name</th>
					<th>Patient First Name</th>
					<th>&nbsp;</th>
				</tr>
				<?php
	$nowdate = date('Y/m/d', time());
	while($row = mysqli_fetch_assoc($inbillingresult)) {
		if($row["groupvalue"] != $lastgroupvalue) {
// 306aa3
// 5985b5
// c0cde2
			echo('<tr><td colspan="8" style="font-size:larger; font-weight:bold; background-color:#306aa3; color:#FFFFFF">' . $row["grouptitle"] . '</td></tr>');
			$lastgroupvalue = $row["groupvalue"];
		}
		if($row["thid"] != $lastrow) {
			$functions = '<div id="functions">
			<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />
			<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />
			<br>Submitted&nbsp;on&nbsp;'. date('m/d/Y', strtotime($row['thsbmdate'])) . '</div>';
			$lastrow = $row["thid"];
		}
		else
			$functions = "&nbsp;";
// Case Type 8 Private, 61 DOL
		if(($row['thctmcode'] == '8' || $row['thctmcode'] == '61') && (!empty($row['billcode'])))
			$bcode='B';
		else
			unset($bcode);
?>
				<tr>
					<td><?php echo($row["thcnum"]); ?>&nbsp;</td>
					<td><?php echo date("m/d/Y", strtotime($row["thdate"])); ?>&nbsp;</td>
					<td><?php echo($row["thpnum"]); ?>&nbsp;</td>
					<td><?php echo($bcode . $row["billcode"]); ?>&nbsp;</td>
					<td><?php echo($row["billdescription"]); ?>&nbsp;</td>
					<td><?php echo($row["thlname"]); ?>&nbsp;</td>
					<td><?php echo($row["thfname"]); ?>&nbsp;</td>
					<td><?php echo '<input id="dummycheckbox" type="checkbox" title="Entered Into Billing System">'; echo($functions); ?> 
					</td>
				</tr>
				<?php
	}
?>
			</table>
		</form>
		<div class="donotprintthis" style="float:right">
			<input name="print" type="button" value="Print" onclick="window.print();">
		</div>
		<?php
}
else {
	echo('No treatments found.');
}
mysqli_close($dbhandle);
?>
	</div>
	</fieldset>
</div>