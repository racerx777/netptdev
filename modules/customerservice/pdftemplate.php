<?php
function sessionstart() {
	session_start();
	$_SESSION['ready'] = time();
	//$_SESSION['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);

	// if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") != false)  
	// 	$_SESSION['iphone'] = '1';
	// else
	// 	unset($_SESSION['iphone']);

	// if (strpos($_SERVER['HTTP_USER_AGENT'],"Mobile") != false)  
	// 	$_SESSION['mobile'] = '1';
	// else
	// 	unset($_SESSION['mobile']);

	require_once('../../common/debug.php');
	require_once('../../common/user.php');
	require_once('../../common/sitedivs.php');
	require_once('../../common/appdata.php');
}
//	session_set_cookie_params(120*60);
sessionstart();
$myServer = "localhost";
$myDB = "netptwsp_netwsptn";
$myUser = "netptwsp_netwsptn";
$myPass = "mVa0}*.HS)b?";
$dbhandle = mysqli_connect($myServer, $myUser, $myPass,$myDB);


ini_set('max_execution_time', '0');
ini_set('memory_limit', '5G');
$html = "";
$wheredoctor = "";
$readmit = "";
$referraldate = "";
$casestatuscodewhere = "";
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
if(!empty($_REQUEST['casestatuscode']))
	$casestatuscode=$_REQUEST['casestatuscode'];
else
	unset($casestatuscode);

if(!empty($from) && empty($to) ) 
	$to=$from; 
if(!empty($to) && empty($from) ) 
	$from=$to; 


if(!empty($from) && !empty($to)) {
	if(!empty($_REQUEST['refdmid']))
		$wheredoctor = " and crrefdmid='". $_REQUEST['refdmid'] ."' ";
if(!empty($casestatuscode))
	$casestatuscodewhere=" and crcasestatuscode='$casestatuscode'";
else
	//unset($casestatuscodewhere);

	$bgcolor['wes']="#BBBBBB";
	$bgcolor['net']="#DDDDDD";
	$bgcolor['oth']="#FFFFFF";
	$bgcolor['new']="#999999";
	$referrals=array();
	$readmits=array();
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

	
	 $query= "
SELECT crdate, dmlname,dmfname, dlzip,dlcity, dlphone,crlname, crfname, crcasetypecode, crtherapytypecode, crcnum, cmname, crsalesid, msmlname, crcasestatuscode, crapptdate, crreadmit, crcancelreasoncode, ccrmdescription, c.upddate, crcanceldate, crphone1
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
	WHERE c.crdate between '$fromdate' and '$todate' $wheredoctor $casestatuscodewhere
ORDER BY crsalesid, dmlname, dmfname, dlcity, dlzip, crcasestatuscode
";


$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Patient Status Report '.displayDate($fromdate) . " - " . displayDate($todate).'</title>
</head>
<body>
<div style="float:left"><img src="wsptn logo bw outline.jpg" width="300px"></div>
<div style="float:right">
	<h1>Patient Status Report '.displayDate($fromdate) . " - " . displayDate($todate).'</h1>
</div>
<div style="clear:both;">';
	if($result = mysqli_query($dbhandle,$query)) {
if($displaydetailtable) {
   
	$html .= '<table style="text-align:center; border-collapse: collapse; border: solid;table-layout:fixed;" width="100%" >
		<tr>
			<th style="font-size:10px">Referral Date</th>
			<th style="font-size:10px">Physician</th>
			<th style="font-size:10px">City</th>
			<th style="font-size:10px">Zip</th>
			<th style="font-size:10px">Phone</th>
			<th style="font-size:10px">Patient</th>
			<th style="font-size:10px">Case Type</th>
			<th style="font-size:10px">Therapy Type</th>
			<th style="font-size:10px">CaseStatus</th>
			<th style="font-size:10px">Appt Date</th>
			<th style="font-size:10px">Clinic Name</th>
			<th style="font-size:10px">Marketer</th>
			<th style="font-size:10px">Readmit Flag</th>
		</tr>';
		
		}
		while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			//echo $referraldate = displayDate($row['crdate']);die;

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
			if(substr(strtolower($clinicname),0,8) =='weststar') {
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
// Not Cancelled or Readmit - Must Be New
					$readmit = 'N';
					$new["$grp"]++;
				}
			}
			if($displaydetailtable) {

		$html .='<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="'.$bgcolor["$grp"].'">
			<td align="center" style="font-size:10px;width:1%" >'.$referraldate.'&nbsp;&nbsp;</td>
			<td align="center" style="font-size:8px;width:2%" nowrap="nowrap">'.$physician.'</td>
			<td align="center" style="font-size:10px;width:2%" >'.$city.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$zip.'</td>
			<td align="center" style="font-size:10px;width:1.5%" >'.$phone.'</td>
			<td align="center" style="font-size:8px;width:1.9%" >'.$patient.'</td>
			<td style="font-size:10px;width:1%">'.$casetype.'</td>
			<td style="font-size:10px;width:1%">'.$therapytype.'</td>
			<td style="font-size:10px;width:1%">'.$casestatus.'</td>
			<td align="center" style="font-size:10px;width:2%" >'.$apptdate.'&nbsp;&nbsp;</td>
			<td align="center" style="font-size:8px;width:3%" >'.$clinicname.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$marketer.'</td>
			<td align="center" style="font-size:10px;width:1%" >'.$readmit.'</td>
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

	$html .= '<div align="center">
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" >
		<caption>';
		$html .= 'Patient Status Report Summary<br /> 
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
</div>
</body>
</html>';
 } ?>