<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
errorclear();
if(!empty($_SESSION['id'])) {
	$script = 'SQLUpdateLocation';
	$table = 'doctor_locations';
	$keyfield = 'dlid';
	$fields[$table]=array(
				'dlinactive'=>'boolean',
				'dlsname'=>'name',
				'dlname'=>'name',
				'dldescphys'=>'memo',
				'dldlsid'=>'int',
				'dlphone'=>'phone',
				'dlemail'=>'email',
				'dlfax'=>'phone',
				'dladdress'=>'name',
				'dlcity'=>'name',
				'dlstate'=>'name',
				'dlzip'=>'zip',
				'dlterritory'=>'code',
				'dlofficehours'=>'memo'
			);
// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}
// Validate form fields
//	require_once('validationLocation.php');
	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
		$query = "UPDATE $table ";

		foreach($fields[$table] as $fieldname=>$fieldtype) {
			if(isset($_POST["$fieldname"])) {
				switch($fieldtype):
					case 'boolean' :
						$values["$fieldname"] = ($_POST["$fieldname"]=='1');
						break;
					case 'name' :
						$values["$fieldname"] = strtoupper($_POST["$fieldname"]);
						break;
					case 'date' :
						if(!empty($_POST["$fieldname"]))
							$values["$fieldname"] = dbDate($_POST["$fieldname"]);
						else
							$values["$fieldname"] = NULL;
						break;
					case 'phone' :
						$values["$fieldname"] = dbPhone($_POST["$fieldname"]);
						break;
					case 'zip' :
						$values["$fieldname"] = dbZip($_POST["$fieldname"]);
						break;
					default:
						$values["$fieldname"] = $_POST["$fieldname"];
						break;
				endswitch;
			}
			if(!empty($values["$fieldname"]))
				$values["$fieldname"] = "'" . mysqli_real_escape_string($dbhandle,$values["$fieldname"]) . "'";
			else
				$values["$fieldname"] = "NULL";
		}

		if(count($values) > 0) {
			$auditfields = getauditfields();
			$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
			$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
			$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
			$set=array();
			foreach($values as $fieldname=>$fieldvalue) 
				$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
			$query .= "SET " . implode(', ', $set) . " ";
			
			$query .= "WHERE $keyfield='" . $_SESSION['id'] . "'";
//dumppost();
//dump("query",$query);
//execute the SQL query 
			$result = mysqli_query($dbhandle,$query);
			if($result) {
				notify("001", "Doctor Location " . $values['dlname'] . ", " . displayPhone($values['dlphone']) . " successfully updated.");
				foreach($fields[$table] as $fieldname=>$fieldtype) 
					unset($_POST["$fieldname"]);
				unset($_SESSION['button']);
//				clearformvars('doctor', 'searchdoctor');
				$_POST['buttonSetSearchDoctor']='1';
				$_POST['searchdoctor']['dlid'] = $_SESSION['id'];
			}
			else 
				error("001", "MYSQL" . mysqli_error($dbhandle));
		}
		mysqli_close($dbhandle);
	}
}
else
	error("001", "id field error (should never happen).");

?>