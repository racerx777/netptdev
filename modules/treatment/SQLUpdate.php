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
// Connect to database 
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	list($m, $d, $y) = explode('/', $_POST['thdate']);
	$dbDate = "$y-$m-$d 0:0:0";

	list($m, $d, $y) = explode('/', $_POST['thnadate']);
	$dbnaDate = "$y-$m-$d 0:0:0";

	//declare the SQL statement that will query the database
	$query1 = "UPDATE treatment_header ";
	$query1 .= "SET ";
//	if(isset($_SESSION['user']['umclinic']) && !empty($_SESSION['user']['umclinic']))
//		$query1 .= "thcnum ='" . mysqli_real_escape_string($dbhandle,$_SESSION['user']['umclinic']) . "', ";
	$query1 .= "thcnum ='" . mysqli_real_escape_string($dbhandle,$_POST['thcnum']) . "', ";
	$query1 .= "thdate='" . mysqli_real_escape_string($dbhandle,$dbDate) . "', ";
	$query1 .= "thpnum='" . mysqli_real_escape_string($dbhandle,$_POST['thpnum']) . "', ";
	$query1 .= "thlname='" . mysqli_real_escape_string($dbhandle,$_POST['thlname']) . "', ";
	$query1 .= "thfname='" . mysqli_real_escape_string($dbhandle,$_POST['thfname']) . "', ";
	$query1 .= "thctmcode='" . mysqli_real_escape_string($dbhandle,$_POST['thctmcode']) . "', ";
	$query1 .= "thvtmcode='" . mysqli_real_escape_string($dbhandle,$_POST['thvtmcode']) . "', ";
	$query1 .= "thttmcode='" . mysqli_real_escape_string($dbhandle,$_POST['thttmcode']) . "', ";
	$query1 .= "thnadate='" . mysqli_real_escape_string($dbhandle,$dbnaDate) . "', ";
	$auditfields = getauditfields();
	$query1 .= "upddate='" . $auditfields['date'] . "', ";
	$query1 .= "upduser='" . $auditfields['user'] . "', ";
	$query1 .= "updprog='" . $auditfields['prog'] . "' ";
	$query1 .= "WHERE thid='" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "'";	
	//execute the SQL query and return records
	$result1 = mysqli_query($dbhandle,$query1);

	if($result1) {
		addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'UPDATE', 'Updated Treatment', $query1);

		$query2 = "DELETE FROM treatment_modalities WHERE thid='" . $_SESSION['id'] . "'";
		$result2 = mysqli_query($dbhandle,$query2);
		if(!$result2) 
			error("deleting old modalities",mysqli_error($dbhandle));
		addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'DELETE', 'Removed Previous Treatment Modalities', $query2);

		$query6 = "DELETE FROM treatment_procedures WHERE thid='" . $_SESSION['id'] . "'";
		$result6 = mysqli_query($dbhandle,$query6);
		if(!$result6) 
			error("deleting old procedures",mysqli_error($dbhandle));
		addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'DELETE', 'Removed Previous Treatment Procedures', $query6);
		
		$query4 = "DELETE FROM treatment_procedure_groups WHERE thid='" . $_SESSION['id'] . "'";
		$result4 = mysqli_query($dbhandle,$query4);
		if(!$result4) 
			error("deleting old procedures",mysqli_error($dbhandle));
		addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'DELETE', 'Removed Previous Treatment Procedure Groups', $query4);

// Insert Visit Report Codes
		if(isset($_POST['thvtmcode']) && !empty($_POST['thvtmcode'])) {
			$query1a = "INSERT INTO treatment_procedures ";
			$query1a .= "(thid, pmcode, crtdate, crtuser, crtprog) ";
			$query1a .= "VALUES(";
			$query1a .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "', ";
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
			addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Insert Visit Reports', $query1a);
		}

