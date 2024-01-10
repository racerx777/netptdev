<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5); 
$crid=$_SESSION['id'];
require_once('viewCaseFunctions.php');

if($case=getCaseRecord($crid)) {

	$crcnum=$case['crcnum'];
	$provider=getProviderRecord($crcnum);

	$crpaid=$case['crpaid'];
	$patient=getPatientRecord($crpaid);

	$crrefdmid=$case['crrefdmid'];
	$doctor=getDoctorRecord($crrefdmid);

	$crrefdlid=$case['crrefdlid'];
	$location=getDoctorLocationRecord($crrefdlid);

	$crrefdlsid=$case['crrefdlsid'];
	$staff=getDoctorLocationStaffRecord($crrefdlsid);

	$caseTypeOptions=caseTypeOptions();
	$casetypehtml=$caseTypeOptions[$case['crcasetypecode']]['title'];

	if(!empty($case['crpnum']))
		$pnumhtml = $case['crpnum'] . '<input name="crpnum" type="hidden" value="' . $case['crpnum'] . '" />';
	else
		$pnumhtml = '(not assigned)';

	$caseStatusCodes=caseStatusCodes();
	$casestatusdescription = $caseStatusCodes[ $case['crcasestatuscode'] ]['description'];
	if($case['crcasestatuscode']=='CAN')
		$casestatusdescription .= ' ' . displayDate($case['crcanceldate']);
	$crcasestatuscodehtml=$casestatusdescription;

	if(!empty($case['crreadmit']))
		$readmithtml='Patient is a Readmit';
	else
		$readmithtml='Patient is not a Readmit';
		
	if(!empty($case['crrelocate']))
		$relocatehtml='Patient is a Relocate';
	else
		$relocatehtml='Patient is not a Relocate';

	if(!empty($case['crpostsurgical'])) {
		if(!empty($case['crsurgerydate']))
			$crsurgerydatehtml='Postsurgical Patient Sx:'.displayDate($case['crsurgerydate']);
		else
			$crsurgerydatehtml='Postsurgical Patient Sx:not specified';
	}
	else
		$crsurgerydatehtml='Postsurgical Status Unknown';

	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
	$dmid=$case['crrefdmid'];
	$doctor=getDoctorList($dmid, 1);
	$doctorhtml=$doctor["$dmid"]['dmlname'].', '.$doctor["$dmid"]['dmfname'];

	$dlid=$case['crrefdlid'];
	$location=getDoctorLocationList($dmid, $dlid, 1);
	$locationhtml=$location["$dlid"]['dlname'].' '.$location["$dlid"]['dlcity'].' '.$location["$dlid"]['dlphone'];

	$dlsid=$case['crrefdlsid'];
	$contact=getDoctorLocationsContactsList($dmid, $dlid, 1);
	$contacthtml=$contact["$dlsid"]['dlstitle'].' '.$contact["$dlsid"]['dlsname'].' '.$contact["$dlsid"]['dlsphone'].' '.$contact["$dlsid"]['dlsfax'];

	$crdxnature=$case['crdxnature'];
	$injurynatureCodeOptions=injurynatureCodeOptions(1);
	$crdxnaturehtml=$injurynatureCodeOptions["$crdxnature"]['description'];

	$crdxbodypart=$case['crdxbodypart'];
	$bodypartCodeOptions=bodypartCodeOptions(1);
	$crdxbodyparthtml=$bodypartCodeOptions["$crdxbodypart"]['description'];

	$crdxbodypartdescriptor=$case['crdxbodypartdescriptor'];
	$bodypartdescriptorCodeOptions=bodypartdescriptorCodeOptions(1);
	$crdxbodypartdescriptorhtml=$bodypartdescriptorCodeOptions["$crdxbodypartdescriptor"]['description'];

	if(empty($case['crtotalvisits'])) {
		if(!empty($case['crfrequency']) && !empty($case['crduration']))
			$freqdurhtml=$case['crfrequency'].'x'.$case['crduration'].'='.$case['crfrequency']*$case['crduration'];
		else
			$freqdurhtml="not specified";
	}
	else {
		if(empty($case['crfrequency']) || empty($case['crduration']))
			$freqdurhtml=$case['crtotalvisits'].' Visits';
		else
			$freqdurhtml=$case['crfrequency'].'x'.$case['crduration'].'='.$case['crtotalvisits'];
	}
	
	$occupationhtml=occupationOptions();
	$occupationhtml = getSelectOptions($arrayofarrayitems=$occupationhtml, $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$_POST['croccup'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

?>
<div class="centerFieldset" style="clear:both; width:700px;">
	<form action="" method="post" name="viewForm">
		<fieldset style="text-align:center;">
		<legend>View Case Information - Patient Record #<?php echo $case['crpaid']; ?> <?php echo $case['crlname'] . ", " . $case['crfname']; ?>
		</legend>
		<table style="text-align:left;" >
			<th colspan="2">Case/Injury Record #<?php echo $case['crid']; ?></th>
			<tr>
				<td>Injury Date</td>
				<td>
					<table width="100%">
						<tr>
							<td nowrap="nowrap" style="text-decoration:none"><?php echo displayDate($case['crinjurydate']); ?></td>
							<td align="right">PTOS Patient #: <?php echo $pnumhtml; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>Type</td>
				<td><table width="100%">
						<tr>
							<td><?php echo $casetypehtml; ?></td>
							<td align="right">Status: <?php echo $crcasestatuscodehtml; ?> </td>
						</tr>
					</table></td>
			<tr>
				<td colspan="2"><?php echo $readmithtml; ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $relocatehtml; ?></td>
			</tr>

			<tr>
				<td colspan="2"><?php echo $crsurgerydatehtml; ?></td>
			</tr>

			<tr>
				<td>Employer</td>
				<td><?php if(isset($case['crempname'])) echo $case['crempname'];?></td>
			</tr>
			<tr>
				<td>Occupation</td>
				<td><?php echo $case['croccup']; ?></td>
			</tr>
			<tr>
				<td>Note </td>
				<td><?php if(isset($case['crnote'])) echo $case['crnote'];?></td>
			</tr>
			<th colspan="2">Initial Prescription Information</th>
			<tr>
				<td>Prescription Date </td>
				<td nowrap="nowrap" style="text-decoration:none"><?php if(isset($case['crdate'])) echo date("m/d/Y", strtotime($case['crdate'])); ?></td>
			</tr>
			<tr>
				<td>Referring Doctor</td>
				<td><?php echo $doctorhtml ;?></td>
			</tr>
			<tr>
				<td>Referring Dr Location</td>
				<td><?php echo $locationhtml;?></td>
			</tr>
			<tr>
				<td>Referring Dr Phone/Fax</td>
				<td><?php echo $contacthtml;?></td>
			</tr>
			<tr>
				<td>Dx Code </td>
				<td><?php echo $crdxnaturehtml; ?></td>
			</tr>
			<tr>
				<td>Body Part </td>
				<td><?php echo $crdxbodydescriptorhtml; ?>
					<?php echo $crdxbodyparthtml; ?>
				</td>
			</tr>
			<tr>
				<td>Frequency/Duration/Visits </td>
				<td><?php echo $freqdurhtml; ?>
				</td>
			</tr>
			<tr>
				<td>Therapy Type Code</td>
				<td><?php echo $case['crtherapytypecode']; ?></td>
			</tr>
			<tr>
				<td>Treating Clinic</td>
				<td><?php echo $case['crcnum']; ?></td>
			</tr>
			<tr>
				<td>Treating Therapist</td>
				<td><?php echo $case['crtherapcode']; ?></td>
			</tr>
			<tr>
				<td colspan="2">
					<div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Exit" />
						</div>
					</div></td>
			</tr>
<?php 
	$history=getSchedulingHistory($crid);
	$historyHtml=historyHtml('Case Call History', $history);
	echo '<tr><td colspan="2">';
	echo $historyHtml; 
	echo '</td></tr>';

	$history=getAuthorizationHistory($crid);
	$historyHtml=historyHtml('Case Authorization History', $history);
	echo '<tr><td colspan="2">';
	echo $historyHtml; 
	echo '</td></tr>';


	$bnum=$provider['cmbnum'];
	$cnum=$case['crcnum'];
	$pnum=$case['crpnum'];

	if(!empty($bnum) && !empty($cnum) && !empty($pnum)) {
		$history=getTreatmentBillingHistory($bnum, $cnum, $pnum);
		$titles=array("Date","CPT/RVS","Description");
		$historyHtml=historyHtml('Case Treatment Billing History', $history, $titles);
		echo '<tr><td colspan="2">';
		echo $historyHtml; 
		echo '</td></tr>';
	}

	if(!empty($bnum) && !empty($pnum)) {
		$queue=getCollectionsQueue($bnum, $pnum);	
		$titles=array("Date","Queue","");
		$queueHtml=historyHtml('Case Collections Queue', $queue, $titles);
		echo '<tr><td colspan="2">';
		echo $queueHtml; 
		echo '</td></tr>';

		$history=getCollectionsHistory($bnum, $pnum);
		$historyHtml=historyHtml('Case Collections History', $history);
		echo '<tr><td colspan="2">';
		echo $historyHtml; 
		echo '</td></tr>';
	}
?> 
			</table>
		</fieldset>
	</form>
</div>
<?php
	}
	else
		displaysitemessages();
unset($_SESSION['id']);
unset($_SESSION['button']);
?>