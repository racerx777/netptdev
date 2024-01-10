<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
?>
<script>
document.title="Print Request for Authorization"
</script>
<?php
if(!empty($_POST['cpid']))
	$cpid=$_POST['cpid'];

if(!empty($_GET['cpid']))
	$cpid=$_GET['cpid'];

if(!empty($cpid)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if(!empty($_GET['printed'])) {
		$printedtime = date("Y-m-d H:i:s", time());
		$printeduser = getuser();
		$updatequery = "
		update case_prescriptions
		set cprfastatuscode='PRT', cprfastatususer='$printeduser', cprfastatusupdated='$printedtime', cprfaprinteddate='$printedtime', cprfaprinteduser='$printeduser'
		where cpid='$cpid'
		";
		if($updateresult = mysqli_query($dbhandle,$updatequery)) {
			require_once('authprocessingHistory.php');
			rxAddHistory($cpid, 'Printed Request for Authorization');
		}
	}	
	$query = "
			select * 
			from case_prescriptions
			left join cases 
				on cpcrid=crid
			left join master_clinics 
				on cpcnum=cmcnum
			left join doctors
				on cpdmid=dmid
			left join doctor_locations
				on cpdlid=dlid
			left join patients
				on crpaid=paid
			where cpid='$cpid'
			";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
				$icid1=$row['cricid1'];
				$iclid1=$row['criclid1'];
				$icaid1=$row['cricaid1'];
				$i1query = "
					select * 
					from insurance_companies
					left join insurance_companies_locations
						on icid=iclicid
					left join insurance_companies_adjusters 
						on icid=icaicid
					where icid='$icid1' and iclid='$iclid1' and icaid='$icaid1'"
					;
			if($i1result = mysqli_query($dbhandle,$i1query)) {
				if($i1row=mysqli_fetch_assoc($i1result)) {
					$toaddress=array();
					if(!empty($i1row['icname']))
						$toaddress[]=$i1row['icname'];
				
					if(!empty($i1row['icladdress1']))
						$toaddress[]=$i1row['icladdress1'];
				
					if(!empty($i1row['icladdress2']))
						$toaddress[]=$i1row['icladdress2'];
	
					$city=$i1row['iclcity'];
					$state=$i1row['iclstate'];
					$zip=displayZip($i1row['iclzip']);
					if(!empty($city) && !empty($state) && !empty($zip))
						$toaddress[]="$city, $state $zip";
	
					$fname=$i1row['icafname'];
					$lname=$i1row['icalname'];
					if(!empty($fname) && !empty($lname))
						$toaddress[]="ATTN: $fname $lname";
					$rfatoaddressline1=$toaddress[0];
					$rfatoaddressline2=$toaddress[1];
					$rfatoaddressline3=$toaddress[2];
					$rfatoaddressline4=$toaddress[3];
					$rfatoaddressline5=$toaddress[4];
				}
			}
			$buquery = "
					select * 
					from master_business_units b
					LEFT JOIN master_business_units_locations l
					ON bumcode=bulmcode
					where bumcode='".$row['cmbnum']."' and bulmname='POBOX'
					";
			if($buresult = mysqli_query($dbhandle,$buquery)) {
				if($burow=mysqli_fetch_assoc($buresult)) {
					$rfaremittoaddressline1=$burow['bulmaddress0'];
					$rfaremittoaddressline2=$burow['bulmaddress1'].", ".$burow['bulmcity'].", ".$burow['bulmstate']." ".$burow['bulmzip'];
					$rfaremittoaddressline3="TAX ID#:".$burow['bumtaxid'];
					$rfaremittoaddressline4="TEL:".$burow['bulmphone'];
					$rfaremittoaddressline5="FAX:".$burow['bulmfax'];
				}
			}
				if(empty($row['cprfaprinteddate']))
			//	$posdate=displayDate(date('m/d/Y'));
					$rfadate="[REQUEST DATE GOES HERE]";
				else
					$rfadate=displayDate($row['cprfaprinteddate']);
				
				if(empty($row['cprfaprinteduser']))
			//	$posdate=displayDate(date('m/d/Y'));
					$rfaauthorizer="[AUTHORIZER NAME GOES HERE]";
				else
					$rfaauthorizer=getUserNameByUser($row['cprfaprinteduser']);
				
				$fname=$row['pafname'];
				$lname=$row['palname'];
				if(empty($fname)||empty($lname))
					$rfapatientname="[PATIENT NAME GOES HERE]";
				else
					$rfapatientname="$fname $lname";
			
				if(empty($row['passn']))
					$rfapatientssn="[PATIENT SSN GOES HERE]";
				else
					$rfapatientssn=displaySsnAll($row['passn']);
			
				if(empty($row['cricclaimnumber1']))
					$rfacaseclaimnumber="[CASE CLAIM NUMBER GOES HERE]";
				else
					$rfacaseclaimnumber=$row['cricclaimnumber1'];
			
				if(empty($row['crempname']))
					$rfacaseempname="[CASE EMPLOYEE NAME GOES HERE]";
				else
					$rfacaseempname=$row['crempname'];

				if(empty($row['crinjurydate']))
					$rfacaseinjurydate="[CASE DATE OF INJURY GOES HERE]";
				else
					$rfacaseinjurydate=displayDate($row['crinjurydate']);

				unset($rfasx);
				if(!empty($row['crpostsurgical'])) {
					if(!empty($row['crsurgerydate'])) 
						$rfasx = "(SURGERY " . displayDate($row['crsurgerydate']) . ")";
					else
						$rfasx = "(POST SURGICAL PATIENT)";
				}

				if(empty($row['cpdx']))
					$rfarxdiagnosis="[PRESCRIPTION DIAGNOSIS GOES HERE]";
				else
					$rfarxdiagnosis=$row['cpdx'];

$icd9array=icd9CodeOptions();
$dxarray=array();
				if(!empty($row['cpdx1']))
					$dxarray[]=$icd9array[$row['cpdx1']]['description'] . "(".$row['cpdx1'].")";
				if(!empty($row['cpdx2']))
					$dxarray[]=$icd9array[$row['cpdx2']]['description'] . "(".$row['cpdx2'].")";
				if(!empty($row['cpdx3']))
					$dxarray[]=$icd9array[$row['cpdx3']]['description'] . "(".$row['cpdx3'].")";
				if(!empty($row['cpdx4']))
					$dxarray[]=$icd9array[$row['cpdx4']]['description'] . "(".$row['cpdx4'].")";
				$rfaicd91=$dxarray[0];
				$rfaicd92=$dxarray[1];
				$rfaicd93=$dxarray[2];
				$rfaicd94=$dxarray[3];

// body parts
				if(empty($row['crdxbodypart']))
					$rfabodyparts="[PRESCRIPTION BODY PART(S) GOES HERE]";
				else {
					require_once($_SERVER['DOCUMENT_ROOT'] . '/common/injury.options.php');
					$injurybodyparts=getInjuryBodypartTypeOptions($row['crdxbodypart']);
					$rfabodyparts=$injurybodyparts[0]['title'];
				}
// Referring Doctor
				$doctorarray=array();
				$locationarray=array();
//dump("row['cpdmid']",$row['cpdmid']);
//dump("row['cpdlid']",$row['cpdlid']);
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

//dump("doctorarray",$doctorarray);
//dump("locationarray",$locationarray);

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

				if(empty($row['cmname']))
					$rfaservicinglocation="[PRESCRIPTION SERVICING LOCATION GOES HERE]";
				else
					$rfaservicinglocation=strtoupper($row['cmname']) . "(" . strtoupper($row['cmcity']) .")";

				if(empty($row['cptherap']))
					$rfaservicingtherapist="[PRESCRIPTION SERVICING THERAPIST GOES HERE]";
				else {
					require_once($_SERVER['DOCUMENT_ROOT'] . "/common/therapist.options.php");
					$therapists=getTherapistTypeOptions(NULL, NULL, $row['cptherap']);
					$rfaservicingtherapist=$therapists[0]['title'];
				}

				if(empty($row['cpfrequency']) || empty($row['cpduration']))
					$rfatreatmentduration="[PRESCRIPTION FREQUENCY GOES HERE]";
				else
					$rfatreatmentduration=$row['cpfrequency']."x".$row['cpduration'];
			
				if(empty($row['crapptdate']))
					$rfacasefirstvisitdate="PENDING AUTHORIZATION";
				else
					$rfacasefirstvisitdate=displayDate($row['crapptdate']);
			
//				if(empty($row['crnumberofvisits']))
//					$rfacasenumberofvisits="[CASE NUMBER OF VISITS GOES HERE]";
//				else
				if(empty($row['crnumberofvisits']))
					$rfacasenumberofvisits="0";
				else
					$rfacasenumberofvisits=$row['crnumberofvisits'];

				$treatmenttypeoptions=therapyTypeOptions();
				if(empty($row['cpttmcode']))
					$rfatreatmenttype="[PRESCRIPTION TREATMENT TYPE GOES HERE]";
				else
					$rfatreatmenttype=strtoupper($treatmenttypeoptions[$row['cpttmcode']]['title']);

//				if(empty($row['cpdocumentdescription']))
//					$rfadocumentdescription="[PRESCRIPTION DOCUMENT DESCRIPTION GOES HERE]";
//				else
//					$rfadocumentdescription=$row['cpdocumentdescription'];
					$rfadocumentdescription='AUTHORIZATION REQUEST AND PRESCRIPTION';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Authorization Request</title>
</head>
<script type="text/javascript">
	function printPage(){
		var css = '@page { size: portrait; }',
	    head = document.head || document.getElementsByTagName('head')[0],
	    style = document.createElement('style');

		style.type = 'text/css';
		style.media = 'print';

		if (style.styleSheet){
		  style.styleSheet.cssText = css;
		} else {
		  style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);

		window.print();
	}
</script>
<!-- onLoad="window.print();window.opener.location.reload(true);window.close();" -->
<body style="font:Verdana, Arial, Helvetica, sans-serif; font-size:14px;" onLoad="printPage()">
<div align="center"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
<p>
<h2 align="center">********** AUTHORIZATION REQUEST **********</h2>
</p>
<div style="float:right">
<p>
<table>
  <tr height="25px">&nbsp;
    <td rowspan="6" width="72px">&nbsp; 
    <td>
  </tr>
  <tr>
    <td><?php echo "$rfatoaddressline1"; ?></td>
  </tr>
  <tr>
    <td><?php echo "$rfatoaddressline2"; ?></td>
  </tr>
  <tr>
    <td><?php echo "$rfatoaddressline3"; ?></td>
  </tr>
  <tr>
    <td><?php echo "$rfatoaddressline4"; ?></td>
  </tr>
  <tr>
    <td><?php echo "$rfatoaddressline5"; ?></td>
  </tr>
</table>
</p>
</div><div style="clear: both"></div>
<p>
<table>
  <tr>
    <td nowrap="nowrap">Date:</td>
    <td><?php echo $rfadate; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap">Authorization Contact:</td>
    <td><?php echo $rfaauthorizer; ?></td>
  </tr>
</table>
</p>
<p>
<ul>
  <li>Labor Code 4610(g)(1): Prospective or concurrent decisions shall be made in a timely fashion that is appropriate for the nature of the employee's condition, not to exceed five working days from the receipt of the information reasonably necessary to make the determination, but in no event more than 14 days from the date of the medical treatment recommendation by the physician... </li>
  <br />
  <li>Labor Code 4610(e): No person other than a licensed physician who is competent to evaluate the specific clinical issues involved in the medical treatment services, and where these services are within the scope of the physician's practice, requested by the physician may modify, delay, or deny requests for authorization of medical treatment for reasons of medical necessity to cure and relieve.</li>
</ul>
</p>
<div align="center" style="text-align:left; border-style:solid; border:thin; border-color:#FFFFFF;">
  <table border="0" cellspacing="1" cellpadding="3" width="100%">
    
      <th style="font-size:14pt" align="left" colspan="2">Request Information</th>
    <tr>
      <td nowrap="nowrap">Patient Name:</td>
      <td><?php echo $rfapatientname; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Social Security No:</td>
      <td nowrap="nowrap"><?php echo $rfapatientssn; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Claim #:</td>
      <td><?php echo $rfacaseclaimnumber; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Employer:</td>
      <td><?php echo $rfacaseempname; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Date Of Injury:</td>
      <td><?php echo "$rfacaseinjurydate $rfasx"; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Complete Diagnosis:</td>
      <td><?php echo $rfadiagnosis; ?></td>
    </tr>
    <tr>
      <td valign="top" rowspan="4" nowrap="nowrap">ICD-9 Code(s):</td>
      <td><?php echo $rfaicd91; ?></td>
    </tr>
    <tr>
      <td><?php echo $rfaicd92; ?></td>
    </tr>
    <tr>
      <td><?php echo $rfaicd93; ?></td>
    </tr>
    <tr>
      <td><?php echo $rfaicd94; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Body Part(s):</td>
      <td><?php echo $rfabodyparts; ?></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap">Referring Physician:</td>
      <td><?php echo $rfareferringdoctor."<br>".$rfareferringdoctorlocation; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Servicing Location:</td>
      <td><?php echo $rfaservicinglocation; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Servicing Therapist:</td>
      <td><?php echo $rfaservicingtherapist; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Treatment Duration:</td>
      <td><?php echo $rfatreatmentduration; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Date of First Treatment:</td>
      <td><?php echo $rfacasefirstvisitdate; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Number of Visits Received:</td>
      <td><?php echo $rfacasenumberofvisits; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Treatment Type:</td>
      <td><?php echo $rfatreatmenttype; ?></td>
    </tr>
    <tr>
      <td>Enclosed are the following documents:</td>
      <td><?php echo $rfadocumentdescription; ?></td>
    </tr>
  </table>
</div>
<p>We are to be informed if there is a delay or denial on the requested treatment citing the ACOEM Guidelines and the adjuster must provide a clear and concise explanation of the reason(s) for the decision, a description of the criteria or guidelines used, and clinical reasons for the decisions regarding medical necessity.</p>
<hr />
<p>
<table>
  <tr height="30em">
    <td nowrap="nowrap">Treatment authorized/denied by: </td>
    <td>__________________________________________________</td>
  </tr>
  <tr height="30em">
    <td nowrap="nowrap">Date authorized/denied: </td>
    <td>____________________</td>
  </tr>
  <tr height="30em">
    <td>Frequency/Duration authorized: </td>
    <td colspan="3">____________________</td>
  </tr>
  <tr height="30em">
    <td>Reason(s) for Denial: </td>
    <td colspan="3">__________________________________________________</td>
  </tr>
  <tr height="30em">
    <td>&nbsp;</td>
    <td colspan="3">__________________________________________________</td>
  </tr>
  <tr height="30em">
    <td>&nbsp;</td>
    <td colspan="3">__________________________________________________</td>
  </tr>
  <tr height="30em">
    <td>Signature: </td>
    <td>__________________________________________________</td>
  </tr>
  <tr height="30em">
    <td>Signature Date: </td>
    <td>____________________</td>
  </tr>
</table>
</p>
<p>Pursuant to Labor Code 4610(g)(1): Failure to respond within the allotted amount of time (5 days) will result in assumption that the requested treatment was authorized.</p>
<p>Sincerely,<br />
  <?php echo $rfaauthorizer; ?></p>
<p>Mail both pages to the address listed below:</p>
<p align="center">
<table style="text-align:center; font-weight:bold;">
  <tr>
    <td><?php echo $rfaremittoaddressline1; ?></td>
  </tr>
  <tr>
    <td><?php echo $rfaremittoaddressline2; ?></td>
  </tr>
  <tr>
    <td><?php echo $rfaremittoaddressline3; ?></td>
  </tr>
  <tr>
    <td><?php echo $rfaremittoaddressline4; ?></td>
  </tr>
  <tr>
    <td><?php echo $rfaremittoaddressline5; ?></td>
  </tr>
</table>
</p>
<div style="font-size:small; color:#BBBBBB">(<?php echo $row['cpid']." ".$rfapatientname; ?>)</div>
</body>
</html>
<?php
			}
			else 
				echo("FETCH failed. $query<br>".mysqli_error($dbhandle));
		}
		else 
			echo("SELECT QUERY failed. $query<br>".mysqli_error($dbhandle));
//	}
//	else 
//		echo("UPDATE QUERY failed. $update<br>".mysqli_error($dbhandle));
}
else 
	echo("CPID empty");
?>
