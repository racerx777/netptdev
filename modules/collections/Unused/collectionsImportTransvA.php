<?php
set_time_limit(0);
ignore_user_abort();

$script_path='/home/wsptn/public_html/netpt';

require_once($script_path . '/common/session.php');

require_once($script_path . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$truncate1query="TRUNCATE ptos_transv_import";
if($truncate1result=mysqli_query($dbhandle,$truncate1query)) {
	echo("ptos_transv_import truncated<br>");
	processfile("NET", $script_path."/collections/net/transv.txt");
	processfile("WS",  $script_path."/collections/ws/transv.txt");
	$truncatequery="TRUNCATE ptos_transv";
	if($truncateresult=mysqli_query($dbhandle,$truncatequery)) 
		echo('ptos_transv truncated.<br><a href="./collectionsImportTransv.php">Click Here to Start importing records into NetPT 500K at a time.</a>');
	else
		echo("error attempting to truncate ptos_transv<br>");
}
else
	echo("error attempting to truncate ptos_transv_import<br>");

function processfile($business, $myFile) {
	$bnum=strtolower($business);
	$BNUM=strtoupper($business);
	$fh = fopen($myFile, 'r');
	$srcfields=fgets($fh);
	$reads=0;
	$inserts=0;
	echo("<PRE>");
	while($read=fgets($fh)) {
		$reads++;
		if(($reads % 100000)==0)
			echo "$reads<br>";
		$read=strtoupper($read);
		$read="'".mysqli_real_escape_string($dbhandle,$read)."'";
		$query="
			INSERT INTO ptos_transv_import (bnum, importdata) VALUES('$BNUM', $read)
		";
		if($result=mysqli_query($dbhandle,$query)) 
			$inserts++;
		else
			$insertsbad++;
	}
	echo("</PRE>");
	fclose($fh);
	echo("$BNUM Records Read: $reads<br>");
	echo("$BNUM Records Inserted: $inserts ($insertsbad)<br>");
	echo("$BNUM Records Updated: $updates ($updatesbad)<br>");
}
?>