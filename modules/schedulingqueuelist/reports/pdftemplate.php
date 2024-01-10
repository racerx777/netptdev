<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$html = "";
// Select Call Record $callid
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$user = getuser();
//lockuser = '$user' AND
$callquery = "
		SELECT * 
		FROM case_scheduling_queue 
			LEFT JOIN cases 
			ON csqcrid=crid 
			LEFT JOIN patients 
			ON crpaid=paid 
			LEFT JOIN doctors
			ON crrefdmid=dmid
			LEFT JOIN doctor_locations
			ON crrefdlid=dlid
			WHERE crcasestatuscode = 'PEN'
			AND csqschcalldate < (NOW() + INTERVAL 1 MONTH) AND csqschcalldate >= (NOW() - INTERVAL 1 MONTH)
			ORDER BY csqpriority, csqschcalldate, csqid desc
			";
//}

$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Scheduling Queue List Report </title>
</head>
<body>
<div style="float:left"><img src="../wsptn_logo_bw_outline.jpg" width="300px"></div>
<div style="float:right;margin-right:100px;">
	<h1>Scheduling Queue List Report </h1>
</div>
<div style="clear:both;">';

$html .= '<div class="containedBox">
  
      <table border="1" cellpadding="3" cellspacing="0" width="100%" >
        <tr>
          <th>Case Number</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Scheduled Date</th>
          <th>DOB</th>
          <th>Clinic</th>
          <th>S1 csqpriority</th>
          <th>S2 csqschcalldate</th>
          <th>S3 csqid</th>
          <th>Case Status</th>
        </tr>';
    if($callresult = mysqli_query($dbhandle,$callquery)) {
        while ($row = mysqli_fetch_assoc($callresult)) {
        	
        		// open edit case page
				$parm=array();
				$parm[]="button[".$row['crid']."]=Edit Case" ;
				$urlparm=urlencode(implode("&", $parm));

				// open edit patient page
				$pparm=array();
				$pparm[]="button[".$row['paid']."]=Edit Patient" ;
				$urlpparm=urlencode(implode("&", $pparm));

				// go to scheduling queue
				$qparm=array();
				$qparm[]="button[".$row['csqid']."]=fromschedulequeuelist" ;
				$urlqparm=urlencode(implode("&", $qparm));

			$crcnum = "";

			if(!empty($row['crcnum'])) $crcnum = $row['crcnum']; else $crcnum = "Not Assigned";

    		$html .= '<tr>
	          <td>'.$row['crid'].'</td>
	          <td>'.$row['palname'].'</td>
	          <td>'. $row['pafname'].'</td>
	          <td>'. displayDate($row['csqschcalldate']).'</td>
	          <td>'. displayDate($row['padob']).'</td>
	          <td>'.$crcnum.'</td>
	          <td>'. $row['csqpriority'].'</td>
	          <td>'. $row['csqschcalldate'].'</td>
	          <td>'. $row['csqid'].'</td>
	          <td>'. $row['crcasestatuscode'].'</td>
	        </tr>';
    
         } 
    }
    $html .= '</table>
    
</div>';

$html .= "Print Date: ".date('Y-m-d');