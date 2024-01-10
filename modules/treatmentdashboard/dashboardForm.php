<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(20); 

// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$duplicatequery  = "
SELECT d.rowid, d.duplicatecount, a.* from treatment_header a JOIN 
(
	SELECT thcnum,thdate,thpnum,thlname,thfname, _rowid as rowid, count(*) as duplicatecount 
	FROM treatment_header b
	WHERE thsbmstatus > '0' and thsbmstatus < '900'
	GROUP BY thcnum,thdate,thpnum,thlname,thfname
	HAVING count(*) > 1
) as d
ON a.thcnum=d.thcnum and a.thdate=d.thdate and a.thpnum=d.thpnum and a.thlname=d.thlname and a.thfname=d.thfname
WHERE a.thsbmstatus<'900'
ORDER BY a.thcnum, a.thdate, a.thlname, a.thfname";
$duplicateresult = mysqli_query($dbhandle,$duplicatequery);
if(!$duplicateresult)
	error("001","MySql[duplicateresult]:" . mysqli_error($dbhandle));	
$duplicateNumRows = mysqli_num_rows($duplicateresult);

//
// Get Last Submission Date for Clinics
//
$clinicsbmdatequery  = "SELECT cmcnum, coalesce(maxsbmdate,0) as LastSbmDate FROM master_clinics
LEFT JOIN (
	SELECT thcnum, MAX(thsbmDate) as maxsbmdate
	FROM treatment_header
	WHERE thsbmstatus < '900'
	GROUP by thcnum
	) as sbmDate
ON cmcnum = thcnum
WHERE cminactive='0' and coalesce(maxsbmdate,0) <> 0";
$clinicsbmdateresult = mysqli_query($dbhandle,$clinicsbmdatequery);
if(!$clinicsbmdateresult)
		error("002","MySql[clinicsbmdateresult]:" . mysqli_error($dbhandle));	
while($clinicsbmdaterow = mysqli_fetch_array($clinicsbmdateresult,MYSQLI_ASSOC)) {
	$cliniclastsubmissiondate[$clinicsbmdaterow["cmcnum"]] = $clinicsbmdaterow["LastSbmDate"];
}

foreach($cliniclastsubmissiondate as $cmcnum=>$LastSbmDate) {
	//
	// Get Number of Patients in each visit status on last submission date
	//
	$visittypequery  = "
	SELECT thcnum, vtmcode, coalesce(myCount,0) as Num 
	FROM master_visittypes 
	 JOIN (
		SELECT thcnum, thvtmcode, count(*) as myCount 
		FROM treatment_header 
		WHERE thcnum = '" . $cmcnum . "' and thsbmstatus < '900' and DATE_FORMAT( thsbmDate, '%m/%d/%Y') = DATE_FORMAT( '" . date('Y-m-d', strtotime($LastSbmDate)) . "', '%m/%d/%Y')
		GROUP BY thcnum, thvtmcode
	) as j
	ON vtmcode=j.thvtmcode
	";
	$visittyperesult = mysqli_query($dbhandle,$visittypequery);
	if(!$visittyperesult)
			error("001","MySql[visittyperesult]:" . mysqli_error($dbhandle));	
	while($visittyperow = mysqli_fetch_array($visittyperesult,MYSQLI_ASSOC)) {
		$visitinformation[$visittyperow["thcnum"]][$visittyperow["vtmcode"]] = $visittyperow["Num"]; 
	}
	
	//
	// Get Number of Procedures by procedure type on current working date
	//
	$proceduretypequery  = "
	SELECT j.thcnum, pm.pmcode, coalesce(myCount,0) as Num 
	FROM master_procedures pm
	 JOIN (
		SELECT th.thcnum, tp.pmcode, count(*) as myCount 
		FROM treatment_header th
		JOIN treatment_procedures tp
		ON th.thid = tp.thid 
		WHERE thcnum = '" . $cmcnum . "' and thsbmstatus <'900' and DATE_FORMAT( thsbmDate, '%m/%d/%Y') = DATE_FORMAT( '" . date('Y-m-d', strtotime($LastSbmDate)) . "', '%m/%d/%Y')
		GROUP BY th.thcnum, tp.pmcode
	 ) as j
	ON pm.pmcode=j.pmcode
	";
	$proceduretyperesult = mysqli_query($dbhandle,$proceduretypequery);
	if(!$proceduretyperesult)
			error("001","MySql[proceduretyperesult]:" . mysqli_error($dbhandle));	
	while($proceduretyperow = mysqli_fetch_array($proceduretyperesult,MYSQLI_ASSOC)) {
		$proceduresinformation[$proceduretyperow["thcnum"]][$proceduretyperow["pmcode"]] = $proceduretyperow["Num"]; 
	}
	
	//
	// Get Number of Modalities by modality type on current working date
	//
	$modalitiestypequery  = "
	SELECT j.thcnum, mm.mmcode, coalesce(myCount,0) as Num 
	FROM master_modalities mm
	 JOIN (
		SELECT th.thcnum, tm.mmcode, count(*) as myCount 
		FROM treatment_header th
		JOIN treatment_modalities tm
		ON th.thid = tm.thid 
		WHERE thcnum = '" . $cmcnum . "' and thsbmstatus < '900' and DATE_FORMAT( thsbmDate, '%m/%d/%Y') = DATE_FORMAT( '" . date('Y-m-d', strtotime($LastSbmDate)) . "', '%m/%d/%Y')
		GROUP BY th.thcnum, tm.mmcode
	) as j
	ON mm.mmcode=j.mmcode
	";
	$modalitiestyperesult = mysqli_query($dbhandle,$modalitiestypequery);
	if(!$modalitiestyperesult)
			error("001","MySql[proceduretyperesult]:" . mysqli_error($dbhandle));	
	while($modalitiestyperow = mysqli_fetch_array($modalitiestyperesult,MYSQLI_ASSOC)) {
		$modalitiesinformation[$modalitiestyperow["thcnum"]][$modalitiestyperow["mmcode"]] = $modalitiestyperow["Num"]; 
	}
	
	$treatmentsquery  = "
	SELECT thcnum, count(*) as Num 
	FROM treatment_header 
	WHERE thcnum = '" . $cmcnum . "' and thsbmstatus < '900' and DATE_FORMAT( thsbmDate, '%m/%d/%Y') = DATE_FORMAT( '" . date('Y-m-d', strtotime($LastSbmDate)) . "', '%m/%d/%Y') 
	GROUP BY thcnum 
	";
	$treatmentsresult = mysqli_query($dbhandle,$treatmentsquery);
	if(!$treatmentsresult)
			error("003","MySql[treatmentsresult]:" . mysqli_error($dbhandle));	
}

