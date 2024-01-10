<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Authorization Request</title>
</head>
<!-- onLoad="window.print();window.opener.location.reload(true);window.close();" -->
<!-- onLoad="window.print();window.opener.location.reload(true);" -->
<body style="font:Verdana, Arial, Helvetica, sans-serif; font-size:14px;" >
<div id="rfapdf">
<h3 align="center">
  State of California, Division of Workers' Compensation
</h3>
<h2 align="center">REQUEST FOR AUTHORIZATION<h2>
<h3 align="center">DWC Form RFA</h3>
<p>
  Attach the Doctor's First Report of Occupational Injury or Illness, Form DLSR 5021, a Treating Physician's Progress Report, DWC Form PR-2 , or equivalent narrative report substantiating the requested treatment.
</p>
<table width="100%" border="1">
    <tr>
      <td style="border: none;">
        <input type="checkbox" name="newRequest">New Request
      </td>
      <td style="float: right;border: none">
        <input type="checkbox" name="newRequest" width="50%">Resubmission - Change in Material Facts
      </td>
    </tr>
    <tr>
      <td style="border: none;" colspan="2">
        <input type="checkbox" name="newRequest">Expedited Review: Check box if employee faces an imminent and serious threat to his or her health
      </td>
      <td style="border: none;" colspan="2">
        <input type="checkbox" name="newRequest">Check box if request is a written confirmation of a prior oral request.
      </td>
    </tr>
</table>
<table width="100%" border="1">
    <tr class="bggray">
      <td colspan="2" class="bggray">Employee Information</td>
    </tr>
    <tr>
      <td style="border: none;" colspan="2"> Name (Last, First, Middle) : <?php echo $values['patientname'] ?> </td>
    </tr>
    <tr>
      <td>
        Date of Injury (MM/DD/YYYY): <?php echo date("d/m/Y",strtotime($values['doi'])); ?>
      </td>
      <td>
        Date of Birth (MM/DD/YYYY): <?php echo date("d/m/Y",strtotime($values['dob'])); ?>
      </td>
    </tr>
    <tr>
      <td>
        Claim Number: <?php echo $values['claim_number'] ?>
      </td>
      <td>
        Employer: <?php echo $values['employer'] ?>
      </td>
    </tr>
</table>
<table width="100%" border="1">
    <tr class="bggray">
      <td colspan="2" class="bggray">
        Requesting Physician Information
      </td>
    </tr>
    <tr>
      <td style="border: none;" colspan="2">
        Name: <?php echo $values['providername'] ?>
      </td>
    </tr>
    <tr>
      <td>
        Practice Name: <?php echo $values['practicename'] ?>
      </td>
      <td>
        Contact Name: 
      </td>
    </tr>
    <tr>
      <td>
        Address: <?php echo $values['paddress'] ?>
      </td>
      <td>
        <table width="100%">
          <tr>
            <td width="70%" style="border-right: 1px solid;">City: <?php echo $values['city'] ?></td>
            <td width="30%">State: <?php echo $values['state'] ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" >
          <tr>
            <td width="50%" style="border-right: 1px solid;">Zip Code: <?php echo $values['zip'] ?></td>
            <td width="50%">Phone: <?php echo $values['pphone'] ?></td>
          </tr>
        </table>
      </td>
      <td>
        Fax Number: <?php echo $values['pfax'] ?>
      </td>
    </tr>
    <tr>
      <td>
        Specialty: <?php echo $values['speciality'] ?>
      </td>
      <td>
        NPI Number: <?php echo $values['pnpi'] ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        E-mail Address:
      </td>
    </tr>
</table>
<table width="100%" border="1">
    <tr class="bggray">
      <td colspan="2" id="bggray">
        Claims Administrator Information
      </td>
    </tr>
    <tr>
      <td>Insurance Carrier Name: <?php echo $values['claimsadministrator']; ?></td>
    </tr>
    <tr>
      <td width="60%">
        Company Name: 
      </td>
      <td width="40%">
        Contact Name: <?php echo $values['claimsadjuster']; ?>
      </td>
    </tr>
    <tr>
      <td width="60%">
        Address: <?php echo $values['caddress']; ?>
      </td>
      <td>
        <table width="100%" >
          <tr>
            <td width="50%" style="border-right: 1px solid;">City: <?php echo $values['ccity'] ?></td>
            <td width="50%">State: <?php echo $values['cstate'] ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" >
          <tr>
            <td width="50%" style="border-right: 1px solid;">Zip Code: <?php echo $values['czip'] ?></td>
            <td width="50%">Phone: <?php echo $values['cphone'] ?></td>
          </tr>
        </table>
      </td>
      <td>
        Fax Number: <?php echo $values['cfax'] ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        E-mail Address:
      </td>
    </tr>
