<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function breakGroupTotals($bnum, $status, $group, $count, $amount) {
// Output Queue Totals here if desired
?>
	<tr>
		<td><?php echo($bnum); ?></td>
		<td><?php echo($status); ?></td>
		<td><?php echo($group); ?></td>
		<td colspan="6"><?php echo($count); ?> Accounts - Queue Total</td>
		<td align="right"><?php echo displayCurrency($amount); ?></td>
	</tr>
	<?php
}

function breakStatusTotals($bnum, $status, $group, $count, $amount) {
// Output Queue Totals here if desired
?>
	<tr><td colspan="10"><hr></td></tr>
	<tr>
		<td><?php echo($bnum); ?></td>
		<td><?php echo($status); ?></td>
		<td colspan="7"><?php echo($count); ?> Accounts - Status Total</td>
		<td align="right"><?php echo displayCurrency($amount); ?></td>
	</tr>
	<tr><td colspan="10">&nbsp;</td></tr>
	<?php
}

function breakBusinessTotals($bnum, $status, $group, $count, $amount) {
// Output Queue Totals here if desired
?>
	<tr><td colspan="10"><hr></td></tr>
	<tr><td colspan="10"><hr></td></tr>
	<tr>
		<td><?php echo($bnum); ?></td>
		<td colspan="8"><?php echo($count); ?> Accounts - Business Total</td>
		<td align="right"><?php echo displayCurrency($amount); ?></td>
	</tr>
	<tr><td colspan="10">&nbsp;</td></tr>
	<?php
}

function breakReportTotals($bnum, $status, $group, $count, $amount) {
// Output Queue Totals here if desired
?>
	<tr><td colspan="10"><hr></td></tr>
	<tr><td colspan="10"><hr></td></tr>
	<tr><td colspan="10"><hr></td></tr>
	<tr>
		<td colspan="9"><?php echo($count); ?> Accounts - Report Total</td>
		<td align="right"><?php echo displayCurrency($amount); ?></td>
	</tr>
	<?php
}

// list accounts in queue that have not been contacted in the past xx days and/or accounts scheduled to call after xx days
// allow user to specify:
//		Number of days since last contact
//		Number of days until next contact

// List the Queue, Account, Amount, Last Date/Time we contacted them, Next Date/Time we scheduled to contact them
if(isset($_REQUEST['includedetail']))
	$includedetail=$_REQUEST['includedetail'];

if(isset($_REQUEST['morethanxxdaysago']))
	$morethanxxdaysago=$_REQUEST['morethanxxdaysago'];

if(isset($_REQUEST['morethanxxdaysinthefuture']))
	$morethanxxdaysinthefuture=$_REQUEST['morethanxxdaysinthefuture'];

$where=array();

// Leave only Weststar Collectors
//$where[]= "cqgroup not in ('Curren','WSPvt','WSMC','WSDism','WSColl','PI','NetNA')";

if(!empty($morethanxxdaysago)) {
	$today=dbDate(today());
	$whereor[]="( (lasttoucheddate IS NULL AND q.crtdate < DATE_SUB('$today', INTERVAL 30 DAY) ) OR lasttoucheddate < DATE_SUB('$today', INTERVAL $morethanxxdaysago DAY))";
	if(!empty($morethanxxdaysinthefuture)) {
		$whereor[]="(cqschcalldate > DATE_ADD('$today', INTERVAL $morethanxxdaysinthefuture DAY))";
	}
	$where[]=implode(' OR', $whereor);
}

if(count($where)>0)
	$wheresql = "WHERE ".implode(' AND ', $where);

//	WHEN cqgroup in ('10NNA','92NCOL','98NCUR','99NDIS','91WPVT','92WCOL','98WCUR','10WNA') THEN '9. Not Collecting'

$select="
	SELECT
	CASE
	 WHEN cqgroup LIKE '%LOW%' THEN '1. Low'
	 WHEN cqgroup LIKE '%SOF%' THEN '2. Soft'
	 WHEN cqgroup LIKE '%MED%' THEN '3. Medium'
	 WHEN cqgroup LIKE '%HAR%' THEN '4. Hard'
	 WHEN cqgroup LIKE '%DOL%' THEN '5. Dept of Labor'
	 WHEN cqgroup LIKE '%LEGA%' THEN '6. Legal'
	 WHEN cqgroup LIKE '%PI%' THEN '7. PI'
	ELSE '9. Not Collecting'
	END as status,
	capnum, caid,
	bnum, pnum, tbal, acctype,
	cqgroup, cqpriority, cqpriority, cqschcalldate, q.crtdate cqcrtdate, q.crtuser cqcrtuser, q.upddate cqupddate, q.upduser cqupduser, cqcaid,
	ltcaid, lasttoucheddate
	FROM collection_accounts ca
	JOIN PTOS_Patients p
	ON ca.cabnum=p.bnum and ca.capnum=p.pnum
	JOIN collection_queue q
	ON caid=cqcaid
	LEFT JOIN (
		SELECT noappid ltcaid, max(crtdate) lasttoucheddate
		FROM notes
		WHERE noapp='collections'
		GROUP BY noappid
		) s1
	ON cqcaid=ltcaid
	$wheresql
	ORDER BY bnum, status, cqgroup, cqschcalldate, lasttoucheddate, tbal
	";
