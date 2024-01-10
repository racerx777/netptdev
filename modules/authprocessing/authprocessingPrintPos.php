<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
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
		set cprfaposprinteddate='$printedtime', cprfaposprinteduser='$printeduser'
		where cpid='$cpid'
		";
		if($updateresult = mysqli_query($dbhandle,$updatequery)) {
			require_once('authprocessingHistory.php');
			rxAddHistory($cpid, 'Printed Proof of Service');
		}
	}
	$query = "
	select * 
	from case_prescriptions
	left join cases 
		on cpcrid=crid
	left join master_clinics 
		on cpcnum=cmcnum
			left join master_provider_groups
				on cmpgmcode=pgmcode
				left join master_business_units
					on pgmbumcode=bumcode
	left join doctors
		on cpdmid=dmid
	left join doctor_locations
		on cpdlid=dlid
	left join patients
		on crpaid=paid
	where cpid='$cpid'";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			if(empty($row['cricclaimnumber1']))
				$posclaimnumber="[default claim number goes here]";
			else
				$posclaimnumber=$row['cricclaimnumber1'];

			if(empty($row['cprfaprinteduser']))
				$posdeclarantname="[default declarant name goes here]";
			else
				$posdeclarantname=getUserNameByUser($row['cprfaprinteduser']);
$row['cpposdeclarantcounty']="ORANGE";
			if(empty($row['cpposdeclarantcounty']))
				$posdeclarantcounty="[default declarant county goes here]";
			else
				$posdeclarantcounty=$row['cpposdeclarantcounty'];
$row['cpposdeclarantaddress']="PO BOX 2197, LOS ALAMITOS, CA 90720";
			if(empty($row['cpposdeclarantaddress']))
				$posdeclarantaddress="[default declarant address goes here]";
			else
				$posdeclarantaddress=$row['cpposdeclarantaddress'];

			if(empty($row['cprfaprinteddate']))
		//	$posdate=displayDate(date('m/d/Y'));
				$posdate="[default proof of service date goes here]";
			else
				$posdate=displayDate($row['cprfaprinteddate']);

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
					$postoaddressline1=$toaddress[0];
					$postoaddressline2=$toaddress[1];
					$postoaddressline3=$toaddress[2];
					$postoaddressline4=$toaddress[3];
					$postoaddressline5=$toaddress[4];
				}
			}

//			if(empty($row['cpposdocumentdescription']))
//				$posdocumentdescription="[default proof of service document description goes here]";
//			else
//				$posdocumentdescription=$row['cpposdocumentdescription'];
			$posdocumentdescription='AUTHORIZATION REQUEST + PRESCRIPTION';
			$bumcode=$row['bumcode'];
			$bumtaxid=$row['bumtaxid'];
			$bumaddressquery = "
				select * 
				from master_business_units_locations
				where bulmcode='$bumcode' and bulmname='POBOX'
				";
			if($bumaddressresult = mysqli_query($dbhandle,$bumaddressquery)) {
				if($bumaddressrow=mysqli_fetch_assoc($bumaddressresult)) {
					$row['cpposfromaddressline1']=$bumaddressrow['bulmaddress0'];
					$row['cpposfromaddressline2']=
						$bumaddressrow['bulmaddress1'] . ", " . 
						$bumaddressrow['bulmcity'] . ", " . 
						$bumaddressrow['bulmstate'] . ", " . 
						$bumaddressrow['bulmzip'];
					$row['cpposfromaddressline3']="TAX ID#=$bumtaxid";
					$row['cpposfromaddressline4']="&nbsp;";
					$row['cpposfromaddressline5']=$bumaddressrow['bulmphone'] . " Telephone &nbsp;&bull;&nbsp; " . $bumaddressrow['bulmfax'] . " Facsimile";
					$row['cppospostofficecitystate']=$bumaddressrow['bulmcity'] . ", " . $bumaddressrow['bulmstate'];
				}
			}
			if(empty($row['cppospostofficecitystate']))
				$pospostofficecitystate="[default proof of service post office city and state goes here]";
			else
				$pospostofficecitystate=$row['cppospostofficecitystate'];

