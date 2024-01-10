<?php
// process the Call History Records table into Call Queue table
error_reporting(E_ALL);
ini_set("display_errors", 1); 
ini_set('max_execution_time', 0);
ini_set('memory_limit',"100M");
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_GET['clear'])) {
	$clearquery = "truncate table case_scheduling_queue";
	if($clearresult = mysqli_query($dbhandle,$clearquery))
		echo("tables cleared.<br>");
	else
		echo("ERROR:".mysqli_error($dbhandle));
	exit();
}
echo("Converting Call Queue Entries...<br>");
$inserts=0;
$errors=0;
$reads=0;
$selectquery  = "SELECT cshcrid,cshnewpriority, cshnewschcalldate, cshnewphone, cshnewcasestatuscode, cshnewresult, crtdate, crtuser, crtprog FROM case_scheduling_history WHERE cshnewcasestatuscode ='PEN'";
if($selectresult = mysqli_query($dbhandle,$selectquery)) {
	$selectNumRows = mysqli_num_rows($selectresult);
	while($selectrow = mysqli_fetch_assoc($selectresult)) {
		$reads++;
		if(($reads % 1000) == 0) 
			echo(" Records processed ... $reads of $authorizations1NumRows<br>" );

		$values['csqcrid'] = $selectrow['cshcrid'];

		$values['csqpriority'] = $selectrow['cshnewpriority'];
		$values['csqschcalldate'] = $selectrow['cshnewschcalldate'];
		$values['csqphone'] = $selectrow['cshnewphone'];
		$values['csqresult'] = $selectrow['cshnewresult'];

		$values['crtdate'] = $selectrow['crtdate'];
		$values['crtuser'] = $selectrow['crtuser'];
		$values['crtprog'] = $selectrow['crtprog'];
		
		foreach($values as $key=>$value) {
			if(empty($value))
				unset($values["$key"]);
			else
				$values["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) . "'";
		}
		$fieldlist = implode(', ', array_keys($values));
		$valuelist = implode(', ', array_values($values));
		$insertquery = "INSERT INTO case_scheduling_queue ($fieldlist) VALUES($valuelist)";
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 
			$inserts++;
		else {
			$errors++;
			error("001", "QUERY: $insertquery<br>" . mysqli_error($dbhandle));
			break;
		}
	}
}
else {
	$errors++;
	error("002", "QUERY: $selectquery<br>" . mysqli_error($dbhandle));
}
mysql_close($dbhandle);
notify("000", "$reads cases read.");
notify("000", "$inserts scheduling queue records created.");
displaysitemessages();
?>