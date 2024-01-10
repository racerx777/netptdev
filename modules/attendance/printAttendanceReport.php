<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
if($_REQUEST['detail'] == '0')
	$displaydetailtable=FALSE;
else
	$displaydetailtable=TRUE;
if($_REQUEST['summary'] == '0')
	$displaysummarytable=FALSE;
else
	$displaysummarytable=TRUE;
if(!empty($_REQUEST['from']))
	$from=$_REQUEST['from'];
if(!empty($_REQUEST['to']))
	$to=$_REQUEST['to'];
if(!empty($from) && empty($to) ) 
	$to=$from; 
if(!empty($to) && empty($from) ) 
	$from=$to; 
if(!empty($from) && !empty($to)) {
	$bgcolor['wes']="#BBBBBB";
	$bgcolor['net']="#DDDDDD";
	$bgcolor['oth']="#FFFFFF";
	$bgcolor['new']="#999999";
	$referrals=array();
	$readmits=array();
	$relocates=array();
	$new=array();
	$canceled=array();
	$referrals['wes']=0;
	$referrals['net']=0;
	$referrals['oth']=0;
	$referrals['new']=0;
	$readmits['wes']=0;
	$readmits['net']=0;
	$readmits['oth']=0;
	$readmits['new']=0;
	$relocates['wes']=0;
	$relocates['net']=0;
	$relocates['oth']=0;
	$relocates['new']=0;
	$canceled['wes']=0;
	$canceled['net']=0;
	$canceled['oth']=0;
	$canceled['new']=0;
	$new['wes']=0;
	$new['net']=0;
	$new['oth']=0;
	$new['new']=0;

	$fromdate=date("Y-m-d 00:00:00", strtotime($from));
	$todate=date("Y-m-d 23:59:59", strtotime($to));
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query= "
SELECT crdate, dmlname,dmfname, dlzip,dlcity, dlphone,crlname, crfname, crcasetypecode, crtherapytypecode, crcnum, cmname, crsalesid, msmlname, crcasestatuscode, crapptdate, crreadmit, crrelocate, crcancelreasoncode, ccrmdescription, c.upddate, crcanceldate, crphone1
FROM `cases` c
	LEFT JOIN doctors 
	ON crrefdmid = dmid
	LEFT JOIN doctor_locations 
	ON crrefdlid=dlid
	LEFT JOIN master_clinics 
	ON crcnum = cmcnum
	LEFT JOIN master_case_cancelreasoncodes
	ON crcancelreasoncode = ccrmcode
	LEFT JOIN master_sales_marketers
	ON crsalesid = msmcode
	WHERE crapptdate between '$fromdate' and '$todate' and crcasestatuscode='SCH'
ORDER BY cmname, crapptdate
";
	if($result = mysqli_query($dbhandle,$query)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Attendance Report <?php echo(displayDate($fromdate) . " - " . displayDate($todate)) ?></title>
</head>
<body>
<div style="float:left"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
<div style="float:right">
	<h1>Attendance Report <?php echo(displayDate($fromdate) . " - " . displayDate($todate)) ?></h1>
</div>
<div style="clear:both;">
<?php 
		if($displaydetailtable) {
?>
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" width="100%">
		<tr>
			<th>Appt Date</th>
			<th>Physician</th>
			<th>City</th>
			<th>Zip</th>
			<th>Phone</th>
			<th>Patient</th>
			<th>Case Type</th>
			<th>Therapy Type</th>
			<th>CaseStatus</th>
			<th>Referral Date</th>
			<th>Clinic Name</th>
			<th>Marketer</th>
			<th>Readmit Flag</th>
			<th>Relocate Flag</th>
		</tr>
		<?php
		}
		while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$referraldate = displayDate($row['crdate']);

			if(!empty($row['dmfname']))
				$physician = $row['dmlname'] . ', ' . $row['dmfname'];
			else
				$physician = $row['dmlname'];

			$city = $row['dlcity'];
			$zip = $row['dlzip'];
			$phone = displayPhone($row['crphone1']);

			if(!empty($row['crfname']))
				$patient = $row['crlname'] . ', ' . $row['crfname'];
			else
				$patient = $row['crlname'];

			$casetype = $row['crcasetypecode'];
			$therapytype = $row['crtherapytypecode'];
			$cliniccode = $row['crcnum'];
			$clinicname = $row['cmname'];
			$marketer = $row['msmlname'];
			$casestatus = $row['crcasestatuscode'];

			if(!empty($row['crapptdate']))
				$apptdate = displayDate($row['crapptdate']) . " " . displayTime($row['crapptdate']);
			else
				$apptdate = "";

// Prepare Summary Totals
			if(strtolower(substr($clinicname,0,8)) =='weststar') {
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

			$referrals["$grp"]++;
			unset($cancelreason);

//	Count Cancelled
			if($casestatus=='CAN') {
				$cancelreason = 'Canceled on ' . displayDate($row['crcanceldate']) . "-" . $row['ccrmdescription'];
				$canceled["$grp"]++;
			}
// Not Cancelled
			else { 
// ReAdmit
				if($row['crreadmit'] == 1) {
					$readmit = 'Y';
					$readmits["$grp"]++;
				}
				else {
					$readmit = 'N';
// Relocate
					if($row['crrelocate'] == 1) {
						$relocate = 'Y';
						$relocates["$grp"]++;
					}
					else
						$relocate = 'N';
				}
				if($readmit!='Y' && $relocate!='Y') {
// Not Cancelled, relocate or readmit - Must Be New
						$new["$grp"]++;
				}
			}
			if($displaydetailtable) {
?>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="<?php echo $bgcolor["$grp"]; ?>">
			<td align="left" nowrap="nowrap"><?php echo $apptdate; ?>&nbsp;&nbsp;</td>
			<td align="left" nowrap="nowrap"><?php echo $physician; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $city; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $zip; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $phone; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $patient; ?></td>
			<td nowrap="nowrap"><?php echo $casetype; ?></td>
			<td nowrap="nowrap"><?php echo $therapytype; ?></td>
			<td nowrap="nowrap"><?php echo $casestatus; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $referraldate; ?>&nbsp;&nbsp;</td>
			<td align="left" nowrap="nowrap"><?php echo $clinicname; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $marketer; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $readmit; ?></td>
			<td align="left" nowrap="nowrap"><?php echo $relocate; ?></td>
		</tr>
		<?php
			if(isset($cancelreason)) {
?>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="<?php echo $bgcolor["$grp"]; ?>">
			<td align="center" colspan="13" nowrap="nowrap"><?php echo $cancelreason; ?></td>
		</tr>
		<?php
			}
		}
		}
		$new['wes'] = $referrals['wes']-$readmits['wes']-$canceled['wes'];
		$new['net'] = $referrals['net']-$readmits['net']-$canceled['net'];
		$new['oth'] = $referrals['oth']-$readmits['oth']-$canceled['oth'];

		$readmits['tot'] = array_sum($readmits);
		$relocates['tot'] = array_sum($relocates);
		$new['tot'] = array_sum($new);
		$canceled['tot'] = array_sum($canceled);
		$referrals['tot'] = array_sum($referrals);
	}
}
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
		Attendance Report Summary<br /><?php echo(displayDate($fromdate) . " - " . displayDate($todate)) ?>
		</caption>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th>&nbsp;</th>
			<th bgcolor="<?php echo $bgcolor['wes']; ?>">WestStar</th>
			<th bgcolor="<?php echo $bgcolor['net']; ?>">Network</th>
			<th bgcolor="<?php echo $bgcolor['oth']; ?>">Unassigned</th>
			<th>Total</th>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">All Referrals </th>
			<td><?php echo $referrals['wes']; ?>&nbsp;</td>
			<td><?php echo $referrals['net']; ?>&nbsp;</td>
			<td><?php echo $referrals['oth']; ?>&nbsp;</td>
			<td><?php echo $referrals['tot']; ?>&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Canceled</th>
			<td><?php echo $canceled['wes']; ?>&nbsp;</td>
			<td><?php echo $canceled['net']; ?>&nbsp;</td>
			<td><?php echo $canceled['oth']; ?>&nbsp;</td>
			<td><?php echo $canceled['tot']; ?>&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Readmits</th>
			<td><?php echo $readmits['wes']; ?>&nbsp;</td>
			<td><?php echo $readmits['net']; ?>&nbsp;</td>
			<td><?php echo $readmits['oth']; ?>&nbsp;</td>
			<td><?php echo $readmits['tot']; ?>&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Relocates</th>
			<td><?php echo $relocates['wes']; ?>&nbsp;</td>
			<td><?php echo $relocates['net']; ?>&nbsp;</td>
			<td><?php echo $relocates['oth']; ?>&nbsp;</td>
			<td><?php echo $relocates['tot']; ?>&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">New</th>
			<td><?php echo $new['wes']; ?>&nbsp;</td>
			<td><?php echo $new['net']; ?>&nbsp;</td>
			<td><?php echo $new['oth']; ?>&nbsp;</td>
			<td><?php echo $new['tot']; ?>&nbsp;</td>
		</tr>
	</table>
	</div>
<?php
		}
?>
</div>
</body>
</html>
