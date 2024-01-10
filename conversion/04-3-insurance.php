<?php
// process the Authoriation1 table into tables
error_reporting(E_ALL);
ini_set("display_errors", 1); 
ini_set('max_execution_time', 0);

$errors=array();
$inserts['company']=0;
$inserts['location']=0;
$inserts['adjuster']=0;
$updates['company']=0;
$updates['location']=0;
$updates['adjuster']=0;
$errors['ERR10']=0;
$errors['ERR20']=0;
$errors['ERR30']=0;
$errors['ERR31']=0;
$errors['ERR32']=0;
$errors['ERR99']=0;
$errors['ERR110']=0;
$errors['ERR120']=0;
$errors['ERR130']=0;
$errors['ERR131']=0;
$errors['ERR132']=0;
$errors['ERR199']=0;
$errors['ERR210']=0;
$errors['ERR220']=0;
$errors['ERR230']=0;
$errors['ERR231']=0;
$errors['ERR232']=0;
$errors['ERR299']=0;
$errors['ERR999']=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_GET['clear'])) {
	$clearquery = "UPDATE Authorizations1 SET importedinsurance=0";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table insurance_companies";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table insurance_company_locations";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table insurance_company_adjusters";
	$result=mysqli_query($dbhandle,$clearquery);
	echo("tables cleared.<br>");
	exit();
}

$authorizations1query  = "SELECT id, InsuranceName, InsurancePhone, Adjuster, AdjusterFax FROM Authorizations1 WHERE importedinsurance=0";

if($authorizations1result = mysqli_query($dbhandle,$authorizations1query)) {
	$auditfields = getauditfields();
	$authorizations1NumRows = mysqli_num_rows($authorizations1result);

// Initialize Loop counter
	$reads=0;
	while($authorizations1Row = mysqli_fetch_assoc($authorizations1result)) {
		// Increment loop counter...
		$reads++;

		if(($reads % 1000) == 0) {
			echo("Records processed ... $reads of $authorizations1NumRows<br>" );
		}

		// Clear output fields
		$map['company'] = array();
		
		// Validate all input fields

		// Assign/Format output fields

		$icname = strtoupper(trim($authorizations1Row['InsuranceName']));
		if(empty($icname)) 
			$icname = "UNASSIGNED";

		$map['company']['icname'] = $icname;
		// importid = Import Authorization Database Record Id
		$map['company']['crtuser'] = $auditfields['user'];
		$map['company']['crtdate'] = $auditfields['date'];
		$map['company']['crtprog'] = $auditfields['prog'];

		if(count($map['company'])) {
			foreach($map['company'] as $key=>$value) {
				$map['company']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}

		// Insert Information
		$query = "INSERT INTO insurance_companies ";
		$query .= '(' . implode(', ', array_keys($map['company'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['company'])) . ') ';
		$error = 'ERR99'; // unknown error
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts['company']=$inserts['company']+1;
			$idquery = "SELECT LAST_INSERT_ID() as icid FROM insurance_companies ";
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importedinsurance = $idrow['icid'];
					$updatequery="UPDATE Authorizations1 SET importedinsurance='" . $importedinsurance . "' WHERE id='" . $authorizations1Row['id'] . "'";
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['company']=$updates['company']+1;
				}
				else 
					$error = 'ERR10'; // error retrieving row id
			}
			else 
				$error = 'ERR20'; // error with select query
		}
		else {
			$error = 'ERR30'; // error with insert query
			$idquery = "SELECT icid FROM insurance_companies WHERE icname=".$map['company']['icname'];
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importedinsurance = $idrow['icid'];
					$updatequery="UPDATE Authorizations1 SET importedinsurance='" . $importedinsurance . "' WHERE id='" . $authorizations1Row['id'] . "'";
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['company']=$updates['company']+1;
				}
				else 
					$error = 'ERR31'; // error retrieving row id
			}
			else 
				$error = 'ERR32'; // error with select query
		}
		if(isset($error)) {
			unset($id);
			$errors["$error"]=$errors["$error"]+1;
			echo("<h1>ERROR $error</H1><br>");
			unset($error);
		}

