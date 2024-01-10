<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 

if($_POST['submit']=='Cancel') {
?>
<script type="text/javascript" language="javascript">
window.close();
</script>
<?php
}

if( empty($_POST['crid']) && !empty($_REQUEST['crid']) ) {
	$crid=$_REQUEST['crid'];
	if(!empty($crid)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "
		select * 
		from cases 
			left join master_clinics 
			on crcnum=cmcnum
			left join patients
			on crpaid = paid
			left join doctor_relationships
			on crrefdmid = drdmid and crrefdlid=drdlid
				left join doctors
				on drdmid=dmid
				left join doctor_locations
				on drdlid=dlid
		where crid='$crid'";
	
		if($result = mysqli_query($dbhandle,$query)) {
			if($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				foreach($row as $field=>$value) 
					$_POST["$field"]=$value;
				$status = $_POST['crcasestatuscode'];
				$clinicname = $_POST['cmname'];
				$referraldate = displayDate($_POST['crdate']);
				$evaldate = displayDate($_POST['crapptdate']) . " at " . displayTime($_POST['crapptdate']);
				$therapytype = $_POST['crtherapytypecode'];
				$patientname = properCase($_POST['pafname']. " " . $_POST['palname']);
				$patientaddress = properCase(trim($_POST['paaddress1']). " " . trim($_POST['paaddress2']));
				$patientcitystatezip = properCase($_POST['pacity']) . ", " . strtoupper($_POST['pastate']) . " " . displayZip($_POST['pazip']);
				if(!empty($_POST['dmfname']))
					$dr = $_POST['dmlname'] . ", " . $_POST['dmfname'];
				else
					$dr = $_POST['dmlname'];
				$drphone = displayPhone($_POST['dlphone']);
				$drfax = displayPhone($_POST['dlfax']);
			}
			else 
				echo("Error: Fetch Failed. QUERY: $query");
		}
		else 
			echo("Error: Query Failed. QUERY: $query");
	}
	else 
		echo("Error: Case number not passed to program. CRID: $crid");
}

// POPULATE and FORMAT fields posted
if(!empty($_POST['crfname']))
	$patient = $_POST['crlname'] . ", " . $_POST['crfname'];
else {
	if(!empty($_POST['crlname']))
		$patient = $_POST['crlname'];
	else
		$patient = $default;
}
if(!empty($_POST['crphone1'])) 	
	$phone = displayPhone($_POST['crphone1']);
else
	$phone = $default;
if(!empty($_POST['crphone2'])) 	
	$altphone = displayPhone($_POST['crphone2']);
else
	$altphone = $default;

if(!empty($_POST['crdob'])) 	
	$dob = displayDate($_POST['crdob']);
else
	$dob = $default;
if(!empty($_POST['crinjurydate'])) 	
	$doi = displayDate($_POST['crinjurydate']);
else
	$doi = $default;

if($_POST['crcasestatuscode']=='SCH' || $_POST['crcasestatuscode']=='ACT') { 
	$referralstatus='
<!-- Referral Status BEGIN -->
<tr>
	<td>To Clinic:</td>
	<td>'.$clinicname.'</td>
	<td>Eval Date:</td>
	<td>'.$evaldate.'</td>
</tr>
<!-- Referral Status END -->
';
}

if($_POST['crreadmit']==1) 
	$readmit='Yes';
else
	$readmit='No';

$header='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Scheduling Update Letter</title>
</head>';
$body='
<body style="width:576px">
<table border="0" cellspacing="0" cellpadding="3">
		<tr>
			<td>
				<div>
					<div style="float:left"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
					<div style="float:right">
						<h3>Scheduling Update</h3>
					</div>
				</div>
			</td>
		</tr>';
$footer='
</table>
</body>
</html>';
$patientinfo001='
<tr>
	<td>
		<!-- Patient Information BEGIN -->
		<div style="border:double; border-bottom-color:#000000; width:100%">
			<table border="0" cellspacing="5" cellpadding="3">
				<th style="font-size:14pt" align="left" colspan="4">Patient Information</th>
				<tr>
					<td>Patient Name:</td>
					<td colspan="3">'.$patient.'</td>
				</tr>
				<tr>
					<td>Patient Ph#:</td>
					<td>'.$phone.'</td>
					<td>Alt. Ph#:</td>
					<td>'.$altphone.'</td>
				</tr>
				<tr>
					<td>DOB:</td>
					<td>'.$dob.'</td>
					<td>DOI:</td>
					<td>'.$doi.'</td>
				</tr>
			</table>
		</div>
		<!-- Patient Information END -->
	</td>
</tr>
';

