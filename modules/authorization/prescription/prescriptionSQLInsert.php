<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
errorclear();
if(!empty($crid)) {
	// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}

	// Validate form fields
	require_once('prescriptionValidation.php');
	
	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		if(empty($_POST['cpdate']))
			$cpdate = "NULL, ";
		else
			$cpdate = "'" . mysqli_real_escape_string($dbhandle,dbDate($_POST['cpdate'])) . "', ";
		if(empty($_POST['cpexpiredate']))
			$cpexpiredate = "NULL, ";
		else
			$cpexpiredate = "'" . mysqli_real_escape_string($dbhandle,dbDate($_POST['cpexpiredate'])) . "', ";
		
		//declare the SQL statement that will query the database
		$query = "INSERT INTO case_prescriptions ";
		$query .= "(cpcrid, cpdx1, cpdx2, cpdx3, cpdx4, cpdx5 , cpdx6 ,cpdx7 , cpdx8 , cpdx9 , cpdx10 ,cpdx11,cpdx12 , cpfrequency, cpduration, cptotalvisits, cpttmcode, cpdmid, cpdlid, cpdate, cpexpiredate, cptherap, cpcnum, cpnote, cpstatuscode, cpstatusupdated,  crtdate, crtuser, crtprog) ";
		$query .= "VALUES(";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$crid) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpdx1']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpdx2']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpdx3']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpdx4']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx5']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx6']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx7']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx8']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx9']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx10']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx11']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx12']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpfrequency']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpduration']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cptotalvisits']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpttmcode']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpdmid']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpdlid']) . "', ";
		$query .= $cpdate;
		$query .= $cpexpiredate;
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cptherap']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpcnum']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpnote']) . "', ";
		$query .= "'NEW', ";
		$query .= "NOW(), ";
//		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cpauthstatuscode']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['crtdate']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['crtuser']) . "', ";
		$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['crtprog']) . "' ";
		$query .= ")";
//	notify("000",$query);
		//execute the SQL query 
		if($result = mysqli_query($dbhandle,$query)) {
			notify("000","Record successfully inserted.");
			foreach($_POST as $key=>$val) 
				unset($_POST[$key]);
		}
		else 
			error('001', "Error Inserting Record : " . mysqli_error($dbhandle)); 	
		//close the connection
		mysqli_close($dbhandle);
	}
}
else 
	error('000', "Error crid : $crid");
?>