<?php
$instructions=array();
$_SESSION['headerspace']="";
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query  = "
	SELECT crsmdspseq, crcasestatuscode, crsmdescription, count(*) as cntstatus 
	FROM cases
	LEFT JOIN master_casestatus ON crcasestatuscode = crsmcode
	GROUP BY crsmdspseq, crcasestatuscode, crsmdescription
	ORDER BY crsmdspseq, crcasestatuscode, crsmdescription
	";
$statusarray=array();
if($result = mysqli_query($dbhandle,$query)) {
	while($row = mysqli_fetch_assoc($result)) {
		$statusarray[] = "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['cntstatus'] . " " . strtoupper($row['crsmdescription']) ;
	}
}

if(isset($instructions[$_SESSION['button']]))
	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
else
	$_SESSION['headerspace'] = $instructions['default'];

$_SESSION['headerspace'] = '<div style="float:left;">' . $_SESSION['headerspace'] . '</div>';
$_SESSION['headerspace'] .= '<div style="float:left;"><ul><u>Queue Status:</u><li>' . implode("</li><li>", $statusarray) . '</li></ul></div>';
$_SESSION['init']['sales']=1;
?>