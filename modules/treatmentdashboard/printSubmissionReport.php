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
if(!empty($_REQUEST['fromdate']))
	$fromdate=$_REQUEST['fromdate'];
if(!empty($_REQUEST['todate']))
	$todate=$_REQUEST['todate'];

if(!empty($fromdate) && empty($todate) ) 
	$todate=$fromdate; 
if(!empty($todate) && empty($fromdate) ) 
	$fromdate=$todate;

$report=array();
if(!empty($todate)) {
	$report['type'] = 1;
	$report['column1'] = "Submission Date";
	$report['fromdate']=date("Y-m-d 00:00:00", strtotime($fromdate));
	$report['todate']=date("Y-m-d 23:59:59", strtotime($todate));
	$report['title'] = "Submission Report by Business Unit, Provider, Clinic " . displayDate($report['fromdate']) . " - " . displayDate($report['todate']); 
	$report['where'] = "WHERE thsbmdate between '" . $report['fromdate'] . "' and '" . $report['todate'] . "' and thsbmstatus between '100' and '800'";
	$report['orderby'] = "ORDER BY bumname, pgmname, cmname, thcnum";
}
else
	unset($report);

if(is_array($report) && count($report)!=0) {
//dump("report",$report);
//dump("isarray report",is_array($report));
//dump("count report",count($report));
	$bgcolor['wes']="#BBBBBB";
	$bgcolor['net']="#DDDDDD";
	$bgcolor['oth']="#FFFFFF";
	$bgcolor['new']="#999999";

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$query= "
SELECT bumname, pgmbumcode, pgmname, cmpgmcode, cmname, thcnum, count(*) as sbmcount, sum(txhrs) as sumtxhrs, sum(txcount) as sumtxcount, sum(txhrs)/sum(txcount) as avgsbmhrs
FROM (
	SELECT bumname, pgmbumcode, pgmname, cmpgmcode, cmname, thcnum, thsbmdate,  sum(TIMEDIFF(thsbmdate,thdate)) as txhrs, count(*) as txcount
	FROM treatment_header
	LEFT JOIN master_clinics
	ON thcnum = cmcnum
		LEFT JOIN master_provider_groups
		ON cmpgmcode=pgmcode
			LEFT JOIN master_business_units
			ON pgmbumcode = bumcode
	$where
	GROUP BY bumname, pgmname, cmname, thcnum, thsbmdate
) as a
GROUP BY bumname, pgmname, cmname, thcnum
$orderby
";
//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $report['title']; ?></title>
</head>
<body>
<div style="float:left"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
<div style="float:right">
	<div><?php echo $report['title']; ?></div>
</div>
<div style="clear:both;">
<?php 
		if($displaydetailtable) {
?>
	<table style="text-align:center; border-collapse: collapse; border: solid;" cellpadding="3" width="100%">
		<tr>
			<th>Bus Unit</th>
			<th>Provider</th>
			<th>Clinic</th>
			<th># Submissions</th>
			<th>Avg Hrs/Submit</th>
		</tr>
		<?php
		}
		while($row=mysqli_fetch_assoc($result)) {
			$bu = $row['bumname'];
			$bucode = $row['pgmbumcode'];
			$provider = $row['pgmname'];
			$providercode = $row['cmpgmcode'];
			$clinicname = $row['cmname'] . "(" . $row['thcnum'] . ")";
			$submissions = $row['sbmcount'];
			$hours = $row['avgsbmhrs'];

		if($displaydetailtable) {
?>
			<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;" bgcolor="<?php echo $bgcolor["$grp"]; ?>">
				<td align="left" nowrap="nowrap"><?php echo $bu; ?></td>
				<td align="left" nowrap="nowrap"><?php echo $provider; ?></td>
				<td align="left" nowrap="nowrap"><?php echo $clinicname; ?></td>
				<td align="right" nowrap="nowrap"><?php echo $submissions; ?></td>
				<td align="right" nowrap="nowrap"><?php echo $hours; ?></td>
			</tr>
			<?php
		}
		$records["TOT"]++;
		if($bucode=='WS' || $bucode=='NET') 
			$records["$bucode"]++;
		else
			$records["OTH"]++;
		} // while
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
			<th bgcolor="<?php echo $bgcolor['WS']; ?>">WestStar</th>
			<th bgcolor="<?php echo $bgcolor['NET']; ?>">Network</th>
			<th bgcolor="<?php echo $bgcolor['OTH']; ?>">Unassigned</th>
			<th>Total</th>
		</tr>
		<tr style="border-collapse:collapse; border:solid; border-bottom-color:#000000;">
			<th align="right">Reporting Clinics</th>
			<td><?php echo $records['WS']; ?>&nbsp;</td>
			<td><?php echo $records['NET']; ?>&nbsp;</td>
			<td><?php echo $records['OTH']; ?>&nbsp;</td>
			<td><?php echo $records['TOT']; ?>&nbsp;</td>
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