// Insert Supply Modalities
		if(isset($_POST['supplymodalities'][$_POST['thttmcode']])) {
			foreach($_POST['supplymodalities'][$_POST['thttmcode']] as $key=>$val) {
				$query3a = "INSERT INTO treatment_modalities ";
				$query3a .= "(thid, mmcode, upddate, upduser, updprog) ";
				$query3a .= "VALUES(";
				$query3a .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "', ";
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
				addheaderhistory($id, $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Added New Treatment Supply Modalities', $query3a);
			}
		}

		if(isset($_POST['modalities'][$_POST['thttmcode']])) {
			foreach($_POST['modalities'][$_POST['thttmcode']] as $key=>$val) {
				$query3 = "INSERT INTO treatment_modalities (thid, mmcode, upddate, upduser, updprog) VALUES(";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$key) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
				$query3 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "')";
				$result3 = mysqli_query($dbhandle,$query3);
				if(!$result3) {
					error("001", mysqli_error($dbhandle));
				}
				addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Added New Treatment Modalities', $query3);
			}
		}

		if(isset($_POST['procedure'][$_POST['thttmcode']])) {
			foreach($_POST['procedure'] as $key=>$val) {
				$query5 = "INSERT INTO treatment_procedure_groups (thid, gmcode, upddate, upduser, updprog) VALUES(";
				$query5 .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "', ";
				$query5 .= "'" . mysqli_real_escape_string($dbhandle,$val) . "', ";
				$query5 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
				$query5 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
				$query5 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "')";
				$result5 = mysqli_query($dbhandle,$query5);
				if(!$result5) {
					error("002", mysqli_error($dbhandle));
				}
				addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Added New Treatment Procedure Groups', $query5);

// Insert Procedures 
				$query5a = "INSERT INTO treatment_procedures (thid, pmcode, upddate, upduser, updprog)";
				$query5a .= "(SELECT tpg.thid, pg.pmcode, tpg.upddate, tpg.upduser, tpg.updprog ";
				$query5a .= "FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid='" . $_SESSION['id'] ."'";
				$query5a .= ")";
//execute the SQL query 
				$result5a = mysqli_query($dbhandle,$query5a);
				if(!$result5a) {
					error("003", mysqli_error($dbhandle));
				}
				addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Added New Treatment Procedures', $query5a);
			}
		}
// Insert Special Individual Procedures
		if(isuserlevel(23) || $_SESSION['user']['umrole'] == 10) {
			if(isset($_POST['individualprocedures'][$_POST['thttmcode']])) {
				foreach($_POST['individualprocedures'][$_POST['thttmcode']] as $key=>$val) {
					$query7 = "INSERT INTO treatment_procedures (thid, pmcode, upddate, upduser, updprog,qty) VALUES(";
					$query7 .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "', ";
					$query7 .= "'" . mysqli_real_escape_string($dbhandle,$key) . "', ";
					$query7 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "', ";
					$query7 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "', ";
					$query7 .= "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "',";
					$query7 .= "'" . mysqli_real_escape_string($dbhandle,$_POST['proceduresSelect'][$key]) . "') ";
					$result7 = mysqli_query($dbhandle,$query7);
					if(!$result7) {
						error("004", mysqli_error($dbhandle));
					}
					addheaderhistory($_SESSION['id'], $auditfields['date'], $auditfields['user'], 0, $_SESSION['application'], 'INSERT', 'Added New Treatment Individual Procedures', $query7);
				}
			}
		}

		$_SESSION['notify'][] = "Record successfully updated in current treatment list.";
		unset($_POST['thdate']);
		unset($_POST['thlname']);
		unset($_POST['thfname']);
		unset($_POST['thctmcode']);
		unset($_POST['thvtmcode']);
		unset($_POST['thttmcode']);
		unset($_POST['thnadate']);
		unset($_POST['procedure']);
		unset($_POST['modalities']);		
	}
	else
		error("000", mysqli_error($dbhandle));
	//close the connection
	mysqli_close($dbhandle);
}
else {
	$_SESSION['button']='Edit';
	unset($_POST['button']);
	$_POST['button'][]='Edit';
}
?>