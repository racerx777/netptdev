<?php
$script_path='/home/wsptn/public_html/netpt';
require_once($script_path . '/common/session.php');
require_once($script_path . '/config/mysql/wsptn_db.php');

$dbhandle = dbconnect();

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
?>