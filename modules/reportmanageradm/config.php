<?php
$_SESSION['init']['reportmanageradm']=1;
function getReportDescription($id) {
	$selectquery="SELECT * FROM report_template_header where rthid='$id'";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if($selectrow=mysqli_fetch_assoc($selectresult)) 
			return($selectrow['rthdescription']);
		else
			return('Report Type Not Specified');
	}
	else
		error("999","reportType:SELECT error.<br>$selectquery<br>".mysqli_error($dbhandle));
}
function getReportTypes() {
	$reporttypes=array();
	$selectquery="SELECT * FROM report_template_header order by rthsequence";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		while($selectrow=mysqli_fetch_assoc($selectresult)) 
			$reporttypes[]=$selectrow;
	}
	else
		error("999","reportType:getReportTypes:SELECT report_template_header error.<br>$selectquery<br>".mysqli_error($dbhandle));
	return($reporttypes);
}
?>