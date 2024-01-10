<?php
// process the Authoriation1 table into tables
error_reporting(E_ALL);
ini_set("display_errors", 1); 
ini_set('max_execution_time', 0);

$errors=array();
$inserts['doctor']=0;
$inserts['location']=0;
$inserts['relationship']=0;
$updates['doctor']=0;
$updates['location']=0;
$updates['relationship']=0;
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
	$clearquery = "UPDATE Authorizations1 SET importeddoctor=0";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table doctors";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table doctor_locations";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table doctor_relationships";
	$result=mysqli_query($dbhandle,$clearquery);
	echo("tables cleared.<br>");
	exit();
}

$authorizations1query  = "SELECT id, RefPhysician, DrCity, DrPhone, DrFax FROM Authorizations1 WHERE importeddoctor=0";

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
		$map['doctor'] = array();
		
		// Validate all input fields

		// Assign/Format output fields

		// dmid : Id Generated Automatically

		$name = trim($authorizations1Row['RefPhysician']);
		if(empty($name)) {
			$name = "UNASSIGNED, UNASSIGNED";
		}

		$names = array();
		$names = split(",", $name);
		if(count($names)==2) {
			list($dmlname, $dmfname) = $names;
		}
		else {
			if(count($names)==1) {
				list($dmlname) = $names;
				$dmfname = "UNASSIGNED";
			}
			else
				dump("names",$names);
		}
		$dmlname = strtoupper(trim($dmlname));
		$dmfname = strtoupper(trim($dmfname));

		// dmsname : PTOS Short Name

		// dmfname : First Name - Cleaned and Uppercased - MUST HANDLE 18 CHARS
		$map['doctor']['dmfname'] = $dmfname;
		// dmlname : Last Name - Cleaned and Uppercased - MUST HANDLE 21 CHARS
		$map['doctor']['dmlname'] = $dmlname;

		// importid = Import Authorization Database Record Id
		$map['doctor']['crtuser'] = $auditfields['user'];
		$map['doctor']['crtdate'] = $auditfields['date'];
		$map['doctor']['crtprog'] = $auditfields['prog'];

		if(count($map['doctor'])) {
			foreach($map['doctor'] as $key=>$value) {
				$map['doctor']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}

		// Insert Information
		$query = "INSERT INTO doctors ";
		$query .= '(' . implode(', ', array_keys($map['doctor'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['doctor'])) . ') ';
		$error = 'ERR99'; // unknown error
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts['doctor']=$inserts['doctor']+1;
			$idquery = "SELECT LAST_INSERT_ID() as dmid FROM doctors ";
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importeddoctor = $idrow['dmid'];
					$updatequery="UPDATE Authorizations1 SET importeddoctor='" . $importeddoctor . "' WHERE id='" . $authorizations1Row['id'] . "'";
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['doctor']=$updates['doctor']+1;
				}
				else 
					$error = 'ERR10'; // error retrieving row id
			}
			else 
				$error = 'ERR20'; // error with select query
		}
		else {
			$error = 'ERR30'; // error with insert query
			$idquery = "SELECT dmid FROM doctors WHERE dmlname=".$map['doctor']['dmlname']." and dmfname=".$map['doctor']['dmfname']." ";
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importeddoctor = $idrow['dmid'];
					$updatequery="UPDATE Authorizations1 SET importeddoctor='" . $importeddoctor . "' WHERE id='" . $authorizations1Row['id'] . "'";
					if($updateresult= mysqli_query($dbhandle,$updatequery)) 
						$updates['doctor']=$updates['doctor']+1;
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

// Insert Doctor Location
// dlid, dlsname, dlname, dldescphy, dlphone, dlfax, dlcity, dlstate, crtdate, crtuser, crtprog
		// Clear output fields
		$map['location'] = array();
		
		// Validate all input fields

		// Assign/Format output fields

		// dlid : Id Generated Automatically

		// dlsname : Short Name

		$dlcity = strtoupper(trim($authorizations1Row['DrCity']));
		$dlphone = dbPhone($authorizations1Row['DrPhone']);
		$dlfax = dbPhone($authorizations1Row['DrFax']);
		$dlstate = 'CA';
		$dlname = "$dlcity PHONE:" . displayPhone($dlphone) . " FAX:" . displayPhone($dlfax) ;

		$map['location']['dlname'] = $dlname;
		$map['location']['dlphone'] = $dlphone;
		$map['location']['dlfax'] = $dlfax;
		$map['location']['dlcity'] = $dlcity;
		$map['location']['dlstate'] = $dlstate;
		$map['location']['crtuser'] = $auditfields['user'];
		$map['location']['crtdate'] = $auditfields['date'];
		$map['location']['crtprog'] = $auditfields['prog'];

		if(count($map['location'])) {
			foreach($map['location'] as $key=>$value) {
				$map['location']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}

		// Insert Information
		$query = "INSERT INTO doctor_locations ";
		$query .= '(' . implode(', ', array_keys($map['location'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['location'])) . ') ';
		$error = 'ERR199'; // unknown error
//dump("query",$query);
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts['location']=$inserts['location']+1;
			$idquery = "SELECT LAST_INSERT_ID() as dlid FROM doctor_locations ";
//dump("idquery1",$idquery);
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importeddoctorlocation = $idrow['dlid'];
					$updatequery="UPDATE Authorizations1 SET importeddoctorlocation='" . $importeddoctorlocation . "' WHERE id='" . $authorizations1Row['id'] . "'";
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
			$idquery = "SELECT dlid FROM doctor_locations WHERE 
				dlphone=".$map['location']['dlphone']." and 
				dlcity=".$map['location']['dlcity'];
			if($idresult = mysqli_query($dbhandle,$idquery)) {
				if($idrow = mysqli_fetch_assoc($idresult)) {
					unset($error); // no error
					$importeddoctorlocation = $idrow['dlid'];
					$updatequery="UPDATE Authorizations1 SET importeddoctorlocation='" . $importeddoctorlocation . "' WHERE id='" . $authorizations1Row['id'] . "'";
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

// Doctor Location relationship
		// Insert Information
		$map['relationship']['drdmid']=$importeddoctor;
		$map['relationship']['drdlid']=$importeddoctorlocation;
		$map['relationship']['crtuser'] = $auditfields['user'];
		$map['relationship']['crtdate'] = $auditfields['date'];
		$map['relationship']['crtprog'] = $auditfields['prog'];

		if(count($map['relationship'])) {
			foreach($map['relationship'] as $key=>$value) {
				$map['relationship']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}

		$query = "INSERT INTO doctor_relationships ";
		$query .= '(' . implode(', ', array_keys($map['relationship'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['relationship'])) . ') ';
//dump("relationship query",$query);
//		$error = 'ERR299'; // unknown error
		if($result = mysqli_query($dbhandle,$query)) 
			$inserts['relationship']=$inserts['relationship']+1;
//		else 
//			$error = 'ERR232'; // error with insert query

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