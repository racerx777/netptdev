<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(30); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/shelltools.php');
//$command="dir c:\ /s > apachedircommand.txt";
$command="dirtest.cmd";
$pid=start_proc($command);
echo ("$pid started...");
if(proc_isalive($pid)==TRUE)
	echo ("Running...");
//proc_kill ($pid);
exit();
// connect to NetPtLink Webserver and update an account type on PTOS

// Establish encrypted connection to webserver SSL?
// Start stession
// Send Command and Parameters : "UPDATEACCTYPE&pnum=123456&acctyp=75"

// NetPT Link authenticates requester
// Receives Request
// Parses Parameters
// Executes Command with parameters
// Returns results XML?
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mssql/ptos_db.php');
$dbhandle = dbconnect();


$result = mssql_query("SET ANSI_NULLS ON") or die(mssql_get_last_message());
$result = mssql_query("SET ANSI_WARNINGS ON") or die(mssql_get_last_message());

$query="
	SELECT * 
	FROM ptosnetworknow...pat1
	WHERE pnum='59943'
";
if($result=mssql_query($query)) {
	$message = mssql_get_last_message();
	echo("result:$message<br>");
	$numrows=mssql_num_rows($result);
	echo("numrows:$numrows<br>");
	while($row = mssql_fetch_assoc($result)) {
		echo($row['pnum']);
	}
	$message = mssql_get_last_message();
	echo("LAST $message<br>");
}
else
	echo(mssql_error());
?>
