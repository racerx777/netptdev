<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
errorclear();
$script = 'SQLInsert';
$table = 'patients';
$fields[$table]=array(
				'painactive'=>'boolean',
				'pafname'=>'name',
				'pamname'=>'name',
				'palname'=>'name',
				'pasex'=>'name',
				'passn'=>'numeric',
				'padob'=>'date',
				'paphone1'=>'phone',
				'paphone2'=>'phone',
				'pacellphone'=>'phone',
				'paemail'=>'email',
				'paaddress1'=>'name',
				'paaddress2'=>'name',
				'pacity'=>'name',
				'pastate'=>'name',
				'pazip'=>'numeric',
				'panote'=>'memo'
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
// audit fields
		$auditfields = getauditfields();
		$values['crtuser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$values['crtdate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$values['crtprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";

		$query .= '(' . implode(', ', array_keys($values)) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($values)) . ') ';
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			notify("000", "Patient " . $_POST['palname'] . ", " . $_POST['pafname'] . " successfully added.");
			foreach($fields[$table] as $fieldname=>$fieldtype) 
				unset($_POST["$fieldname"]);
			unset($_SESSION['button']);
			$query2 = "SELECT LAST_INSERT_ID() as paid FROM $table ";
			$result2 = mysqli_query($dbhandle,$query2);
			if($result2) {
				$row = mysqli_fetch_array($result2,MYSQLI_ASSOC);
				if($row) {
					clearformvars('customerservice', 'search');
					$_POST['buttonSetSearch']='1';
					$_POST['paid'] = $row['paid'];
				}
			}
		}
		else
			error('001', mysqli_error($dbhandle));
	}
	//execute the SQL query and return records
	mysqli_close($dbhandle);
}
?>