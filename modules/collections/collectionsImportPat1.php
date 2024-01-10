<?php
set_time_limit(0);
ignore_user_abort();
$script_path='/home/wsptn/public_html/netpt';
$newline="\n";

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

//procedure_step_title("UNgZip FILES:");
//
//$root=$script_path.'/collections/';
//
//$source=$root.'ws/pat1.txt.gz';
//$target=$root.'ws/pat1.txt';
//ungzip($source, $target, false);
//
////$source=$root.'ws/transv.txt.gz';
////$target=$root.'ws/transv.txt';
////ungzip($source, $target);
//
//$source=$root.'net/pat1.txt.gz';
//$target=$root.'net/pat1.txt';
//ungzip($source, $target, false);
//
////$source=$root.'net/transv.txt.gz';
////$target=$root.'net/transv.txt';
////ungzip($source, $target);
//displaysitemessages();

procedure_step_title("CONNECT TO DATABASE:");

$myServer = 'localhost';
$myUser = "wsptn_netpt";
$myPass = "OsmWoL?cUt~aco89";
$myDB = "wsptn_netpt";
$dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));
notify("000","Connected.");
displaysitemessages();

//procedure_step_title("IMPORT PAT1:");
//
//processfile("NET", $script_path."/collections/net/pat1.txt");
//processfile("WS",  $script_path."/collections/ws/pat1.txt");

//function processfile($business, $myFile) {
//	$bnum=strtolower($business);
//	$BNUM=strtoupper($business);
//	$fh = fopen($myFile, 'r');
//	$srcfields=fgetcsv($fh,9999,"|");
//	$dstfields=array('bnum', 'pnum', 'fname', 'lname', 'ssn', 'phone', 'padd1', 'padd2', 'padd3', 'doc', 'retdr', 'therap', 'sex', 'injury', 'discharge', 'fvisit', 'lvisit', 'birth', 'sort', 'notes1', 'notes2', 'occup', 'emp', 'eadd1', 'eadd2', 'payor', 'payadd1', 'payadd2', 'payadd3', 'acctype', 'lastproc', 'pinsurance', 'pid', 'pgroup', 'padjust', 'pphone', 'prelate', 'psname', 'psadd1', 'psadd2', 'sinsurance', 'sid', 'sgroup', 'sadjust', 'sphone', 'srelate', 'ssname', 'ssadd1', 'ssadd2', 'attorney', 'lbilp', 'lbili', 'lpayp', 'lpayi', 'tbal', 'tbalp', 'tpayp', 'tbali', 'tpayi', 't120', 't90', 't60', 't30', 'tcurr', 'visits', 'rvisits', 'adjust', 'payments', 'charges', 'unbilled', 'claim', 'assign', 'wphone', 'trnote', 'icd1', 'icd2', 'icd3', 'icd4', 'dx1', 'dx2', 'dx3', 'dx4', 'authdate', 'authvis', 'injarea', 'cnum', 'cellphone', 'email');
//	$dftfields['bnum']=$BNUM;
//	foreach($dstfields as $id=>$dstfield) {
//		if( ($key = array_search($dstfield, $srcfields))===FALSE)
//			$mapfields["$dstfield"]="Not Mapped"; // zero means it's in the db but not mapped from data
//		else
//			$mapfields["$dstfield"]=$key;
//	}
//	$reads=0;
//	$inserts=0;
//	$updates=0;
//	$insertsbad=0;
//	$updatesbad=0;
//	$validacctypearray=array('','00','1','15','16','17','18','19','2','3','31','4','44','43','5','51','52','53','54','55','56','57','6','61','62','64','7','71','72','73','75','76','77','78','79','8','9','92','94','95','96','97','98','99','FC');
//	while($read=fgetcsv($fh,9999,"|")) {
//		$num=count($read);
//		$reads++;
//		$pat1=cleanfields($mapfields, $read, $dftfields);
//		$pat1num=count($pat1);
//		if($num!='159' || $pat1num != '88') {
//			echo "Delimiter/Field error Record:$reads / $num Fields Read / $pat1num Fields Mapped to PTOS<br />\n";
//			dump('pat1', $pat1);
//		}
//		else {
//			if(!in_array($pat1['acctype'], $validacctypearray)) {
//				echo "Account Type error Record:$reads Fields:".$pat1['acctype']."<br />\n";
//				dump('pat1', $pat1);
//			}
//			else {
//				if(chain($BNUM, $pat1['pnum']) ) {
//					if(update($BNUM, $pat1['pnum'], $pat1))
//						$updates++;
//					else
//						$updatesbad++;
//				}
//				else {
//					if(insert($pat1))
//						$inserts++;
//					else
//						$insertsbad++;
//				}
//			}
//		}
//	}
//	fclose($fh);
//	notify("000", "$BNUM Records Read: $reads<br>");
//	notify("000", "$BNUM Records Inserted: $inserts ($insertsbad)<br>");
//	notify("000", "$BNUM Records Updated: $updates ($updatesbad)<br>");
//}

