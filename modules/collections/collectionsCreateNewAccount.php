<?php
//$script_path='/home/wsptn/public_html/netpt';
//require_once($script_path . '/common/session.php');
//require_once($script_path . '/config/mysql/wsptn_db.php');
//$dbhandle = dbconnect();
//
echo('Create New Account<br>');
$dbhandle = mysql_connect('localhost', 'wsptn_netpt', 'OsmWoL?cUt~aco89') or die("1. Couldn't connect to SQL Server on $myServer, $myUser, $myPass");
$dbselect = mysql_select_db('wsptn_netpt', $dbhandle) or die("3.Error selecting database. ".mysqli_error($dbhandle));

$script_path='/home/wsptn/public_html/netpt';
require_once($script_path . '/common/sitedivs.php');

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
	$auditfields = getauditfields();
	$crtuser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
	$crtdate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
	$crtprog = "'" . mysqli_real_escape_string($dbhandle,basename($auditfields['prog'])) . "'";
	$acctinfoarray = collectionsAccountTypeXref();
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
?>