</table>
<table width="100%" border="1">
  <tr class="bggray">
    <td colspan="5" class="bggray">Requested Treatment (see instructions for quidance, attached additional pages if necessary)</td>
  </tr>
  <tr>
    <td colspan="5">List each specific requested medical services, goods, or items in the below space or indicate the spacific page number(s) of the attached medical report on which the requested treatment can be found. Up to Five(s) procedures may be entered;</td>
  </tr>
  <tr>
    <td style="width: 20%;height: 50px;text-align: center;">Diagnosis (Required)</td>
    <td style="width: 20%;height: 50px;text-align: center;">ICD-Code (Required)</td>
    <td style="width: 20%;height: 50px;text-align: center;">Service/Good Requested (Required)</td>
    <td style="width: 20%;height: 50px;text-align: center;">CPT/HCPCS Code (If Known)</td>
    <td style="width: 20%;height: 50px;text-align: center;">Other Information (Frequency, Duration Quantity, etc.)</td>
  </tr>
  <tr>
    <!-- <td style="height: 20px"><//?=preg_replace('/\d/', '', $values['diagnosis'] );?></td> -->
    <!-- <td style="height: 20px">S83.91XD</td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx1']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx1'] ?></td> 
    
    <td style="height: 20px"><?=$rfatreatmenttype?></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"><?=$rfatreatmentduration?> Weeks</td>
  </tr>
  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx2']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx2'] ?> </td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px">SEE REPORT</td>
  </tr>
  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx3']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx3'] ?> </td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>
  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx4']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx4'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx5']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx5'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx6']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx6'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx7']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx7'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>

  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx8']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx8'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx9']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx9'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx10']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx10'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx11']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx11'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <!-- <td style="height: 20px"></td> -->
    <!-- <td style="height: 20px"></td> -->
    <td style="height: 20px"><?=str_replace('.', '', (preg_replace('/[\d,]+/', '', $values['imdxCpdx12']) ));?></td>

    <td style="height: 20px"><?php echo $values['cpdx12'] ?></td> 

    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>


  <tr>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
    <td style="height: 20px"></td>
  </tr>
  <tr>
    <td colspan="4">Requesting Physician Signature:</td>
    <td colspan="1">Date: </td>
  </tr>
</table>
<table width="100%" border="1">
  <tr class="bggray">
    <td colspan="5" class="">Claims Administrator/Utilization Review Organization (URO) Response</td>
  </tr>
  <tr>
    <td style="border: none;height: 20px" colspan="1">
      <input type="checkbox" name="approved">Approved 
    </td>
    <td style="border: none;height: 20px" colspan="1">
      <input type="checkbox" name="deniedormodified">Denied or Modified
    </td>
    <td style="border: none;height: 20px" colspan="1">
      <input type="checkbox" name="approved">Delay
    </td>
    <td style="border: none;" colspan="1">
      <input type="checkbox" name="prevdenied">Requestd Treatment has been previously denied 
    </td>
    <td style="border: none;" colspan="1">
      <input type="checkbox" name="deniedormodified">Liability for treatment is disputed
    </td>
  </tr>
  <tr>
    <td colspan="4">
      Authorization Number (if assigned):
    </td>
    <td colspan="1">
      Date: 
    </td>
  </tr>
  <tr>
    <td colspan="4">
      Authorized Agent Name:
    </td>
    <td colspan="1">
      Signature: 
    </td>
  </tr>
  <tr>
    <td colspan="3">
      Phone:
    </td>
    <td colspan="1">
      Fax Number: 
    </td>
    <td colspan="1">
      E-mail Address:
    </td>
  </tr>
  <tr>
    <td colspan="5" height="50">
      Comments:
    </td>
  </tr>
</table>
<div style="font-size:small; color:#BBBBBB">(<?php echo $row['cpid']." ".$rfapatientname; ?>)</div>
</div>
<style type="text/css">
  tr.bggray {
      background-color: lightgray !important;
      -webkit-print-color-adjust: exact; 
    }
  @media print {
    tr.bggray {
      background-color: lightgray !important;
      -webkit-print-color-adjust: exact; 
    }
    #rfapdf {
      font-size: 14px !important;
    }
    #rfapdf  table {
      font-size: 14px !important;
    }
    /*table {page-break-inside: avoid;}*/
  }
</style>
</body>
</html>