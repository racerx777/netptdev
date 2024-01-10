<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
errorclear();
if(isset($_SESSION['id'])) {
	$script = 'SQLUpdate';
	$table = 'patients';
	$keyfield = 'paid';
	$fields[$table]=array(
				'pafname'=>'name',
				'pamname'=>'name',
				'palname'=>'name',
				'pasex'=>'name',
				'passn'=>'ssn',
				'padob'=>'date',
				'paphone1'=>'phone',
				'paaddress1'=>'name',
				'pacity'=>'name',
				'pastate'=>'name',
				'pazip'=>'zip'
					);
// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}
// Validate form fields
	require_once('validation.php');
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
						$values["$fieldname"] = date("Y-m-d H:i:s", strtotime($_POST["$fieldname"]));
						break;
					case 'phone' :
						$values["$fieldname"] = dbPhone($_POST["$fieldname"]);
						break;
					case 'ssn' :
						$values["$fieldname"] = dbSsn($_POST["$fieldname"]);
						break;
					case 'zip' :
						$values["$fieldname"] = dbZip($_POST["$fieldname"]);
						break;
					default:
						$values["$fieldname"] = $_POST["$fieldname"];
						break;
				endswitch;
			}
			$values["$fieldname"] = "'" . mysqli_real_escape_string($dbhandle,$values["$fieldname"]) . "'";
		}

		if(count($values) > 0) {
// audit fields
			$auditfields = getauditfields();
			$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
			$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
			$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";

			$set=array();
			foreach($values as $fieldname=>$fieldvalue) 
				$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
			$query .= "SET " . implode(', ', $set) . " ";
			
			$query .= "WHERE $keyfield='" . $_SESSION['id'] . "'";
//execute the SQL query 
			$result = mysqli_query($dbhandle,$query);
			if($result) {
				notify("001", "Patient " . $_POST['palname'] . ", " . $_POST['pafname'] . " successfully updated.");
				foreach($fields[$table] as $fieldname=>$fieldtype) 
					unset($_POST["$fieldname"]);
				unset($_SESSION['button']);
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