<?php
// process the Authoriation1 table into patient tables
error_reporting(E_ALL);
ini_set("display_errors", 1); 
ini_set('max_execution_time', 0);
ini_set('memory_limit',"100M");

$inserts['patients']=0;
$updates['patients']=0;
$errors['ERR10']=0;
$errors['ERR20']=0;
$errors['ERR30']=0;
$errors['ERR31']=0;
$errors['ERR32']=0;
$errors['ERR33']=0;
$errors['ERR99']=0;
$errors['ERR999']=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_GET['clear'])) {
	$clearquery = "UPDATE Authorizations1 SET importedpatient=0";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table patients";
	$result=mysqli_query($dbhandle,$clearquery);
	echo("tables cleared.<br>");
	exit();
}
$authorizations1query  = "SELECT id, Active, FirstName, LastName, MF, SocSecuirty, DOB, Pt, Email FROM Authorizations1 WHERE importedpatient=0";

if($authorizations1result = mysqli_query($dbhandle,$authorizations1query)) {
	$auditfields = getauditfields();
	$authorizations1NumRows = mysqli_num_rows($authorizations1result);

// Initialize Loop counter
	$reads=0;
	while($authorizations1Row = mysqli_fetch_assoc($authorizations1result)) {
		unset($error);
		// Increment loop counter...
		$reads++;

		if(($reads % 1000) == 0) {
			echo("Records processed ... $reads of $authorizations1NumRows<br>" );
		}

		// Clear output fields
		$map['patients'] = array();
		
		// Validate all input fields

		// Assign/Format output fields

		if($authorizations1Row['Active'] == 'F') 
			$map['patients']['painactive'] = '1'; 
		else 
			$map['patients']['painactive'] = '0'; 
		$map['patients']['pafname'] = strtoupper(trim($authorizations1Row['FirstName'])); 
		$map['patients']['pamname'] = ''; 
		$map['patients']['palname'] = strtoupper(trim($authorizations1Row['LastName'])); 
		$MF = strtoupper(trim($authorizations1Row['MF']));
		if($MF=='M') 
			$map['patients']['pasex'] = 'M';
		else {
			if($MF=='F')
				$map['patients']['pasex'] = 'F';
			else
				$map['patients']['pasex'] = NULL;
		}
		$map['patients']['passn'] = substr(dbSsn($authorizations1Row['SocSecuirty']),0,9);
		if(!empty($authorizations1Row['DOB'])) {
			$padob=date("Y-m-d H:i:s", strtotime($authorizations1Row['DOB']));
			if($padob==(-1)) 
				unset($map['patients']['padob']);
			else
				$map['patients']['padob']=$padob;
		}
		else
			unset($map['patients']['padob']);
		$map['patients']['paphone1'] = dbPhone($authorizations1Row['Pt']);
		$map['patients']['paphone2'] = '';
		$map['patients']['pacellphone'] = '';
		$map['patients']['paemail'] = strtolower($authorizations1Row['Email']);
		$map['patients']['paaddress1'] = '';
		$map['patients']['paaddress2'] = '';
		$map['patients']['pacity'] = '';
		$map['patients']['pastate'] = 'CA';
		$map['patients']['pazip'] = '';
		$map['patients']['panote'] = '';
		$map['patients']['importid'] = $authorizations1Row['id'];
		$map['patients']['crtuser'] = $auditfields['user'];
		$map['patients']['crtdate'] = $auditfields['date'];
		$map['patients']['crtprog'] = $auditfields['prog'];
		if(count($map['patients'])) {
			foreach($map['patients'] as $key=>$value) {
				$map['patients']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}
		// Insert Patient Information
		$query = "INSERT INTO patients ";
		$query .= '(' . implode(', ', array_keys($map['patients'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['patients'])) . ') ';
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts['patients']=$inserts['patients']+1;
			if($paid = mysql_insert_id()) {
				$updatequery="UPDATE Authorizations1 SET importedpatient='$paid' WHERE id='" . $authorizations1Row['id'] . "'";
				if($updateresult= mysqli_query($dbhandle,$updatequery)) 
					$updates['patients']=$updates['patients']+1;
				else {
					$error = 'ERR10';
					dump("updatequery",$updatequery);
					dump("mysql",mysqli_error($dbhandle));
				}
			}
			else {
				$error = 'ERR20'; // error with insert query
				dump("paid",$paid);
				dump("mysql",mysqli_error($dbhandle));
				exit();
			}
		}
		else {
			$error = 'ERR30'; // error with insert query
			$idquery = "SELECT paid FROM patients WHERE 
				palname=".$map['patients']['palname']." and 
				pafname=".$map['patients']['pafname']." and 
				pasex=".$map['patients']['pasex']." and 
				padob=".$map['patients']['padob']." and 
				passn=".$map['patients']['passn'];
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$paid = $idrow['paid'];
					$updatequery="UPDATE Authorizations1 SET importedpatient='$paid' WHERE id='" . $authorizations1Row['id'] . "'";
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['patients']=$updates['patients']+1;
					else {
						$error = 'ERR33';
						dump("updatequery",$updatequery);
						dump("mysql",mysqli_error($dbhandle));
					}
				}
				else {
					$error = 'ERR31'; // error retrieving row id
					dump("idquery",$idquery);
					dump("mysql",mysqli_error($dbhandle));
				}
			}
			else {
				$error = 'ERR32'; // error with select query
				dump("idquery",$idquery);
				dump("mysql",mysqli_error($dbhandle));
			}
		}

		if(isset($error)) {
			$errors["$error"]=$errors["$error"]+1;
			echo("<h1>ERROR $error</H1><br>");
		}
	} // While
} // If
else {
	$errors['ERR999'] = 1;
}

echo("Processed $reads records.<br>");
dump("inserts", $inserts);
dump("updates", $updates);
if(count($errors) > 0) {
	dump("errors", $errors);
}
mysql_close($dbhandle);
?>