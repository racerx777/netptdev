<?php
set_time_limit(0);
ignore_user_abort();
$script_path='/home/wsptn/public_html/netpt';
$newline="<br />";


function dump($title, $var) {
		echo("<h1>");
		echo($title);
		echo("</h1>");
		echo("<pre>");
		var_dump($var);
		echo("</pre>");
}

function procedure_step_title($title) {
	global $newline;
	echo("$newline");
	echo("Procedure Step: $title $newline");
	echo("-------------------------------------------------------------------- $newline");
}

// Functions copied and modified from sitedivs
function notifyclear() {
	$_SESSION['notify'] = array();
}
function notifycount() {
	return(count($_SESSION['notify']));
}
function notify($num, $msg) {
	$_SESSION['notify'][] = $msg;
}
function errorclear() {
	$_SESSION['error'] = array();
}
function errorcount() {
	if(isset($_SESSION['error']) && is_array($_SESSION['error']))
		return(count($_SESSION['error']));
	else 
		return(0);
}
function error($num, $msg) {
	$_SESSION['error'][] = $_SESSION['application'] . ':' . $num . ':' . $msg.$newline.$newline;
}
function displaysitemessages() {
	displaynotify();
	displayerror();
}
function displayerror() {
	global $newline;
// Output Error Messages here.
	if( isset($_SESSION['error']) && count($_SESSION['error']) > 0) {
		echo("********** Error Message Notifications ********** $newline");
		foreach($_SESSION['error'] as $num=>$msg)
			echo("   $msg $newline");
		$_SESSION['error'] = array();
		echo("************************************************* $newline");
	}
}
function displaynotify() {
	global $newline;
	// Output Notification Messages here.
	if( isset($_SESSION['notify']) && count($_SESSION['notify']) > 0) {
		echo("Notification Messages:$newline");
		foreach($_SESSION['notify'] as $num=>$msg)
			echo("$msg $newline");
		$_SESSION['notify'] = array();
		echo($newline);
	}
}

function getauditfields() {
	$auditfields=array();
	$auditfields['date'] = date('Y-m-d H:i:s', time());
	$auditfields['user'] = 'NetPT Cron Job';
	$auditfields['prog'] = $_SERVER['PHP_SELF'];
	return($auditfields);
}

procedure_step_title("CONNECT TO DATABASE:");

$myServer = 'localhost';
$myUser = "wsptn_netpt";
$myPass = "OsmWoL?cUt~aco89";
$myDB = "wsptn_netpt";
$dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));
notify("000","Connected.");
displaysitemessages();

// Insurance Import
procedure_step_title("INSURANCE IMPORT:");

$processquery = "SELECT * FROM ptos_insure_import";

$count=array();
$count['records_read']=0;
$count['insert_ok']=0;
$count['insert_failed']=0;
$count['update_ok']=0;
$count['update_failed']=0;
$count['sql_error_condition']=0;
$count['sql_fetch_query_failed']=0;
$count['sql_select_query_failed']=0;
$count['sql_process_query_failed']=0;
$count['import_records_ok']=0;
$count['import_records_failed']=0;

if($result = mysqli_query($dbhandle,$processquery)) {
	$auditfields = getauditfields();
	$audituser = mysqli_real_escape_string($dbhandle,$auditfields['user']);
	$auditdate = mysqli_real_escape_string($dbhandle,$auditfields['date']);
	$auditprog = mysqli_real_escape_string($dbhandle,basename($auditfields['prog']));
	while($row = mysqli_fetch_assoc($result)) {
		$successful=false;
		$count['records_read']++;
		$bnum=$row['bnum'];
		$icode=$row['icode'];
		$selectquery="SELECT count(*) as found FROM ptos_insure WHERE bnum='$bnum' and icode='$icode'";
		if($selectresult=mysqli_query($dbhandle,$selectquery)) {
			if($find=mysqli_fetch_assoc($selectresult)) {
	
				if($find['found']==0 || $find['found']==1) {
					$set=array();
					unset($where);
					foreach($row as $field=>$value) 
						$set[]="$field='".mysqli_real_escape_string($dbhandle,$value)."'";
		
					if($find['found']==0) {
						$set[]="crtuser='$audituser'";
						$set[]="crtdate='$auditdate'";
						$set[]="crtprog='$auditprog'";
						$set='SET '.implode(',',$set);
						$query="INSERT INTO ptos_insure $set";
						if($successful=mysqli_query($dbhandle,$query)) {
							$count['insert_ok']++;
						}
						else {
							$count['insert_failed']++;
							error("999","*** INSERT INTO ptos_insure FAILED (bnum='$bnum' and icode='$icode') $newline QUERY:".$query." $newline MYSQL_ERROR:".mysqli_error($dbhandle));
						}					
					}
	
					if($find['found']==1) {
						$set[]="upduser='$audituser'";
						$set[]="upddate='$auditdate'";
						$set[]="updprog='$auditprog'";
						$set='SET '.implode(',',$set);
						$where="WHERE bnum='$bnum' and icode='$icode'";
						$query="UPDATE ptos_insure $set $where";
						if($successful=mysqli_query($dbhandle,$query))
							$count['udpate_ok']++;
						else {
							$count['update_failed']++;
							error('999',"*** UPDATE ptos_insure FAILED (bnum='$bnum' and icode='$icode') $newline QUERY:".$query." $newline MYSQL_ERROR:".mysqli_error($dbhandle));
						}
					}
	
				}
	
				else {
					$count['sql_error_condition']++;
					error('999',"*** SQL ERROR CONDITION on ptos_insure (bnum='$bnum' and icode='$icode') $newline QUERY:".$selectquery." $newline MYSQL_ERROR:".mysqli_error($dbhandle));
				}
			}
			else {
				$count['sql_fetch_query_failed']++;
				error('999',"*** SQL SELECT FAILED on ptos_insure (bnum='$bnum' and icode='$icode') $newline QUERY:".$selectquery." $newline MYSQL_ERROR:".mysqli_error($dbhandle));
			}
	
			if($successful) {
				$deletequery="DELETE FROM ptos_insure_import WHERE bnum='$bnum' and icode='$icode'";
				if(mysqli_query($dbhandle,$deletequery)) 
					$count['import_records_ok']++;
				else
					$count['import_records_failed'];
			}
		}
		else {
			$count['sql_select_query_failed']++;
			error('999',"*** SQL SELECT QUERY FAILED on ptos_insure (bnum='$bnum' and icode='$icode') $newline QUERY:".$selectquery." $newline MYSQL_ERROR:".mysqli_error($dbhandle));
		}

	}
}
else {
	$count['sql_process_query_failed']++;
	error('999',"*** SQL PROCESS FAILED on ptos_insure (bnum='$bnum' and icode='$icode') $newline QUERY:".$processquery." $newline MYSQL_ERROR:".mysqli_error($dbhandle));
}

notify("000","ptos_insure Count Summary");
foreach($count as $type=>$amount)
	notify("000","$type					= $amount");

displaysitemessages();

procedure_step_title("DONE.");
?>