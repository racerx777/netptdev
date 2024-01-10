<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
errorclear();
$script = 'SQLInsert';
$table = 'cases';
$keyfield = 'crid';
$fields[$table]=array(
				'crcasestatuscode'=>'code',
				'crpaid'=>'integer',
				'crlname'=>'name',
				'crmname'=>'name',
				'crfname'=>'name',
				'craddress1'=>'name',
				'craddress2'=>'name',
				'crcity'=>'name',
				'crstate'=>'name',
				'crzip'=>'char',
				'crphone1'=>'number',
				'crphone2'=>'number',
				'crphone3'=>'number',
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
				'crtherapcode'=>'code'
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
	
	$values=array();
	foreach($fields[$table] as $fieldname=>$fieldtype) {
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
				$datetest=NULL;
				if(!empty($_POST["$fieldname"])) { 
					if(strtotime($_POST["$fieldname"])) {
						$datetest = date("Y-m-d H:i:s", strtotime($_POST["$fieldname"]));
					}
				}
				$values["$fieldname"]=$datetest;
				break;
			default:
				$values["$fieldname"] = $_POST["$fieldname"];
				break;
		endswitch;
		if($values["$fieldname"] == NULL) 
			unset($values["$fieldname"]);
		else 
			$values["$fieldname"] = "'" . mysqli_real_escape_string($dbhandle,$values["$fieldname"]) . "'";
	}
	if(count($values) > 0) {
// audit fields
		$auditfields = getauditfields();
		$values['crtuser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$values['crtdate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$values['crtprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";

		$query = "INSERT INTO $table ";
		$query .= '(' . implode(', ', array_keys($values)) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($values)) . ') ';
		if($result = mysqli_query($dbhandle,$query)) {
			notify("000", "Case " . $_POST["$keyfield"] . " successfully added.");
			foreach($fields[$table] as $fieldname=>$fieldtype) 
				unset($_POST["$fieldname"]);
			unset($_SESSION['button']);
			$query2 = "SELECT LAST_INSERT_ID() as $keyfield FROM $table ";
			if($result2 = mysqli_query($dbhandle,$query2)) {
				if($row = mysqli_fetch_assoc($result2)) {
					clearformvars('case', 'search');
					$_POST['buttonSetSearch']='1';
					$_POST["$keyfield"] = $row["$keyfield"];
					require_once('SQLUpdateFunctions.php');
					prescriptionadd($row["$keyfield"]);
				}
			}
		}
		else
			error('001', mysqli_error($dbhandle));
	}
	//execute the SQL query and return records
	if(is_resource($dbhandle))
		mysqli_close($dbhandle);
}
?>