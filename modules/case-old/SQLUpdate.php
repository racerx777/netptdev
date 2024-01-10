<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
errorclear();
if(isset($_SESSION['id'])) {
	$script = 'SQLUpdate';
	$table = 'cases';
	$keyfield = 'crid';
	$fields[$table]=array( 
				'crlname'=>'name',
				'crmname'=>'name',
				'crfname'=>'name',
				'craddress1'=>'name',
				'craddress2'=>'name',
				'crcity'=>'name',
				'crstate'=>'name',
				'crzip'=>'char',
				'crphone1'=>'number',
				'crdob'=>'date',
				'crsex'=>'name',
				'crssn'=>'number',
				'crinjurydate'=>'date',
				'crpnum'=>'char',
				'crcasetypecode'=>'code',
				'crreadmit'=>'boolean',
				'crrelocate'=>'boolean',
				'crpostsurgical'=>'boolean',
				'crsurgerydate'=>'date',
				'crempname'=>'char',
				'croccup'=>'char',
				'crnote'=>'memo',
				'crdate'=>'date',
				'crrefdmid'=>'integer',
				'crrefdlid'=>'integer',
				'crdxnature'=>'code',
				'crdxbodypart'=>'code',
				'crdxbodydescriptor'=>'code',
				'crfrequency'=>'number',
				'crduration'=>'number',
				'crtotalvisits'=>'number',
				'crtherapytypecode'=>'code',
				'crcnum'=>'code',
				'crtherapcode'=>'code',
				'raid'=>'number'
			);

// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}
// Validate form fields
	if($_POST['crcasetypecode']=='FC') {
		$_POST['crcasestatuscode']='CAN';
	}
	require_once('validation.php');
	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
		$query = "UPDATE $table ";
		foreach($fields[$table] as $fieldname=>$fieldtype) {
//			if(isset($_POST["$fieldname"])) {
				switch($fieldtype):
					case 'boolean' :
						if(isset($_POST["$fieldname"]))
							$values["$fieldname"] = '1';
						else
							$values["$fieldname"] = '0';
						break;
					case 'name' :
						$values["$fieldname"] = strtoupper($_POST["$fieldname"]);
						break;
					case 'date' :
						$datetest='NULL';
						if(!empty($_POST["$fieldname"])) { 
							$strtotime = strtotime($_POST["$fieldname"]);
							if($strtotime!=-1) {
								$datetest = date("Y-m-d H:i:s", strtotime($_POST["$fieldname"]));
							}
						}
						else
							$datetest='NULL';
						$values["$fieldname"]=$datetest;
						break;
					default:
						$values["$fieldname"] = $_POST["$fieldname"];
						break;
				endswitch;
//			}
			if($values["$fieldname"] == 'NULL') {
				$values["$fieldname"] = 'NULL';
			}
			else {
				$values["$fieldname"] = "'" . mysqli_real_escape_string($dbhandle,$values["$fieldname"]) . "'";
			}
//			if(!isset($_POST["$fieldname"]))
//				unset($values["$fieldname"]);
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
			$query .= "SET " . implode(', 
			', $set) . " ";
			
			$query .= "WHERE $keyfield='" . $_SESSION['id'] . "'";
//execute the SQL query 
			$result = mysqli_query($dbhandle,$query);
			if($result) {
				notify("001", "Case " . $_POST['crid'] . " successfully updated.");
				foreach($fields[$table] as $fieldname=>$fieldtype) 
					unset($_POST["$fieldname"]);
				unset($_SESSION['button']);
			}
			else 
				error("001", "Query:" . $query . "<br>Error:" . mysqli_error($dbhandle));
		}
		mysqli_close($dbhandle);
	}
}
else
	error("001", "id field error (should never happen).");

?>