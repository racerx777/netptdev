<!-- '%FDF-1.2
1 0 obj<</FDF<< /Fields[
<</T(topmostSubform[0].Page1[0].Patient_name[0])/V(<?php echo $values['patientname'] ?>)>>
<</T(topmostSubform[0].Page1[0].BOB[0])/V(<?php echo $values['dob'] ?>)>>
<</T(topmostSubform[0].Page1[0].DOI[0])/V(<?php echo $values['doi'] ?>)>>
<</T(topmostSubform[0].Page1[0].Employer[0])/V(<?php echo $values['employer'] ?>)>>
<</T(topmostSubform[0].Page1[0].Claim_number[0])/V(<?php echo $values['claim_number'] ?>)>>

<</T(topmostSubform[0].Page1[0].provider_name[0])/V(<?php echo $values['providername'] ?>)>>
<</T(topmostSubform[0].Page1[0].Practice_name[0])/V(<?php echo $values['practicename'] ?>)>>
<</T(topmostSubform[0].Page1[0].Address[0])/V(<?php echo $values['paddress'] ?>)>>
<</T(topmostSubform[0].Page1[0].City__State__Zip[0])/V(<?php echo $values['pcitystatezip'] ?>)>>
<</T(topmostSubform[0].Page1[0].Phone_Number[0])/V(<?php echo $values['pphone'] ?>)>>
<</T(topmostSubform[0].Page1[0].Fax_number[0])/V(<?php echo $values['pfax'] ?>)>>
<</T(topmostSubform[0].Page1[0].Provider_specialty[0])/V(<?php echo $values['speciality'] ?>)>>
<</T(topmostSubform[0].Page1[0].provider_state_license_number[0])/V(<?php echo $values['psln'] ?>)>>
<</T(topmostSubform[0].Page1[0].National_provider_ID_number[0])/V(<?php echo $values['pnpi'] ?>)>>

<</T(topmostSubform[0].Page1[0].Claims_administrator[0])/V(<?php echo $values['claimsadministrator'] ?>)>>
<</T(topmostSubform[0].Page1[0].Adjustor[0])/V(<?php echo $values['claimsadjuster'] ?>)>>
<</T(topmostSubform[0].Page1[0].Claims_administrator_address[0])/V(<?php echo $values['caddress'] ?>)>>
<</T(topmostSubform[0].Page1[0].Claims_administrator_City__State__Zip[0])/V(<?php echo $values['ccitystatezip'] ?>)>>
<</T(topmostSubform[0].Page1[0].Claims_admistrator_Phone_number[0])/V(<?php echo $values['cphone'] ?>)>>
<</T(topmostSubform[0].Page1[0].Claimd_Administror_Fax_number[0])/V(<?php echo $values['cfax'] ?>)>>

<</T(topmostSubform[0].Page1[0].Diagnosis[0])/V(<?php echo $values['diagnosis'] ?>)>>
<</T(topmostSubform[0].Page1[0].ICD_Code[0])/V(<?php echo $values['icd'] ?>)>>
<</T(topmostSubform[0].Page1[0].Procedure_Requested[0])/V(<?php echo $values['procedurerequested'] ?>)>>
<</T(topmostSubform[0].Page1[0].CPTHCPCS_Code[0])/V(<?php echo $values['cptcode'] ?>)>>
<</T(topmostSubform[0].Page1[0].Other_Information_Frequency_Duration_Quantity_Facility_etc[0])/V(<?php echo $values['other'] ?>)>>
] >> >>
endobj
trailer
<</Root 1 0 R>>
%%EOF' -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Authorization Request</title>
</head>
<!-- onLoad="window.print();window.opener.location.reload(true);window.close();" -->
<body style="font:Verdana, Arial, Helvetica, sans-serif; font-size:14px;" onLoad="window.print();window.opener.location.reload(true);">
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
<div align="center" style="text-align:left; border-style:solid; border:thin; border-color:#FFFFFF;">
  <table border="0" cellspacing="1" cellpadding="3" width="100%">
    
      <th style="font-size:14pt" align="left" colspan="2">Request Information</th>
    <tr>
      <td nowrap="nowrap">Patient Name:</td>
      <td><?php echo $values['patientname'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">DOB:</td>
      <td nowrap="nowrap"><?php echo $values['dob'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Date Of Injury #:</td>
      <td><?php echo $values['doi'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Employer:</td>
      <td><?php echo $values['employer'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Claim NUmber:</td>
      <td><?php echo $values['claim_number'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Provider Name:</td>
      <td><?php echo $values['providername'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Practice Name:</td>
      <td><?php echo $values['practicename'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Address:</td>
      <td><?php echo $values['paddress'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">City__State__Zip:</td>
      <td><?php echo $values['pcitystatezip'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Phone Number:</td>
      <td><?php echo $values['pphone'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Fax Number:</td>
      <td><?php echo $values['pfax'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Provider Specialty:</td>
      <td><?php echo $values['speciality'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Provider State License Number:</td>
      <td><?php echo $values['psln'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">National Provider ID Number:</td>
      <td><?php echo $values['pnpi'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Claims Administrator:</td>
      <td><?php echo $values['claimsadministrator'] ?></td>
    </tr>
	<tr>
      <td nowrap="nowrap">Adjuster:</td>
      <td><?php echo $values['claimsadjuster'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Claims Administrator Address:</td>
      <td><?php echo $values['ccitystatezip'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Claims Administrator Address:</td>
      <td><?php echo $values['ccitystatezip'] ?></td>
    </tr>
	<tr>
      <td nowrap="nowrap">Claims Admistrator Phone number:</td>
      <td><?php echo $values['cphone'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Claims Admistrator Fax number:</td>
      <td><?php echo $values['cfax'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Diagnosis:</td>
      <td><?php echo $values['diagnosis'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">ICD_Code:</td>
      <td><?php echo $values['icd'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Procedure Requested:</td>
      <td><?php echo $values['procedurerequested'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">CPTHCPCS Code:</td>
      <td><?php echo $values['cptcode'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap">Other Information:</td>
      <td><?php echo $values['other'] ?></td>
    </tr>
    
    <tr>
      <td>Enclosed are the following documents:</td>
      <td><?php echo $rfadocumentdescription; ?></td>
    </tr>
  </table>
</div>
<div style="font-size:small; color:#BBBBBB">(<?php echo $row['cpid']." ".$rfapatientname; ?>)</div>
</body>
</html>