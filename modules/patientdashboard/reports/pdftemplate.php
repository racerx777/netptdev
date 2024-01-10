<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
ini_set('max_execution_time', '0');
ini_set('memory_limit', '5G');
$html = "";
function evaluatedates($from=NULL, $thru=NULL) {
	if(!empty($from)) 
		$from=date("Y-m-d 00:00:00", strtotime($from));
	if(!empty($thru))
		$thru=date("Y-m-d 23:59:59", strtotime($thru));
	if(!empty($from) && empty($thru) ) 
		$thru=date("Y-m-d 23:59:59", strtotime($from)); 
	if(!empty($thru) && empty($from) ) 
		$from=date("Y-m-d 00:00:00", strtotime($thru)); 
	return(array('from'=>$from, 'thru'=>$thru));
}

if($_REQUEST['detail'] == 'on')
	$displaydetailtable=TRUE;
else
	$displaydetailtable=FALSE;
if($_REQUEST['summary'] == 'on')
	$displaysummarytable=TRUE;
else
	$displaysummarytable=FALSE;

// Referral Date Range Specified
$ref=evaluatedates($_REQUEST['reffrom'], $_REQUEST['refthru']);
// Appointment Date Range Specified
$appt=evaluatedates($_REQUEST['apptfrom'], $_REQUEST['apptthru']);
// Injury Date Range Specified
$doi=evaluatedates($_REQUEST['doifrom'], $_REQUEST['doithru']);

if(!empty($_REQUEST['casestatus']))
	$casestatus=$_REQUEST['casestatus'];
else
	unset($casestatus);

if($_REQUEST['nopnum'] == 'on')
	$nopnum=TRUE;
else
	$nopnum=FALSE;

if(!empty($_REQUEST['bnum']))
	$bnum=$_REQUEST['bnum'];
else
	unset($bnum);

if(!empty($_REQUEST['cnum']))
	$cnum=$_REQUEST['cnum'];
else
	unset($cnum);


