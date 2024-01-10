<?php
$instructions=array();
$_SESSION['headerspace']="";

$instructions['default']= "<ul><u>Customer Service Instructions</u>
	<li>Enter patient search information - at least one field required.</li>
	<li>Click 'Search' to search patient information for existing patient.</li>
	<li>Click 'Add' button to add new patient, or select from existing patients.</li>
	<li>Click 'Clear' to clear search results and start over.</li>
</ul>";
$instructions['Edit Patient'] = "<ul><u>Edit Patient Instructions</u>
	<li>Please update all necessary information.</li>
	<li>Click the 'Update' button to save and return to the Search Patients screen.</li>
	<li>Click the 'Cancel' button to return to the Search Patients screen without updating.</li>
</ul>";
$instructions['Patient Status Report'] = "<ul><u>Patient Status Report Instructions</u>
	<li>Enter From and Thru date - This selects referrals received between these dates.</li>
	<li>Select Print Detail and/or Summary to specify reports desired.</li>
	<li>Click the 'Print Patient Status Report' button to generate the report in a new window.</li>
</ul>";

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$rapen=array();
$raquery  = "
	SELECT crcasestatuscode, count(*) as cntra 
	FROM cases
	WHERE crreadmit=1
	group by crcasestatuscode
	";
if($raresult = mysqli_query($dbhandle,$raquery)) {
	while($rarow = mysqli_fetch_assoc($raresult)) {
		$crcasestatuscode=$rarow['crcasestatuscode'];
		$rapen["$crcasestatuscode"] = $rarow['cntra'];
	}
}

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
		$crcasestatuscode=$row['crcasestatuscode'];
		if(empty($rapen["$crcasestatuscode"]))
			$rapen["$crcasestatuscode"]=0;
		$statusarray[] = "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['cntstatus'] . " " . strtoupper($row['crsmdescription']." (".$rapen[$crcasestatuscode]." Readmits)") ;
	}
}

if(isset($instructions[$_SESSION['button']]))
	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
else
	$_SESSION['headerspace'] = $instructions['default'];
$_SESSION['headerspace'] = '<div style="float:left;">' . $_SESSION['headerspace'] . '</div>';
$_SESSION['headerspace'] .= '<div style="float:left;"><ul><u>Queue Status:</u><li>' . implode("</li><li>", $statusarray) . '</li></ul></div>';
$_SESSION['init']['customerservice']=1;
?>