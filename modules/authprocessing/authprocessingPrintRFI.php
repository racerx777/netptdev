<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
// Required Information from POST form variables:
//		$cpid - Case Prescription Identifier
//		$today - Today's Date
// 		$pnum - Patient Account Number
//		Address Arrays - Each array contains fname (or blank), lname, address1, address2, city, state, zip
// 			$patient[] - Patient Address Array
// 			$employer[] - Employer Address Array
// 			$attorney[] - Applicant Attorney Address Array
// 		$injurydate - Patient Date of Injury
// 		$fvisit, $lvisit - Transaction Date Range
// 		$username - Signature Name
// 		$usertitle - Signature Title
//
//	Values Retrieved:
//		Business Unit Name and Address
//		Clinic Name and Location
?>
<script>
document.title="Print Request for Insurance"
</script>
<?php
//dumppost();
if(!empty($_POST['cpid']))
	$cpid=$_POST['cpid'];
if(!empty($_POST['crid']))
	$crid=$_POST['crid'];
if(!empty($_POST['paid']))
	$paid=$_POST['paid'];
  
if(!empty($_POST['today']))
	$today=$_POST['today'];
if(!empty($_POST['injurydate']))
	$injurydate=$_POST['injurydate'];
if(!empty($_POST['fvisit']))
	$fvisit=$_POST['fvisit'];
if(!empty($_POST['lvisit']))
	$lvisit=$_POST['lvisit'];

if(!empty($_POST['bnum']))
	$bnum=$_POST['bnum'];
if(!empty($_POST['cnum']))
	$cnum=$_POST['cnum'];
if(!empty($_POST['pnum']))
	$pnum=$_POST['pnum'];

if(!empty($_POST['signedname']))
	$signedname=$_POST['signedname'];
if(!empty($_POST['signedtitle']))
	$signedtitle=$_POST['signedtitle'];