function insert($pat1) {
	echo "ATTEMPTING INSERT FOR ".$pat1['pnum'].".\n";
	$fields=array_keys($pat1);
	$fields=implode(", ", $fields);
	foreach($pat1 as $field=>$value)
		$values[]="'".mysqli_real_escape_string($dbhandle,$value)."'";
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

	foreach($pat1 as $field=>$value)
		$set[]="$field='".mysqli_real_escape_string($dbhandle,$value)."'";
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
displaysitemessages();

procedure_step_title("CREATE NEW ACCOUNTS:");
// Create New Accounts in Collections Account Table
$query = "
SELECT *
FROM PTOS_Patients p
LEFT JOIN collection_accounts ca
ON bnum=cabnum and pnum=capnum
WHERE caid IS NULL
";
$inserted=0;
$notinserted=0;
$cqinserted=0;
$cqnotinserted=0;
if($result = mysqli_query($dbhandle,$query)) {
	$auditfields = getauditfields(); // FUNCTION
	$crtuser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
	$crtdate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
	$crtprog = "'" . mysqli_real_escape_string($dbhandle,basename($auditfields['prog'])) . "'";
	$acctinfoarray = collectionsAccountTypeXref(); // FUNCTION
	while($row = mysqli_fetch_assoc($result)) {
		$bnum=mysqli_real_escape_string($dbhandle,$row['bnum']);
		$pnum=mysqli_real_escape_string($dbhandle,$row['pnum']);
		$cnum=mysqli_real_escape_string($dbhandle,$row['cnum']);
		$acctype=mysqli_real_escape_string($dbhandle,$row['acctype']);
		$match=$acctinfoarray[$acctype];
		if(is_array($match)) {
// if in array assign from array
			$accttype=$acctinfoarray["$acctype"]['accttype'];
			$acctsubtype=$acctinfoarray["$acctype"]['acctsubtype'];
			$acctgroup=$acctinfoarray["$acctype"]['acctgroup'];
			$acctstatus=$acctinfoarray["$acctype"]['acctstatus'];
			$lienstatus=$acctinfoarray["$acctype"]['lienstatus'];
			$dorstatus=$acctinfoarray["$acctype"]['dorstatus'];
			$settlestatus=$acctinfoarray["$acctype"]['settlestatus'];
		}
		else {
// Setup default values to insert into collections accounts table
			$accttype='RVW';
			$acctsubtype='';
			$acctgroup='';
			$acctstatus='';
			$lienstatus='';
			$dorstatus='';
			$settlestatus='';
		}
		$caquery = "
			INSERT INTO collection_accounts (cabnum, cacnum, capnum, caaccttype, caacctsubtype, caacctgroup, caacctstatus, calienstatus, cadorstatus, casettlestatus, crtdate, crtuser, crtprog) VALUES('$bnum', '$cnum', '$pnum', '$accttype', '$acctsubtype', '$acctgroup', '$acctstatus', '$lienstatus', '$dorstatus', '$settlestatus', $crtdate, $crtuser, $crtprog);
		";

		if($caresult=mysqli_query($dbhandle,$caquery)) {
// Insert New Record
			$inserted++;
			$caid=mysql_insert_id();
			notify('000',"Inserted NEW collection_account record $caid.");
// Insert Collection Queue Entry
			$cqquery = "
				INSERT INTO collection_queue (cqcaid, crtdate, crtuser, crtprog) VALUES('$caid', $crtdate, $crtuser, $crtprog);
			";
			if($cqresult=mysqli_query($dbhandle,$cqquery)) {
	// Insert New Record
				$cqinserted++;
				notify('000',"Inserted collection_queue record for collection_account $caid.");
			}
			else {
				$cqnotinserted++;
				error('000',"Failed to insert collection_queue record for collection_account<br>$cqquery<br>".mysqli_error($dbhandle));
			}
		}
		else {
			$notinserted++;
			error('000',"Failed to insert collection_account<br>$caquery<br>".mysqli_error($dbhandle));
		}

	}
}
notify("000","$inserted collection account records created. $notinserted not created.");
notify("000","$cqinserted collection queue account records created. $cqnotinserted not created.");
displaysitemessages();

// Clean Accounts
procedure_step_title("CLEAN ACCOUNTS:");
// Remove Accounts from PTOS_Patients where first and last name are NULL
$query = "
DELETE
FROM PTOS_Patients
WHERE lname IS NULL and fname IS NULL
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"PTOS_Patients NULL names cleaned.");
else
	error("999","Error cleaning PTOS_Patients.<br>$query<br>".mysqli_error($dbhandle));

