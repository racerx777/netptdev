<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$html = "";
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

$tocand = !empty($_REQUEST['tocan'])?displayDate($_REQUEST['tocan']):"none";
$fromcand = !empty($_REQUEST['fromcan'])?displayDate($_REQUEST['fromcan']):"none";

$torefd = !empty($_REQUEST['toref'])?displayDate($_REQUEST['toref']):"none";
$fromrefd = !empty($_REQUEST['fromref'])?displayDate($_REQUEST['fromref']):"none";

$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cancel Reason Report '.displayDate($fromcan) . " - " . displayDate($tocan).'</title>
</head>
<body>
<div style="float:left"><img src="../wsptn_logo_bw_outline.jpg" width="300px"></div>
<div style="float:right;margin-right:100px;">
	<h1>Cancel Reason Report </h1>
	<p>Referal Date from: '.$fromrefd . " to: " . $torefd.'</p>
	<p style="margin-top:-15px">Cancel Date from: '.$fromcand . " to: " . $tocand.'</p>
</div>
<div style="clear:both;">';
	if($result = mysqli_query($dbhandle,$query)) {
if($displaydetailtable) {
   
	$html .= '<table style="text-align:center; border-collapse: collapse; border: solid;table-layout:fixed;" width="100%" >
		<tr>
			<th style="font-size:10px">'.$report['column1'].'</th>
			<th style="font-size:10px">'.$report['column2'].'</th>
			<th style="font-size:10px">Case Status</th>
			<th style="font-size:10px">Cancel Reason</th>
			<th style="font-size:10px">Readmit Flag</th>
			<th style="font-size:10px">Patient</th>
			<th style="font-size:10px">Case Type</th>
			<th style="font-size:10px">Therapy Type</th>
			<th style="font-size:10px">Physician</th>
			<th style="font-size:10px">City</th>
		</tr>';
		
		}
		while($row=mysqli_fetch_assoc($result)) {
			//echo $referraldate = displayDate($row['crdate']);die;
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

		$html .='<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="'.$bgcolor["$grp"].'">
			<td style="font-size:10px;width:1%">'.$column1.'</td>
			<td style="font-size:10px;width:1%">'.$column2.'</td>
			<td style="font-size:10px;width:1%">'.$casestatus.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$cancelreasoncode.'&nbsp;&nbsp;</td>
			<td align="center" style="font-size:10px;width:1%" >'.$readmit.'</td>
			<td align="center" style="font-size:8px;width:1.9%" >'.$patient.'</td>
			<td style="font-size:10px;width:1%">'.$casetype.'</td>
			<td style="font-size:10px;width:1%">'.$therapytype.'</td>
			<td align="center" style="font-size:8px;width:2%" nowrap="nowrap">'.$physician.'</td>
			<td align="center" style="font-size:10px;width:2%" >'.$city.'</td>
		</tr>';
		
			if(isset($cancelreason) && 1==2) {

		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="'.$bgcolor["$grp"].'">
			<td align="center" colspan="13" style="font-size:10px;" >'.$cancelreason.'</td>
		</tr>';
		
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
	}
}
		if($displaydetailtable) {

	$html .= '</table>';
	$html .= '<br />';

		}

		$marginTopsl = "";
		if($displaysummarytable) {
		if(mysqli_num_rows($result) % 44 >= 32) {
			$html .= '<p style="page-break-before: always">';
			$marginTopsl = "margin-top:20px";
		}
	$html .= '<div >
	<table style="border-collapse: collapse; border: solid;" cellpadding="3" >
		<caption style="text-align:left">';
		$html .= 'Summary by Business Unit<br /> 
		</caption>';
		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th>&nbsp;</th>
			<th bgcolor="'.$bgcolor['wes'].'">WestStar</th>
			<th bgcolor="'.$bgcolor['net'].'">Network</th>
			<th bgcolor="'.$bgcolor['oth'].'">Unassigned</th>
			<th>Total</th>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Readmits</th>
			<td>'.$readmits['wes'].'&nbsp;</td>
			<td>'.$readmits['net'].'&nbsp;</td>
			<td>'.$readmits['oth'].'&nbsp;</td>
			<td>'.$readmits['tot'].'&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">New</th>
			<td>'.$new['wes'].'&nbsp;</td>
			<td>'.$new['net'].'&nbsp;</td>
			<td>'.$new['oth'].'&nbsp;</td>
			<td>'.$new['tot'].'&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">New</th>
			<td>'.$new['wes'].'&nbsp;</td>
			<td>'.$new['net'].'&nbsp;</td>
			<td>'.$new['oth'].'&nbsp;</td>
			<td>'.$new['tot'].'&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">Total Canceled</th>
			<td>'.$canceled['wes'].'&nbsp;</td>
			<td>'.$canceled['net'].'&nbsp;</td>
			<td>'.$canceled['oth'].'&nbsp;</td>
			<td>'.$canceled['tot'].'&nbsp;</td>
		</tr>
	</table>
	</div>';
	if(mysqli_num_rows($result) % 44 >= 18 && mysqli_num_rows($result) % 44 <= 30) {
		$html .= '<p style="page-break-before: always">';
	}
	$html .= '<div style="'.$marginTopsl.'">
	<table style="border-collapse: collapse; border: solid;" cellpadding="3" >
		<caption style="text-align:left">
		Summary by Cancel Reason 
		</caption>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th>&nbsp;</th>
			<th bgcolor="'.$bgcolor['wes'].'">WestStar</th>
			<th bgcolor="'.$bgcolor['net'].'">Network</th>
			<th bgcolor="'.$bgcolor['oth'].'">Unassigned</th>
			<th>Total</th>
		</tr>';

// sort array of reasons
ksort($cancelreasoncodes);
// for each reason code
foreach($cancelreasoncodes as $code=>$description) { 

		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">'."$description ($code)".'</th>
			<td>'.$cancelreasoncodecount['wes']["$code"].'&nbsp;</td>
			<td>'.$cancelreasoncodecount['net']["$code"].'&nbsp;</td>
			<td>'.$cancelreasoncodecount['oth']["$code"].'&nbsp;</td>
			<td>'.$cancelreasoncodecount['tot']["$code"].'&nbsp;</td>
		</tr>';
}
 if(is_array($cancelreasoncodecount['wes'])) $cwes = array_sum($cancelreasoncodecount['wes']); else $cwes = "0";
 if(is_array($cancelreasoncodecount['net'])) $cnet = array_sum($cancelreasoncodecount['net']); else $cnet = "0";
 if(is_array($cancelreasoncodecount['oth'])) $coth = array_sum($cancelreasoncodecount['oth']); else $coth = "0";
 if(is_array($cancelreasoncodecount['tot'])) $ctot = array_sum($cancelreasoncodecount['tot']); else $ctot = "0";
// list total counts for group

		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">Totals</th>
			<td>'.$cwes.'&nbsp;</td>
			<td>'.$cnet.'&nbsp;</td>
			<td>'.$coth.'&nbsp;</td>
			<td>'.$ctot.'&nbsp;</td>
		</tr>
	</table>
	</div><br /> <br /> Print Date:'.date('Y-m-d');
'</div>
</body>
</html>';
 } ?>