// retrieve these 
// Clinic
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/clinic.options.php");
$clinicrow = getMaster_Clinics(NULL, $cnum);
$clinic['name'] = $clinicrow['cmname'];
$clinic['city'] = $clinicrow['cmcity'];
$bnum=$clinicrow['cmbnum'];
function fetchbusiness($bnum) {
	$buquery = "
			select * 
			from master_business_units b
			LEFT JOIN master_business_units_locations l
			ON bumcode=bulmcode
			where bumcode='$bnum' and bulmname='POBOX'
			";
	$array=array();
	if($buresult = mysqli_query($dbhandle,$buquery)) {
		if($burow=mysqli_fetch_assoc($buresult)) {
			$array['0']=$burow['bulmaddress0'];
			$array['1']=$burow['bulmaddress1'].", ".$burow['bulmcity'].", ".$burow['bulmstate']." ".$burow['bulmzip'];
			$array['2']="TAX ID#:".$burow['bumtaxid'];
			$array['3']="TEL:".$burow['bulmphone'];
			$array['4']="FAX:".$burow['bulmfax'];
			return($array);
		}
	}
	return(false);
}
if(!empty($cpid)) {
// Get Business Unit Information
	if(!empty($bnum)) 
		$business = fetchbusiness($bnum);

// Referring Doctor
			$doctorarray=array();
			$locationarray=array();
			if(empty($row['cpdmid']))
				$rfareferringdoctor="[PRESCRIPTION REFERRING DOCTOR GOES HERE]";
			else {
				require_once($_SERVER['DOCUMENT_ROOT'] . "/common/doctor.options.php");
				$doctorarray=getDoctorList($row['cpdmid'],"1");
				if(!empty($row['cpdlid'])) 
					$locationarray=getDoctorLocationList($row['cpdmid'], $row['cpdlid'], "1");
				$fname=$doctorarray[$row['cpdmid']]['dmfname'];
				$lname=$doctorarray[$row['cpdmid']]['dmlname'];
				if(empty($fname))
					$name="$lname";
				else
					$name="$lname, $fname";		
				$rfareferringdoctor=$name;
			}

// Referring Doctor Location
			if(empty($row['cpdlid']))
				$rfareferringdoctorlocation="[PRESCRIPTION REFERRING DOCTOR LOCATION GOES HERE]";
			else {
				$phone=displayPhone($locationarray[$row['cpdlid']]['dlphone']);
				$city=$locationarray[$row['cpdlid']]['dlcity'];
				$state=$locationarray[$row['cpdlid']]['dlstate'];
				if(empty($state))
					$name="$city, CA $phone";
				else
					$name="$city, $state $phone";		
				$rfareferringdoctorlocation=$name;
			}

			$treatmenttypeoptions=therapyTypeOptions();
			$treatmenttype=$treatmenttypeoptions[$prescription['cpttmcode']]['title'];

			$documentdescription='REQUEST FOR INSURANCE INFORMATION';
			$underline="______________________________";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insurance Information Request</title>
</head>
<body style="font:Verdana, Arial, Helvetica, sans-serif; font-size:14px;" onLoad="window.print();window.opener.location.reload(true);window.close();">
<div align="center"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
<p><h2 align="center"><?php echo $documentdescription; ?></h2></p>
<div style="float:right">
<p><?php echo "Date: ".today(); ?>
<table>
	<tr height="25px">&nbsp;
		<td rowspan="6" width="72px">&nbsp;<td>
	</tr>
	<tr>
		<td><?php echo strtoupper($rfatoaddressline1); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($rfatoaddressline2); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($rfatoaddressline3); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($rfatoaddressline4); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($rfatoaddressline5); ?></td>
	</tr>
</table>
</p>
</div><div style="clear:both"></div>
<p>
<table>
	<tr>
		<td nowrap="nowrap">Regarding:</td>
		<td><?php echo strtoupper($patient['name']); ?></td>
	</tr>
	<tr>
		<td nowrap="nowrap">Account #:</td>
		<td><?php echo strtoupper($pnum); ?></td>
	</tr>
	<tr>
		<td nowrap="nowrap">Date(s) of Injury:</td>
		<td><?php echo strtoupper($injurydate); ?></td>
	</tr>
	<tr>
		<td nowrap="nowrap">Date(s) of Service:</td>
		<td><?php echo strtoupper($fvisit).'-'.strtoupper($lvisit); ?></td>
	</tr>
</table>
</p>
<p><?php echo properCase($patient['name']); ?> was referred to our <?php echo properCase($clinic['name']); ?> clinic for <?php echo properCase($treatmenttype['name']); ?> services in reference a <?php echo properCase($casetype['name']); ?> injury. </p>
<p> We need to bill for these services; however, have been unable to do so as we have yet to be provided with the correct insurance information. At this time we are asking that you provide us with the following information: </p>
<div align="center" style="text-align:left; border-style:solid; border:thin; border-color:#FFFFFF;">
	<table border="0" cellspacing="1" cellpadding="3" >
		<th style="font-size:14pt" align="left" colspan="2">Request Insurance Information</th>
		<tr>
			<td nowrap="nowrap">Insurance Carrier Name</td>
			<td><?php echo $underline; ?></td>
		</tr>
		<tr>
			<td nowrap="nowrap">Address:</td>
			<td nowrap="nowrap"><?php echo $underline; ?></td>
		</tr>
		<tr>
			<td nowrap="nowrap">City, State, Zip:</td>
			<td><?php echo $underline; ?></td>
		</tr>
		<tr>
			<td nowrap="nowrap">Telephone Number:</td>
			<td><?php echo $underline; ?></td>
		</tr>
		<tr>
			<td nowrap="nowrap">Claim/Policy Number:</td>
			<td><?php echo $underline; ?></td>
		</tr>
		<tr>
			<td nowrap="nowrap">Adjuster Name:</td>
			<td><?php echo $underline; ?></td>
		</tr>
	</table>
</div>
<p>Sincerely,<br />
<?php echo properCase($signedname); ?><br />
<?php echo properCase($signedtitle); ?>
</p>
<hr />
<table>
	<tr>
		<td><?php echo strtoupper($business['0']); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($business['1']); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($business['2']); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($business['3']); ?></td>
	</tr>
	<tr>
		<td><?php echo strtoupper($business['4']); ?></td>
	</tr>
</table>
<div style="font-size:small; color:#BBBBBB"><?php echo "($bnum/$pnum/$cpid/$crid/$paid)"; ?></div>
</body>
</html>
<?php
}
else 
	echo("CPID empty");
?>
