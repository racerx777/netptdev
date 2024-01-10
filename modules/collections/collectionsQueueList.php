<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);
function my_wordwrap($str, $width=75, $break="<br>/n", $cut=true) {
	$fullstr=trim($str);
	$linearray=array();
	while(strlen($fullstr) > 0) {
		$fullstrlen = strlen($fullstr);
		if(strlen($fullstr) > $width) {
			$mystr=substr($fullstr, 0, $width);
			$lastpos = strrpos( $mystr , " ");
			if($lastpos > 0)
				$line=trim(substr($mystr, 0, $lastpos));
			else
				$line=trim(substr($mystr, 0, $width-1));
			$linestrlen = strlen($line);
			$linearray[]=$line;
			$newstr = substr($fullstr, $linestrlen+1, ($fullstrlen-$linestrlen) );
			$fullstr=trim($newstr);
		}
		else {
			$linearray[]=trim($fullstr);
			unset($fullstr);
		}
	}
	return implode($break, $linearray);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$acctype=mysqli_real_escape_string($dbhandle,$_REQUEST['acctype']);
$group=mysqli_real_escape_string($dbhandle,$_REQUEST['group']);
$detail=mysqli_real_escape_string($dbhandle,$_REQUEST['detail']);
$start=mysqli_real_escape_string($dbhandle,$_REQUEST['start']);
$limit=mysqli_real_escape_string($dbhandle,$_REQUEST['limit']);

if(empty($acctype))
	$acctypewhere="";
else
	$acctypewhere="and acctype='$acctype'";

// Default all groups
if(empty($group))
	$groupwhere="";
else
	$groupwhere="and cqgroup='$group'";
// Default Summary
if(empty($detail))
	$detail=false;
else {
	$detail=true;
}
if(empty($start))
	$start='0';
if(empty($limit))
	$limit='100';

if($detail) {
	$queuequery = "
		SELECT cqgroup,
			CASE
			 WHEN cqpriority<=10 AND DATE(cqschcalldate)<=DATE(NOW()) THEN '10-Waiting Callback'
			 WHEN cqpriority<=10 AND DATE(cqschcalldate)>DATE(NOW()) THEN '98-Future Callback'
			 WHEN cqpriority between 11 and 20 AND DATE(cqschcalldate)>DATE(NOW()) THEN '99-Future Confirmation'
			 WHEN cqpriority between 11 and 20 AND DATE(cqschcalldate)<=DATE(NOW()) THEN '20-Waiting Confirmation'
			 WHEN cqpriority between 21 and 30 THEN '30-Reqular Queued Call'
			ELSE '00-Unknown Priority'
			END as priority,
			CASE
				WHEN DATE(cqschcalldate)<=DATE(NOW()) OR cqschcalldate IS NULL THEN DATE(NOW())
				ELSE DATE(cqschcalldate)
			END as schcallday,
		cqcaid, cqpriority, cqrtbal, cqschcalldate, lockuser, lockdate, cq.crtdate queue_crtdate, cq.upddate queue_upddate, cabnum, cacnum, capnum, caaccttype, caacctsubtype, caacctgroup, caacctstatus, calienstatus, calienamount, caliendate, cadorstatus, cadordate, casettlestatus, casettleamount, casettledate, acctype, lname, fname, ssn, phone, padd1, padd2, padd3, doc, injury, discharge, fvisit, lvisit, birth, lpayi, tbal, t120, t90, t60, t30, tcurr, visits, adjust, payments, charges, unbilled
		FROM collection_queue cq
		LEFT JOIN collection_accounts ca
		ON cqcaid=caid
		LEFT JOIN PTOS_Patients p
		ON cabnum=bnum and capnum=pnum
		WHERE 1=1 $groupwhere $acctypewhere
		ORDER BY cqgroup, priority, cqpriority, schcallday, cqrtbal desc, cqcaid
		LIMIT $start, $limit
	";
}
else {
	$queuequery = "
		SELECT cqgroup, acctype, count(*) num, sum(tcurr), sum(t30), sum(t60), sum(t90), sum(t120), sum(tbal) tbal
		FROM collection_queue cq
		LEFT JOIN collection_accounts ca
		ON cqcaid=caid
		LEFT JOIN PTOS_Patients p
		ON cabnum=bnum and capnum=pnum
		WHERE 1=1 $groupwhere
		GROUP BY cqgroup, acctype
		ORDER BY cqgroup, acctype
	";
}
//dump("queuequery",$queuequery);
	if($queueresult = mysqli_query($dbhandle,$queuequery)) {
		$queuenumrows=mysqli_num_rows($queueresult);
		if($queuenumrows > 0) {
?>
<table>
<?php
			while($queuerow = mysqli_fetch_assoc($queueresult)) {
				if($queuerow['cqgroup']!=$savedgroup) {
// Output Summary and reset counters
					$queuecounter=1;
					$savedgroup=$queuerow['cqgroup'];
				}
				if(empty($tableheader)) {
					$queuecounter=1;
?>
<tr>
	<th>Count</th>
<?php
					foreach($queuerow as $fieldname=>$fieldvalue) {
?>
<th><?php echo $fieldname; ?></th>
<?php
					}
?>
</tr>
<?php
					$tableheader=1;
				}
				else {
				}

?>
<tr>
	<td nowrap="nowrap"><?php echo $queuecounter++; ?></td>
<?php
					foreach($queuerow as $fieldname=>$fieldvalue) {
						if($fieldname=='cqgroup') {
?>
	<td nowrap="nowrap"><?php echo $fieldvalue; ?></td>
<?php
						}
						else {
?>
	<td nowrap="nowrap" align="right"><?php echo $fieldvalue; ?></td>
<?php
						}
					}
?>
</tr>
<?php			}
?>
</table>
<?php
		}
	}
	else
		error("001","collectionsQueueList: SELECT Error. $queuequery<br>".mysqli_error($dbhandle));

mysqli_close($dbhandle);
?>