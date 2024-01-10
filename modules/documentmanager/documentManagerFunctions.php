<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function requestArchive($entity, $lname, $pnum, $fname, $clinic, $docdate, $filename, $docref=NULL, $notes=NULL, $appname=NULL, $docname=NULL, $docid=NULL, $attach=NULL, $words=NULL, $filepath=NULL, $filetype=NULL) {
	$auditfields=getauditfields();
	$crtdate=$auditfields['date'];
	$crtuser=$auditfields['user'];
	$crtprog=$auditfields['prog'];
	$insert="INSERT INTO D2M_interface SET 
	distatus='REQUESTED', 
	difile_status=NULL, 
	diarchive_status=NULL, 
	diappname='$appname', 
	diappdocname='$docname', 
	diappdocdate='$docdate', 
	diappdocid='$docid', 
	difilewithDOCTYPE='$attach', 
	diDOCENTITY='$entity', 
	diDOCFOLDER='$lname', 
	diDOCTYPE='$pnum', 
	diDOCSOURCE='$fname', 
	diIMPORTANCE='$clinic', 
	diDOCREF=NULL,
	diNOTES=NULL,
	diWORDS='$words', 
	diappfilepath='$filepath', 
	diappfilename='$filename', 
	diappfiletype='$filetype', 
	crtdate='$crtdate', 
	crtuser='$crtuser', 
	crtprog='$crtprog'
	";
	if(!mysqli_query($dbhandle,$insert)) {
		error("","Error inserting request to archive report<br />$insert<br />".mysqli_error($dbhandle));
		return(false);
	}
	else 
		return(true);
}
?>