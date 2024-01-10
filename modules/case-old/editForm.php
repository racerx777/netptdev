<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script>
function togglepostsurgical() {
	var control = document.editForm.crpostsurgical;
	var data = document.editForm.crsurgerydate;
	if(control.checked) 
		data.disabled=false;
	else 
		data.disabled=true;
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php
$script = 'editForm';
$table = 'cases';
$keyfield = 'crid';
$fields[$table]=array(
				'crlname'=>'name',
				'crmname'=>'name',
				'crfname'=>'name',
				'craddress1'=>'name',
				'craddress2'=>'name',
				'crcity'=>'name',
				'crstate'=>'name',
				'crzip'=>'char',
				'crphone1'=>'number',
				'crdob'=>'date',
				'crsex'=>'name',
				'crssn'=>'number',
				'crinjurydate'=>'date',
				'crpnum'=>'char',
				'crcasetypecode'=>'code',
				'crreadmit'=>'boolean',
				'crrelocate'=>'boolean',
				'crpostsurgical'=>'boolean',
				'crsurgerydate'=>'date',
				'crempname'=>'char',
				'croccup'=>'char',
				'crnote'=>'memo',
				'crdate'=>'date',
				'crrefdmid'=>'integer',
				'crrefdlid'=>'integer',
				'crrefdlsid'=>'integer',
				'crdxnature'=>'code',
				'crdxbodypart'=>'code',
				'crdxbodydescriptor'=>'code',
				'crfrequency'=>'number',
				'crduration'=>'number',
				'crtotalvisits'=>'number',
				'crtherapytypecode'=>'code',
				'crcnum'=>'code',
				'crtherapcode'=>'code',
				'raid'=>'number',
			);
$casestatusarray=caseStatusCodes();
$dbhandle = dbconnect();
// if add and patient provided...
if(!empty($_POST['searchcase']['paid'])) 
	$_POST['crpaid'] = $_POST['searchcase']['paid'];

if(!isset($_POST['crrefdmid'])){
	$_POST['crrefdmid'] = $_SESSION['crrefdmid'];
	unset($_SESSION['crrefdmid']);
}
if(!isset($_POST['crrefdlid'])){
	$_POST['crrefdlid'] = $_SESSION['crrefdlid'];
	unset($_SESSION['crrefdlid']);
}
if(!isset($_POST['crrefdlsid'])){
	$_POST['crrefdlsid'] = $_SESSION['crrefdlsid'];
	unset($_SESSION['crrefdlsid']);
}
if($_SESSION['button']=='Add') {
 if(isset($_POST['crpaid']))	
 	$_SESSION['crpaid'] = $_POST['crpaid'];
 if(!empty($_SESSION['crpaid'])) {
 	$_POST['crpaid'] = $_SESSION['crpaid'];
	$buttonvalue = 'Add Case';
// If First time
	if($_POST['editFormLoaded']!='1') {
// initialize default values
		$_POST['crdate'] = displayDate(date('Y-m-d',time()));
		$_POST['crcasestatuscode'] = 'NEW';
		$_POST['crcasetypecode'] = '6';
		$_POST['crdxbodypart'] = '231';
		$_POST['crdxbodypartdescriptor'] = 'B';
		$_POST['crfrequency'] = '3';
		$_POST['crduration'] = '4';
		$_POST['crtotalvisits'] = '';
		$_POST['crtherapytypecode'] = 'PT';

// Get related patient information id, first and last name
		$paid=$_POST['crpaid'];
		$patientquery = "
		SELECT paid, palname, pafname
		FROM patients 
		WHERE paid='$paid'";
		$patientresult = mysqli_query($dbhandle,$patientquery);
		if($patientresult) {
			$patientrow = mysqli_fetch_assoc($patientresult);
			foreach($patientrow as $fieldname=>$fieldvalue) {
				if(!empty($fieldvalue)) 
					$_POST[$fieldname] = $fieldvalue;
			}
		}
		else
			error('011', "SELECT patient error.$patientquery<br>".mysqli_error($dbhandle));	
	}
  }
  else
	error('111', "Cannot add case. You must search and select patient first.");	
}

// if edit and case id provided...
if( !empty($_SESSION['id']) && $_SESSION['button']=='Edit Case' ){
	$crid=$_SESSION['id'];
	$buttonvalue = 'Update Case';
	$fieldslist = implode(", ", array_keys($fields[$table]));
//	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
//	$dbhandle = dbconnect();
//	
	if($_POST['editFormLoaded']!='1') {
		$query = "
		SELECT $fieldslist, crcanceldate, crptosstatus, crcasestatuscode, crpaid, paid, palname, pafname
		FROM $table 
		LEFT JOIN patients
			on crpaid=paid
		WHERE $keyfield='$crid'
		";
//dump("query",$query);
		if($result_id = mysqli_query($dbhandle,$query)) {
			$numRows = mysqli_num_rows($result_id);
			if($numRows==1) {
				$result = mysqli_fetch_assoc($result_id);
				foreach($result as $fieldname=>$fieldvalue) {
					if($fields["$table"]["$fieldname"]=='date') {
						if($fieldvalue == '1999-11-30 00:00:00') $fieldvalue=NULL;
					}
					if(!empty($fieldvalue)) 
						$_POST[$fieldname] = $fieldvalue;
				}
$callhistory="";
$callhistoryquery = "
		SELECT * 
		FROM case_scheduling_history 
		WHERE cshcrid='$crid'
		";
if($callhistoryresult = mysqli_query($dbhandle,$callhistoryquery)) {
	while($callhistoryrow = mysqli_fetch_assoc($callhistoryresult)) {
		$callhistory .= displayDate($callhistoryrow['crtdate']) . " " . displayTime($callhistoryrow['crtdate']) . "-" .$callhistoryrow['crtuser'] . " " . $callhistoryrow['cshdata'] . "<br>";
	}
	$_POST['callhistory']=$callhistory;
}
else
	error("801", mysqli_error($dbhandle));


			}
			else
				error('001', "Non-unique field error (should never happen).");	
		}
		else
			error('002', mysqli_error($dbhandle));
	}
}
if(errorcount() == 0 && !empty($buttonvalue)) {
	$casestatusdescription = $casestatusarray[$_POST['crcasestatuscode']]['description'];
	if($_POST['crcasestatuscode']=='CAN')
		$casestatusdescription .= ' ' . displayDate($_POST['crcanceldate']);
	$crcasestatuscodehtml=$casestatusdescription . '<input name="crcasestatuscode" type=hidden value="'.$_POST['crcasestatuscode'].'">';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
	$doctorlistoptions="";
	$doctorlocationlistoptions="";
	$doctorlocationcontactlistoptions="";
	$doctorlocationdisabled='disabled="disabled"';
	$doctorlist  = getDoctorList();

	if($doctorlist) {
		if(count($doctorlist) > 0) {
			$doctorlistoptions =  getSelectOptions(
				$arrayofarrayitems=$doctorlist, 
				$optionvaluefield='dmid', 
				$arrayofoptionfields=array(
					'dmlname'=>', ', 
					'dmfname'=>' (NPI=',
					'dmnpi'=>')' 
					), 
				$defaultoption=$_POST['crrefdmid'], 
				$addblankoption=TRUE, 
				$arraykey='', 
				$arrayofmatchvalues=array()); 
			if(!empty($_POST['crrefdmid'])) {
				$doctorlocationdisabled="";
				$doctorlocationlist  = getDoctorLocationList($_POST['crrefdmid']);
				$doctorlocationlistoptions = getSelectOptions(
					$arrayofarrayitems=$doctorlocationlist, 
					$optionvaluefield='dlid', 
					$arrayofoptionfields=array(
						'dlname'=>', ', 
						'dlcity'=>', ', 
						'dlphone'=>'' 
						), 
					$defaultoption=$_POST['crrefdlid'], 
					$addblankoption=TRUE, 
					$arraykey='', 
					$arrayofmatchvalues=array());
				if(!empty($_POST['crrefdlid'])) {
					$doctorlocationcontactdisabled="";
					$doctorlocationcontactlist  = getDoctorLocationsContactsList($_POST['crrefdmid']);
					$doctorlocationcontactlistoptions = getSelectOptions(
						$arrayofarrayitems=$doctorlocationcontactlist, 
						$optionvaluefield='dlsid', 
						$arrayofoptionfields=array(
							'dlstitle'=>', ', 
							'dlsname'=>', ', 
							'dlsphone'=>', ', 
							'dlsfax'=>'' 
							), 
						$defaultoption=$_POST['crrefdlsid'], 
						$addblankoption=TRUE, 
						$arraykey='dlstitle', 
						$arrayofmatchvalues=array('REFERRALS'));
				}
				else
					$doctorlocationlistcontactsoptions = '<option value="">Select a Location...</option>';
			}
			else
				$doctorlocationlistoptions = '<option value="">Select a Doctor...</option>';
		}
		else
			echo("Error-No Doctors in Doctor Master.");
	}
	else
		echo("Error-getDoctorList.");

	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/injury.options.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/clinic.options.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/therapist.options.php');

	$readmitchecked="";
	if(isset($_POST['crreadmit'])) 
		$readmitchecked="checked";
	$relocatechecked="";
	if(isset($_POST['crrelocate'])) 
		$relocatechecked="checked";

	$surgerydatedisabled='disabled="disabled"';
	if(!empty($_POST['crsurgerydate'])) {
//		dump("_POST['crsurgerydate']",$_POST['crsurgerydate']);
		$postsurgicalchecked='checked';
		$surgerydatedisabled='';
	}
	if(isset($_POST['crpostsurgical'])) {
		$postsurgicalchecked='checked';
		$surgerydatedisabled='';
	}

	if( (userlevel()==21 && (empty($_POST['crptosstatus']) || $_POST['crptosstatus']=='NEW') && ($_POST['crcasestatuscode']=='ACT' || $_POST['crcasestatuscode']=='SCH')) or isuserlevel(90)) 
		$pnumhtml = '<input id="crpnum" name="crpnum" type="text" size="10" maxlength="6" value="' . $_POST['crpnum'] . '" onchange="upperCase(this.id)" />';
	else {
		if(!empty($_POST['crpnum']))
			$pnumhtml = $_POST['crpnum'] . '<input name="crpnum" type="hidden" value="' . $_POST['crpnum'] . '" />';
		else
			$pnumhtml = '(not assigned)';
	}
// Allow update only until seen
// Exception is Administrator and Gladys with a non-exported to ptos case, once sent to PTOS we aren't updating it
	if(
	$buttonvalue == 'Add Case' ||
	isuserlevel(90) || 
	($_POST['crcasestatuscode'] == 'NEW' || $_POST['crcasestatuscode'] == 'PEN' || $_POST['crcasestatuscode'] == 'PEA' || $_POST['crcasestatuscode'] == 'SCH') || 
	( userlevel()==21 && ( empty($_POST['crptosstatus']) || $_POST['crptosstatus']=='NEW') )
	) 
		$updatebuttondisabled='';
	else
		$updatebuttondisabled='disabled="disabled"';


$occupationhtml=occupationOptions();
$occupationhtml = getSelectOptions($arrayofarrayitems=$occupationhtml, $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$_POST['croccup'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

$att_query = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id ORDER BY `attorney`.`name_first` ASC";
$referring_attorney_value = '';
$att_test = mysqli_query($dbhandle,$att_query);
$i = 1;
$result_txt = '';
$edit_id_sel = '';
while($att_row = mysqli_fetch_array($att_test)){
	if($i == 1){
		$referring_attorney_value .= '<option value="" data-text=""></option>';
	}
	if(!empty($att_row['name_middle'])){
		$middle_name = substr($att_row['name_middle'], 0, 1);
	}else{
		$middle_name = '';
	}
	$city = $att_row['city'];
	$zip = $att_row['zip'];
	if(!empty($city) && !empty($zip)){
		$show_text = ' | '.$city .', '.$zip; 
	}elseif(!empty($city)){
		$show_text = ' | '.$city;
	}elseif(!empty($zip)){
		$show_text = ' | '.$zip;
	}else{
		$show_text = '';
	}
	$name = $att_row['name_first'].' '.$middle_name.' '.$att_row['name_last'];
	$select_text = $att_row['firm_name'].' '.$show_text;
	if($att_row['id'] == $_POST['raid']){
		$referring_attorney_value .= '<option value="'.$att_row['id'].'" data-text="'.$select_text.'" selected>'.$name.'</option>';
		$result_txt = $select_text;
		$edit_id_sel = $att_row['id'];
		$sel_referring_attorney_value = '<option value="'.$att_row['id'].'" data-text="'.$select_text.'" selected>'.$name.'</option>';
	}else{
		$referring_attorney_value .= '<option value="'.$att_row['id'].'" data-text="'.$select_text.'">'.$name.'</option>';
	}
	$i++;
}

?>
<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Edit Case Information - Patient  Record #<?php echo $_POST['paid']; ?> <?php echo $_POST['palname'] . ", " . $_POST['pafname']; ?>
		<input name="paid" type="hidden" value="<?php echo $_POST['paid']; ?>">
		<input name="palname" type="hidden" value="<?php echo $_POST['palname']; ?>">
		<input name="pafname" type="hidden" value="<?php echo $_POST['pafname']; ?>">
		<input name="callhistory" type="hidden" value="<?php echo $_POST['callhistory']; ?>">
		<input name="crpaid" type="hidden" value="<?php echo $_POST['crpaid']; ?>">
		</legend>
		<table style="text-align:left;">
			<th colspan="2">Case/Injury Record #<?php echo $_SESSION['id']; ?></th>
			<tr>
				<td>Injury Date*</td>
				<td><table width="100%">
						<tr>
							<td nowrap="nowrap" style="text-decoration:none"><input id="crinjurydate" name="crinjurydate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['crinjurydate'])) echo displayDate($_POST['crinjurydate']); ?>"  onchange="validateDate(this.id)">
								<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].crinjurydate,'anchor2','MM/dd/yyyy'); return false;" /></td>
							<td align="right">PTOS Patient #:* <?php echo $pnumhtml; ?></td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<td>Type</td>
				<td><table width="100%">
						<tr>
							<td><select name="crcasetypecode" id="crcasetypecode">
									<?php echo getSelectOptions($arrayofarrayitems=caseTypeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crcasetypecode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
								</select>
							</td>
							<td align="right">Status: <?php echo $crcasestatuscodehtml; ?> </td>
						</tr>
					</table></td>
			<tr>
				<td>Readmit Flag </td>
				<td><input name="crreadmit" type="checkbox" value="<?php echo $_POST['crreadmit']; ?>" <?php echo $readmitchecked; ?> />
					&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Relocate Flag
					<input name="crrelocate" type="checkbox" value="<?php echo $_POST['crrelocate']; ?>" <?php echo $relocatechecked; ?> /></td>
			</tr>
			<tr>
				<td>Postsurgical  Flag </td>
				<td style="text-decoration:none">
					<input id="crpostsurgical" name="crpostsurgical" type="checkbox" value="<?php echo $_POST['crpostsurgical']?>" <?php echo $postsurgicalchecked; ?> onchange="togglepostsurgical()"/>
					&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Surgery Date
					<input id="crsurgerydate" name="crsurgerydate" type="text" size="10" maxlength="10" <?php echo $surgerydatedisabled; ?> value="<?php if(isset($_POST['crsurgerydate'])) echo displayDate($_POST['crsurgerydate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor3" id="anchor3" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].crsurgerydate,'anchor3','MM/dd/yyyy'); return false;" /> </td>
			</tr>
			<?php if( userlevel()==21 or isuserlevel(90) ) { ?>
			<tr>
				<td>Employer*</td>
				<td><input id="crempname" name="crempname" type="text" size="35" maxlength="30" value="<?php if(isset($_POST['crempname'])) echo $_POST['crempname'];?>" onchange="upperCase(this.id)" /></td>
			</tr>
			<tr>
				<td>Occupation*</td>
				<td>
			<select name="croccup" >
				<?php echo $occupationhtml; ?>
			</select>
				</td>
			</tr>
			<?php } 
			else { ?>
				<input name="crempname" type="hidden" value="<?php if(isset($_POST['crempname'])) echo $_POST['crempname'];?>" />
				<input name="croccup" type="hidden" value="<?php if(isset($_POST['croccup'])) echo $_POST['croccup'];?>" />
			<?php } ?>
			<tr>
				<td>Note </td>
				<td><input name="crnote" type="text" size="60" maxlength="60" value="<?php if(isset($_POST['crnote'])) echo $_POST['crnote'];?>" onchange="upperCase(this.id)" /></td>
			</tr>
			<th colspan="2">Initial Prescription Information</th>
			<tr>
				<td>Prescription Date </td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crdate" name="crdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['crdate'])) echo date("m/d/Y", strtotime($_POST['crdate'])); ?>" onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].crdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Referring Doctor</td>
				<td><select id="crrefdmid" name="crrefdmid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['crrefdmid'])) echo $_POST['crrefdmid'];?>" onchange="javascript:submit()" >
					
					<?php echo $doctorlistoptions; ?>
					</select>
					... <?php if(userlevel()=='99' || userlevel()=='17' || userlevel()=='21') { ?>
						<input type="button" onclick="window.open('modules/doctor/doctorManagement.php?crid=<?php echo $crid; ?>&dmid=<?php echo $_POST['crrefdmid']; ?>&dlid=<?php echo $_POST['crrefdlid']; ?>&dlsid=<?php echo $_POST['crrefdlsid']; ?>','UpdateDoctorInformation','width=1200,height=800,scrollbars=yes')" value="Add Doctor" />

<?php
	if(!empty($crid) && !empty($_POST['crrefdmid'])) {
?>
						<input type="button" onclick="window.open('modules/case/editDoctorForm.php?crid=<?php echo $crid; ?>&crrefdmid=<?php echo $_POST['crrefdmid']; ?>','UpdateDoctorInformation','width=700,height=800,scrollbars=yes')" value="Edit Doc" />	
<?php
 }
?>
					<?php }
					if(userlevel()>='99') { ?>
						<input type="button" onclick="window.open('modules/doctor/doctorEditForm.php?crid=<?php echo $crid; ?>','UpdateDoctorInformation','width=700,height=800,scrollbars=yes')" value="Add Doctor" />
					<?php } ?>
					</td>
			</tr>
			<tr>
				<td>Referring Dr Location</td>
				<td><select id="crrefdlid" name="crrefdlid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['crrefdlid'])) echo $_POST['crrefdlid'];?>" <?php echo $doctorlocationdisabled; ?>/>
					
					<?php echo $doctorlocationlistoptions; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Referring Dr Phone/Fax</td>
				<td><select id="crrefdlsid" name="crrefdlsid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['crrefdlsid'])) echo $_POST['crrefdlsid'];?>" <?php echo $doctorlocationcontactdisabled; ?>/>
					
					<?php echo $doctorlocationcontactlistoptions; ?>
					</select>
				</td>
			</tr>

			<?php if($_SESSION['user']['umrole'] == 17){ ?>
			<tr>
				<td style="vertical-align: top; padding-top: 5px;">Referring Attorney</td>
				<td>
					<select id='selUser' name="raid" style='width: 200px;'>
						<?php echo $referring_attorney_value; ?>
					</select>   
					<input type='button' value='Edit' id='attorney_edit'>
					<input type='button' value='Add' id='attorney_add'>
					<br/>
					<?php echo (empty($result_txt)) ? "<div id='result' style='display:none;'></div>" : "<div id='result'>".$result_txt."</div>"; ?>
					<!-- Script -->
				</td>
			</tr>
		<?php }else{
			?>
			<tr>
				<td style="vertical-align: top; padding-top: 5px;">Referring Attorney</td>
				<td>
					<select id='selUser' name="raid" style='width: 200px;' disabled="disabled">
						<?php echo $sel_referring_attorney_value; ?>
					</select>
					<br/>
					<?php echo (empty($result_txt)) ? "<div id='result' style='display:none;'></div>" : "<div id='result'>".$result_txt."</div>"; ?>
					<!-- Script -->
				</td>
			</tr>
			<?php
		} ?>
