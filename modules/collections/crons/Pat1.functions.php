<?php

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
	$_SESSION['error'][] = $_SESSION['application'] . ':' . $num . ':' . $msg;
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
function collectionsAccountTypeXref($acctype=NULL, $inactive=NULL) {
	$dbhandle = dbCon();
	$array = array();
	if(empty($inactive))
		$wherearray[]="atxinactive='0'";
	if( !empty( $acctype ) )
		$wherearray[] = "acctype='$acctype'";
	if( count($wherearray) > 0)
		$where = 'WHERE '. implode(" and ", $wherearray);
	$query  = "
		SELECT acctype, atxdspseq, atxaccttype, atxacctsubtype, atxacctgroup, atxacctstatus, atxlienstatus, atxdorstatus, atxsettlestatus
		FROM master_collections_accounttype_xref
		$where
		ORDER BY atxdspseq
		";
	if($result = mysqli_query($dbhandle,$query)) {
		while($row = mysqli_fetch_assoc($result)) {
			$array[$row['acctype']] = array(
				"acctype"=>$row['acctype'],
				"accttype"=>$row['atxaccttype'],
				"acctsubtype"=>$row['atxacctsubtype'],
				"acctgroup"=>$row['atxacctgroup'],
				"acctstatus"=>$row['atxacctstatus'],
				"lienstatus"=>$row['atxlienstatus'],
				"dorstatus"=>$row['atxdorstatus'],
				"settlestatus"=>$row['atxsettlestatus']
			);
		}
	}
	return($array);
}
function getauditfields() {
	$auditfields=array();
	$auditfields['date'] = date('Y-m-d H:i:s', time());
	$auditfields['user'] = 'NetPT Cron Job';
	$auditfields['prog'] = $_SERVER['PHP_SELF'];
	return($auditfields);
}

function ungzip($source, $target, $delete=true, $verbose=true) {
	unset($result);
	$message = "FAILED";
	if($fp=fopen($target,"a")) {
		if($gz=gzopen($source,"r")) {
			while($string = gzread($gz, 16384))
				$result = fwrite($fp, $string);
			$message = "SUCCESSFUL";
			gzclose($gz);
			if($delete) unlink($source);
		}
		fclose($fp);
	}
	if($verbose) notify("000", "Attempt to uncompress $source to $target...$message.");
	return($result);
}

function insert($pat1) {
	$dbhandle = dbCon();
	echo "ATTEMPTING INSERT FOR ".$pat1['pnum'].".\n";
	$fields=array_keys($pat1);
	$fields=implode(", ", $fields);
	foreach($pat1 as $field=>$value)
		$values[]="'".mysqli_real_escape_string($value)."'";
	$values=implode(", ", $values);
	$query="
		INSERT INTO PTOS_Patients ($fields) VALUES($values)
	";
	if($result=mysqli_query($dbhandle,$query))
		return(true);
	else
		error("999", "Failed Insert for ".$pat1['pnum'].".\n: $query\n");
	return(false);
}

function update($BNUM, $pnum, $pat1) {
	$fields=array_keys($pat1);
	$fields=implode(", ", $fields);
	$dbhandle = dbCon();
	foreach($pat1 as $field=>$value)
		$set[]="$field='".mysqli_real_escape_string($value)."'";
	$set=implode(", ", $set);
	$query="
		UPDATE PTOS_Patients SET $set WHERE bnum='$BNUM' and pnum='$pnum'
	";
//	dump("query $BNUM $pnum",$query);
	if($result=mysqli_query($dbhandle,$query))
		return(true);
	else
		error("999", "Failed Update: $query");
	return(false);
}

function chain($BNUM, $pnum) {
	$dbhandle = dbCon();
	$query="
		SELECT count(*) as recordcount
		FROM PTOS_Patients
		WHERE bnum='$BNUM' and pnum='$pnum'
	";
//	echo "chain to BNUM:$BNUM PNUM:$pnum ";
	if($result=mysqli_query($dbhandle,$query)) {
		$row=mysqli_fetch_assoc($result);
		if($row['recordcount']==1) {
//			echo 'found<br>/n';
			return(true);
		}
//		else
//			echo 'NOT FOUND!<br>/n';
	}
	return(false);
}

function cleanfields($mapfields, $data, $dftfields) {
	$array=array();
	foreach($mapfields as $field=>$index) {
		if($index==="Not Mapped")
			$array["$field"]=$dftfields["$field"];
		else {
			$value=$data[$index];
			$value=trim($value);
			$value=strtoupper($value);
			$array["$field"]=$value;
		}
	}
	return($array);
}

function dbCon(){
	 $myServer = '127.0.0.1';
	 $myUser = "netptwsp_netpt";
	 $myPass = "OsmWoL?cUt~aco89";
	 $myDB = "netptwsp_netpt";

	 return $dbhandle = mysqli_connect($myServer, $myUser, $myPass,$myDB);
}