dump("select",$select);
if($result=mysqli_query($dbhandle,$select)) {
?>

<table border="1">
	<tr>
		<th>Business</th>
		<th>Status</th>
		<th>Queue</th>
<?php
if($includedetail) {
?>
		<th>#</th>
		<th>PTOS Account</th>
		<th>Collection Id</th>
		<th>Schedule Call Date</th>
		<th>Last Contact Date</th>
		<th>acctype</th>
<?php
}
else {
?>
		<th colspan="6">&nbsp;</th>
<?php
}
?>
		<th>tbal</th>
	</tr>
	<?php
while($row=mysqli_fetch_assoc($result)) {
	if($savedbnum<>$row['bnum'] or $savedstatus<>$row['status'] or $savedgroup<>$row['cqgroup']) {
		if($notfirstpage) {
			$savedgroupcount+=$savedcount;
			$savedgrouptbal+=$savedtbal;
			breakGroupTotals($savedbnum, $savedstatus, $savedgroup, $savedgroupcount, $savedgrouptbal);
			$savedstatuscount+=$savedgroupcount;
			$savedstatustbal+=$savedgrouptbal;
		}

		if($savedbnum<>$row['bnum'] or $savedstatus<>$row['status']) {
			if($notfirstpage) {
				breakStatusTotals($savedbnum, $savedstatus, NULL, $savedstatuscount, $savedstatustbal);
				$savedbnumcount+=$savedstatuscount;
				$savedbnumtbal+=$savedstatustbal;
			}
			$tablestatus=$row['status'];
			$savedstatus=$row['status'];

			$savedstatuscount=0;
			$savedstatustbal=0;
		}
		else
			$tablestatus=$row['status'];

		if($savedbnum<>$row['bnum']) {
			if($notfirstpage) {
				breakBusinessTotals($savedbnum, NULL, NULL, $savedbnumcount, $savedbnumtbal);
				$reportcount+=$savedbnumcount;
				$reporttbal+=$savedbnumtbal;
			}
			$tablebnum=$row['bnum'];
			$savedbnum=$row['bnum'];

			$savedbnumcount=0;
			$savedbnumtbal=0;
		}
		else
			$tablebnum=$row['bnum'];

		if(!$notfirstpage)
			$notfirstpage=true;

		$tablegroup=$row['cqgroup'];
		$savedgroup=$row['cqgroup'];
		$savedgroupcount=0;
		$savedgrouptbal=0;

		$savedcount=0;
		$savedtbal=0;
	}
	else
		$tablegroup="&nbsp";

	$tablepnum=$row['pnum'];
	$tablecaid=$row['caid'];
	$tablecalldate=$row['cqschcalldate'];
	$tablelastdate=$row['lasttoucheddate'];
	$tableacctype=$row['acctype'];
	$tabletbal=$row['tbal'];

	$savedcount++;
	$savedtbal+=$tabletbal;

	$totalacctypecount["$tableacctype"]=$totalacctypecount["$tableacctype"]+1;
	$totalacctypetbal["$tableacctype"]+=$tabletbal;

	if(empty($tablecalldate))
		$tablecalldate="NOW";

	if(empty($tablelastdate))
		$tablelastdate="NEVER CONTACTED";
	if($includedetail) {
?>
	<tr>
		<td><?php echo $tablebnum; ?></td>
		<td><?php echo $tablestatus; ?></td>
		<td><?php echo $tablegroup; ?></td>
		<td><?php echo $savedcount; ?></td>
		<td><?php echo $tablepnum; ?></td>
		<td><?php echo $tablecaid; ?></td>
		<td><?php echo $tablecalldate; ?></td>
		<td><?php echo $tablelastdate; ?></td>
		<td><?php echo $tableacctype; ?></td>
		<td align="right"><?php echo displayCurrency($tabletbal); ?></td>
	</tr>
	<?php
	}
}
// Report Totals
?>
<?php
	$savedgroupcount+=$savedcount;
	$savedgrouptbal+=$savedtbal;
	breakGroupTotals($savedbnum, $savedstatus, $savedgroup, $savedgroupcount, $savedgrouptbal);

	$savedstatuscount+=$savedgroupcount;
	$savedstatustbal+=$savedgrouptbal;
	breakStatusTotals($savedbnum, $savedstatus, NULL, $savedbnumcount, $savedbnumtbal);

	$savedbnumcount+=$savedstatuscount;
	$savedbnumtbal+=$savedstatustbal;
	breakBusinessTotals($savedbnum, NULL, NULL, $savedbnumcount, $savedbnumtbal);

	$reportcount+=$savedbnumcount;
	$reporttbal+=$savedbnumtbal;
	breakReportTotals(NULL, NULL, NULL, $reportcount, $reporttbal);
?>
</table>
<?php
	if(count($totalacctypecount)>0) {
		ksort($totalacctypecount);
?>
<table border="1">
	<tr>
		<th>Account Type</th>
		<th>Count</th>
		<th>Balance</th>
		<th>Pct/Report</th>
	</tr>
	<?php
		foreach($totalacctypecount as $acctype=>$count) {
			$percent=$totalacctypetbal["$acctype"] / $reporttbal;
	?>
	<tr>
		<td><?php echo($acctype);?></td>
		<td><?php echo($count);?></td>
		<td align="right"><?php echo(displayCurrency($totalacctypetbal["$acctype"])); ?></td>
		<td align="right"><?php echo(displayPercent($percent,2,"","%") ); ?></td>
	</tr>
	<?php
		}
	?>
</table>
<?php
	}
}
else
	dump("mysql error", mysqli_error($dbhandle));
mysqli_close($dbhandle);
exit();
?>