// Remove Accounts from Queue where not in Collections Account Table
$query = "
DELETE
FROM collection_accounts
WHERE caaccttype NOT IN
(SELECT catmcode FROM master_collections_accounttype_codes)
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"collection_accounts cleaned.");
else
	error("999","Error cleaning collection_accounts.<br>$query<br>".mysqli_error($dbhandle));

// Remove Accounts from collection_accounts where not in PTOS
$query = "
DELETE FROM collection_accounts
WHERE NOT EXISTS
	(select *
     from PTOS_Patients
     where collection_accounts.cabnum = PTOS_Patients.bnum
     and collection_accounts.capnum = PTOS_Patients.pnum );
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"collection_accounts cleaned.");
else
	error("999","Error cleaning collection_accounts.<br>$query<br>".mysqli_error($dbhandle));
displaysitemessages();

// Clean Queue
procedure_step_title("CLEAN QUEUE:");
// Remove Accounts from Queue where not in Collections Account Table
$query = "
DELETE
FROM collection_queue
WHERE cqcaid NOT IN
(SELECT caid FROM collection_accounts)
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"collection_queue cleaned.");
else
	error("999","Error 1 cleaning collection_queue.<br>$query<br>".mysqli_error($dbhandle));

// Insert collection_accounts into the queue where there is a ptos balance and not already in the queue
$query = "
INSERT INTO collection_queue( cqcaid, cqpriority, crtdate, crtuser, crtprog )
SELECT caid, 30, crtdate, crtuser, 'CleanQueue'
FROM collection_accounts
JOIN PTOS_Patients
ON cabnum=bnum and capnum=pnum
WHERE tbal>0 and caid NOT
IN (
SELECT cqcaid
FROM collection_queue
)
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"collection_queue updated.");
else
	error("999","Error 2 cleaning collection_queue.<br>$query<br>".mysqli_error($dbhandle));

// Remove Accounts from Queue where tbal <= 0
$query = "
DELETE
FROM collection_queue
WHERE cqcaid IN
(
SELECT caid
FROM collection_accounts
JOIN PTOS_Patients
ON cabnum=bnum and capnum=pnum
WHERE tbal<=0
)
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"collection_queue zero balances cleaned.");
else
	error("999","Error 1 cleaning zero balances from collection_queue.<br>$query<br>".mysqli_error($dbhandle));

$query = "
UPDATE collection_queue
SET cqpriority='30'
WHERE cqpriority < '10' OR cqpriority>'40'
";
if($result = mysqli_query($dbhandle,$query))
	notify('000',"collection_queue priority updated.");
