<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
errorclear();
// trim and UPPER all input
foreach($_POST as $key=>$val) {
	if($key != 'button') {
		if(is_string($_POST[$key]))
			$_POST[$key] = stripslashes(strip_tags(strtoupper(trim($val))));
	}
}

require_once('validate.php');

if(errorcount() == 0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$auditfields = getauditfields();
	//declare the SQL statement that will query the database
	list($m, $d, $y) = explode('/', $_POST['thdate']);
	$dbDate = "$y-$m-$d 0:0:0";

	list($m, $d, $y) = explode('/', $_POST['thnadate']);
	$dbnaDate = "$y-$m-$d 0:0:0";

	$query1 = "INSERT INTO treatment_header ";
	$query1 .= "(thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thnadate, thsbmstatus, thsbmdate, crtdate, crtuser, crtprog) ";
	$query1 .= "VALUES(";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thcnum']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$dbDate) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thpnum']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thlname']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thfname']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thctmcode']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thvtmcode']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thttmcode']) . "', ";
	$query1 .= "'" . mysqli_real_escape_string($dbhandle,$dbnaDate) . "', ";
// if added by UR
	if(isuserlevel(20)) {
		$query1 .= "'100', ";
		$query1 .= "'" . $auditfields['date'] . "', ";
	}
// if added by clinic
	else {
		$query1 .= "'0', ";
		$query1 .= "NULL, ";
	}
	$query1 .= "'" . $auditfields['date'] . "', ";
	$query1 .= "'" . $auditfields['user'] . "', ";
	$query1 .= "'" . $auditfields['prog'] . "' ";
	$query1 .= ")";
	//execute the SQL query 
	$result1 = mysqli_query($dbhandle,$query1);
	if($result1) {
		$id = mysqli_insert_id($dbhandle);
		addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Treatment Header', $query1);
// Insert Visit Report Codes
		if(isset($_POST['thvtmcode']) && !empty($_POST['thvtmcode'])) {
			$query1a = "INSERT INTO treatment_procedures ";
			$query1a .= "(thid, pmcode, crtdate, crtuser, crtprog) ";
			$query1a .= "VALUES(";
			$query1a .= "'" . mysqli_real_escape_string($dbhandle,$id) . "', ";
			$query1a .= "'" . mysqli_real_escape_string($dbhandle,$_POST['thvtmcode']) . "', ";
			$query1a .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
			$query1a .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
			$query1a .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "' ";
			$query1a .= ")";
//execute the SQL query 
			$result1a = mysqli_query($dbhandle,$query1a);
			if(!$result1a) {
				error("001", mysqli_error($dbhandle));
			}
			addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Visit Reports', $query1a);
		}
//		dumppost();
// Insert Procedures Groups
		if($_SESSION['user']['umrole'] != 10) {
			if(isset($_POST['procedure'][$_POST['thttmcode']]) && !empty($_POST['procedure'][$_POST['thttmcode']])) {
				$query2 = "INSERT INTO treatment_procedure_groups ";
				$query2 .= "(thid, gmcode, upddate, upduser, updprog) ";
				$query2 .= "VALUES(";
				$query2 .= "'" . mysqli_real_escape_string($dbhandle,$id) . "', ";
				$query2 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['procedure'][$_POST['thttmcode']]) . "', ";
				$query2 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
				$query2 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
				$query2 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "' ";
				$query2 .= ")";
	//execute the SQL query 
				$result2 = mysqli_query($dbhandle,$query2);
				if(!$result2) {
					error("001", mysqli_error($dbhandle));
				}
				addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Treatment Procedure Groups', $query2);
	// Insert Procedures 
				$query2a = "INSERT INTO treatment_procedures ";
				$query2a .= "(SELECT tpg.thid, pg.pmcode, '', '', '', tpg.upduser, tpg.upduser, tpg.updprog ";
				$query2a .= "FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid='" . $id . "'";
				$query2a .= ")";
	//execute the SQL query 
				$result2a = mysqli_query($dbhandle,$query2a);
				if(!$result2a) {
					error("002", mysqli_error($dbhandle));
				}
				addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Treatment Procedures', $query2a);
			}
		}
// Insert Modalities
		if(isset($_POST['modalities'][$_POST['thttmcode']]) && !empty($_POST['modalities'][$_POST['thttmcode']])) {
			foreach($_POST['modalities'][$_POST['thttmcode']] as $key=>$val) {
				$query3 = "INSERT INTO treatment_modalities ";
				$query3 .= "(thid, mmcode, upddate, upduser, updprog) ";
				$query3 .= "VALUES(";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$id) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$key) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
				$query3 .= ")";
//execute the SQL query 
				$result3 = mysqli_query($dbhandle,$query3);
				if(!$result3) {
					error("003", mysqli_error($dbhandle));
				}
				addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Treatment Modalities', $query3);
			}
		}
// Insert Supply Modalities
		if(isset($_POST['supplymodalities'][$_POST['thttmcode']]) && !empty($_POST['supplymodalities'][$_POST['thttmcode']])) {
			foreach($_POST['supplymodalities'][$_POST['thttmcode']] as $key=>$val) {
				$query3a = "INSERT INTO treatment_modalities ";
				$query3a .= "(thid, mmcode, upddate, upduser, updprog) ";
				$query3a .= "VALUES(";
				$query3a .= "'" . mysqli_real_escape_string($dbhandle,$id) . "', ";
				$query3a .= "'" . mysqli_real_escape_string($dbhandle,$key) . "', ";
				$query3a .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
				$query3a .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
				$query3a .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
				$query3a .= ")";
//execute the SQL query 
				$result3a = mysqli_query($dbhandle,$query3a);
				if(!$result3a) {
					error("003", mysqli_error($dbhandle));
				}
				addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Treatment Supply Modalities', $query3a);
			}
		}

// Insert Special Individual Procedures
		if(isuserlevel(23) || $_SESSION['user']['umrole'] == 10) {
			if(isset($_POST['individualprocedures'][$_POST['thttmcode']]) && !empty($_POST['individualprocedures'][$_POST['thttmcode']])) {
				foreach($_POST['individualprocedures'][$_POST['thttmcode']] as $key=>$val) {
					$query4 = "INSERT INTO treatment_procedures ";
					$query4 .= "(thid, pmcode, upddate, upduser, updprog, qty) ";
					$query4 .= "VALUES(";
					$query4 .= "'" . mysqli_real_escape_string($dbhandle,$id) . "', ";
					$query4 .= "'" . mysqli_real_escape_string($dbhandle,$key) . "', ";
					$query4 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
					$query4 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
					$query4 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "', ";
					$query4 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['proceduresSelect'][$key]) . "' ";
					$query4 .= ")";
//execute the SQL query 
					$result4 = mysqli_query($dbhandle,$query4);
					if(!$result4) {
						error("004", mysqli_error($dbhandle));
					}
					addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Treatment Individual Procedures', $query4);
				}
			}
		}
		$_SESSION['notify'][] = "Patient: " . $_POST['thlname'] . ", ". $_POST['thfname'] . " successfully added to current treatment list.";
		unset($_POST['thdate']);
		if(!empty($_POST['thpnum'])) {
			unset($_POST['thlname']);
			unset($_POST['thfname']);
		}
		else {
			unset($_POST['thpnum']); 
		}
	}
	else
		error("001", mysqli_error($dbhandle));
	//close the connection
	mysqli_close($dbhandle);
}
?>