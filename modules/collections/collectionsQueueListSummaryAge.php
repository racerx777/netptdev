<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
//securitylevel(1);

function myDisplayCurrency($number) {
	return(displayCurrency($number, ",", "$"));
}

function displayInteger($number) {
	return(round($number, 0));
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$group=mysqli_real_escape_string($dbhandle,$_REQUEST['group']);
// Default all groups
if(empty($group)) {
	if(isuserlevel(34) || $user='SunniSpoon')
		$groupwhere="";
	else
		exit();
}
else
	$groupwhere="and cqgroup='$group'";


$queuequery = "
	SELECT bnum,
	case when lvisit > '20110131' then 'Treating'
	else 'Not Treating'
	end as Treating,
	case when fvisit > '20109330' then 'Newer Account'
	else 'Older Account'
	end as AccountAge,
	case when lpayi = '' then '000 -No Payments'
	 when lpayi > '20101231' then '090 - Recent Payment'
	 when lpayi > '20109330' then '180 - Slow Payment'
	else '180+ No Recent Payment Activity'
	end as PaymentAge,
	count(*) acctypecount, sum(tcurr) tcurr, sum(t30) t30, sum(t60) t60, sum(t90) t90, sum(t120) t120, sum(tbal) tbal
	FROM collection_queue cq
	LEFT JOIN collection_accounts ca
	ON cqcaid=caid
	LEFT JOIN PTOS_Patients p
	ON cabnum=bnum and capnum=pnum
	WHERE bnum='WS' $groupwhere
	GROUP BY bnum, Treating, AccountAge, PaymentAge
	ORDER BY bnum, Treating, AccountAge, PaymentAge
";

if($queueresult = mysqli_query($dbhandle,$queuequery)) {
	$queuenumrows=mysqli_num_rows($queueresult);
	if($queuenumrows > 0) {
?>
<table border="1" cellpadding="5" cellspacing="0">
<?php
		$firstgroup=true;
		while($queuerow = mysqli_fetch_assoc($queueresult)) {
//			if($queuerow['cqgroup'] != $savedgroup) {
			$breakfields=$queuerow['bnum'].'-'.$queuerow['Treating'].'-'.$queuerow['AccountAge'].'-'.$queuerow['PaymentAge'];
			if($breakfields != $savedgroup) {
				if($firstgroup) {
// Output Report Heading
?>
	<tr>
		<th colspan="10">
			Weststar Collections Queue Summary Report<br />by Treating, Age, Payment Age
		</th>
	</tr>
<tr bgcolor="#AAAAAA">
	<th>Queue Group</th>
	<th>Acctype</th>
	<th>Current</th>
	<th>30+</th>
	<th>60+</th>
	<th>90+</th>
	<th>120+</th>
	<th>Balance</th>
	<th>Accounts</th>
	<th>Avg Bal</th>
</tr>
<?php
				}
				else {
// Output Current Level Break Sub-Totals
?>
<!--<tr bgcolor="#BBBBBB">
		<td nowrap="nowrap" align="right"><?php echo "Sub Total:"; ?></td>
		<td nowrap="nowrap" align="right"><?php echo $total['queuecounter']; ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['tcurr']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t30']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t60']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t90']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t120']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['tbal']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo displayInteger($total['acctypecount']); ?></td>
		<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency(($total['tbal']/$total['acctypecount'])); ?></td>
	</tr>
	<tr height="10px"><td colspan="10"></td>
	</tr>
--><?php
				}
// Update Report Total
				$report['queuecounter']+=$total['queuecounter'];
				$report['acctype']+=$total['acctype'];
				$report['acctypecount']+=$total['acctypecount'];
				$report['tcurr']+=$total['tcurr'];
				$report['t30']+=$total['t30'];
				$report['t60']+=$total['t60'];
				$report['t90']+=$total['t90'];
				$report['t120']+=$total['t120'];
				$report['tbal']+=$total['tbal'];
// Reset Break Totals & Counters
				$queuecounter = 0;
				$firstbreakfields=$breakfields;
				$savedgroup = $breakfields;
				$total['queuecounter']=0;
				$total['acctype']=0;
				$total['acctypecount']=0;
				$total['tcurr']=0;
				$total['t30']=0;
				$total['t60']=0;
				$total['t90']=0;
				$total['t120']=0;
				$total['tbal']=0;
// Output New Header
?>
<!--<tr bgcolor="#AAAAAA">
	<th>Queue Group</th>
	<th>Acctype</th>
	<th>Current</th>
	<th>30+</th>
	<th>60+</th>
	<th>90+</th>
	<th>120+</th>
	<th>Balance</th>
	<th>Accounts</th>
	<th>Avg Bal</th>
</tr>
--><?
				$firstgroup=false;
			}
// Output Detail
$queuecounter++
?>
<tr>
	<td nowrap="nowrap" align="left"><?php echo $firstbreakfields; ?></td>
	<td nowrap="nowrap" align="right"><?php echo $queuerow['acctype']; ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($queuerow['tcurr']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($queuerow['t30']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($queuerow['t60']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($queuerow['t90']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($queuerow['t120']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($queuerow['tbal']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo displayInteger($queuerow['acctypecount']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency(($queuerow['tbal']/$queuerow['acctypecount'])); ?></td>
</tr>
<?php
// Update Group Total
				$total['queuecounter']=$queuecounter;
				$total['acctype']+=$queuerow['acctype'];
				$total['acctypecount']+=$queuerow['acctypecount'];
				$total['tcurr']+=$queuerow['tcurr'];
				$total['t30']+=$queuerow['t30'];
				$total['t60']+=$queuerow['t60'];
				$total['t90']+=$queuerow['t90'];
				$total['t120']+=$queuerow['t120'];
				$total['tbal']+=$queuerow['tbal'];
				$firstbreakfields="";
		}
if(empty($group)) {
// Output Last Sub-Total
?>
<!--<tr bgcolor="#BBBBBB">
	<td nowrap="nowrap" align="right"><?php echo "Sub Total:"; ?></td>
	<td nowrap="nowrap" align="right"><?php echo $total['queuecounter']; ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['tcurr']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t30']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t60']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t90']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['t120']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($total['tbal']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo displayInteger($total['acctypecount']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency(($total['tbal']/$total['acctypecount'])); ?></td>
</tr>
-->			<tr height="10px"><td colspan="10"></td>
			</tr>
<?php
}
// Update Report Total
				$report['queuecounter']+=$total['queuecounter'];
				$report['acctype']+=$total['acctype'];
				$report['acctypecount']+=$total['acctypecount'];
				$report['tcurr']+=$total['tcurr'];
				$report['t30']+=$total['t30'];
				$report['t60']+=$total['t60'];
				$report['t90']+=$total['t90'];
				$report['t120']+=$total['t120'];
				$report['tbal']+=$total['tbal'];
// Output Report-Totals
?>
	<tr height="1px"><td colspan="10"></td>
	</tr>
<tr bgcolor="#DDDDDD">
	<td nowrap="nowrap" align="right"><?php echo "Report Totals:"; ?></td>
	<td nowrap="nowrap" align="right"><?php echo $report['queuecounter']; ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($report['tcurr']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($report['t30']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($report['t60']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($report['t90']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($report['t120']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($report['tbal']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo displayInteger($report['acctypecount']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency(($report['tbal']/$report['acctypecount'])); ?></td>
</tr>

</table>
<?php
	}
	else
		error("002","collectionsQueueList: No Rows Selected. $queuequery<br>".mysqli_error($dbhandle));
}
else
	error("001","collectionsQueueList: SELECT Error. $queuequery<br>".mysqli_error($dbhandle));
mysqli_close($dbhandle);
displaysitemessages();
?>