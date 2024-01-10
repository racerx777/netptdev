<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
// determine if detail, summary or both
if($_REQUEST['detail'] == '0')
	$displaydetailtable=FALSE;
else
	$displaydetailtable=TRUE;
if($_REQUEST['summary'] == '0')
	$displaysummarytable=FALSE;
else
	$displaysummarytable=TRUE;
// determine if dates provided are referral dates or cancelled dates
if(!empty($_REQUEST['fromref']))
	$fromref=$_REQUEST['fromref'];
if(!empty($_REQUEST['toref']))
	$toref=$_REQUEST['toref'];
if(!empty($_REQUEST['fromcan']))
	$fromcan=$_REQUEST['fromcan'];
if(!empty($_REQUEST['tocan']))
	$tocan=$_REQUEST['tocan'];
if(!empty($fromref) && empty($toref) ) 
	$toref=$fromref; 
if(!empty($toref) && empty($fromref) ) 
	$fromref=$toref;
if(!empty($fromcan) && empty($tocan) ) 
	$tocan=$fromcan; 
if(!empty($tocan) && empty($fromcan) ) 
	$fromcan=$tocan;
$report=array();
if(!empty($toref)) {
	$report['type'] = 1;
	$report['title'] = "Referral Date"; 
	$report['column1'] = "Referral Date";
	$report['column2'] = "Cancel Date";
	$report['fromdate']=date("Y-m-d 00:00:00", strtotime($fromref));
	$report['todate']=date("Y-m-d 23:59:59", strtotime($toref));
	$report['where'] = "crcasestatuscode = 'CAN' and c.crdate between '" . $report['fromdate'] . "' and '" . $report['todate'] . "'";
	$report['orderby'] = "c.crdate, c.crcanceldate";
}
else {
	if(!empty($tocan)) {
		$report['type'] = 2;
		$report['title'] = "Cancel Date";
		$report['column1'] = "Cancel Date";
		$report['column2'] = "Referral Date";
		$report['fromdate']=date("Y-m-d 00:00:00", strtotime($fromcan));
		$report['todate']=date("Y-m-d 23:59:59", strtotime($tocan));
		$report['where'] = "crcasestatuscode = 'CAN' and c.crcanceldate between '" . $report['fromdate'] . "' and '" . $report['todate'] . "'";
		$report['orderby'] = "c.crcanceldate, c.crdate";
	}
	else
		unset($report);
}
if(is_array($report) && count($report)!=0) {
//dump("report",$report);
//dump("isarray report",is_array($report));
//dump("count report",count($report));
	$bgcolor['wes']="#BBBBBB";
	$bgcolor['net']="#DDDDDD";
	$bgcolor['oth']="#FFFFFF";
	$bgcolor['new']="#999999";
	$cancelreasoncodecount=array();
	$readmits=array();
	$new=array();
	$canceled=array();
//	$cancelreasoncodecount['wes']=0;
//	$cancelreasoncodecount['net']=0;
//	$cancelreasoncodecount['oth']=0;
//	$cancelreasoncodecount['tot']=0;
	$readmits['wes']=0;
	$readmits['net']=0;
	$readmits['oth']=0;
	$readmits['new']=0;
	$canceled['wes']=0;
	$canceled['net']=0;
	$canceled['oth']=0;
	$canceled['new']=0;
	$new['wes']=0;
	$new['net']=0;
	$new['oth']=0;
	$new['new']=0;

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$query= "
SELECT crdate, dmlname,dmfname, dlzip,dlcity, dlphone,crlname, crfname, crcasetypecode, crtherapytypecode, crcnum, cmname, crsalesid, crcasestatuscode, crapptdate, crreadmit, crcancelreasoncode, ccrmdescription, c.upddate, crcanceldate, palname, pafname
FROM cases c
	LEFT JOIN doctors 
	ON crrefdmid = dmid
	LEFT JOIN doctor_locations 
	ON crrefdlid=dlid
	LEFT JOIN master_case_cancelreasoncodes
	ON crcancelreasoncode = ccrmcode
	LEFT JOIN master_clinics 
	ON crcnum = cmcnum
	LEFT JOIN patients
	ON crpaid = paid
	WHERE " . $report['where'] . "
ORDER BY " . $report['orderby']
;
	if($result = mysqli_query($dbhandle,$query)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cancel Report by <?php echo( $report['title'] . " " . displayDate($report['fromdate']) . " - " . displayDate($report['todate'])) ?></title>
</head>
<body>
<div style="float:left"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
<div style="float:right">
	<div>Cancel Report by <?php echo( $report['title'] . " " . displayDate($report['fromdate']) . " - " . displayDate($report['todate'])) ?></div>
</div>
<div style="clear:both;">
<?php 
		if($displaydetailtable) {
?>
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" width="100%">
		<tr>
			<th><?php echo $report['column1'] ?></th>
			<th><?php echo $report['column2'] ?></th>
			<th>Case Status</th>
			<th>Cancel Reason</th>
			<th>Readmit Flag</th>
			<th>Patient</th>
			<th>Case Type</th>
			<th>Therapy Type</th>
			<th>Physician</th>
			<th>City</th>
		</tr>
		<?php
		}
		while($row=mysqli_fetch_assoc($result)) {
			$referraldate = displayDate($row['crdate']);
			if(!empty($row['dmfname']))
				$physician = $row['dmlname'] . ', ' . $row['dmfname'];
			else
				$physician = $row['dmlname'];
			$city = $row['dlcity'];
			if(!empty($row['crfname']))
				$patient = $row['palname'] . ', ' . $row['pafname'];
			else
				$patient = $row['palname'];
			$casetype = $row['crcasetypecode'];
			$therapytype = $row['crtherapytypecode'];
			$cliniccode = $row['crcnum'];
			$clinicname = $row['cmname'];
			$casestatus = $row['crcasestatuscode'];
			$canceldate = displayDate($row['crcanceldate']);
			$cancelreasoncode = $row['crcancelreasoncode'];
			if($report['type']=='1') {
				$column1 = $referraldate;
				$column2 = $canceldate;
			}
			else {
				if($report['type']=='2') {
				$column1 = $canceldate;
				$column2 = $referraldate;
				}
			}
// Prepare Summary Totals
			if(substr($clinicname,0,8) =='WESTSTAR') {
				$grp = 'wes';
			}
			else {
				if(!empty($clinicname)) {
					$grp = 'net';
				}
				else {
					$grp = 'oth';
				}
			}
//			$referrals["$grp"]++;

//	Count Cancelled
			$cancelreason = 'Canceled on ' . displayDate($row['crcanceldate']) . "-" . $row['ccrmdescription'];
			$canceled["$grp"]++;

// ReAdmit
			if($row['crreadmit'] == 1) {
				$readmit = 'Y';
				$readmits["$grp"]++;
			}
			else {
// Not Readmit - is New
				$readmit = 'N';
				$new["$grp"]++;
			}

			$cancelreasoncodes["$cancelreasoncode"]=$row['ccrmdescription'];
			$cancelreasoncodecount["$grp"]["$cancelreasoncode"]++;
			$cancelreasoncodecount['tot']["$cancelreasoncode"]++;
			
			if($displaydetailtable) {
?>
			<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="<?php echo $bgcolor["$grp"]; ?>">
				<td align="left" nowrap="nowrap"><?php echo $column1; ?>&nbsp;&nbsp;</td>
				<td align="left" nowrap="nowrap"><?php echo $column2; ?>&nbsp;&nbsp;</td>
				<td nowrap="nowrap"><?php echo $casestatus; ?></td>
				<td nowrap="nowrap"><?php echo $cancelreasoncode; ?></td>
				<td align="left" nowrap="nowrap"><?php echo $readmit; ?></td>
				<td align="left" nowrap="nowrap"><?php echo $patient; ?></td>
				<td nowrap="nowrap"><?php echo $casetype; ?></td>
				<td nowrap="nowrap"><?php echo $therapytype; ?></td>
				<td align="left" nowrap="nowrap"><?php echo $physician; ?></td>
				<td align="left" nowrap="nowrap"><?php echo $city; ?></td>
			</tr>
			<?php
				if(isset($cancelreason) && 1==2) {
?>
			<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="<?php echo $bgcolor["$grp"]; ?>">
				<td align="center" colspan="13" nowrap="nowrap"><?php echo $cancelreason; ?></td>
			</tr>
			<?php
				}
			}
		}
		$new['wes'] = $canceled['wes']-$readmits['wes'];
		$new['net'] = $canceled['net']-$readmits['net'];
		$new['oth'] = $canceled['oth']-$readmits['oth'];

		$readmits['tot'] = array_sum($readmits);
		$new['tot'] = array_sum($new);
		$canceled['tot'] = array_sum($canceled);
//		$referrals['tot'] = array_sum($referrals);
		if($displaydetailtable) {
?>
	</table>
	<br />
<?php
		}
		if($displaysummarytable) {
?>
	<div align="center">
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" >
		<caption>
		Summary by Business Unit 
		</caption>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th>&nbsp;</th>
			<th bgcolor="<?php echo $bgcolor['wes']; ?>">WestStar</th>
			<th bgcolor="<?php echo $bgcolor['net']; ?>">Network</th>
			<th bgcolor="<?php echo $bgcolor['oth']; ?>">Unassigned</th>
			<th>Total</th>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Readmits</th>
			<td><?php echo $readmits['wes']; ?>&nbsp;</td>
			<td><?php echo $readmits['net']; ?>&nbsp;</td>
			<td><?php echo $readmits['oth']; ?>&nbsp;</td>
			<td><?php echo $readmits['tot']; ?>&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">New</th>
			<td><?php echo $new['wes']; ?>&nbsp;</td>
			<td><?php echo $new['net']; ?>&nbsp;</td>
			<td><?php echo $new['oth']; ?>&nbsp;</td>
			<td><?php echo $new['tot']; ?>&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">Total Canceled</th>
			<td><?php echo $canceled['wes']; ?>&nbsp;</td>
			<td><?php echo $canceled['net']; ?>&nbsp;</td>
			<td><?php echo $canceled['oth']; ?>&nbsp;</td>
			<td><?php echo $canceled['tot']; ?>&nbsp;</td>
		</tr>
	</table>
	</div>
	<div align="center">
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" >
		<caption>
		Summary by Cancel Reason
		</caption>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th>&nbsp;</th>
			<th bgcolor="<?php echo $bgcolor['wes']; ?>">WestStar</th>
			<th bgcolor="<?php echo $bgcolor['net']; ?>">Network</th>
			<th bgcolor="<?php echo $bgcolor['oth']; ?>">Unassigned</th>
			<th>Total</th>
		</tr>
<?php
// sort array of reasons
ksort($cancelreasoncodes);
// for each reason code
foreach($cancelreasoncodes as $code=>$description) { 
?>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right"><?php echo "$description ($code)"; ?></th>
			<td><?php echo $cancelreasoncodecount['wes']["$code"]; ?>&nbsp;</td>
			<td><?php echo $cancelreasoncodecount['net']["$code"]; ?>&nbsp;</td>
			<td><?php echo $cancelreasoncodecount['oth']["$code"]; ?>&nbsp;</td>
			<td><?php echo $cancelreasoncodecount['tot']["$code"]; ?>&nbsp;</td>
		</tr>
<?php
}
// list total counts for group
?>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">Totals</th>
			<td><?php if(is_array($cancelreasoncodecount['wes'])) echo array_sum($cancelreasoncodecount['wes']); else echo "0";?>&nbsp;</td>
			<td><?php if(is_array($cancelreasoncodecount['net'])) echo array_sum($cancelreasoncodecount['net']); else echo "0"; ?>&nbsp;</td>
			<td><?php if(is_array($cancelreasoncodecount['oth'])) echo array_sum($cancelreasoncodecount['oth']); else echo "0"; ?>&nbsp;</td>
			<td><?php if(is_array($cancelreasoncodecount['tot'])) echo array_sum($cancelreasoncodecount['tot']); else echo "0"; ?>&nbsp;</td>
		</tr>
	</table>
	</div>
<?php
		}
?>
</div>
</body>
</html>
<?php
	} // result
	else
		dump("query",$query);
} // is_array
?>