<?php
$script_path='/home/wsptn/public_html/netpt';
require_once($script_path . '/common/session.php');

require_once($script_path . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


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
displaysitemessages();

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
displaysitemessages();

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

// Update Account Type from PTOS Acctype
$pat1query="SELECT * FROM PTOS_Patients";
if($pat1result=mysqli_query($dbhandle,$pat1query)) {
	while($pat1=mysqli_fetch_assoc($pat1result)) {
		$bnum=$pat1['bnum'];
		$pnum=$pat1['pnum'];
		if($xrefarray=collectionsAccountTypeXref($pat1['acctype'])) {
			$xref=$xrefarray[$pat1['acctype']];
			$accttype=$xref['accttype'];
			$acctsubtype=$xref['acctsubtype'];
			$acctgroup=$xref['acctgroup'];
			$updatequery="UPDATE collection_accounts SET caaccttype='$accttype', caacctsubtype='$acctsubtype', caacctgroup='$acctgroup' WHERE cabnum='$bnum' and capnum='$pnum'";
			if($updateresult=mysqli_query($dbhandle,$updatequery))
				$yesupdate[$pat1['acctype']]++;
			else
				$noupdate[$pat1['acctype']]++;
		}
		else
			$noxref[$pat1['acctype']]++;
	}
}
if(count($yesupdate)>0) {
	foreach($yesupdate as $acctype=>$acctcount)
		notify("000","Updated Account Type : $acctype = $acctcount");
}

if(count($noupdate)>0) {
foreach($noupdate as $acctype=>$acctcount)
	error("000","SQL Update Failed Account Type: $acctype = $acctcount");
}

if(count($noxref)>0) {
foreach($noxref as $acctype=>$acctcount)
	error("000","No Account Type Cross Reference: $acctype = $acctcount");
}
displaysitemessages();
mysqli_close($dbhandle);
?>