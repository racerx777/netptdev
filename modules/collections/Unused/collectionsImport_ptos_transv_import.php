<?php
set_time_limit(0);
ignore_user_abort();
$script_path='/home/wsptn/public_html/netpt';
$newline="\n";

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
	$_SESSION['error'][] = $num . ':' . $msg;
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

procedure_step_title("UNgZip FILES:");

function ungzip($source, $target, $delete=true, $verbose=true, $backup=true) {
	unset($result);
	$message = "FAILED";
	if($fp=fopen($target,"w")) {
		if($gz=gzopen($source,"r")) {
			while($string = gzread($gz, 16384))
				$result = fwrite($fp, $string);
			$message = "SUCCESSFUL";
			gzclose($gz);
                        if($backup) copy($source, $source.'.yesterday');
			if($delete) unlink($source);
		}
		fclose($fp);
	}
	if($verbose) notify("000", "Attempt to uncompress $source to $target...$message.");
	return($result);
}

$root=$script_path.'/collections/';

$source=$root.'ws/transv.txt.gz';
$target=$root.'ws/transv.txt';
if (file_exists($source) && filesize($source) > 0) {
    ungzip($source, $target, false);
} else {
    error('999', 'WS File Does Not Exist.');
}

$netExists = false;
$source=$root.'net/transv.txt.gz';
$target=$root.'net/transv.txt';
if (file_exists($source) && filesize($source) > 0) {
    ungzip($source, $target, false);
} else {
    error('999', 'NET File Does Not Exist.');
}

if (errorcount()) {
    displaysitemessages();
    exit;
}
displaysitemessages();

procedure_step_title("CONNECT TO DATABASE:");
$myServer = 'localhost';

$myUser = "wsptn_netpt";
$myPass = "OsmWoL?cUt~aco89";
$myDB = "wsptn_netpt";

$dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));
notify("000","Connected.");
displaysitemessages();

