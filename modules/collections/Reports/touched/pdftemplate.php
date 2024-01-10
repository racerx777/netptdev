<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
$html = "";
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if(isset($_REQUEST['userid']))
	$userid=$_REQUEST['userid'];

if(isset($_REQUEST['fromdate']))
	$fromdate=dbDate($_REQUEST['fromdate']);

if(isset($_REQUEST['thrudate']))
	$thrudate=dbDate($_REQUEST['thrudate']);

if($_REQUEST['includedetails']=='true')
	$includedetails=true;
else
	$includedetails=false;

if($_REQUEST['untouched']=='true')
	$untouched=true;
else
	$untouched=false;


if($_REQUEST['touched']=='true')
	$touched=true;
else
	$touched=false;

if(isset($_REQUEST['mintbal']))
	$mintbal=$_REQUEST['mintbal'];
else
	$mintbal=0;


$where=array();

$thisuser=getuser();
if (!isuserlevel(34)) {
    $userid=getuserid();
}
//if ($thisuser != 'mtwheater'
//    && $thisuser != 'VidalSolorzano'
//    && $thisuser != 'ShawnaClay'
//    && $thisuser != 'Constance'
//    && $thisuser != 'ConstanceCollect'
//    && $thisuser != 'JackieCollect'
//    && $thisuser != 'JacquelineH'
//    ) {
//
//}

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/user.options.php');

if(!empty($userid)) {
	$userinformation=getUserInformation($userid);
	$umuser=$userinformation['umuser'];
	$where[]="n.crtuser='$umuser'";
}

if(!empty($fromdate)) {
	$dbfromdate=dbDate($fromdate,'Y-m-d')." 00:00:00";
	if(!empty($thrudate))
		$dbthrudate=dbDate($thrudate,'Y-m-d')." 23:59:59";
	else
		$dbthrudate=dbDate($fromdate,'Y-m-d')." 23:59:59";
	$where[]="n.crtdate between '$dbfromdate' and '$dbthrudate'";
}

if(count($where)>0)
	$wheresql = "WHERE ".implode(' AND ', $where);

// Original Select
$select="
	SELECT cqgroup, n.crtuser, cabnum, nopnum, max(n.crtdate) as ltdate, tbal, cqpriority, cqschcalldate, q.crtdate cqcrtdate
	FROM notes n
	LEFT JOIN PTOS_Patients ON nopnum=pnum
	LEFT JOIN collection_accounts on capnum=nopnum
	LEFT JOIN collection_queue q on caid=cqcaid
	$wheresql
	GROUP BY cqgroup, n.crtuser, cabnum, nopnum
";

// Revised Select 08/13/2012
$select="
	SELECT cqgroup, n.crtuser, cabnum, nopnum, max(n.crtdate) as crtdate, tbal as cqrtbal, cqpriority, cqschcalldate, q.crtdate cqcrtdate
	FROM notes n
	LEFT JOIN PTOS_Patients ON nopnum=pnum
	LEFT JOIN collection_accounts on capnum=nopnum
	LEFT JOIN collection_queue q on caid=cqcaid
	$wheresql
	GROUP BY cqgroup, n.crtuser, cabnum, nopnum
";

if($includedetails)
	$select = "SELECT cqgroup, n1.crtuser, n1.nopnum, n1.crtdate, n1.cqrtbal, n2.nobutton, n2.nonote FROM ($select) n1 JOIN notes n2 on n1.crtuser=n2.crtuser and n1.nopnum=n2.nopnum and DATE_FORMAT(n1.crtdate,'%Y%m%d') = DATE_FORMAT(n2.crtdate,'%Y%m%d') ";

$select .= " ORDER BY n1.crtuser, n1.crtdate, n1.nopnum";
$datetitle="Last Touched (balance)";

