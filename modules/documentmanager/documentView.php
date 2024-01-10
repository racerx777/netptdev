<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(99); 
function getFileData($filename){
	if(file_exists($filename)) {
		$handle = fopen($filename,"rb");
		$data = fread($handle, filesize($filename));
		fclose($handle);
		return($data);
	}
	else {
		error("999","Document ".basename($filename)." not found.");
?>
<script>
		window.close();
</script>
<?	}
	return(false);
}
$docpath=$_REQUEST['diappfilepath'];
$docname=$_REQUEST['diappfilename'];
$doctype=$_REQUEST['diappfiletype'];
$filename1=$docpath."/".$docname;
// View
if($buf=getFileData($filename1)) {
	$len = strlen($buf);
	header("Content-type: application/$doctype");
	header("Content-Length: $len");
	header("Content-Disposition: inline; filename=$docname");
	print $buf;
}
?>