<?php if( userlevel()==21 or isuserlevel(90) ) { ?>
			<tr>
				<td>Dx Code </td>
				<td><select name="crdxnature" id="crdxnature">
						<?php echo getSelectOptions($arrayofarrayitems=getInjuryNatureTypeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crdxnature'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
<?php } 
			else { ?>
				<input name="crdxnature" type="hidden" value="<?php if(isset($_POST['crdxnature'])) echo $_POST['crdxnature'];?>" />
			<?php } ?>
			<tr>
				<td>Body Part </td>
				<td><select name="crdxbodydescriptor" id="crdxbodydescriptor">
						<?php echo getSelectOptions($arrayofarrayitems=getInjuryDescriptorTypeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crdxbodydescriptor'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
					<select name="crdxbodypart" id="crdxbodypart">
						<?php echo getSelectOptions($arrayofarrayitems=getInjuryBodypartTypeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crdxbodypart'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Frequency/Duration/Visits </td>
				<td>Frequency
					<select name="crfrequency" id="crfrequency">
						<option value=""<?php if(empty($_POST['crfrequency'])) echo ' selected="selected"'; ?>></option>
						<option value="1"<?php if($_POST['crfrequency']=='1') echo ' selected="selected"'; ?>>1</option>
						<option value="2"<?php if($_POST['crfrequency']=='2') echo ' selected="selected"'; ?>>2</option>
						<option value="3"<?php if($_POST['crfrequency']=='3') echo ' selected="selected"'; ?>>3</option>
						<option value="4"<?php if($_POST['crfrequency']=='4') echo ' selected="selected"'; ?>>4</option>
						<option value="5"<?php if($_POST['crfrequency']=='5') echo ' selected="selected"'; ?>>5</option>
						<option value="6"<?php if($_POST['crfrequency']=='6') echo ' selected="selected"'; ?>>6</option>
						<option value="7"<?php if($_POST['crfrequency']=='7') echo ' selected="selected"'; ?>>7</option>
					</select>
					X
					Duration
					<select name="crduration" id="crduration">
						<option value=""<?php if(empty($_POST['crduration'])) echo ' selected="selected"'; ?>></option>
						<option value="1"<?php if($_POST['crduration']=='1') echo ' selected="selected"'; ?>>1</option>
						<option value="2"<?php if($_POST['crduration']=='2') echo ' selected="selected"'; ?>>2</option>
						<option value="3"<?php if($_POST['crduration']=='3') echo ' selected="selected"'; ?>>3</option>
						<option value="4"<?php if($_POST['crduration']=='4') echo ' selected="selected"'; ?>>4</option>
						<option value="5"<?php if($_POST['crduration']=='5') echo ' selected="selected"'; ?>>5</option>
						<option value="6"<?php if($_POST['crduration']=='6') echo ' selected="selected"'; ?>>6</option>
						<option value="7"<?php if($_POST['crduration']=='7') echo ' selected="selected"'; ?>>7</option>
						<option value="8"<?php if($_POST['crduration']=='8') echo ' selected="selected"'; ?>>8</option>
						<option value="9"<?php if($_POST['crduration']=='9') echo ' selected="selected"'; ?>>9</option>
						<option value="10"<?php if($_POST['crduration']=='10') echo ' selected="selected"'; ?>>10</option>
						<option value="11"<?php if($_POST['crduration']=='11') echo ' selected="selected"'; ?>>11</option>
						<option value="12"<?php if($_POST['crduration']=='12') echo ' selected="selected"'; ?>>12</option>
					</select>
					= 
					Visits
					<input name="crtotalvisits" type="text" size="5" maxlength="5" value="<?php if(isset($_POST['crtotalvisits'])) echo $_POST['crtotalvisits'];?>" />
				</td>
			</tr>
			<tr>
				<td>Therapy Type Code</td>
				<td><select name="crtherapytypecode" id="crtherapytypecode" onchange="javascript:submit()">
						<?php echo getSelectOptions($arrayofarrayitems=therapyTypeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crtherapytypecode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
					... </td>
			</tr>
			<tr>
				<td>Treating Clinic</td>
				<td><select name="crcnum" id="crcnum" onchange="javascript:submit()">
						<?php echo getSelectOptions($arrayofarrayitems=getClinicTypeOptions($_POST['crtherapytypecode']), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crcnum'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
					... </td>
			</tr>
<?php if( userlevel()==21 or isuserlevel(90) ) { ?>
			<tr>
				<td>Treating Therapist</td>
				<td><select name="crtherapcode" id="crtherapcode">
						<?php echo getSelectOptions($arrayofarrayitems=getTherapistTypeOptions($_POST['crcnum'], $_POST['crtherapytypecode']), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$_POST['crtherapcode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
<?php } 
			else { ?>
				<input name="crtherapcode" type="hidden" value="<?php if(isset($_POST['crtherapcode'])) echo $_POST['crtherapcode'];?>" />
			<?php } ?>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Cancel" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" <?php echo $updatebuttondisabled; ?> type="submit" value="<?php echo $buttonvalue; ?>" />
						</div>
						<input type="hidden" name="editFormLoaded" value="1" />
					</div></td>
			</tr>
		</table>
		<table>
			<tr>
				<td valign="top">Case Call History:</td>
				<td align="left"><?php echo $_POST['callhistory']; ?> </td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">
	<!-- Modal content -->
	<div class="modal-content">
		<span class="close modal-close">&times;</span>
		<div class="centerFieldset" style="margin:50px 0px;">
			<form class="add-form">
				<fieldset style="width: 80%;">
				<legend>Add Reffering Attorney Information</legend>
				<table>
					<tbody>
						<tr>
							<td>Attorney First Name </td>
							<td><input name="name_first" class="add-firstname" type="text" value=""> <span class="add-fn-msg" style="color:red; display:none;">This field is required</span></td>
						</tr>
						<tr>
							<td>Attorney Middle Name </td>
							<td><input name="name_middle" class="add-middlename" type="text" value=""></td>
						</tr>
						<tr>
							<td>Attorney Last Name </td>
							<td><input name="name_last" class="add-lastname" type="text" value=""> <span class="add-ln-msg" style="color:red; display:none;">This field is required</span></td>
						</tr>
						<tr>
							<td>Attorney Firm </td>
							<td><input name="firm" class="add-firm" type="text" value=""> <span class="add-firm-msg" style="color:red; display:none;">This field is required</span></td>
						</tr>
						<tr>
							<td>Attorney Address </td>
							<td><input name="address" class="add-address" type="text" value=""></td>
						</tr>
						<tr>
							<td>Attorney City </td>
							<td><input name="city" class="add-city" type="text" value=""> <span class="add-city-msg" style="color:red; display:none;">This field is required</span></td>
						</tr>
						<tr>
							<td>Attorney State </td>
							<td><input name="state" class="add-state" type="text" value=""></td>
						</tr>
						<tr>
							<td>Attorney Zip </td>
							<td><input name="zip" class="add-zip" type="text" value=""> <span class="add-zip-msg" style="color:red; display:none;">This field is required</span></td>
						</tr>
						<tr>
							<td>Attorney Phone </td>
							<td><input name="phone" class="add-phone" type="phone" value=""> <span class="add-phone-msg" style="color:red; display:none;">Phone number is not valid</span></td>
						</tr>
						<tr>
							<td>Attorney Email </td>
							<td><input name="email" class="add-email" type="text" value=""> <span class="add-email-msg" style="color:red; display:none;">Email is not valid</span></td>
						</tr>
					</tbody>
				</table>
				<div class="containedBox">
					<div style="float:left; margin:10px;">
						<input type="hidden" name="paid_id" value="<?php echo 'paid_'.$_POST['paid']; ?>">
						<input type="hidden" name="attorney_form" value="add">
						<input type="button" class="modal-close" value="Cancel">
					</div>
					<div style="float:left; margin:10px;">
						<input type="submit" value="Add Reffering Attorney">
					</div>
				</div>
				</fieldset>
			</form>
			<form  class="edit-form">
				<fieldset style="width: 80%;">
				<legend>Edit Reffering Attorney Information</legend>
				<table>
					<tbody>
						<tr>
							<td>Attorney First Name </td>
							<td><input name="name_first" class="edit-firstname" type="text" value=""> <span class="edit-fn-msg" style="color:red; display:none;">This field is required</span>
							</td>
						</tr>
						<tr>
							<td>Attorney Middle Name </td>
							<td><input name="name_middle" class="edit-middlename" type="text" value="">
							</td>
						</tr>
						<tr>
							<td>Attorney Last Name </td>
							<td><input name="name_last" class="edit-lastname" type="text" value=""> <span class="edit-ln-msg" style="color:red; display:none;">This field is required</span>
							</td>
						</tr>
						<tr>
							<td>Attorney Firm </td>
							<td><input name="firm" class="edit-firm" type="text" value=""> <span class="edit-firm-msg" style="color:red; display:none;">This field is required</span>
							</td>
						</tr>
						<tr>
							<td>Attorney Address </td>
							<td><input name="address" class="edit-address" type="text" value="">
							</td>
						</tr>
						<tr>
							<td>Attorney City </td>
							<td><input name="city" class="edit-city" type="text" value=""> <span class="edit-city-msg" style="color:red; display:none;">This field is required</span>
							</td>
						</tr>
						<tr>
							<td>Attorney State </td>
							<td><input name="state" class="edit-state" type="text" value="">
							</td>
						</tr>
						<tr>
							<td>Attorney Zip </td>
							<td><input name="zip" class="edit-zip" type="text" value=""> <span class="edit-zip-msg" style="color:red; display:none;">This field is required</span>
							</td>
						</tr>
						<tr>
							<td>Attorney Phone </td>
							<td><input name="phone" class="edit-phone" type="phone" value=""> <span class="edit-phone-msg" style="color:red; display:none;">Phone number is not valid</span>
							</td>
						</tr>
						<tr>
							<td>Attorney Email </td>
							<td><input name="email" class="edit-email" type="text" value=""> <span class="edit-email-msg" style="color:red; display:none;">Email is not valid</span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="containedBox">
					<div style="float:left; margin:10px;">
						<input type="hidden" name="paid_id" value="<?php echo 'paid_'.$_POST['paid']; ?>">
						<input type="hidden" class="edit_id" name="edit_id" value="<?php echo $edit_id_sel; ?>">
						<input type="hidden" name="attorney_form" value="edit">
						<input type="button" class="modal-close" value="Cancel">
					</div>
					<div style="float:left; margin:10px;">
						<input type="submit" value="Save">
					</div>
				</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<style type="text/css" media="screen">
	/* The Modal (background) */
	.modal {
		display: none; /* Hidden by default */
		position: fixed; /* Stay in place */
		z-index: 1; /* Sit on top */
		padding-top: 100px; /* Location of the box */
		left: 0;
		top: 0;
		width: 100%; /* Full width */
		height: 100%; /* Full height */
		overflow: auto; /* Enable scroll if needed */
		background-color: rgb(0,0,0); /* Fallback color */
		background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	/* Modal Content */
	.modal-content {
		background-color: #fefefe;
		margin: auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
	}

	/* The Close Button */
	.close {
		color: #aaaaaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.close:hover,
	.close:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}
	#attorney_edit{
		display: none;
	}
	#result{
		padding: 5px 0px 5px 0px;
	}

	#edit-form{
		display: none;
	}

	#add-form{
		display: none;
	}
</style>
<script>
	$(document).ready(function(){
		// Initialize select2
		$("#selUser").select2();

		$("#selUser").on('change',function(){
			if($(this).val() == ''){
				$('#attorney_edit').hide();
				$('#result').hide();
			}else{
				$('#attorney_edit').show();
				$('#result').show();
			}
			var firmData = $('#selUser option:selected').data("text");
			$(".edit_id").val($(this).val());
			$('#result').html(firmData);
		});

		// Read selected option
		$('#attorney_edit').click(function(){
			var editId = $(".edit_id").val();
			$.ajax({
				type: 'post',
				url: 'modules/case/ajax/formpost.php',
				dataType: 'json',
				data: {'editbtn': editId},
				success: function (data) {
					$('.edit-firm').val(data.firm_name);
					$('.edit-firstname').val(data.name_first);
					$('.edit-middlename').val(data.name_middle);
					$('.edit-lastname').val(data.name_last);
					$('.edit-address').val(data.address);
					$('.edit-city').val(data.city);
					$('.edit-state').val(data.state);
					$('.edit-zip').val(data.zip);
					$('.edit-phone').val(data.phone);
					$('.edit-email').val(data.email);
					$('#myModal').show();
					$('.edit-form').show();
					$('.add-form').hide();
				}
			});
		});

		$('.add-lastname').on('change',function(){
			if($('.add-firstname').val() != '' && $('.add-lastname').val() != ''){
				var firmName = $(this).val() + ', ' + $('.add-firstname').val();
				if($('.add-firm').val() == ''){
					$('.add-firm').val(firmName);
				}
			}
		});

		$('#attorney_add').click(function(){
			$('#myModal').show();
			$('.edit-form').hide();
			$('.add-form').show();
		});

		$('.modal-close').click(function(){
			$('#myModal').hide();
		});

		if($('#selUser').find(":selected").val() != ''){
			$('#attorney_edit').show();
		}
		$('.add-form').on('submit', function (e) {
			e.preventDefault();
			var afirm = $('.add-firm').val();
			var afname = $('.add-firstname').val();
			var alname = $('.add-lastname').val();
			var acity = $('.add-city').val();
			var azip = $('.add-zip').val();
			var aphone = $('.add-phone').val();
			var aemail = $('.add-email').val();
			if(aphone != ''){
				if(isNaN(aphone)){
					var newaddno = aphone.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
					var countaddPhone = newaddno.length;
					if(isNaN(newaddno)){
						$('.add-phone-msg').show();
						return false;
					}else if(countaddPhone != 10){
						$('.add-phone-msg').show();
						return false;
					}else{
						$('.add-phone').val(newaddno);
					}
				}else{
					var countaddPhone = aphone.length;
					if(countaddPhone != 10){
						$('.add-phone-msg').show();
						return false;
					}
				}
			}

			if(aemail != ''){
				var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				if (!testEmail.test(aemail)){
					$('.add-email-msg').show();
					return false;
				}
			}

			if((afirm == '') || (afname == '') || (alname == '') || (acity == '') || (azip == '')){
				$('.add-phone-msg').hide();
				$('.add-email-msg').hide();
				if(afirm == ''){
					$('.add-firm-msg').show();
				}else{
					$('.add-firm-msg').hide();
				}
				if(afname == ''){
					$('.add-fn-msg').show();
				}else{
					$('.add-fn-msg').hide();
				}
				if(alname == ''){
					$('.add-ln-msg').show();
				}else{
					$('.add-ln-msg').hide();
				}
				if(acity == ''){
					$('.add-city-msg').show();
				}else{
					$('.add-city-msg').hide();
				}
				if(azip == ''){
					$('.add-zip-msg').show();
				}else{
					$('.add-zip-msg').hide();
				}
			}else{
				$.ajax({
					type: 'post',
					dataType: 'json',
					url: 'modules/case/ajax/formpost.php',
					data: $('.add-form').serialize(),
					success: function (data) {
						$('#selUser').html(data.aoption);
						$('#result').html(data.resulttxt);
						$('#selUser option[value="'+data.id+'"]').attr("selected", "selected");
						$(".edit_id").val(data.id);
						$('#attorney_edit').show();
						$('#myModal').hide();
						$('.add-firm-msg').hide();
						$('.add-fn-msg').hide();
						$('.add-ln-msg').hide();
						$('.add-city-msg').hide();
						$('.add-zip-msg').hide();
						$('.add-phone-msg').hide();
						$('.add-email-msg').hide();
					}
				});
			}
		});

		$('.edit-form').on('submit', function (e) {
			e.preventDefault();
			var efirm = $('.edit-firm').val();
			var efname = $('.edit-firstname').val();
			var elname = $('.edit-lastname').val();
			var ecity = $('.edit-city').val();
			var ezip = $('.edit-zip').val();
			var ephone = $('.edit-phone').val();
			var eemail = $('.edit-email').val();
			if(ephone != ''){
				if(isNaN(ephone)){
					var newno = ephone.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
					var countPhone = newno.length;
					if(isNaN(newno)){
						$('.edit-phone-msg').show();
						return false;
					}else if(countPhone != 10){
						$('.edit-phone-msg').show();
						return false;
					}else{
						$('.edit-phone').val(newno);
					}
				}else{
					var countPhone = ephone.length;
					if(countPhone != 10){
						$('.edit-phone-msg').show();
						return false;
					}
				}
			}
			if(eemail != ''){
				var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				if (!testEmail.test(eemail)){
					$('.edit-email-msg').show();
					return false;
				}
			}
			if((efirm == '') || (efname == '') || (elname == '') || (ecity == '') || (ezip == '')){
				$('.edit-phone-msg').hide();
				$('.edit-email-msg').hide();
				if(efirm == ''){
					$('.edit-firm-msg').show();
				}else{
					$('.edit-firm-msg').hide();
				}
				if(efname == ''){
					$('.edit-fn-msg').show();
				}else{
					$('.edit-fn-msg').hide();
				}
				if(elname == ''){
					$('.edit-ln-msg').show();
				}else{
					$('.edit-ln-msg').hide();
				}
				if(ecity == ''){
					$('.edit-city-msg').show();
				}else{
					$('.edit-city-msg').hide();
				}
				if(ezip == ''){
					$('.edit-zip-msg').show();
				}else{
					$('.edit-zip-msg').hide();
				}
			}else{
				$.ajax({
					type: 'post',
					dataType: 'json',
					url: 'modules/case/ajax/formpost.php',
					data: $('.edit-form').serialize(),
					success: function (data) {
						$('#selUser').html(data.aoption);
						$('#result').html(data.resulttxt);
						$('#myModal').hide();
						$('.edit-firm-msg').hide();
						$('.edit-fn-msg').hide();
						$('.edit-ln-msg').hide();
						$('.edit-city-msg').hide();
						$('.edit-zip-msg').hide();
						$('.edit-phone-msg').hide();
						$('.edit-email-msg').hide();
						var id = $(".edit_id").val();
						$('#selUser option[value="'+id+'"]').attr("selected", "selected");
					}
				});
			}
		});

		$(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
		});
	});
</script>
<?php
	}
	else
		displaysitemessages();
//}
?>