<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(13); 

function getFileData($filename){
	if(file_exists($filename)) {
		$handle = fopen($filename,"rb");
		$data = fread($handle, filesize($filename));
		fclose($handle);
		return($data);
	}
	else {
		error("999","Report ".$filename." was never generated. Please click Generate to create the report.");
?>
<script>
	window.close();
</script>
<?	
	}
	return(false);
}

$rhid=$_REQUEST['rhid'];
$popup=true;
require_once('reportGeneratorFunctions.php');
$report=getReport($rhid);
$filename1=$report['header']['rhreportname'];
//	$filename1=$reportname['nameandname'];
//	$filename1=$_SERVER['DOCUMENT_ROOT']."/modules/documentmanager/documents/".$reportname.".pdf";
//$filename1=$_SERVER['DOCUMENT_ROOT'].'/modules/documentmanager/documents/Report_'.$rhid.'.pdf';
$shortfilename1=basename($filename1);
// View
if($buf=getFileData($filename1)) {
	$len = strlen($buf);
	header("Content-type: application/pdf");
	header("Content-Length: $len");
	header("Content-Disposition: inline; filename=$shortfilename1");
	print $buf;
}
?>