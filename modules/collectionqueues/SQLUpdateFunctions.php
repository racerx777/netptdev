<?php
function updateinactivate($cqmgroup) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(23);
	errorclear();
	if(isset($cqmgroup)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE master_collections_queue_groups SET cqminactive = NOT cqminactive WHERE cqmgroup='" . $cqmgroup . "'";
		$result1 = mysqli_query($dbhandle,$query1);
		if($result1) {
			$_SESSION['notify'][] = $numRows . "Record(s) $id Updated.";
		}
		else 
			error("001", "SQLUpdateFunctions:Error<br/>$query1<br />".mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
}
?>