//$posaddressfromline1="PO BOX 160<br>LOS ALAMITOS,CA 90720<br>Tax ID#:33-0574098<br>Phone:714-827-4822<br>Fax:714-252-5711";

			if(empty($row['cpposfromaddressline1']))
				$posfromaddressline1="[DEFAULT PROOF OF SERVICE FROM ADDRESS LINE 1 GOES HERE]";
			else
				$posfromaddressline1=$row['cpposfromaddressline1'];
		
			if(empty($row['cpposfromaddressline2']))
				$posfromaddressline2="[DEFAULT PROOF OF SERVICE FROM ADDRESS LINE 2 GOES HERE]";
			else
				$posfromaddressline2=$row['cpposfromaddressline2'];
		
			if(empty($row['cpposfromaddressline3']))
				$posfromaddressline3="[DEFAULT PROOF OF SERVICE FROM ADDRESS LINE 3 GOES HERE]";
			else
				$posfromaddressline3=$row['cpposfromaddressline3'];
		
			if(empty($row['cpposfromaddressline4']))
				$posfromaddressline4="[DEFAULT PROOF OF SERVICE FROM ADDRESS LINE 4 GOES HERE]";
			else
				$posfromaddressline4=$row['cpposfromaddressline4'];

			if(empty($row['cpposfromaddressline5']))
				$posfromaddressline5="[DEFAULT PROOF OF SERVICE FROM ADDRESS LINE 5 GOES HERE]";
			else
				$posfromaddressline5=$row['cpposfromaddressline5'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Proof of Service</title>
</head>
<!-- window.close(); -->
<body style="font:Verdana, Arial, Helvetica, sans-serif; font-size:14px;" onload="window.print();">
<h1 align="center">PROOF OF SERVICE BY MAIL</h1>
<h1 align="center">ORANGE COUNTY</h1>
<h2 align="center">STATE OF CALIFORNIA</h2>
<p>&nbsp;</p>
<p>Claim No. <?php echo $posclaimnumber; ?></p>
<p>&nbsp;</p>
<p>I, <u><?php echo $posdeclarantname; ?></u> declare:</p>
<p>I am a citizen of the United States, a resident of <u><?php echo $posdeclarantcounty; ?> County</u>, and am over 18 years of age. I am not a party to the within entitled action. My business/residence address is <u>
	<?php echo $posdeclarantaddress; ?>
	</u>. On <?php echo $posdate; ?>, I served a copy of the attached <u><?php echo $posdocumentdescription; ?></u> in this action by placing a true copy thereof, enclosed in a sealed envelope with postage thereon fully prepaid, in the United States mail at <u><?php echo $pospostofficecitystate; ?></u>, addressed as follows:</p>
<p>
<table>
	<tr height="25px">&nbsp;
		<td rowspan="6" width="72px">&nbsp;<td>
	</tr>
	<tr>
		<td><?php echo "$postoaddressline1"; ?> </td>
	</tr>
	<tr>
		<td><?php echo "$postoaddressline2"; ?> </td>
	</tr>
	<tr>
		<td><?php echo "$postoaddressline3"; ?> </td>
	</tr>
	<tr>
		<td><?php echo "$postoaddressline4"; ?> </td>
	</tr>
	<tr>
		<td><?php echo "$postoaddressline5"; ?> </td>
	</tr>
</table>
</p>
<p>&nbsp;</p>
<p>I declare, under penalty of perjury, that the foregoing is true and correct.</p>
<p>

<table>
	<tr>
		<td>__________________________________________________</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><?php echo date("m/d/Y"); ?></td>
	</tr>
	<tr>
		<td align="center">Signature of <?php echo $posdeclarantname; ?>, Declarant</td>
		<td>&nbsp;</td>
		<td align="center">Date</td>
	</tr>
	<tr>
		<td style="padding-left: 120px;"><?php echo $_SESSION['user']['umemail']?></td>
		<td></td>
		<td>&nbsp;</td>
	</tr>
</table>
</p>
<p>&nbsp;</p>
<p>
<div align="center" style="text-align:center">
	<table width="100%">
		<tr>
			<td style="font-weight:bold"><?php echo $posfromaddressline1; ?></td>
		</tr>
		<tr>
			<td style="font-weight:bold"><?php echo $posfromaddressline2; ?></td>
		</tr>
		<tr>
			<td style="font-weight:bold"><?php echo $posfromaddressline3; ?></td>
		</tr>
		<tr>
			<td style="font-weight:bold"><?php echo $posfromaddressline4; ?></td>
		</tr>
		<tr>
			<td style="font-weight:bold"><?php echo $posfromaddressline5; ?></td>
		</tr>
	</table>
</div>
</p>
<div style="font-size:small; color:#BBBBBB">(<?php echo $row['cpid']; ?>)</div>
</body>
</html>
<?php
		}
		else 
			echo("1");
	}
	else 
		echo("2");
}
?>
