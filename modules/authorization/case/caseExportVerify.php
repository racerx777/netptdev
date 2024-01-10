<?php
function updatePatientExportStatus() {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(21);
	errorclear();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

// UPdate the status of record in cases from the interface
	$updatequery="
		UPDATE cases
		JOIN ptos_interface
		ON crid=xmlcrid
		SET crptosstatus='IMP'
		WHERE crptosstatus='EXP' and xmldatatype='P' and xmlstatus='IMP'
		";

//	$updatequery="
//		UPDATE cases
//		JOIN PTOS_Patients
//		ON crpnum=pnum and crcnum=cnum
//		SET crptosstatus='IMP'
//		WHERE crptosstatus='EXP'
//		";
	if(!$updateresult=mysqli_query($dbhandle,$updatequery))
		error("999","Error retrieving export status of interface<br>$updatequery<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
?>