<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$html = "";
ini_set('max_execution_time', '0');
ini_set('memory_limit', '5G');
if($_REQUEST['detail'] == '0')
	$displaydetailtable=FALSE;
else
	$displaydetailtable=TRUE;

if($_REQUEST['summary'] == '0')
	$displaysummarytable=FALSE;
else
	$displaysummarytable=TRUE;

if(!empty($_REQUEST['scheduler']))
	$wherescheduler= " and crapptscheduler = '" . $_REQUEST['scheduler'] . "'";
else
	unset($wherescheduler);

if(!empty($_REQUEST['from']))
	$from=$_REQUEST['from'];

if(!empty($_REQUEST['to']))
	$to=$_REQUEST['to'];

if(!empty($from) && empty($to) ) 
	$to=$from; 

if(!empty($to) && empty($from) ) 
	$from=$to; 

if(!empty($from) && !empty($to)) {
	$bgcolor['report']="#FFFFFF";
// count scheduler referrals, sch/act, can, readmits
	$schedulersummary=array();
	$fromdate=date("Y-m-d 00:00:00", strtotime($from));
	$todate=date("Y-m-d 23:59:59", strtotime($to));
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query= "
SELECT bumseq, bumname, pgmbumcode, pgmcode, pgmname, crdate, crapptscheduler, crapptscheduleddate, crapptdate, crcnum, cmname, crlname, crfname, crcasetypecode, crtherapytypecode, crcasestatuscode,  crreadmit, crcancelreasoncode, ccrmdescription, c.upddate, crcanceldate, timediff(crapptscheduleddate, c.crtdate) as hrstosch, timediff(crapptdate, c.crtdate) as hrstoappt
FROM cases c
	LEFT JOIN case_scheduling_queue csq 
	ON crid = csqcrid
	LEFT JOIN master_case_cancelreasoncodes crcm
	ON crcancelreasoncode = ccrmcode
	LEFT JOIN master_clinics
	ON crcnum = cmcnum
		LEFT JOIN master_provider_groups
		ON cmpgmcode = pgmcode
			LEFT JOIN master_business_units
			ON pgmbumcode=bumcode
WHERE c.crdate between '$fromdate' and '$todate' $wherescheduler
ORDER BY bumseq, crcasestatuscode, crreadmit, pgmname, cmcnum, crcancelreasoncode, crapptscheduler, crapptscheduleddate
";

$tod = !empty($_REQUEST['to'])?displayDate($_REQUEST['to']):"none";
$fromd = !empty($_REQUEST['from'])?displayDate($_REQUEST['from']):"none";

$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Scheduling Performance Report '.displayDate($fromdate) . " - " . displayDate($todate).'</title>
</head>
<body>
<div style="float:left"><img src="../wsptn_logo_bw_outline.jpg" width="300px"></div>
<div style="float:right;margin-right:100px;">
	<h1>Scheduling Performance Report </h1>
	<p>Date from: '.$fromd . " to: " . $tod.'</p>
</div>
<div style="clear:both;">';
if($result = mysqli_query($dbhandle,$query)) {
	if($displaydetailtable) {
   
	$html .= '<table style="text-align:center;border-collapse: collapse; border: solid;table-layout:fixed;" width="100%" >
		<tr>
			<th style="font-size:10px">Clinic Name</th>
			<th style="font-size:10px">CaseStatus</th>
			<th style="font-size:10px">Readmit Flag</th>
			<th style="font-size:10px">Scheduler</th>
			<th style="font-size:10px">Referral Date</th>
			<th style="font-size:10px">Scheduled Date</th>
			<th style="font-size:10px">Appt Date</th>
			<th style="font-size:10px">Patient</th>
			<th style="font-size:10px">Case Type</th>
			<th style="font-size:10px">Therapy Type</th>
		</tr>';
		
		}
		while($row=mysqli_fetch_assoc($result)) {
			//echo $referraldate = displayDate($row['crdate']);die;

			if(!empty($row['crapptscheduler']))
				$scheduler = $row['crapptscheduler'];
			else
				$scheduler = 'UNASSIGNED';

			$referraldate = displayDate($row['crdate']);
			$scheduleddate = displayDate($row['crapptscheduleddate']);
			if(!empty($row['crapptdate']))
				$apptdate = displayDate($row['crapptdate']) . " " . displayTime($row['crapptdate']);
			else
				$apptdate = "";

			unset($bu);
			unset($bumname);
			unset($clinicname);
			if(!empty($row['crcnum'])) {
				if(!empty($row['pgmbumcode'])) {
					$bu = $row['pgmbumcode'];
					$bumname = $row['bumname'];
					$clinicname = "$bu-" . $row['crcnum'] . "-" . $row['cmname'];
				}
				else {
					$bu = " UNKNOWN BU/CLINIC " . $row['crcnum'];
					$bumname = ' No BU/Clinic Relation ' . $row['crcnum'];
					$clinicname = "$bu-'" . $row['crcnum'] . "'";
				}
			}
			else {
				$bu=" UNASSIGNED CLINIC";
				$bumname = ' No Clinic Assigned';
				$clinicname = "$bu";
			}

			if(!empty($row['crfname']))
				$patient = $row['crlname'] . ', ' . $row['crfname'];
			else
				$patient = $row['crlname'];

			$casetype = $row['crcasetypecode'];
			$therapytype = $row['crtherapytypecode'];
			$casestatus = $row['crcasestatuscode'];
			unset($cancelreason);

			$bus["$bu"]=$bumname;
			$schedulers["$scheduler"]=1;

			$totalref["$bu"]++;
			$schedulertotal["$scheduler"]['REFTOTAL']["$bu"]++;

			// Total Canned 
			if($casestatus=='CAN') {
				$totalcanned["$bu"]++;
				$schedulertotal["$scheduler"]['CANTOTAL']["$bu"]++;
				$cancelreason = 'Canceled on ' . displayDate($row['crcanceldate']) . "-" . $row['ccrmdescription'];
			}

			$referraldatetime = strtotime($row['crdate']);
			$scheduleddatetime = strtotime($row['crapptscheduleddate']);
			$apptdatetime = strtotime($row['crapptdate']);
			$hrstosch = $row['hrstosch'];
			$hrstoappt = $row['hrstoappt'];
			if($row['crreadmit'] == 1) {
				$readmit = 'Y';
				$totalreadmits["$bu"]++;
			}
			else {
				$readmit = 'N';
				if($casestatus=='SCH' || $casestatus=='ACT') {
					if(!empty($apptdatetime) && $apptdatetime > $referraldatetime) {
						$appts["$scheduler"][]=$hrstoappt;
					}
					if(!empty($scheduleddatetime) && $scheduleddatetime > $referraldatetime) {
						$schs["$scheduler"][]=$hrstosch;
					}
					$totalnew["$casestatus"]["$bu"]++;
				}
				else {
					$totalnew['OTH']["$bu"]++;
				}
			}

			if($casestatus=='SCH') {
				if($row['crreadmit'] == 1) 
					$schedulertotal["$scheduler"]['SCHREA']["$bu"]++;
				else
					$schedulertotal["$scheduler"]['SCHNEW']["$bu"]++;
			}
			else {
				if($casestatus=='ACT') {
					if($row['crreadmit'] == 1) 
						$schedulertotal["$scheduler"]['ACTREA']["$bu"]++;
					else
						$schedulertotal["$scheduler"]['ACTNEW']["$bu"]++;
				}
				else 
						$schedulertotal["$scheduler"]['OTHER']["$bu"]++;					
			}

			if($displaydetailtable) {

				$html .='<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" >
					<td align="center" style="font-size:8px;width:3%" >'.$clinicname.'</td>
					<td style="font-size:10px;width:1%">'.$casestatus.'</td>
					<td align="center" style="font-size:10px;width:1%" >'.$readmit.'</td>';

					if(isset($cancelreason)) {
						$html .= '<td colspan="4" align="left" style="font-size:10px;width:10%" nowrap="nowrap">'.$cancelreason.'</td>';
					}else{
						$html .= '<td align="center" style="font-size:10px;width:1%" >'.$scheduler.'</td>
								  <td align="center" style="font-size:10px;width:1%" >'.$referraldate.'&nbsp;&nbsp;</td>
								  <td align="center" style="font-size:10px;width:1%" >'.$scheduleddate.'&nbsp;&nbsp;</td>
								  <td align="center" style="font-size:10px;width:2%" >'.$apptdate.'&nbsp;&nbsp;</td>';
					}

					$html .= '<td align="center" style="font-size:8px;width:1.9%" >'.$patient.'</td>
					<td style="font-size:10px;width:1%">'.$casetype.'</td>
					<td style="font-size:10px;width:1%">'.$therapytype.'</td>
				</tr>';
		
			}
		}
	}
}
if($displaydetailtable) {
	$html .= '</table>';
	$html .= '<br />';
}
if($displaysummarytable) {

		if(mysqli_num_rows($result) % 46 >= 32 && $displaydetailtable) {
			$html .= '<span style="page-break-before: always"></p>';
		}
		$html .= '<div align="center">
		<table cellpadding="0" cellspacing="0" border="1" width="720px">
			<caption style="text-align:left;">
			Scheduling Performance Report Summary 
			</caption>
			<caption style="text-align:left;">
			'.displayDate($fromdate) . " - " . displayDate($todate).'
			</caption>
			<tr>
				<td><table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" width="100%">
						<tr style="border-bottom-color:#FFFFFF; border-top-color:#CCCCCC; border:solid;">
							<th nowrap="nowrap">Business Unit Summary</th>
							<th nowrap="nowrap">Total Referrals</th>
							<th nowrap="nowrap">- Re-Admits</th>
							<th nowrap="nowrap">= New Referrals</th>
							<th nowrap="nowrap">Scheduled</th>
							<th nowrap="nowrap">Active</th>
							<th nowrap="nowrap">Sch+Act</th>
							<th nowrap="nowrap">Conversion</th>
							<th nowrap="nowrap">Canned</th>
						</tr>';
						
					ksort($bus);
					foreach($bus as $bucode=>$val) { 
						if(is_numeric($totalref["$bucode"])) 
							$tref = $totalref["$bucode"];
						else
							$tref = 0;
						if(is_numeric($totalreadmits["$bucode"]))
							$trea = $totalreadmits["$bucode"];
						else
							$trea = 0;

						$tnew = $tref-$trea;

						if(is_numeric($totalnew['SCH']["$bucode"]))
							$tsch = $totalnew['SCH']["$bucode"];
						else
							$tsch = 0;

						if(is_numeric($totalnew['ACT']["$bucode"]))
							$tact = $totalnew['ACT']["$bucode"];
						else
							$tact = 0;

						if($tnew > 0) {
							$tschact = $tsch+$tact;
							$tcon = round(($tschact/$tnew*100),2);
						}
						else
							$tcon = "ERROR";

						if(is_numeric($totalcanned["$bucode"]))
							$tcan = $totalcanned["$bucode"];
						else
							$tcan = 0;

						$html .= '<tr style="border-bottom-color:#FFFFFF; border-top-color:#CCCCCC; border:solid;">
							<th align="right">'.$bus["$bucode"].'</th>
							<td align="right">'.$tref.'</td>
							<td align="right">'.$trea.'</td>
							<td align="right">'.$tnew.'</td>
							<td align="right">'.$tsch.'</td>
							<td align="right">'.$tact.'</td>
							<td align="right">'.$tschact.'</td>
							<td align="right">'.$tcon.'</td>
							<td align="right">'.$tcan.'</td>
						</tr>';

					} 
						if(is_array($totalref))
							$grandtotalref = array_sum($totalref);
						else
							$grandtotalref = 0;
						if(is_array($totalreadmits))
							$grandtotalreadmits = array_sum($totalreadmits);
						else
							$grandtotalreadmits = 0;
						$grandtotalnew = $grandtotalref-$grandtotalreadmits;
						if(is_array($totalnew['SCH']))
							$grandtotalscheduled = array_sum($totalnew['SCH']);
						else 
							$grandtotalscheduled = 0;
						if(is_array($totalnew['ACT']))
							$grandtotalactive = array_sum($totalnew['ACT']);
						else 
							$grandtotalactive = 0;
						$grandtotalschact = $grandtotalscheduled+$grandtotalactive;
						if($grandtotalnew!=0)
							$grandtotalconversion = $grandtotalschact/$grandtotalnew*100;
						else
							$grandtotalconversion = 'ERROR';
						if(is_array($totalcanned))
							$grandtotalcanned = array_sum($totalcanned);
						else
							$grandtotalcanned = 0;
					
						$html .= '<tr>
							<th nowrap="nowrap" align="right">'."TOTAL:".'</th>
							<td nowrap="nowrap" align="right">'.$grandtotalref.'</td>
							<td nowrap="nowrap" align="right">'.$grandtotalreadmits.'</td>
							<td nowrap="nowrap" align="right">'.$grandtotalnew.'</td>
							<td nowrap="nowrap" align="right">'.$grandtotalscheduled.'</td>
							<td nowrap="nowrap" align="right">'.$grandtotalactive.'</td>
							<td nowrap="nowrap" align="right">'.$grandtotalschact.'</td>
							<td nowrap="nowrap" align="right">'.round($grandtotalconversion,2) . "%".'</td>
							<td nowrap="nowrap" align="right">'.$grandtotalcanned.'</td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>';

			$html .= '<tr style="border-collapse:collapse; border:solid;">
				<td><table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" width="100%">
						<tr>
							<th rowspan="3" valign="bottom" align="left">Schedulers Summary</th>
							<th rowspan="3" valign="bottom" bgcolor="'.$bgcolor['report'].'">OTH</th>
							<th align="center" colspan="4">Network</th>
							<th align="center" colspan="4">WestStar</th>
							<th valign="bottom" align="center" rowspan="3">Total</th>
							<th valign="bottom" align="center" rowspan="3">CAN</th>
							<th valign="bottom" align="center" rowspan="3">Hrs<br />To<br />Sch</th>
							<th valign="bottom" align="center" rowspan="3">Hrs<br />To<br />Appt</th>
						</tr>
						<tr>
							<th align="center" colspan="2">Scheduled</th>
							<th align="center" colspan="2">Seen</th>
							<th align="center" colspan="2">Scheduled</th>
							<th align="center" colspan="2">Seen</th>
						</tr>
						<tr>
							<th align="right">New</th>
							<th align="right">RA</th>
							<th align="right">New</th>
							<th align="right">RA</th>
							<th align="right">New</th>
							<th align="right">RA</th>
							<th align="right">New</th>
							<th align="right">RA</th>
						</tr>';
						
			ksort($schedulers);
			foreach($schedulers as $scheduler=>$value) { 
				if(is_array($schedulertotal["$scheduler"]['REFTOTAL'])) 
					$grandtotalref = array_sum($schedulertotal["$scheduler"]['REFTOTAL']);
				else
					$grandtotalref = 0;
				if(is_array($schedulertotal["$scheduler"]['CANTOTAL'])) 
					$grandtotalcanned = array_sum($schedulertotal["$scheduler"]['CANTOTAL']);
				else
					$grandtotalcanned = 0;
				if(is_array($schedulertotal["$scheduler"]['OTHER']))
					$grandtotalother = array_sum($schedulertotal["$scheduler"]['OTHER']);
				else
					$grandtotalother = 0;
				if(is_numeric($schedulertotal["$scheduler"]['SCHNEW']['NET']))
					$grandtotalschnewnw = $schedulertotal["$scheduler"]['SCHNEW']['NET'];
				else
					$grandtotalschnewnw = 0;
				if(is_numeric($schedulertotal["$scheduler"]['SCHREA']['NET']))
					$grandtotalschreanw = $schedulertotal["$scheduler"]['SCHREA']['NET'];
				else
					$grandtotalschreanw = 0;
				if(is_numeric($schedulertotal["$scheduler"]['ACTNEW']['NET']))
					$grandtotalactnewnw = $schedulertotal["$scheduler"]['ACTNEW']['NET'];
				else
					$grandtotalactnewnw = 0;
				if(is_numeric($schedulertotal["$scheduler"]['ACTREA']['NET']))
					$grandtotalactreanw = $schedulertotal["$scheduler"]['ACTREA']['NET'];
				else
					$grandtotalactreanw = 0;
				if(is_numeric($schedulertotal["$scheduler"]['SCHNEW']['WS']))
					$grandtotalschnewws = $schedulertotal["$scheduler"]['SCHNEW']['WS'];
				else
					$grandtotalschnewws = 0;
				if(is_numeric($schedulertotal["$scheduler"]['SCHREA']['WS']))
					$grandtotalschreaws = $schedulertotal["$scheduler"]['SCHREA']['WS'];
				else
					$grandtotalschreaws = 0;
				if(is_numeric($schedulertotal["$scheduler"]['ACTNEW']['WS']))
					$grandtotalactnewws = $schedulertotal["$scheduler"]['ACTNEW']['WS'];
				else
					$grandtotalactnewws = 0;
				if(is_numeric($schedulertotal["$scheduler"]['ACTREA']['WS']))
					$grandtotalactreaws = $schedulertotal["$scheduler"]['ACTREA']['WS'];
				else
					$grandtotalactreaws = 0;
				if(is_array($appts["$scheduler"])) {
					$cappt["$scheduler"]=count($appts["$scheduler"]);
					$sappt["$scheduler"]=array_sum($appts["$scheduler"]);
					if($sappt["$scheduler"] > 0)
						$apptavg=round($sappt["$scheduler"]/$cappt["$scheduler"], 1);
					else
						$apptavg="";
				}
				else
					$apptavg="";
				if(is_array($schs["$scheduler"])) {
					$csch["$scheduler"]=count($schs["$scheduler"]);
					$ssch["$scheduler"]=array_sum($schs["$scheduler"]);
					if($ssch["$scheduler"]>0)
						$schsavg=round($ssch["$scheduler"]/$csch["$scheduler"], 1);
					else
						$schsavg="";
				}
				else
					$schsavg="";


						$html .= '<tr style="border-bottom-color:#FFFFFF; border-top-color:#CCCCCC; border:solid;">
							<th align="right">'.$scheduler.'</th>
							<td align="right">'.$grandtotalother.'</td>
							<td align="right">'.$grandtotalschnewnw.'</td>
							<td align="right">'.$grandtotalschreanw.'</td>
							<td align="right">'.$grandtotalactnewnw.'</td>
							<td align="right">'.$grandtotalactreanw.'</td>
							<td align="right">'.$grandtotalschnewws.'</td>
							<td align="right">'.$grandtotalschreaws.'</td>
							<td align="right">'.$grandtotalactnewws.'</td>
							<td align="right">'.$grandtotalactreaws.'</td>
							<td align="right">'.$grandtotalref.'</td>
							<td align="right">'.$grandtotalcanned.'</td>
							<td align="right">'.$schsavg.'</td>
							<td align="right">'.$apptavg.'</td>
						</tr>';
						
				$stother += $grandtotalother;
				$stschnewnw += $grandtotalschnewnw;
				$stschreanw += $grandtotalschreanw;
				$stactnewnw += $grandtotalactnewnw;
				$stactreanw += $grandtotalactreanw;
				$stschnewws += $grandtotalschnewws;
				$stschreaws += $grandtotalschreaws;
				$stactnewws += $grandtotalactnewws;
				$stactreaws += $grandtotalactreaws;
				$stref += $grandtotalref;
				$stcanned += $grandtotalcanned;
			}
			$stschsavg=round(array_sum($ssch)/array_sum($csch), 1);
			$stapptavg=round(array_sum($sappt)/array_sum($cappt), 1);

						$html .= '<tr style="border-bottom-color:#FFFFFF; border-top-color:#CCCCCC; border:solid;">
							<th align="right">'."TOTAL:".'</th>
							<td align="right">'.$stother.'</td>
							<td align="right">'.$stschnewnw.'</td>
							<td align="right">'.$stschreanw.'</td>
							<td align="right">'.$stactnewnw.'</td>
							<td align="right">'.$stactreanw.'</td>
							<td align="right">'.$stschnewws.'</td>
							<td align="right">'.$stschreaws.'</td>
							<td align="right">'.$stactnewws.'</td>
							<td align="right">'.$stactreaws.'</td>
							<td align="right">'.$stref.'</td>
							<td align="right">'.$stcanned.'</td>
							<td align="right">'.$stschsavg.'</td>
							<td align="right">'.$stapptavg.'</td>
						</tr>
					</table></td>
			</tr>
		</table>
	</div>';
 } 
'</div>
</body>
</html>';