else
	error("999","Error 3 cleaning collection_queue.<br>$query<br>".mysqli_error($dbhandle));

displaysitemessages();









// Queue assignment
procedure_step_title("QUEUE ASSIGNMENT:");
// Select Queue Definitions then update collection record queue assignment
$query = "
	SELECT *
	FROM master_collections_queue_groups
	WHERE cqminactive='0'
	ORDER BY cqmselseq, cqmgroup
";
$queuesprocessed=0;
if($result = mysqli_query($dbhandle,$query)) {
	$auditfields = getauditfields();
	$audituser = $auditfields['user'];
	$auditdate = $auditfields['date'];
	$auditprog = basename($auditfields['prog']);
	while($row = mysqli_fetch_assoc($result)) {
		$cqmgroup=mysqli_real_escape_string($dbhandle,$row['cqmgroup']);
		$cqmdescription=mysqli_real_escape_string($dbhandle,$row['cqmdescription']);

		$where=array();
		if(!empty($_REQUEST['assignmode']))
			$where[]="cqgroup IS NULL";

		if(!empty($row['cqmsql']))
			$where[]=$row['cqmsql'];

		if(count($where)>0)
			$wheresql="WHERE " . implode("AND", $where);
		else
			unset($wheresql);

		$cqquery="
			UPDATE	collection_queue cq
			JOIN	collection_accounts ca ON caid=cqcaid
			JOIN 	PTOS_Patients p on cabnum=bnum and capnum=pnum
			SET	cq.cqgroup='$cqmgroup', cq.cqrtbal=ROUND(p.tbal/1000, 0), cq.lockuser=NULL, cq.lockdate=NULL, cq.upddate='$auditdate', cq.upduser='$audituser', cq.updprog='$auditprog'
			$wheresql
		";
		$cqresult=mysqli_query($dbhandle,$cqquery);
		if($cqresult) {
			if(!empty($wheresql))
				notify("000", "QUEUE: $cqmgroup processed using the selection: $wheresql");
			else
				notify("000", "QUEUE: $cqmgroup processed using no selection criteria.");
			$queuesprocessed++;
		}
		else
			error("999", "QUEUE: $cqmgroup experienced an error.<br>QUERY:$cqquery<br>".mysqli_error($dbhandle));
	}
}
notify("000","$queuesprocessed collection queues processed by $audituser on $auditdate by $auditprog.");
displaysitemessages();

procedure_step_title("DONE.");






// Queue assignment ARCHIVE
procedure_step_title("QUEUE ARCHIVE:");
// get today's date
$today=date("Y-m-d",time());

// remove archive from today's date
$deletequery="DELETE FROM collection_queue_summary_history WHERE cqshdate = '$today'";
if($deleteresult = mysqli_query($dbhandle,$deletequery)) {

// add today to archive
	$insertquery = "
	INSERT INTO collection_queue_summary_history (cqshdate, cqshgroup, cqshacctype, cqshacctypecount, cqshtcurr, cqsht30, cqsht60, cqsht90, cqsht120, cqshtbal, upddate)
	SELECT CURDATE() cqshdate, cqgroup cqshgroup, acctype cqshacctype, count(*) cqshacctypecount, sum(tcurr) cqshtcurr, sum(t30) cqsht30, sum(t60) cqsht60, sum(t90) cqsht90, sum(t120) cqsht120, sum(tbal) cqshtbal, NOW() upddate
	FROM collection_queue cq
		LEFT JOIN collection_accounts ca
		ON cqcaid=caid
		LEFT JOIN PTOS_Patients p
		ON cabnum=bnum and capnum=pnum
	GROUP BY cqgroup, acctype
	";

	if($insertresult=mysqli_query($dbhandle,$insertquery))
		notify("000","Collection_Queue_Summary_History updated.");
	else
		error("999","Collection_Queue_Summary_History NOT updated.");
}
displaysitemessages();
procedure_step_title("DONE.");





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