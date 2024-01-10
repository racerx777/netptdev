<?php
// unset($_SESSION['clinics']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

// $query = "SELECT cmcnum, cmname FROM master_clinics ";
// if(isuserlevel(20))
// 	$query .= "WHERE cminactive = 0 ";
// else
// 	$query .= "WHERE cminactive = 0 and cmcnum='" . getuserclinic() . "' ";
// $query .= " ORDER BY cmcnum";
// $result_id = mysqli_query($dbhandle,$query);
// $numRows = mysqli_num_rows($result_id);
// $clinicsarray=array();
// for($i=1; $i<=$numRows; $i++) {
// 	$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
// 	if($result) 
// 		$clinicsarray[$result['cmcnum']] = $result['cmname'];
// }
// $_SESSION['clinics']=$clinicsarray;
// $_SESSION['init']['clinic']=1;

// $instructions=array();
// $_SESSION['headerspace']="";

// $instructions['default']= "<ul><u>Attorney Instructions</u>
// 	<li>Enter search information - at least one field required.</li>
// 	<li>Click 'Search' to search information.</li>
// </ul>";
// $instructions['Attorney Report'] = "<ul><u>Attorney Report Instructions</u>
// 	<li>Enter From and Thru date - This selects referrals scheduled between these dates.</li>
// 	<li>Select Print Detail and/or Summary to specify reports desired.</li>
// 	<li>Click the 'Print Attorney Report' button to generate the report in a new window.</li>
// </ul>";

// if(isset($instructions[$_SESSION['button']]))
// 	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
// else
// 	$_SESSION['headerspace'] = $instructions['default'];

// $_SESSION['init']['attorney_report']=1;
?>