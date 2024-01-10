<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
//dumppost();
errorclear();
$script = 'SQLInsert';
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
//require_once('validationLocation.php');
if(errorcount() == 0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "INSERT INTO $table ";
	$values=array();
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
					$values["$fieldname"] = date("Y-m-d H:i:s", strtotime($_POST["$fieldname"]));
					break;
				default:
					$values["$fieldname"] = $_POST["$fieldname"];
					break;
			endswitch;
		}
		$values["$fieldname"] = "'" . mysqli_real_escape_string($dbhandle,$values["$fieldname"]) . "'";
	}
	if(count($values) > 0) {
		$values['crtuser'] = "'" . mysqli_real_escape_string($dbhandle,$_SESSION['user']['umuser']) . "'";
		$values['crtdate'] = "'" . date('Y-m-d H:i:s') . "'";
		$values['crtprog'] = "'" . $script . "'";

		$query .= '(' . implode(', ', array_keys($values)) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($values)) . ') ';

		if($result = mysqli_query($dbhandle,$query)) {
			notify("000","Record successfully added.");
			if(!empty($dmid)) {
				require_once("SQLUpdateFunctions.php");
				relationshipCreate($dmid, $dlid);
			}
			foreach($fields[$table] as $fieldname=>$fieldtype) 
				unset($_POST["$fieldname"]);
			unset($_SESSION['button']);
		}
		else
			error('001', mysqli_error($dbhandle));
	}
	//execute the SQL query and return records
	mysqli_close($dbhandle);
}
?>