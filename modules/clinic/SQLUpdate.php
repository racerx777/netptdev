<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
errorclear();
$script = 'SQLUpdate';
$table = 'master_clinics';
$keyfield = 'cmcnum';
$fields[$table]=array(
				'cminactive'=>'boolean',
				'cmpgmcode'=>'code',
				'cmcnum'=>'code',
				'cmname'=>'name',
				'cmaddress1'=>'name',
				'cmaddress2'=>'name',
				'cmcity'=>'name',
				'cmstate'=>'name',
				'cmzip'=>'zip',
				'cmemail'=>'email',
				'cmphone'=>'phone',
				'cmfax'=>'phone'
			);
if(isset($_SESSION['id'])) {
	// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}

	if(!isset($_POST['cminactive'])) 
		$_POST['cminactive']=0;

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
			if($result = mysqli_query($dbhandle,$query)) {
				$sel_query = "SELECT * FROM master_clinics WHERE latitude='".$lat."' AND longitude='".$lng."' AND cmcnum='".$_SESSION['id']."'";
				$result_id = mysqli_query($dbhandle,$sel_query);
				if($result_id) {
				}else{
					#A4 1310 3rd Avenue Ste A4 Chula Vista, CA 91911
					$address = $_POST['cmaddress2'] .' '. $_POST['cmaddress1'].' '. $_POST['cmcity'].','. $_POST['cmstate'].' '. $_POST['cmzip'];
					// $address = str_replace(' ', '+', $address);
					$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyCtgQzrja4KtWMZoko3V9SShu_XUa7gsPs&sensor=false";
					$geocode = file_get_contents($url);
					$json = json_decode($geocode);
					$lat = $json->results[0]->geometry->location->lat;
					$lng = $json->results[0]->geometry->location->lng;
					$place_id = $json->results[0]->place_id;
					$update="UPDATE master_clinics SET latitude='".$lat."', longitude='".$lng."', placeId='".$place_id."' WHERE cmcnum='".$_SESSION['id']."'";
					$updateresult=mysqli_query($dbhandle,$update);
					notify("001", "Clinic " . $values['cmname'] . ", " . displayPhone($values['cmphone']) . " successfully updated.");
	//				foreach($fields[$table] as $fieldname=>$fieldtype) 
	//					unset($_POST["$fieldname"]);
					unset($_SESSION['button']);
				}
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