function processfile($business, $myFile) {
	procedure_step_title("Process $business File: $myFile");
	$bnum=strtolower($business);
	$BNUM=strtoupper($business);
	$fh = fopen($myFile, 'r');
	$srcfields=fgets($fh);
	$reads=0;
	$inserts=0;
        $insertsbad=0;
	$auditfields = getauditfields(); // FUNCTION
	$crtuser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
	$crtdate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
	$crtprog = "'" . mysqli_real_escape_string($dbhandle,basename($auditfields['prog'])) . "'";
	while($read=fgets($fh)) {
		$read=strtoupper($read);
		$read="'".mysqli_real_escape_string($dbhandle,$read)."'";
		$reads++;
		$query="INSERT INTO ptos_transv_import (bnum, importdata, crtuser, crtdate, crtprog) VALUES('$BNUM', $read, $crtuser, $crtdate, $crtprog)";
		if($result=mysqli_query($dbhandle,$query)) 
			$inserts++;
		else {
			error("999","Error inserting into ptos_transv_import: $query $newline".mysqli_error($dbhandle));
			$insertsbad++;
		}
	}
	fclose($fh);
	notify("000", "$BNUM Records Read: $reads");
	notify("000", "$BNUM Records Inserted: $inserts");
	notify("000", "$BNUM Records NOT Inserted: $insertsbad");
	displaysitemessages();
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









function processimporttable($bnum){
	global $script_path;
	procedure_step_title("BEGIN Process $bnum import table:");
	$auditfields = getauditfields(); // FUNCTION
	$fh = fopen($script_path."/collections/net/transv.txt", 'r');
	$srcfields=fgetcsv($fh,9999,"|");
	fclose($fh);
	$dstfields=array(
	'bnum', 'pnum', 'date', 'code', 'descrip', 'amount', 'therap', 'billed', 'visit', 'acctype', 'ipayed', 'ppayed', 'credit', 'dr1', 'dr2', 'dr3', 'dr4', 'dr5', 'dr6', 'dr7', 'dr8', 'dr9', 'drc1', 'drc2', 'drc3', 'drc4', 'drc5', 'drc6', 'drc7', 'drc8', 'drc9', 'cnum', 'inscd', 'crtuser', 'crtdate', 'crtprog'
	);
	$dftfields['crtuser'] = $auditfields['user'];
	$dftfields['crtdate'] = $auditfields['date'];
	$dftfields['crtprog'] = basename($auditfields['prog']);
	$dftfields['bnum']=$bnum;
	foreach($dstfields as $id=>$dstfield) { 
		if( ($key = array_search($dstfield, $srcfields))===FALSE) 
			$mapfields["$dstfield"]="Not Mapped"; // zero means it's in the db but not mapped from data
		else
			$mapfields["$dstfield"]=$key;
	}
	$reads=0;
	$inserts=0;
	$updates=0;
	$insertsbad=0;
	$updatesbad=0;
        $deletes=0;
	$selectquery="SELECT recno, bnum, importdata FROM ptos_transv_import WHERE bnum='$bnum'";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		while($row=mysqli_fetch_assoc($selectresult)) {
			$read=explode("|",$row['importdata']);
			$transv=cleanfields($mapfields, $read, $dftfields);
			$reads++;
			$fields=array_keys($transv);
			$fields=implode(", ", $fields);
			$values=array();
			foreach($transv as $field=>$value) 
				$values[]="'".mysqli_real_escape_string($dbhandle,$value)."'";
			$sqlvalues=implode(", ", $values);
			$query="INSERT INTO ptos_transv ($fields) VALUES($sqlvalues)";
			if($result=mysqli_query($dbhandle,$query)) {
				$inserts++;
				$recno=$row['recno'];
				$deletequery="DELETE FROM ptos_transv_import WHERE recno='$recno'";
				if($deleteresult=mysqli_query($dbhandle,$deletequery)) 
					$deletes++;
				else {
					$deletesbad++;
					error("999", "Error deleting ptos_transv_import record.");
					error("999", "QUERY:".$deletequery);
					error("999", "MYSQL ERROR:".mysqli_error($dbhandle));
				}
			}
			else {
				$insertsbad++;
				error("999", "Error inserting ptos_transv record.");
				error("999", "QUERY:".$query);
				error("999", "MYSQL ERROR:".mysqli_error($dbhandle));
			}
		} // while
		notify("000", "$bnum Records Read: $reads");
		notify("000", "$bnum Records Inserted: $inserts");
		notify("000", "$bnum Records NOT Inserted: $insertsbad");
		notify("000", "$bnum Records Deleted: $deletes");
		notify("000", "$bnum Records NOT Deleted: $deletesbad");
	}
	else {
		error("999", "Error selecting records for $bnum");
		error("999", "QUERY:".$selectquery);
		error("999", "MYSQL ERROR:".mysqli_error($dbhandle));
	}
	displaysitemessages();
}

procedure_step_title("Empty ptos_transv_import table:");
$truncate_ptos_transv_import_query="TRUNCATE TABLE ptos_transv_import";
if($truncate_ptos_transv_import_result=mysqli_query($dbhandle,$truncate_ptos_transv_import_query)) {
	notify("000", "ptos_transv_import table emptied.");
	displaysitemessages();

	processfile("NET", $script_path."/collections/net/transv.txt");
	processfile("WS",  $script_path."/collections/ws/transv.txt");

	procedure_step_title("Empty ptos_transv table:");
	$truncate_ptos_transv_query="TRUNCATE TABLE ptos_transv";
	if($truncate_ptos_transv_result=mysqli_query($dbhandle,$truncate_ptos_transv_query)) {
		notify("000", "ptos_transv table emptied.");
		displaysitemessages();

		processimporttable("NET");
		processimporttable("WS");

	}
	else {
		error("999", "Error truncating records for ptos_transv");
		error("999", "QUERY:".$truncate_ptos_transv_query);
		error("999", "MYSQL ERROR:".mysqli_error($dbhandle));
	}
}
else {
	error("999", "Error truncating records for ptos_transv_import");
	error("999", "QUERY:".$truncate_ptos_transv_import_query);
	error("999", "MYSQL ERROR:".mysqli_error($dbhandle));
}
notify("000","DONE.");
displaysitemessages();

$sql = "INSERT INTO ptos_transv_import_status (finish_time) VALUES (now());";
mysqli_query($dbhandle,$sql);

?>