if( $untouched && isset($userid) ) {

// Get all accounts in user queue that either have no notes or the last note is not between start and end date
	$usergroup = getUserQueueAssignment($umuser);

	if($usergroup=='')
		$select="
			SELECT cqgroup, q.crtuser, cqpriority, cabnum,  capnum as nopnum, cqpriority, cqschcalldate, q.crtdate cqcrtdate, q.crtuser cqcrtuser, q.upddate cqupddate, q.upduser cqupduser, cqcaid, ltcaid, ltdate, cqrtbal, tbal
			FROM collection_accounts ca
			JOIN collection_queue q
			ON caid=cqcaid
			LEFT JOIN (
				SELECT noappid ltcaid, max(crtdate) ltdate
				FROM notes
				WHERE noapp='collections'
				GROUP BY noappid
				) s1
			ON cqcaid=ltcaid
			LEFT JOIN PTOS_Patients ON capnum=pnum
			WHERE (cqschcalldate IS NULL or cqschcalldate < DATE_SUB(NOW(),INTERVAL 1 DAY)  ) and (ltdate IS NULL or (ltdate not between '$dbfromdate' and '$dbthrudate')) and tbal>$mintbal
			ORDER BY cqgroup, ltdate, cqschcalldate, cqcrtdate
		";

	else {

		$select="
			SELECT cqgroup, q.crtuser, cqpriority, cabnum,  capnum as nopnum, cqpriority, cqschcalldate, q.crtdate cqcrtdate, q.crtuser cqcrtuser, q.upddate cqupddate, q.upduser cqupduser, cqcaid, ltcaid, ltdate, cqrtbal, tbal
			FROM collection_accounts ca
			JOIN collection_queue q
			ON caid=cqcaid
			LEFT JOIN (
				SELECT noappid ltcaid, max(crtdate) ltdate
				FROM notes
				WHERE noapp='collections'
				GROUP BY noappid
				) s1
			ON cqcaid=ltcaid
			LEFT JOIN PTOS_Patients ON capnum=pnum
			WHERE cqgroup='$usergroup' and (cqschcalldate IS NULL or cqschcalldate < DATE_SUB(NOW(),INTERVAL 1 DAY)  ) and (ltdate IS NULL or (ltdate not between '$dbfromdate' and '$dbthrudate'))  and tbal>$mintbal
			ORDER BY ltdate, cqschcalldate, cqcrtdate
		";

// Changed selection 03/12/13
		$select="
			SELECT cqgroup, q.crtuser, cqpriority, cabnum,  capnum as nopnum, cqpriority, cqschcalldate, q.crtdate cqcrtdate, q.crtuser cqcrtuser, q.upddate cqupddate, q.upduser cqupduser, cqcaid, ltcaid, ltdate, cqrtbal, tbal
			FROM collection_accounts ca
			JOIN collection_queue q
			ON caid=cqcaid
			LEFT JOIN (
				SELECT noappid ltcaid, max(crtdate) ltdate
				FROM notes
				WHERE noapp='collections'
				GROUP BY noappid
				) s1
			ON cqcaid=ltcaid
			LEFT JOIN PTOS_Patients ON capnum=pnum
			WHERE cqgroup='$usergroup' and (ltdate IS NULL or (ltdate not between '$dbfromdate' and '$dbthrudate'))  and tbal>$mintbal
			ORDER BY ltdate, cqschcalldate, cqcrtdate
		";
	}


	$datetitle="(Pty) Scheduled Call Date/Last Touched";
	$includedetails=false;
}

if($_REQUEST['sql'] == 'show') {
	dump("untouched",$untouched);
	dump("userid",$userid);
	dump("select",$select);
}

$result=mysqli_query($dbhandle,$select);


$fromdate = !empty($_REQUEST['fromdate'])?displayDate($_REQUEST['fromdate']):"none";
$thrudate = !empty($_REQUEST['thrudate'])?displayDate($_REQUEST['thrudate']):"none";


$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Touched Accounts Report '.displayDate($appt['from']) . " - " . displayDate($appt['thru']).'</title>
</head>
<body>
<div style="float:left"><img src="../../wsptn_logo_bw_outline.jpg" width="300px"></div>
<div style="float:right">
	<h1>Touched Accounts Report</h1>
	<p>Date from: '.$fromdate . " to: " . $thrudate.'</p>
</div>
<style>
	tr {
border: 1px solid #ccc;
}

</style>
<div style="clear:both;">';

$html .= '<table border="0" cellspacing="2" style="border:1; border-collapse: collapse; border: solid;table-layout:fixed;" width="100%">';

// Output Title
		$html .= '<tr style="border: 1px solid #ccc; line-height: 1.8em;">';
		if($untouched)
			$html .= '<th colspan="9">Untouched Listing '.$usergroup.'</th>';
		else
			$html .='<th colspan="9">Touched Listing '.$umuser.'</th>';
		$html .='</tr>';

		$html .="<tr style='border: 1px solid #ccc; line-height: 1.8em;'>";

		if($untouched)
			$html .="<th>Queue</th>";
		else
			$html .="<th>User</th>";

		$html .="<th>Bus</th><th>#</th><th>Account</th><th>Priority</th><th>last touch</th><th>next touch</th><th>Create Date</th><th>Balance</th>";

		if($includedetails)
			$html .="<th>Button</th><th>Note</th>";

		$html .="</tr>";

		while($row=mysqli_fetch_assoc($result)) {
			$html .="<tr style='border: 1px solid #ccc; line-height: 1.8em;'>";

// Queue name
			if($untouched)
				$html .= "<td>".$row['cqgroup']."</td>";
			else {
				$html .= "<td>".$row['crtuser']."</td>";
				if($saved<>$row['crtuser']) {
					$saved=$row['crtuser'];
					$savedcount=0;
				}
			}

// Bus name
			$html .= "<td>".$row['cabnum']."</td>";

// Account Number
					if($savedpnum<>$row['nopnum']) {
						$savedcount++;
						$html .="<td>$savedcount</td><td>".$row['nopnum']."</td>";
						$savedpnum=$row['nopnum'];
					}
					else
						$html .="<td>&nbsp;</td><td>&nbsp;</td>";

// Priority
					$html .="<td>".$row['cqpriority']."</td>";
// Last Touch Date
					$html .="<td>".displayDate($row['ltdate'])."</td>";

// Next Touch Date
					$html .="<td>".displayDate($row['cqschcalldate'])."</td>";

// Crt Date
					$html .="<td>".displayDate($row['cqcrtdate'])."</td>";
// Balance
					$html .='<td align="right">'.$row['tbal']."</td>";
$total=$total+$row['tbal'];
					if($includedetails)
						$html .="<td>".$row['nobutton']."</td><td>".$row['nonote']."</td>";

			$html .="</tr>";
		}
$html .='<tr style="border: 1px solid #ccc; line-height: 1.8em;"><td colspan="8">';
if($untouched)
	$html .= 'Total Untouched';
else
	$html .=  'Total Touched';
$html .='</td>';
$html .='<td>'.displayCurrency($total,',','$').'</td>';
$html .='</tr>';
$html .="</table>";

mysqli_close($dbhandle);

?>