$referral='
<tr>
	<td>
		<!-- Referral Information BEGIN -->
		<div style="border:double; border-bottom-color:#000000; width:100%">
			<table border="0" cellspacing="5" cellpadding="3">
				<th style="font-size:14pt" align="left" colspan="4">Referral Information</th>
				<tr>
					<td>Referral Date</td>
					<td colspan="3">'.$referraldate.'</td>
				</tr>
		'.$referralstatus.'
				<tr>
					<th colspan="4" align="left">
						STATUS: '. $_POST['crcasestatuscode'] .'
					</th>
				</tr>
				<tr>
					<td>Therapy:</td>
					<td style="text-align:left;">'.$therapytype.'</td>
					<td>ReAd/Relo:</td>
					<td style="text-align:left;">'.$readmit.'</td>
				</tr>
			</table>
		</div>
		<!-- Referral Information END -->
	</td>
</tr>
';

$doctor='
<tr>
	<td>
		<!-- Doctor Information BEGIN -->
		<div style="border:double; border-bottom-color:#000000; width:100%">
			<table border="0" cellspacing="5" cellpadding="3">
				<th style="font-size:14pt" align="left" colspan="5">Doctor Information</th>
				<tr>
					<td>Doctor Name:</td>
					<td colspan="3">'.$dr.'</td>
				</tr>
				<tr>
					<td>Doctor Phone#:</td>
					<td>'.$drphone.'</td>
					<td>Fax#:</td>
					<td>'.$drfax.'</td>
				</tr>
			</table>
		</div>
		<!-- Doctor Information END -->
	</td>
</tr>
';

$signature='
<!-- Signature Block BEGIN -->
<tr>
	<td>West-Star Physical Therapy - Scheduling Department<br />Tel: (888) 786-2888 or (714) 236-7959<br />Fax: (866) 295-3343 or (714) 236-8265<br />
	</td>
</tr>
<!-- Signature Block END -->
';

if($status=='PEA') {
	$content='
<tr>
<td>Dear Treating Physician,<br />
<p>The prescription for '.$patientname.' is currently pending authorization for their '.$therapytype.' evaluation and treatment.</p>
</td>
</tr>
'.$patientinfo001.$referral.$doctor;
	$historydata = "Print Pending Auth letter. Dr. $dr.";
}


if($status=='PEN') {
	$content='
<tr>
<td>Dear Treating Physician,<br />
<p>The prescription for '.$patientname.' has been recieved. The patient is currently pending scheduling for their '.$therapytype.' evaluation and treatment.</p>
</td>
</tr>
'.$patientinfo001.$referral.$doctor;
	$historydata = "Print Call Us letter. $patientaddress $patientcitystatezip.";
}


if($status=='SCH') {
	$content='
<tr>
<td>Dear Treating Physician,<br />
<p>'.$patientname.' has been scheduled on '.$evaldate.' at '.$clinicname.' for their physical therapy, acupuncture, or aquatic therapy evaluation and treatment.</p>
</td>
</tr>
'.$patientinfo001.$referral.$doctor;
	$historydata = "Print Scheduled letter. Dr $dr.";
}


if($status=='ACT') {
	$content='
<tr>
<td>Dear Treating Physician,<br />
<p>'.$patientname.' was initially seen on '.$evaldate.' at '.$clinicname.' for their '.$therapytype.' evaluation and treatment.</p>
</td>
<tr>
'.$patientinfo001.$referral.$doctor;
	$historydata = "Print Seen letter. Dr $dr.";
}


if($status=='CAN') {
	$content='
<tr>
<td>Dear Treating Physician,<br />
<p>'.$patientname.' has NOT been seen at the listed time for their physical therapy, acupuncture, or aquatic therapy evaluation and treatment due to:</p>
</td>
</tr>
'.$patientinfo001.$referral.$doctor;
	$historydata = "Print Cancelled letter. Dr $dr.";
}


if($_POST['submit']=='Print') {
	echo $header;
	echo $body;
	echo $content;
	echo $signature;
	echo $footer;
?>
<script type="text/javascript" language="javascript">
window.print();
window.close();
</script>
<?php
}


if($_POST['submit']=='E-mail') {
// Perform e-mail
	$email=$header.$body.$content.$signature.$footer;
	emaildocument
?>
<script type="text/javascript" language="javascript">
window.close();
</script>
<?php
}

if($_POST['submit']=='Print' || $_POST['submit']=='E-mail') {
// Record History
//				require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/scheduling/SQLUpdateFunctions.php');
//				caseschedulinghistoryadd($crid, $historydata);
} 
else {
	if(empty($_POST['submit'])) {
		$form='
<tr>
	<td>
		<form name="SchedulingUpdateLetterForm" method="post" >
			<input id="Cancel" type="submit" name="submit" value="Cancel" />E-mail Address:<input id="email" name="email" value="" /><input type="submit" name="submit" value="E-mail" /><input id="Print" type="submit" name="submit" value="Print" />
		</form>
	</td>
</tr>';
		echo $header;
		echo $body;
		echo $content;
		echo $form;
		echo $footer;
	}
}