<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$script = 'cancelForm';
$table = 'cases';
$keyfield = 'crid';
$fields[$table]=array(
				'crinactive'=>'boolean',
				'crpaid'=>'integer',
				'palname'=>'varchar',
				'pafname'=>'varchar',
				'pamname'=>'varchar',
				'padob'=>'date', 
				'paphone1'=>'phone',
				'passn'=>'ssn', 
				'crrefdmid'=>'integer',
				'dmlname'=>'varchar',
				'dmfname'=>'varchar',
				'crrefdlid'=>'integer',
				'dlsname'=>'varchar',
				'dlname'=>'varchar',
				'dlcity'=>'varchar',
				'dlphone'=>'phone',
				'crrefnum'=>'memo',
				'crinjurydate'=>'date',
				'crdxcode'=>'dxcode',
				'crinjurytypecode'=>'code',
				'crsurgerydate'=>'date',
				'crdate'=>'date',
				'crcasetypecode'=>'code',
				'crcnum'=>'code',
				'crcasestatuscode'=>'code',
				'crtherapytypecode'=>'code',
				'crnote'=>'memo',
				'crfvisitdate'=>'date',
				'crcanceldate'=>'date',
				'crcancelreasoncode'=>'code'
			);

if(!empty($_SESSION['id']) && $_SESSION['id']!= 0) {
	$buttonvalue = 'Confirm Cancel Referral';
	$fieldslist = implode(", ", array_keys($fields[$table]));
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

// Case Information
	$query = "
		SELECT $fieldslist FROM $table 
		LEFT JOIN patients
		ON crpaid = paid
		LEFT JOIN doctors
		ON crrefdmid = dmid
		LEFT JOIN doctor_locations
		ON crrefdlid = dlid
		WHERE $keyfield='" . $_SESSION['id'] . "'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_assoc($result_id);
			foreach($result as $fieldname=>$fieldvalue) {
				if($fields["$table"]["$fieldname"]=='date') {
					if($fieldvalue == '1999-11-30 00:00:00')
						$fieldvalue=NULL;
				}
				if(!empty($fieldvalue))
					$_POST[$fieldname] = $fieldvalue;
			}
		}
		else
			error('001', "Non-unique field error (should never happen).");	
	}
	else
		error('002', mysqli_error($dbhandle));

	if(errorcount()==0) {

		if(!empty($_POST['dmlname']))
			if(!empty($_POST['dmfname']))
				$doctor=$_POST['dmlname'] . ", " . $_POST['dmfname'];
			else
				$doctor=$_POST['dmlname'];
		else
			$doctor="(No Referrer Specified)";

		if(!empty($_POST['dlsname']))
			if(!empty($_POST['dlname']))
				$location=$_POST['dlsname'] . "-" . $_POST['dlname'];
			else
				$location=$_POST['dlsname'];
		else
			$location="(No Location Specified)";

		if(!empty($_POST['crdxcode']))
			$dxcode=$_POST['crdxcode'];
		else
			$dxcode="(No Dx Code Specified)";

		if(!empty($_POST['crinjurydate']))
			$injurydate=displayDate($_POST['crinjurydate']);
		else
			$injurydate="(No Injury Date Specified)";

		if(!empty($_POST['crinjurytypecode']))
			$injurytypecode=displayDate($_POST['crinjurytypecode']);
		else
			$injurytypecode="(No Injury Type Code Specified)";

		if(!empty($_POST['crsurgerydate']))
			$surgerydate=displayDate($_POST['crsurgerydate']);
		else
			$surgerydate="(No Surgery Date Specified)";

		if(!empty($_POST['crdate']))
			$casedate=displayDate($_POST['crdate']);
		else
			$casedate="(No Case/Referral Date Specified)";

		if(!empty($_POST['crcasetypecode']))
			$casetypecode=$_POST['crcasetypecode'];
		else
			$casetypecode="(No Case Type Specified)";

		if(!empty($_POST['crcnum']))
			$caseclinic=$_POST['crcnum'];
		else
			$caseclinic="(No Clinic Specified)";

		if(!empty($_POST['crcasestatuscode']))
			$casestatuscode=$_POST['crcasestatuscode'];
		else
			$casestatuscode="(No Status Code Specified)";

		if(!empty($_POST['crtherapytypecode']))
			$casetherapytypecode=$_POST['crtherapytypecode'];
		else
			$casetherapytypecode="(No Therapy Type Code Specified)";

		if(!empty($_POST['crnote']))
			$casenote=$_POST['crnote'];
		else
			$casenote="(No Notes Specified)";

		if(!empty($_POST['crfvisitdate']))
			$casefirstvisitdate=displayDate($_POST['crfvisitdate']);
		else
			$casefirstvisitdate="(No First Visit Date Specified)";

		$casecanceldate=time();
	?>
<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="cancelForm">
		<fieldset style="text-align:center;">
		<legend>Cancel Case</legend>
		<table style="text-align:left;">
			<th colspan="2">Referring Doctor</th>
			<tr>
				<td>Name </td>
				<td><?php echo $doctor;?> </td>
			</tr>
			<tr>
				<td>Location </td>
				<td><?php echo $location;?>
				</td>
			</tr>
			<th>Injury Information
			</tr>
			<tr>
				<td>Dx Code </td>
				<td><?php echo $dxcode;?>
				</td>
			</tr>
			<tr>
				<td>Date </td>
				<td><?php echo $injurydate; ?>
				</td>
			</tr>
			<tr>
				<td>Type </td>
				<td><?php echo $injurytypecode;?>
				</td>
			</tr>
			<tr>
				<td>Surgery Date </td>
				<td><?php echo $surgerydate; ?>
				</td>
			</tr>
			<th>Case Information</th>
			<tr>
				<td>Referral Date </td>
				<td><?php echo $casedate; ?>
				</td>
			</tr>
			<tr>
				<td>Type </td>
				<td><?php echo $casetypecode;?>
				</td>
			</tr>
			<tr>
				<td>Clinic </td>
				<td><?php echo $caseclinic;?>
				</td>
			</tr>
			<tr>
				<td>Status </td>
				<td><?php echo $casestatuscode;?>
				</td>
			</tr>
			<tr>
				<td>Therapy Type Code </td>
				<td><?php echo $casetherapytypecode;?>
				</td>
			</tr>
			<tr>
				<td>Note </td>
				<td><?php echo $casenote;?>
				</td>
			</tr>
			<tr>
				<td>Conversion/First Visit Date </td>
				<td><?php echo $casefirstvisitdate; ?>
				</td>
			</tr>
			<tr>
				<td>Cancel Date </td>
				<td><?php echo date("m/d/Y", $casecanceldate); ?> </td>
			</tr>
			<tr>
				<td>Cancel Reason Code </td>
				<td><select name="crcancelreasoncode" id="crcancelreasoncode">
					<?php echo getSelectOptions($arrayofarrayitems = caseCancelReasonCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$_POST['crcancelreasoncode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select>			<a href="" style="text-decoration:none" onclick="window.open(somewindow)">&nbsp;+&nbsp;</a>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="<?php echo $buttonvalue; ?>" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
	}
}
else
	error("001", "ERROR: Cannot cancel case. Case Id not set.");
?>
