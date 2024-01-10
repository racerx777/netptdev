<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $myServer = '127.0.0.1';
// $myUser = "wsptn_netpt";
// $myPass = "OsmWoL?cUt~aco89";
// $myDB = "wsptn_netpt";
// 
 $myServer = '127.0.0.1';
 // $myServer = '66.81.18.2/27';
 $myUser = "netptwsp_netpt";
 $myPass = "OsmWoL?cUt~aco89";
 $myDB = "netptwsp_netpt";

$dbhandle = mysqli_connect($myServer, $myUser, $myPass,$myDB);

// $dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
//$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));

$sql = "UPDATE `PTOS_Payments` p
JOIN collection_accounts ca ON p.pnum = ca.capnum
JOIN collection_queue cq ON ca.caid = cq.cqcaid
JOIN master_collections_queue_assign ON cqagroup = cqgroup
SET queue = cqgroup, collector = cqauser
WHERE queue IS NULL and collector IS NULL";
mysqli_query($dbhandle,$sql);
echo mysqli_error($dbhandle);

$sql = "TRUNCATE TABLE report_collectors_cache";
mysqli_query($dbhandle,$sql);
echo mysqli_error($dbhandle);

$sql = "
INSERT INTO report_collectors_cache (bnum, pnum, acctype, amount, date, crtuser, cqagroup, cur_user, cur_group)
SELECT p.bnum, t.pnum, p.acctype, t.amount, t.date, n.crtuser, mcqa.cqagroup, t.collector, t.queue
FROM PTOS_Payments t
LEFT JOIN PTOS_Patients p ON t.pnum = p.pnum
LEFT JOIN notes n ON noid = (
    SELECT noid
    FROM notes n
    JOIN master_user mu ON n.crtuser = mu.umuser
    WHERE p.bnum = n.nobnum
    and t.pnum = n.nopnum
    and n.crtdate <= str_to_date( t.date, '%Y%m%d')
    and n.crtuser <> 'SunniSpoon'
    and n.crtuser <> 'AlesiaGuzman'
    and n.crtuser <> 'EddieOrtiz'
    and n.crtuser <> 'MariaRuiz'
    and n.crtuser <> 'MelissaKrueger'
    and n.crtuser <> 'LoriS'
    and n.crtuser <> 'MoMo'
    and n.crtuser <> 'GeovanaMoreno'
    ORDER BY n.crtdate DESC LIMIT 1
)
LEFT JOIN master_collections_queue_assign mcqa ON mcqa.cqauser = n.crtuser";

mysqli_query($dbhandle,$sql);
echo mysqli_error($dbhandle);

?>
