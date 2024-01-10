<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
ignore_user_abort();

 $myServer = '127.0.0.1';
 $myUser = "netptwsp_netpt";
 $myPass = "OsmWoL?cUt~aco89";
 $myDB = "netptwsp_netpt";

$dbhandle = mysqli_connect($myServer, $myUser, $myPass,$myDB);

require_once('Pat1.functions.php');


notify("000","Connected.");
displaysitemessages();
$newline="\n";

procedure_step_title("CONNECT TO DATABASE:");

// $myServer = '127.0.0.1';
// $myUser = "wsptn_netpt";
// $myPass = "OsmWoL?cUt~aco89";
// $myDB = "wsptn_netpt";
// $dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
// $dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));



/*
procedure_step_title("RENAME QUEUES");

$sql = "SELECT cqmgroup, cqauser, umname
        FROM master_collections_queue_groups
        LEFT JOIN master_collections_queue_assign ON cqmgroup = cqagroup
        LEFT JOIN master_user ON umuser = cqauser
        WHERE cqmgroup NOT IN (
            SELECT cqagroup
            FROM master_collections_queue_assign
            GROUP BY cqagroup
            HAVING COUNT(*) >1
        )
        ORDER BY cqmselseq";

$result = mysqli_query($dbhandle,$sql);

while ($row = mysqli_fetch_assoc($result)) {
    $queue = $row['cqmgroup'];
    $name = $row['umname'];

    $queuePart = substr($queue, -6);

    $rename  = $queuePart;
    if ($name) {
        $nameArr = explode(' ', $name, 2);
        $fpart = strtoupper(substr($nameArr[0], 0, 3) . substr($nameArr[1], 0, 3));
        $rename = "$fpart $queuePart";
    }

    $sql = "UPDATE master_collections_queue_groups
            SET cqmgroup = '$rename'
            WHERE cqmgroup like '%$queuePart'";

    mysqli_query($dbhandle,$sql);

    $sql = "UPDATE master_collections_queue_assign
            SET cqagroup = '$rename'
            WHERE cqagroup like '%$queuePart'";

    mysqli_query($dbhandle,$sql);

}
*/
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
if($result = mysqli_query($dbhandle, $query)) {
	$auditfields = getauditfields(); // FUNCTION
	$crtuser = "'" . mysqli_real_escape_string($auditfields['user']) . "'";
	$crtdate = "'" . mysqli_real_escape_string($auditfields['date']) . "'";
	$crtprog = "'" . mysqli_real_escape_string(basename($auditfields['prog'])) . "'";
	$acctinfoarray = collectionsAccountTypeXref(); // FUNCTION
	while($row = mysqli_fetch_assoc($result)) {
		$bnum=mysqli_real_escape_string($row['bnum']);
		$pnum=mysqli_real_escape_string($row['pnum']);
		$cnum=mysqli_real_escape_string($row['cnum']);
		$acctype=mysqli_real_escape_string($row['acctype']);
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
			$caid=mysqli_insert_id($dbhandle);
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
//// Remove Accounts from PTOS_Patients where first and last name are NULL
//$query = "
//DELETE
//FROM PTOS_Patients
//WHERE lname IS NULL and fname IS NULL
//";
//if($result = mysqli_query($dbhandle,$query))
//	notify('000',"PTOS_Patients NULL names cleaned.");
//else
//	error("999","Error cleaning PTOS_Patients.<br>$query<br>".mysqli_error($dbhandle));

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
WHERE
NOT EXISTS
	(select *
     from PTOS_Patients
     where collection_accounts.cabnum = PTOS_Patients.bnum
     and collection_accounts.capnum = PTOS_Patients.pnum )

AND NOT
EXISTS (
    SELECT *
    FROM PTOS_Transactions
    WHERE collection_accounts.cabnum = PTOS_Transactions.bnum
    AND collection_accounts.capnum = PTOS_Transactions.pnum
);";
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
SELECT DISTINCT caid, 30, crtdate, crtuser, 'CleanQueue'
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
		$cqmgroup=mysqli_real_escape_string($row['cqmgroup']);
		$cqmdescription=mysqli_real_escape_string($row['cqmdescription']);

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