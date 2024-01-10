<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

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
$wheresql
ORDER BY cqgroup, cabnum, upddate, cqschcalldate, crtdate
";
?>

<table border="1" cellspacing="0" cellpadding="3" width="550px">
	<tr>
		<th colspan="13">Untouched Listing : <?php echo $queue; ?> </th>
	</tr>
	<tr>
		<th>Queue</th>
		<th>#</th>
		<th>Bus</th>
		<th>Account</th>
		<th>Pty</th>
		<th>last touch Date</th>
		<th>next touch Date</th>
		<th>Create Date</th>
		<th>Balance</th>
<?php
if($printnotes!='none') {
		echo "<th>Note Date</th>";
		echo "<th>User</th>";
		echo "<th>Button</th>";
		echo "<th>Note</th>";
}
?>
	</tr>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$reportcount=0;
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
?>
	<tr>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo $groupprint; ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo $groupcount; ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo $bnumprint; ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo $row['capnum']; ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo $row['cqpriority']; ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo displayDate($row['upddate']); ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo displayDate($row['cqschcalldate']); ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top"><?php echo displayDate($row['crtdate']); ?></td>
		<td rowspan="<?php echo $numrows+1; ?>" valign="top" align="right"><?php echo $row['tbal']; ?></td>
<?php
		if($numrows==0) { // none
			if($printnotes!='none') {
?>
			<td valign="top"><?php echo "&nbsp;"; ?></td>
			<td valign="top"><?php echo "&nbsp;"; ?></td>
			<td valign="top"><?php echo "&nbsp;"; ?></td>
			<td valign="top"><?php echo "&nbsp;"; ?></td>
<?php
			}
		}
		else {
			while($noterow=mysqli_fetch_assoc($noteresult)) {
?>
		<tr>
			<td valign="top"><?php echo displayDate($noterow['crtdate']); ?></td>
			<td valign="top"><?php echo $noterow['crtuser']; ?></td>
			<td valign="top"><?php echo $noterow['nobutton']; ?></td>
			<td valign="top"><?php echo $noterow['nonote']; ?></td>
		</tr>
<?php
			}
		}
	}
?>
		</td>
	</tr>
<?php
	mysqli_close($dbhandle);
?>
	<tr>
		<td colspan="8"><?php echo $reportcount; ?> Total Untouched</td>
		<td><?php echo displayCurrency($total,',','$'); ?></td>
<?php
	if($printnotes!='none')
		echo '<td colspan="4">&nbsp;</td>';
?>
	</tr>
</table>
<?php
}
else {
	error("999","Error Mysql $select<br />".mysqli_error($dbhandle));
	displaysitemessages();
}
if($_REQUEST['sql'] == 'show') {
	dump("userid",$userid);
	dump("select",$select);
}
?>