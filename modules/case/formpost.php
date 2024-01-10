<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
// print_r($_POST);die;
if(!empty($_POST['editbtn'])){
	$id = $_POST['editbtn'];
	$select = "select * from attorney where id='$id'";
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
		if($key != 'edit_id' && $key != 'attorney_form'){
			if($val != ''){
				$cols[] = "$key = '$val'";
			}
		}
	}
	$where = "id = '".$_POST['edit_id']."'";
	$sql = "UPDATE attorney SET " . implode(', ', $cols) . " WHERE $where";
	if($updateresult = mysqli_query($dbhandle,$sql)) {
		echo "success";
	}else{
		echo "Error";
	}
	die;
}

if(!empty($_POST['attorney_form']) && ($_POST['attorney_form'] == 'add')){
	$insertvalues = array();
	foreach($_POST as $key=>$val) {
		if($key != 'attorney_form'){
			if($val == ''){
				$val = 'NULL';
			}
			$insertvalues[] = "$key = '$val'";
		}
	}
	$insertquery = "INSERT INTO attorney SET " . implode(", ", $insertvalues);
	if($insertresult = mysqli_query($dbhandle,$insertquery)) {
		$selectquery2 = "SELECT LAST_INSERT_ID() as aid FROM attorney";
		if($selectresult2 = mysqli_query($dbhandle,$selectquery2)) {
			if($selectrow2 = mysqli_fetch_assoc($selectresult2)) {
				$firminsertquery = "INSERT INTO attorney_firm SET firm_id = '".$selectrow2['aid']."', firm_name = '".$_POST['firm']."'";
				if(mysqli_query($dbhandle,$firminsertquery)){
					echo "success";
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
?>