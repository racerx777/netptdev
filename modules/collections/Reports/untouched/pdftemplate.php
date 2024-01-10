<?php
set_time_limit(0);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
$html = "";
ini_set('max_execution_time', '0');
ini_set('memory_limit', '5G');
if(!empty($_REQUEST['days']) || $_REQUEST['days']==0)   // number of days since last touched
	$days=$_REQUEST['days']+0;
else
	$days=60;

if(!empty($days) || $days==0) {
	$today=today();
	$daysdate = date("Y-m-d", strtotime($today . " -" . $days . " day"));
	$dbfromdate=dbDate($daysdate,'Y-m-d')." 00:00:00";

	$excludedate = date("Y-m-d",strtotime($today . " -1 day"));
	$dbexcludedate=dbDate($excludedate,'Y-m-d')." 00:00:00";
}
else {
	echo "Number of Days cannot be empty/zero.";
	exit();
}

$start = 1;
$end = 100;
if(isset($_REQUEST['start'])) {
	$start = $_REQUEST['start'];
}
if(isset($_REQUEST['end'])) {
	$end = $_REQUEST['end'];
}

if(isset($_REQUEST['userid'])) // If selected a user translate to queue
	$userid=$_REQUEST['userid'];
else
	unset($userid);

if(isset($_REQUEST['printnotes']))
	$printnotes=$_REQUEST['printnotes'];
else
	$printnotes='none';

if(isset($_REQUEST['mintbal']))
	$mintbal=$_REQUEST['mintbal']+0;
else
	$mintbal=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/user.options.php');
$thisuser=getuser();

$where=array();

// if it is not Sunni, Vidal or constance reset user id to current user id
if (!isuserlevel(34)) {
    $userid=getuserid();
}
$queue = "";
if(!empty($userid)) {
	$userinformation=getUserInformation($userid);
	$umuser=$userinformation['umuser'];
	if(isset($umuser)) {
		$queue=getUserQueueAssignment($umuser);
//		$where[]="cqauser='$umuser'";
		$where[]="cqgroup='$queue'";
	}
}



$where[]="(ca.upddate IS NULL or ca.upddate < '$dbfromdate')";
$where[]="(ca.crtdate IS NULL or ca.crtdate < '$dbexcludedate')";
//$where[]="( cqschcalldate IS NULL OR cqschcalldate < DATE_SUB( NOW( ) , INTERVAL 1 DAY ) )";
//$where[]="tbal > $mintbal and t30+t60+t90+t120>0";
//$where[]="cqauser IS NOT NULL";

if(count($where)>0)
	$wheresql = "WHERE ".implode(' AND ', $where);

$select="
SELECT caid, cabnum, capnum, ca.crtdate crtdate, ca.upddate upddate, cqgroup, cqpriority, cqschcalldate, tbal, t30+t60+t90+t120 pastdue
FROM collection_accounts ca
LEFT JOIN collection_queue q ON caid = cqcaid
LEFT JOIN ( SELECT pnum, t30, t60, t90, t120, tbal FROM PTOS_Patients WHERE tbal>$mintbal and t30+t60+t90+t120>0 ) pat1 ON capnum=pnum
$wheresql ORDER BY cqgroup, cabnum, upddate, cqschcalldate, crtdate
";

$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<div style="float:right">
	<p>Days: '.$days.'</p>
</div>
<div style="clear:both;">';

$html .= '<table border="0" cellpadding="2">';

$html .= '
	<tr >
		<td style="border: 1px solid #ccc;"><strong>Queue</strong></td>
		<td style="border: 1px solid #ccc;"><strong>#</strong></td>
		<td style="border: 1px solid #ccc;"><strong>Bus</strong></td>
		<td style="border: 1px solid #ccc;"><strong>Account</strong></td>
		<td style="border: 1px solid #ccc;"><strong>Pty</strong></td>
		<td style="border: 1px solid #ccc;"><strong>last touch Date</strong></td>
		<td style="border: 1px solid #ccc;"><strong>next touch Date</strong></td>
		<td style="border: 1px solid #ccc;"><strong>Create Date</strong></td>
		<td style="border: 1px solid #ccc;"><strong>Balance</strong></td>';

