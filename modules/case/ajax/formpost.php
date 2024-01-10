<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/injury.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/clinic.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/therapist.options.php');
$dbhandle = dbconnect();
if(!empty($_POST['editbtn'])){
	$id = $_POST['editbtn'];
	$select = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE attorney.id='$id'";
	if($selectresult = mysqli_query($dbhandle,$select)) { 
		if($selectrow = mysqli_fetch_assoc($selectresult)) {
			echo json_encode($selectrow);die;
		}else{
			error("002", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		}
		mysqli_close($dbhandle);
	}
}

if(!empty($_POST['attorney_form']) && ($_POST['attorney_form'] == 'edit')){
	$cols = array();
	foreach($_POST as $key=>$val) {
		if(($key != 'edit_id') && ($key != 'attorney_form') && ($key != 'paid_id')){
			if($val != ''){
				if($key == 'firm'){
					$val = $_POST['edit_id'];
				}
				$cols[] = "$key = '$val'";
			}else{
				$val = 'NULL';
				$cols[] = "$key = ".$val;
			}
		}
	}
	$where = "id = '".$_POST['edit_id']."'";
	$sql = "UPDATE attorney SET " . implode(', ', $cols) . " WHERE $where";
	if($updateresult = mysqli_query($dbhandle,$sql)) {
		$update_sql = "UPDATE `attorney_firm` SET firm_name = '".$_POST['firm']."' WHERE firm_id = '".$_POST['edit_id']."'";
		if($updateresult = mysqli_query($dbhandle,$update_sql)) {
			$show_txt = $_POST['firm'].' | '.$_POST['city'] .', '.$_POST['zip'];
			echo json_encode(array('aoption' => referring_attorney_option($dbhandle),'resulttxt'=> $show_txt));
		}
	}else{
		echo "Error";
	}
	die;
}

if(!empty($_POST['attorney_form']) && ($_POST['attorney_form'] == 'add')){
	$insertvalues = array();
	foreach($_POST as $key=>$val) {
		if(($key != 'attorney_form') && ($key != 'paid_id')){
			if($val == ''){
				$insertvalues[] = "$key = NULL";
			}else{
				$insertvalues[] = "$key = '$val'";
			}
		}
	}
	$insertquery = "INSERT INTO attorney SET " . implode(", ", $insertvalues);
	if($insertresult = mysqli_query($dbhandle,$insertquery)) {
		$selectquery2 = "SELECT LAST_INSERT_ID() as aid FROM attorney";
		if($selectresult2 = mysqli_query($dbhandle,$selectquery2)) {
			if($selectrow2 = mysqli_fetch_assoc($selectresult2)) {
				$firminsertquery = "INSERT INTO attorney_firm SET firm_id = '".$selectrow2['aid']."', firm_name = '".$_POST['firm']."'";
				if(mysqli_query($dbhandle,$firminsertquery)){
					$update_sql = "UPDATE `attorney` SET firm= '".$selectrow2['aid']."' WHERE id = '".$selectrow2['aid']."'";
					if($updateresult = mysqli_query($dbhandle,$update_sql)) {
						$show_txt = $_POST['firm'].' | '.$_POST['city'] .', '.$_POST['zip'];
						echo json_encode(array('aoption' => referring_attorney_option($dbhandle),'id'=>$selectrow2['aid'],'resulttxt'=> $show_txt));
					}
				}else{
					echo "Error";
				}
			}
		}
	}else{
		echo "Error";
	}
	die;
}

function referring_attorney_option($dbhandle){
	$att_query = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id ORDER BY `attorney`.`name_first` ASC";
	$referring_attorney_value = '';
	$att_test = mysqli_query($dbhandle,$att_query);
	$i = 1;
	while($att_row = mysqli_fetch_array($att_test)){
		if($i == 1){
			$referring_attorney_value .= '<option value="" data-text=""></option>';
		}
		if(!empty($att_row['name_middle'])){
			$middle_name = $att_row['name_middle'];
		}else{
			$middle_name = '';
		}
		$city = $att_row['city'];
		$zip = $att_row['zip'];
		if(!empty($city) && !empty($zip)){
			$show_text = ' | '.$city .', '.$zip; 
		}elseif(!empty($city)){
			$show_text = ' | '.$city;
		}elseif(!empty($zip)){
			$show_text = ' | '.$zip;
		}else{
			$show_text = '';
		}
		$name = $att_row['name_first'].' '.$middle_name.' '.$att_row['name_last'];
		$select_text = $att_row['firm_name'].' '.$show_text;
		$referring_attorney_value .= '<option value="'.$att_row['id'].'" data-text="'.$select_text.'">'.$name.'</option>';
		$i++;
	}
	return $referring_attorney_value;
}

?>