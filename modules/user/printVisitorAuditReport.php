<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
?>
<script type="text/javascript">
function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}

function printReport(elemnam,elemval) {
	from=get('from');
	thru=get('thru');
	$url = "/modules/user/printVisitorAuditReport.php?from="+from+"&thru="+thru+"&"+elemnam+"=" + elemval;
	window.open($url);
	return;
}
</script>
<?php
if(!empty($_REQUEST['from']))
	$from=date('Ymd',strtotime($_REQUEST['from']));
if(!empty($_REQUEST['thru']))
	$thru=date('Ymd',strtotime($_REQUEST['thru']));


if(!empty($_REQUEST['fromtime']))
	$fromtime=date('His', strtotime($_REQUEST['fromtime']));
if(!empty($_REQUEST['thrutime']))
	$thrutime=date('His', strtotime($_REQUEST['thrutime']));



if(!empty($_REQUEST['outside']))
	$outside='1';



if(!empty($_REQUEST['skipblankusers']))
	$skipblankusers=true;
else
	$skipblankusers=false;


if(!empty($_REQUEST['username']))
	$username=$_REQUEST['username'];

if(!empty($_REQUEST['agent']))
	$agent=$_REQUEST['agent'];

if(!empty($_REQUEST['ip']))
	$ip=$_REQUEST['ip'];

if(!empty($_REQUEST['domain']))
	$agent=$_REQUEST['domain'];



if(empty($from)) {
	echo('from time is required.');
	exit();
}

if(empty($thru))
	$thru=$from;

// includecorporateusers=on&includemobileusers=on&user=

$where = array();
$where[] = "DATE_FORMAT(tm, '%Y%m%d') between '$from' and '$thru'";

if(!empty($fromtime) && !empty($thrutime)) {
	if(isset($outside)) 
		$where[] = "DATE_FORMAT(tm, '%H%i%s') not between '$fromtime' and '$thrutime'";
	else
		$where[] = "DATE_FORMAT(tm, '%H%i%s') between '$fromtime' and '$thrutime'";
}

if($skipblankusers)
	$where[] = "user != '' ";

if($username)
	$where[] = "user = '$username' ";

if($agent)
	$where[] = "agent = '$agent' ";

if($ip)
	$where[] = "ip = '$ip' ";

if($domain)
	$where[] = "domain = '$domain' ";


// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query  = "SELECT * FROM netpt_visitor_track ";
if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY tm, user"; 
echo("Criteria:<br />");
foreach($where as $key=>$val)
	echo("$val <br />");
//execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if($result) {
	$numRows = mysqli_num_rows($result);
?>

<fieldset>
<legend>NetPT Visitor Search Results</legend>
<?php
	if($numRows>0) {
		echo $numRows . " visitor(s) found.";
?>
<form method="post" name="searchlist">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Date/Time</th>
			<th>User</th>
			<th>Browser Agent</th>
			<th>IP Address</th>
			<th>DNS Lookup</th>
		</tr>
		<?php
		while($row = mysqli_fetch_assoc($result)) {
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["tm"]; ?></td>
			<td onclick="printReport('username','<?php echo $row["user"]; ?>');"><?php echo $row["user"]; ?></td>
			<td onclick="printReport('agent','<?php echo $row["agent"]; ?>');"><?php echo $row["agent"]; ?></td>
			<td onclick="printReport('ip','<?php echo $row["ip"]; ?>');"><?php echo $row["ip"]; ?></td>
			<td onclick="printReport('domain','<?php echo $row["domain"]; ?>');"><?php echo $row["domain"]; ?></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No visits found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>