if($printnotes!='none') {
		$html .= '<td style="border: 1px solid #ccc;"><strong>Note Date</strong></td>';
		$html .= '<td style="border: 1px solid #ccc;"><strong>User</strong></td>';
		$html .= '<td style="border: 1px solid #ccc;"><strong>Button</strong></td>';
		$html .= '<td style="border: 1px solid #ccc;"><strong>Note</strong></td>';
}
$html .= '</tr>';
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$reportcount=0;
$groupsaved = 0;
if($result=mysqli_query($dbhandle,$select)) {

	while($row=mysqli_fetch_assoc($result)) {
		
		$reportcount++;
		if($groupsaved == $row['cqgroup']) {
			$groupcount++;
			$groupprint=$row['cqgroup'];
		}
		else {
			$groupcount=1;
			$groupprint=$row['cqgroup'];
			$groupsaved=$row['cqgroup'];
		}

		if($bnumsaved == $row['cabnum']) {
			$bnumcount++;
			$bnumprint=$row['cabnum'];
		}
		else {
			$bnumcount=1;
			$bnumprint=$row['cabnum'];
			$bnumsaved=$row['cabnum'];
		}

		$total=$total+$row['tbal'];
		$numrows=0;
		if($printnotes!='none') {
			if($printnotes=='last') $limit="LIMIT 1";
			$notes="SELECT nopnum, nobutton, nonote, crtdate, crtuser FROM notes WHERE nopnum='".$row['capnum']."' order by crtdate desc $limit";
			if($noteresult=mysqli_query($dbhandle,$notes))
				$numrows=mysqli_num_rows($noteresult);
		}
		
	$html .= '<tr>
		<td style="border: 1px solid #ccc;">'. $groupprint.'</td>
		<td style="border: 1px solid #ccc;">'.$groupcount.'</td>
		<td style="border: 1px solid #ccc;">'.$bnumprint.'</td>
		<td style="border: 1px solid #ccc;">'.$row['capnum'].'</td>
		<td style="border: 1px solid #ccc;">'.$row['cqpriority'].'</td>
		<td style="border: 1px solid #ccc;">'.displayDate($row['upddate']).'</td>
		<td style="border: 1px solid #ccc;">'.displayDate($row['cqschcalldate']).'</td>
		<td style="border: 1px solid #ccc;">'.displayDate($row['crtdate']).'</td>
		<td style="border: 1px solid #ccc;">'.$row['tbal'].'</td>';


		if($numrows==0) { // none
			if($printnotes!='none') {

			$html .= '<td style="border: 1px solid #ccc;">'."&nbsp;".'</td>
			<td style="border: 1px solid #ccc;">'. "&nbsp;".'</td>
			<td style="border: 1px solid #ccc;">'. "&nbsp;".'</td>
			<td style="border: 1px solid #ccc; font-size:4px;">'. "&nbsp;".'</td>';

			}
		}
		else {
			while($noterow=mysqli_fetch_assoc($noteresult)) {

		$html .= '
			<td style="border: 1px solid #ccc;">'. displayDate($noterow['crtdate']).'</td>
			<td style="border: 1px solid #ccc;">'. $noterow['crtuser'].'</td>
			<td style="border: 1px solid #ccc;">'. $noterow['nobutton'].'</td>
			<td style="border: 1px solid #ccc;">'. $noterow['nonote'].'</td>
		';

			}
		}
		$html .= '</tr>';
		if($reportcount%5000 == 0)
			$html .= '</tr></table>Break<table>';
	}

	mysqli_close($dbhandle);

	$html .= '<tr>
		<td colspan="8">'.$reportcount.' Total Untouched</td>
		<td>'. displayCurrency($total,',','$').'</td>';


	if($printnotes!='none')
		$html .= '<td colspan="4">&nbsp;</td>';


$html .= '</tr></table>';

}


?>