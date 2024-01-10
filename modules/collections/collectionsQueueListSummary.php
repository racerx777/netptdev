<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

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
	if(isuserlevel(34))
		$groupwhere="";
	else {
		exit();
		dump(isuserlevel(34), $group);
	}
}
else
	$groupwhere="and cqgroup='$group'";

$bnumtotal=array();
$acctypetotal=array();

$queuequery = "
	SELECT bnum, cqgroup, acctype, count(*) acctypecount, sum(tcurr) tcurr, sum(t30) t30, sum(t60) t60, sum(t90) t90, sum(t120) t120, sum(tbal) tbal
	FROM collection_queue cq
	LEFT JOIN collection_accounts ca
	ON cqcaid=caid
	LEFT JOIN PTOS_Patients p
	ON cabnum=bnum and capnum=pnum
	WHERE 1=1 $groupwhere
	GROUP BY bnum, cqgroup, acctype
	ORDER BY bnum, cqgroup, acctype
";

if($queueresult = mysqli_query($dbhandle,$queuequery)) {
	$queuenumrows=mysqli_num_rows($queueresult);
	if($queuenumrows > 0) {
?>
<table border="1" cellpadding="5" cellspacing="0">
<?php
		$firstgroup=true;
		while($queuerow = mysqli_fetch_assoc($queueresult)) {
			if($queuerow['cqgroup'] != $savedgroup) {
				if($firstgroup) {
// Output Report Heading
?>
	<tr>
		<th colspan="10">
			Collections Queue Summary Report<br />by Queue Group and Account Type
		</th>
	</tr>
<?php
				}
				else {
// Output Current Level Break Sub-Totals
?>
<tr bgcolor="#BBBBBB">
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
// Reset Break Totals & Counters
				$queuecounter = 0;
				$firstcqgroup=$queuerow['cqgroup'];
				$firstcqgrouplink='<a href="https://netpt.wsptn.com/modules/collections/collectionsQueueList.php?group='.$firstcqgroup.'&detail=1&limit=2000" >'.$firstcqgroup.'</a>';
				$savedgroup = $queuerow['cqgroup'];
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
<?
				$firstgroup=false;
			}
// Output Detail
$queuecounter++;
$acctypelink='<a href="https://netpt.wsptn.com/modules/collections/collectionsQueueList.php?group='.$queuerow['cqgroup'].'&acctype='.$queuerow['acctype'].'&detail=1&limit=2000" >'.$queuerow['acctype'].'</a>';
?>
<tr>
	<td nowrap="nowrap" align="right"><?php echo $firstcqgrouplink;?></td>
	<td nowrap="nowrap" align="right"><?php echo $acctypelink; ?></td>
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
				$firstcqgroup="";
				$firstcqgrouplink="";

				$bnum=$queuerow['bnum'];

				$bnumtotal["$bnum"]['queuecounter']=$queuecounter;
				$bnumtotal["$bnum"]['acctype']+=$queuerow['acctype'];
				$bnumtotal["$bnum"]['acctypecount']+=$queuerow['acctypecount'];
				$bnumtotal["$bnum"]['tcurr']+=$queuerow['tcurr'];
				$bnumtotal["$bnum"]['t30']+=$queuerow['t30'];
				$bnumtotal["$bnum"]['t60']+=$queuerow['t60'];
				$bnumtotal["$bnum"]['t90']+=$queuerow['t90'];
				$bnumtotal["$bnum"]['t120']+=$queuerow['t120'];
				$bnumtotal["$bnum"]['tbal']+=$queuerow['tbal'];

				$acctype=' '.$queuerow['acctype'].' ';
				$acctypetotal["$bnum"]["$acctype"]['queuecounter']=$queuecounter;
				$acctypetotal["$bnum"]["$acctype"]['acctype']+=$queuerow['acctype'];
				$acctypetotal["$bnum"]["$acctype"]['acctypecount']+=$queuerow['acctypecount'];
				$acctypetotal["$bnum"]["$acctype"]['tcurr']+=$queuerow['tcurr'];
				$acctypetotal["$bnum"]["$acctype"]['t30']+=$queuerow['t30'];
				$acctypetotal["$bnum"]["$acctype"]['t60']+=$queuerow['t60'];
				$acctypetotal["$bnum"]["$acctype"]['t90']+=$queuerow['t90'];
				$acctypetotal["$bnum"]["$acctype"]['t120']+=$queuerow['t120'];
				$acctypetotal["$bnum"]["$acctype"]['tbal']+=$queuerow['tbal'];
		}
if(empty($group)) {
// Output Last Sub-Total
?>
<tr bgcolor="#BBBBBB">
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
	<tr height="1px"><td colspan="10"></td></tr>
<?php
// Output Report-Totals
foreach($bnumtotal as $bnum=>$bnumtotalreport) {
?>
	<tr height="1px"><td colspan="10"><?php echo $bnum; ?></td></tr>
<?php
	ksort($acctypetotal["$bnum"]);
	foreach($acctypetotal["$bnum"] as $acctype=>$acctypetotalreport) {
?>
<tr bgcolor="#DDDDDD">
	<td nowrap="nowrap" align="right"><?php echo "$acctype"; ?></td>
	<td nowrap="nowrap" align="right">&nbsp;</td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($acctypetotalreport['tcurr']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($acctypetotalreport['t30']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($acctypetotalreport['t60']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($acctypetotalreport['t90']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($acctypetotalreport['t120']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($acctypetotalreport['tbal']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo displayInteger($acctypetotalreport['acctypecount']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency(($acctypetotalreport['tbal']/$acctypetotalreport['acctypecount'])); ?></td>
</tr>
<?php
	}
// Output Report-Totals
?>
<tr bgcolor="#DDDDDD">
	<td nowrap="nowrap" align="right"><?php echo "$bnum Totals:"; ?></td>
	<td nowrap="nowrap" align="right">&nbsp;</td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($bnumtotalreport['tcurr']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($bnumtotalreport['t30']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($bnumtotalreport['t60']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($bnumtotalreport['t90']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($bnumtotalreport['t120']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency($bnumtotalreport['tbal']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo displayInteger($bnumtotalreport['acctypecount']); ?></td>
	<td nowrap="nowrap" align="right"><?php echo myDisplayCurrency(($bnumtotalreport['tbal']/$bnumtotalreport['acctypecount'])); ?></td>
</tr>
<tr height="1px"><td colspan="10"></td></tr>
<?php
}
?>
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