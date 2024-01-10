<?php
function updatenetptfromptos($pnum) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	errorclear();
	if(isset($pnum)) {
		// Connect to database
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

		$query = "
SELECT bnum, cnum, lname, fname
FROM patients_active
WHERE pnum='$pnum'
";
		unset($patients_active_bnum);
		unset($patients_active_cnum);
		unset($patients_active_lname);
		unset($patients_active_fname);
		if($result=mysqli_query($dbhandle,$query)) {
			if($patients_active_row=mysqli_fetch_assoc($result)) {
				$patients_active_bnum = $patients_active_row['bnum'];
				$patients_active_cnum = $patients_active_row['cnum'];
				$patients_active_lname = $patients_active_row['lname'];
				$patients_active_fname = $patients_active_row['fname'];
				notify("000", "Found '$patients_active_bnum $pnum $patients_active_fname $patients_active_lname' in clinic $patients_active_cnum.");
			}
		}

		$query = "
SELECT count(*) as treatment_header_count_name_active
FROM treatment_header
WHERE thcnum='$patients_active_cnum' and thlname='$patients_active_lname' and thfname = '$patients_active_fname' and thpnum='$pnum'
";
		$treatment_header_count_name_active=0;
		if($result=mysqli_query($dbhandle,$query)) {
			if($treatment_header_row=mysqli_fetch_assoc($result)) {
				$treatment_header_count_name_active = $treatment_header_row['treatment_header_count_name_active'];
				notify("000", "$treatment_header_count_name_active treatments found for '$pnum $patients_active_fname $patients_active_lname' in clinic $patients_active_cnum.");
			}
		}
		if($treatment_header_count_name_active==0) {
			$query = "
	UPDATE patients_active a
	JOIN patients_ptos p
	ON a.pnum = p.pnum
	SET a.bnum=p.bnum, a.cnum=p.cnum, a.lname=p.lname, a.fname=p.fname
	WHERE a.pnum='$pnum'
	";
			$query = "
	UPDATE patients_active a
	JOIN PTOS_Patients p
	ON a.pnum = p.pnum
	SET a.bnum=p.bnum, a.cnum=p.cnum, a.lname=p.lname, a.fname=p.fname
	WHERE a.pnum='$pnum'
	";
			$result = mysqli_query($dbhandle,$query);
			if($result) {
				notify("000", "Active Patient List with PNUM=$pnum was updated.");
			}
			else
				error("001", mysqli_error($dbhandle));
		}
		else
			error("002", "Active Patient List with PNUM=$pnum was NOT UPDATED. Please correct the treatments for '$patients_active_fname $patients_active_lname' before updating the patient list.");
		//close the connection
		mysqli_close($dbhandle);
	}
	else
		error("003", "Error: Missing pnum.");
}
?>