//
// MAIN LOOP
// Get Active Clinics
//
$clinicsquery  = "SELECT * FROM master_clinics WHERE cminactive=0 and cmcnum <> '@@' ORDER BY cmcnum";
$clinicsresult = mysqli_query($dbhandle,$clinicsquery);
if(!$clinicsresult)
		error("004","MySql[clinicsresult]:" . mysqli_error($dbhandle));	
$numRows = mysqli_num_rows($clinicsresult);
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Treatment Dashboard</legend>
	<?php
if($duplicateNumRows <> 0) {
?>
	<div class="containedBox" style="background-color:#FF0000; color:#000000; font-size:larger;">
		Please check Duplicate Dashboard.
	</div>
<?php
}
if($numRows>0) {
?>
	<div class="containedBox">
		<form method="post" name="searchlist">
			<table border="1" cellpadding="3" cellspacing="0" width="100%">
				<tr style="color:#FFFFFF; background-color:#4682B4;">
					<th>Clinic</th>
					<th>Last Submit</th>
					<th>New</th>
					<th>STD</th>
					<th>RE</th>
					<th>RE w/o</th>
					<th>DC</th>
					<th>DC w/o</th>
					<th>Procedures</th>
					<th>iso treatment</th>
					<th>bio treatments</th>
					<th>Modalities</th>
					<th>active patients</th>
				</tr>
				<?php
	$nowdate = date('Y/m/d', time());
	while($row = mysqli_fetch_array($clinicsresult,MYSQLI_ASSOC)) {
		if(count($proceduresinformation[$row["cmcnum"]]) >0)
			$proceduressum = array_sum($proceduresinformation[$row["cmcnum"]]);
		else
			unset($proceduressum);
		if(count($modalitiesinformation[$row["cmcnum"]]) >0)
			$modalitiessum = array_sum($modalitiesinformation[$row["cmcnum"]]);
		else
			unset($modalitiessum);
?>
				<tr>
					<td><?php echo($row["cmcnum"]); ?></td>
					<td><?php echo($cliniclastsubmissiondate[$row["cmcnum"]]); ?>&nbsp;</td>
					<td><?php echo($visitinformation[$row["cmcnum"]]["NPE"]); ?>&nbsp;</td>
					<td><?php echo($visitinformation[$row["cmcnum"]]["ST"]); ?>&nbsp;</td>
					<td><?php echo($visitinformation[$row["cmcnum"]]["REE"]); ?>&nbsp;</td>
					<td><?php echo($visitinformation[$row["cmcnum"]]["RE"]); ?>&nbsp;</td>
					<td><?php echo($visitinformation[$row["cmcnum"]]["DC"]); ?>&nbsp;</td>
					<td><?php echo($visitinformation[$row["cmcnum"]]["DCW"]); ?>&nbsp;</td>
					<td><?php echo($proceduressum); ?>&nbsp;</td>
					<td><?php echo($proceduresinformation[$row["cmcnum"]]["ISO"]); ?>&nbsp;</td>
					<td><?php echo($proceduresinformation[$row["cmcnum"]]["BIO"]); ?>&nbsp;</td>
					<td><?php echo($modalitiessum); ?>&nbsp;</td>
					<td>
					<?php if(!empty($cliniclastsubmissiondate[$row["cmcnum"]] )) { ?>
					<input name="navigation[<?php echo(urlencode ('sort=1&' . 'searchcnum=' . $row["cmcnum"] . '&' . 'searchfromsubmitdate=' . date('m/d/Y', strtotime($cliniclastsubmissiondate[$row["cmcnum"]])) . '&' . 'searchtosubmitdate=' . date('m/d/Y', strtotime($cliniclastsubmissiondate[$row["cmcnum"]]))));?>]" type="submit" value="Search Treatments" />
					<?php } else { ?>
						&nbsp;
					<?php } ?>
					</td>
				</tr>
				<?php
	}
?>
			</table>
		</form>
		<?php
}
else {
	echo('No active clinics found ');
}
mysqli_close($dbhandle);
?>
	</div>
	</fieldset>
</div>