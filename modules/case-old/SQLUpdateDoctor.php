<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(17); 
dumppost();
errorclear();
if(isset($_SESSION['id'])) {
	$script = 'SQLUpdate';
	$table = 'doctors';
	$keyfield = 'dmid';
	$fields[$table]=array(
					'dminactive'=>'boolean',
					'dmsname'=>'name',
					'dmfname'=>'name',
					'dmlname'=>'name',
					'dmnpi'=>'name',
					'dmupin'=>'name',
					'dmdescphys'=>'memo',
					'dmdob'=>'date',
					'dmdscode'=>'dscode',
					'dmdclass'=>'dclass',
					'dmdescwork'=>'memo',
					'dmwcmix'=>'percentage',
					'dmpimix'=>'percentage',
					'dmothermix'=>'percentage',
					'dmestrefer'=>'integer'
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
						if(!empty($_POST["$fieldname"]))
							$values["$fieldname"] = dbDate($_POST["$fieldname"]);
						else
							$values["$fieldname"] = NULL;
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
				notify("001", "Doctor " . $values['dmlname'] . ", " . $values['dmfname'] . " successfully updated.");
				foreach($fields[$table] as $fieldname=>$fieldtype) 
					unset($_POST["$fieldname"]);
				unset($_SESSION['button']);
//				clearformvars('doctor', 'searchdoctor');
				$_POST['buttonSetSearchDoctor']='1';
				$_POST['searchdoctor']['dmid'] = $_SESSION['id'];
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