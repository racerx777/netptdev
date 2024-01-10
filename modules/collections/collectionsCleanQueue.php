<?php
$script_path='/home/wsptn/public_html/netpt';
require_once($script_path . '/common/session.php');
require_once($script_path . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

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
?>