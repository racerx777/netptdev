<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
errorclear();

if (!empty($cpid)) {
	// trim and strip all input
	foreach ($_POST as $key => $val) {
		if ($key != 'button') {
			if (is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}

	// Validate form fields
	require_once('prescriptionValidation.php');

	if (errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();

		$set = array();
		//declare the SQL statement that will query the database

		$cpdxArray = array();
		for ($i = 1; $i <= 12; $i++) {
			$key = 'cpdx' . $i;
			if(!empty($_POST[$key]) && $_POST[$key] != 'Please select'){
				$cpdxArray[] = $_POST[$key];
			}
		}
		for ($j = 1; $j <= 12; $j++) {
			$new_key = 'cpdx' . $j;
			$search_key = intval($j)-1;
			if(!empty($cpdxArray[$search_key]) && $cpdxArray[$search_key] != 'Please select'){
				$_POST[$new_key] = $cpdxArray[$search_key];
			}else{
				$_POST[$new_key] = 'Please select';
			}
		}

		if (isset($_POST['cpdx1'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx1'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx1'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			}
		}

		if (isset($_POST['cpdx2'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx2'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx2'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}


		if (isset($_POST['cpdx3'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx3'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx3'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}



		if (isset($_POST['cpdx4'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx4'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx4'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}



		if (isset($_POST['cpdx5'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx5'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx5'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}



		if (isset($_POST['cpdx6'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx6'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx6'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}


		if (isset($_POST['cpdx7'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx7'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx7'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}



		if (isset($_POST['cpdx8'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx8'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx8'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}


		if (isset($_POST['cpdx9'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx9'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx9'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}
		if (isset($_POST['cpdx10'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx10'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx10'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}
		if (isset($_POST['cpdx11'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx11'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx11'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}
		if (isset($_POST['cpdx12'])){
			$query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $_POST['cpdx12'] . "'";
			$result1 = mysqli_query($dbhandle, $query1);
			if ($result1) {
				if ($row = mysqli_fetch_assoc($result1)) {
				

					$imicdCount = $row['imicdCount'] + 1;
					$Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $_POST['cpdx12'] . "'";

					$UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
					
				} else {
				}
			} else {
				echo "Error: " . mysqli_error($dbhandle);
			
		}
		$query = "UPDATE case_prescriptions ";
	
		if (isset($_POST['cpcrid']))
			$set[] = "cpcrid ='" . mysqli_real_escape_string($dbhandle, $_POST['cpcrid']) . "'";
		if (isset($_POST['cpdx1']))
			$set[] .= "cpdx1 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx1']) . "'";
		else
			$set[] .= "cpdx1 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx2']))
			$set[] .= "cpdx2 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx2']) . "'";
		else
			$set[] .= "cpdx2 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx3']))
			$set[] .= "cpdx3 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx3']) . "'";
		else
			$set[] .= "cpdx3 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx4']))
			$set[] .= "cpdx4 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx4']) . "'";
		else
			$set[] .= "cpdx4 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx5']))
			$set[] .= "cpdx5 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx5']) . "'";
		else
			$set[] .= "cpdx5 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx6']))
			$set[] .= "cpdx6 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx6']) . "'";
		else
			$set[] .= "cpdx6 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx7']))
			$set[] .= "cpdx7 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx7']) . "'";
		else
			$set[] .= "cpdx7 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx8']))
			$set[] .= "cpdx8 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx8']) . "'";
		else
			$set[] .= "cpdx8 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx9']))
			$set[] .= "cpdx9 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx9']) . "'";
		else
			$set[] .= "cpdx9 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx10']))
			$set[] .= "cpdx10 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx10']) . "'";
		else
			$set[] .= "cpdx10 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx11']))
			$set[] .= "cpdx11 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx11']) . "'";
		else
			$set[] .= "cpdx11 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpdx12']))
			$set[] .= "cpdx12 ='" . mysqli_real_escape_string($dbhandle, $_POST['cpdx12']) . "'";
		else
			$set[] .= "cpdx12 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
		if (isset($_POST['cpfrequency']))
			$set[] .= "cpfrequency='" . mysqli_real_escape_string($dbhandle, $_POST['cpfrequency']) . "'";
		if (isset($_POST['cpduration']))
			$set[] .= "cpduration='" . mysqli_real_escape_string($dbhandle, $_POST['cpduration']) . "'";
		if (!empty($_POST['cptotalvisits']))
			$set[] .= "cptotalvisits='" . mysqli_real_escape_string($dbhandle, $_POST['cptotalvisits']) . "'";
		else
			$set[] .= "cptotalvisits=NULL";
		if (isset($_POST['cpttmcode']))
			$set[] .= "cpttmcode='" . mysqli_real_escape_string($dbhandle, $_POST['cpttmcode']) . "'";
		if (isset($_POST['cpdmid']))
			$set[] .= "cpdmid='" . mysqli_real_escape_string($dbhandle, $_POST['cpdmid']) . "'";
		if (isset($_POST['cpdlid']))
			$set[] .= "cpdlid='" . mysqli_real_escape_string($dbhandle, $_POST['cpdlid']) . "'";
		if (!empty($_POST['cpdate']))
			$set[] .= "cpdate='" . mysqli_real_escape_string($dbhandle, dbDate($_POST['cpdate'])) . "'";
		else
			$set[] .= "cpdate=NULL";
		if (!empty($_POST['cpexpiredate']))
			$set[] .= "cpexpiredate='" . mysqli_real_escape_string($dbhandle, dbDate($_POST['cpexpiredate'])) . "'";
		else
			$set[] .= "cpexpiredate=NULL";
		if (isset($_POST['cptherap']))
			$set[] .= "cptherap='" . mysqli_real_escape_string($dbhandle, $_POST['cptherap']) . "'";
		if (isset($_POST['cpcnum']))
			$set[] .= "cpcnum='" . mysqli_real_escape_string($dbhandle, $_POST['cpcnum']) . "'";
		if (isset($_POST['cpnote']))
			$set[] .= "cpnote='" . mysqli_real_escape_string($dbhandle, $_POST['cpnote']) . "'";
		if (count($set) > 0)
			$query .= "SET " . implode(', ', $set);
		$query .= " WHERE cpid='$cpid'";
		//dump("query",$query);
		//execute the SQL query 
		$result = mysqli_query($dbhandle, $query);
		if ($result) {
			$_SESSION['notify'][] = "Record successfully updated.";
			foreach ($_POST as $key => $val)
				unset($_POST[$key]);
		} else
			error('001', "Error Updating Record : " . mysqli_error($dbhandle));
		//close the connection
		mysqli_close($dbhandle);
	}
} else
	error('000', "Error cpid : $cpid");
?>