// Insert Location
// iclid, iclname, iclphone, iclfax, crtdate, crtuser, crtprog
		// Clear output fields
		$map['location'] = array();
		
		// Validate all input fields

		// Assign/Format output fields

		$iclphone = dbPhone($authorizations1Row['InsurancePhone']);
		$iclfax = dbPhone($authorizations1Row['AdjusterFax']);
		$iclname = "$icname P:" . displayPhone($iclphone) . " F:" . displayPhone($iclfax) ;

		$map['location']['iclname'] = substr($iclname,0,50);
		$map['location']['iclicid'] = $importedinsurance;
		$map['location']['iclphone'] = $iclphone;
		$map['location']['iclfax'] = $iclfax;
		$map['location']['crtuser'] = $auditfields['user'];
		$map['location']['crtdate'] = $auditfields['date'];
		$map['location']['crtprog'] = $auditfields['prog'];

		if(count($map['location'])) {
			foreach($map['location'] as $key=>$value) {
				$map['location']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}

		// Insert Information
		$query = "INSERT INTO insurance_company_locations ";
		$query .= '(' . implode(', ', array_keys($map['location'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['location'])) . ') ';
		$error = 'ERR199'; // unknown error
//dump("query",$query);
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts['location']=$inserts['location']+1;
			$idquery = "SELECT LAST_INSERT_ID() as iclid FROM insurance_company_locations ";
//dump("idquery1",$idquery);
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importedinsurancelocation = $idrow['iclid'];
					$updatequery="UPDATE Authorizations1 SET importedinsurancelocation='" . $importedinsurancelocation . "' WHERE id='" . $authorizations1Row['id'] . "'";
//dump("updatequery1",$updatequery);
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['location']=$updates['location']+1;
				}
				else 
					$error = 'ERR110'; // error retrieving row id
			}
			else 
				$error = 'ERR120'; // error with select query
		}
		else {
			$error = 'ERR130'; // error with insert query
			$idquery = "SELECT iclid FROM insurance_company_locations WHERE 
				iclname=".$map['location']['iclname'];
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importedinsurancelocation = $idrow['iclid'];
					$updatequery="UPDATE Authorizations1 SET importedinsurancelocation='" . $importedinsurancelocation . "' WHERE id='" . $authorizations1Row['id'] . "'";
//dump("updatequery2",$updatequery);
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['location']=$updates['location']+1;
				}
				else {
					$error = 'ERR131'; // error retrieving row id
dump("idquery",$idquery);
				}
			}
			else {
				$error = 'ERR132'; // error with select query
			}
		}
		if(isset($error)) {
			unset($id);
			$errors["$error"]=$errors["$error"]+1;
			echo("<h1>ERROR $error</H1><br>");
			unset($error);
		}

// Adjuster
		// Insert Information
		$map['adjuster']['icaicid'] = $importedinsurance;
		$map['adjuster']['icaiclid'] = $importedinsurancelocation;
		$icalname = strtoupper(trim($authorizations1Row['Adjuster']));
		if(empty($icalname)) 
			$icalname = "UNASSIGNED";
		$icafname = strtoupper(trim($authorizations1Row['Adjuster']));
		if(empty($icafname)) 
			$icafname = "UNASSIGNED";
		$map['adjuster']['icalname'] = $icalname;
		$map['adjuster']['icafname'] = $icafname;
		$icaphone = dbPhone($authorizations1Row['InsurancePhone']);
		$icafax = dbPhone($authorizations1Row['AdjusterFax']);
		$map['adjuster']['icaphone'] = $icaphone;
		$map['adjuster']['icafax'] = $icafax;
		$map['adjuster']['crtuser'] = $auditfields['user'];
		$map['adjuster']['crtdate'] = $auditfields['date'];
		$map['adjuster']['crtprog'] = $auditfields['prog'];

		if(count($map['adjuster'])) {
			foreach($map['adjuster'] as $key=>$value) {
				$map['adjuster']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}

		$query = "INSERT INTO insurance_company_adjusters ";
		$query .= '(' . implode(', ', array_keys($map['adjuster'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['adjuster'])) . ') ';
//dump("relationship query",$query);
		$error = 'ERR299'; // unknown error
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts['adjuster']=$inserts['adjuster']+1;
			$idquery = "SELECT LAST_INSERT_ID() as icaid FROM insurance_company_adjusters ";
//dump("idquery1",$idquery);
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importedinsuranceadjuster = $idrow['icaid'];
					$updatequery="UPDATE Authorizations1 SET importedinsuranceadjuster='" . $importedinsuranceadjuster . "' WHERE id='" . $authorizations1Row['id'] . "'";
//dump("updatequery1",$updatequery);
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['adjuster']=$updates['adjuster']+1;
				}
				else 
					$error = 'ERR110'; // error retrieving row id
			}
			else 
				$error = 'ERR120'; // error with select query
		}
		else {
			$error = 'ERR130'; // error with insert query
			$idquery = "SELECT icaid FROM insurance_company_adjusters WHERE 
				icalname=".$map['adjuster']['icalname']." and 
				icafname=".$map['adjuster']['icafname'];
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importedinsuranceadjuster = $idrow['icaid'];
					$updatequery="UPDATE Authorizations1 SET importedinsuranceadjuster='" . $importedinsuranceadjuster . "' WHERE id='" . $authorizations1Row['id'] . "'";
//dump("updatequery2",$updatequery);
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['adjuster']=$updates['adjuster']+1;
				}
				else {
					$error = 'ERR131'; // error retrieving row id
dump("idquery",$idquery);
				}
			}
			else {
				$error = 'ERR132'; // error with select query
			}
		}
		if(isset($error)) {
			unset($id);
			$errors["$error"]=$errors["$error"]+1;
			echo("<h1>ERROR $error</H1><br>");
			unset($error);
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