if(
(!empty($ref['from']) && !empty($ref['thru'])) ||
(!empty($appt['from']) && !empty($appt['thru'])) ||
(!empty($doi['from']) && !empty($doi['thru']))
) {
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

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$where=array();
	if( !empty($bnum) ) 
		$where[]="(cmbnum='$bnum') ";

	if( !empty($cnum) ) 
		$where[]="(crcnum='$cnum') ";

	if( !empty($ref['from']) && !empty($ref['thru']) ) 
		$where[]="crdate between '".$ref['from']."' and '".$ref['thru']."'";
	
	if( !empty($appt['from']) && !empty($appt['thru']) ) 
		$where[]="crapptdate between '".$appt['from']."' and '".$appt['thru']."'";
	
	if( !empty($doi['from']) && !empty($doi['thru']) ) 
		$where[]="crinjurydate between '".$doi['from']."' and '".$doi['thru']."'";

	if( !empty($nopnum) ) 
		$where[]="(crpnum IS NULL or crpnum='') ";

	if( !empty($casestatus) ) 
		$where[]="crcasestatuscode = '".$casestatus."'";

	if(count($where)>0)
		$wheresql="WHERE ".implode(" and ", $where);
	else
		unset($wheresql);

	$query= "
SELECT crdate, dmlname,dmfname, dlzip,dlcity, dlphone,crlname, crfname, crpnum, crcasetypecode, crtherapytypecode, crcnum, cmbnum, cmpgmcode, cmname, crsalesid, msmlname, crcasestatuscode, crapptdate, crreadmit, crrelocate, crcancelreasoncode, ccrmdescription, c.upddate, crcanceldate
FROM cases c
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
	$wheresql
ORDER BY cmbnum, cmpgmcode, cmname, crapptdate
";

$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Patient List Report '.displayDate($appt['from']) . " - " . displayDate($appt['thru']).'</title>
</head>
<body>
<div style="float:left"><img src="../wsptn_logo_bw_outline.jpg" width="300px"></div>
<div style="float:right">
	<h1>Patient List Report '.displayDate($appt['from']) . " - " . displayDate($appt['thru']).'</h1>
</div>
<div style="clear:both;">';

	if($result = mysqli_query($dbhandle,$query)) {
if($displaydetailtable) {
   
	$html .= '<table style="text-align:center; border-collapse: collapse; border: solid;table-layout:fixed;" width="100%" >
		<tr>
			<th style="font-size:10px">Appt Date</th>
			<th style="font-size:10px">Physician</th>
			<th style="font-size:10px">City</th>
			<th style="font-size:10px">Zip</th>
			<th style="font-size:10px">Phone</th>
			<th style="font-size:10px">Patient</th>
			<th style="font-size:10px">PNUM</th>
			<th style="font-size:10px">Case Type</th>
			<th style="font-size:10px">Therapy Type</th>
			<th style="font-size:10px">CaseStatus</th>
			<th style="font-size:10px">Referral Date</th>
			<th style="font-size:10px">Clinic Name</th>
			<th style="font-size:10px">Marketer</th>
			<th style="font-size:10px">Readmit Flag</th>
			<th style="font-size:10px">Relocate Flag</th>
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
			$zip = $row['dlzip'];
			$phone = displayPhone($row['Phone']);

			if(!empty($row['crfname']))
				$patient = $row['crlname'] . ', ' . $row['crfname'];
			else
				$patient = $row['crlname'];

			$pnum = $row['crpnum'];
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

		$html .='<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="'.$bgcolor["$grp"].'">
			<td align="center" style="font-size:10px;width:3%" >'.$apptdate.'&nbsp;&nbsp;</td>
			<td align="center" style="font-size:8px;width:2%" nowrap="nowrap">'.$physician.'</td>
			<td align="center" style="font-size:10px;width:3%" >'.$city.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$zip.'</td>
			<td align="center" style="font-size:10px;width:1.5%" >'.$phone.'</td>
			<td align="center" style="font-size:8px;width:2.9%" >'.$patient.'</td>
			<td align="center" style="font-size:8px;width:1.9%" >'.$pnum.'</td>
			<td style="font-size:10px;width:1%">'.$casetype.'</td>
			<td style="font-size:10px;width:1%">'.$therapytype.'</td>
			<td style="font-size:10px;width:1%">'.$casestatus.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$referraldate.'&nbsp;&nbsp;</td>
			<td align="center" style="font-size:8px;width:3%" >'.$clinicname.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$marketer.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$readmit.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$relocate.'</td>
		</tr>';
		
			if(isset($cancelreason)) {

		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="'.$bgcolor["$grp"].'">
			<td align="center" colspan="13" style="font-size:10px;" >'.$cancelreason.'</td>
		</tr>';
		
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

	$html .= '</table>';
	$html .= '<br />';

		}
		if($displaysummarytable) {
			//echo mysqli_num_rows($result);
			if(mysqli_num_rows($result) % 40 >= 25 && $displaydetailtable) {
				$html .= '<span style="page-break-before: always">';
			}

	$html .= '<div align="center">
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" >
		<caption>';
		$html .= 'Patient List Report Summary<br /> 
		</caption>';
		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th>&nbsp;</th>
			<th bgcolor="'.$bgcolor['wes'].'">WestStar</th>
			<th bgcolor="'.$bgcolor['net'].'">Network</th>
			<th bgcolor="'.$bgcolor['oth'].'">Unassigned</th>
			<th>Total</th>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">All Referrals </th>
			<td>'.$referrals['wes'].'&nbsp;</td>
			<td>'.$referrals['net'].'&nbsp;</td>
			<td>'.$referrals['oth'].'&nbsp;</td>
			<td>'.$referrals['tot'].'&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Canceled</th>
			<td>'.$canceled['wes'].'&nbsp;</td>
			<td>'.$canceled['net'].'&nbsp;</td>
			<td>'.$canceled['oth'].'&nbsp;</td>
			<td>'.$canceled['tot'].'&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
			<th align="right">Readmits</th>
			<td>'.$readmits['wes'].'&nbsp;</td>
			<td>'.$readmits['net'].'&nbsp;</td>
			<td>'.$readmits['oth'].'&nbsp;</td>
			<td>'.$readmits['tot'].'&nbsp;</td>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">New</th>
			<td>'.$new['wes'].'&nbsp;</td>
			<td>'.$new['net'].'&nbsp;</td>
			<td>'.$new['oth'].'&nbsp;</td>
			<td>'.$new['tot'].'&nbsp;</td>
		</tr>
	</table>
	</div>
</div> <br /> <br /> Print Date:'.date('Y-m-d');

'</body>
</html>';
 } ?>