<?php
$instructions=array();
$_SESSION['headerspace']="";

$instructions['default']= "<ul><u>Attendance Instructions</u>
	<li>Enter search information - at least one field required.</li>
	<li>Click 'Search' to search information.</li>
</ul>";
$instructions['Attendance Report'] = "<ul><u>Attendance Report Instructions</u>
	<li>Enter From and Thru date - This selects referrals scheduled between these dates.</li>
	<li>Select Print Detail and/or Summary to specify reports desired.</li>
	<li>Click the 'Print Attendance Report' button to generate the report in a new window.</li>
</ul>";

if(isset($instructions[$_SESSION['button']]))
	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
else
	$_SESSION['headerspace'] = $instructions['default'];

$_SESSION['init']['attendance']=1;

?>