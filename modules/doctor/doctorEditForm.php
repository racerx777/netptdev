<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/sitedivs.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script type="text/javascript">
document.title="Display/Update Doctor Information";
function toggleenable(id) {
	var ele = document.getElementById(id);
	ele.disabled= !ele.disabled;
}
function toggledoctorNameFields() {
	toggleenable("dmlname");
	toggleenable("dmfname");
}
function cleardoctorNameFields() {
	document.doctorEditForm.dmlname.value='';
	document.doctorEditForm.dmfname.value='';
}
function doctorNameEdit() {
	var divUpd = document.getElementById("doctorNameUpdate");
	var divAdd = document.getElementById("doctorNameAdd");
	var divSel = document.getElementById("doctorNameSelect");
	divUpd.style.display="block";
	divAdd.style.display="none";
	divSel.style.display="none";
	toggledoctorNameFields();
}
function doctorNameNew() {
	var divUpd = document.getElementById("doctorNameUpdate");
	var divAdd = document.getElementById("doctorNameAdd");
	var divSel = document.getElementById("doctorNameSelect");
	divUpd.style.display="none";
	divAdd.style.display="block";
	divSel.style.display="none";
	toggledoctorNameFields();
	cleardoctorNameFields();
}
function doctorNameSelect() {
	var divUpd = document.getElementById("doctorNameUpdate");
	var divAdd = document.getElementById("doctorNameAdd");
	var divSel = document.getElementById("doctorNameSelect");
	divUpd.style.display="none";
	divAdd.style.display="none";
	divSel.style.display="block";
	toggledoctorNameFields();
}

function toggledoctorLocationFields() {
	toggleenable("dlname");
	toggleenable("dladdress");
	toggleenable("dlcity");
	toggleenable("dlstate");
	toggleenable("dlzip");
	toggleenable("dlphone");
	toggleenable("dlfax");
	toggleenable("dlemail");
	toggleenable("dlofficehours");
}
function cleardoctorLocationFields(){
	document.doctorEditForm.dlname.value='';
	document.doctorEditForm.dladdress.value='';
	document.doctorEditForm.dlcity.value='';
	document.doctorEditForm.dlstate.value='';
	document.doctorEditForm.dlzip.value='';
	document.doctorEditForm.dlphone.value='';
	document.doctorEditForm.dlfax.value='';
	document.doctorEditForm.dlemail.value='';
	document.doctorEditForm.dlofficehours.value='';
}
function doctorLocationEdit() {
	var divUpd = document.getElementById("doctorLocationUpdate");
	var divAdd = document.getElementById("doctorLocationAdd");
	var divSel = document.getElementById("doctorLocationSelect");
	divUpd.style.display="block";
	divAdd.style.display="none";
	divSel.style.display="none";
	toggledoctorLocationFields();
}
function doctorLocationNew() {
	var divUpd = document.getElementById("doctorLocationUpdate");
	var divAdd = document.getElementById("doctorLocationAdd");
	var divSel = document.getElementById("doctorLocationSelect");
	divUpd.style.display="none";
	divAdd.style.display="block";
	divSel.style.display="none";
	cleardoctorLocationFields();
	toggledoctorLocationFields();
}
function doctorLocationSelect() {
	var divUpd = document.getElementById("doctorLocationUpdate");
	var divAdd = document.getElementById("doctorLocationAdd");
	var divSel = document.getElementById("doctorLocationSelect");
	divUpd.style.display="none";
	divAdd.style.display="none";
	divSel.style.display="block";
	toggledoctorLocationFields();
}
function toggledoctorContactFields() {
	toggleenable("dlstitle");
	toggleenable("dlsname");
	toggleenable("dlsphone");
	toggleenable("dlsfax");
	toggleenable("dlsemail");
}
function cleardoctorContactFields(){
	document.doctorEditForm.dlstitle.value='';
	document.doctorEditForm.dlsname.value='';
	document.doctorEditForm.dlsphone.value='';
	document.doctorEditForm.dlsfax.value='';
	document.doctorEditForm.dlsemail.value='';
}
function doctorContactEdit() {
	var divUpd = document.getElementById("doctorContactUpdate");
	var divAdd = document.getElementById("doctorContactAdd");
	var divSel = document.getElementById("doctorContactSelect");
	divUpd.style.display="block";
	divAdd.style.display="none";
	divSel.style.display="none";
	toggledoctorContactFields();
}
function doctorContactNew() {
	var divUpd = document.getElementById("doctorContactUpdate");
	var divAdd = document.getElementById("doctorContactAdd");
	var divSel = document.getElementById("doctorContactSelect");
	divUpd.style.display="none";
	divAdd.style.display="block";
	divSel.style.display="none";
	cleardoctorContactFields();
	toggledoctorContactFields();
}
function doctorContactSelect() {
	var divUpd = document.getElementById("doctorContactUpdate");
	var divAdd = document.getElementById("doctorContactAdd");
	var divSel = document.getElementById("doctorContactSelect");
	divUpd.style.display="none";
	divAdd.style.display="none";
	divSel.style.display="block";
	toggledoctorContactFields();
}
</script>
<?php
unset($mode);
if(isset($_POST['mode'])) 
	$mode=$_POST['mode'];
if(isset($_REQUEST['mode'])) 
	$mode=$_REQUEST['mode'];

unset($crid);
if(isset($_POST['crid'])) 
	$crid=$_POST['crid'];
if(isset($_REQUEST['crid'])) 
	$crid=$_REQUEST['crid'];

unset($areacode);
if(isset($_POST['areacode'])) 
	$areacode=$_POST['areacode'];
if(isset($_REQUEST['areacode'])) 
	$areacode=$_REQUEST['areacode'];

unset($doctorlname);
if(isset($_POST['doctorlname'])) 
	$doctorlname=$_POST['doctorlname'];
if(isset($_REQUEST['doctorname'])) 
	$doctorlname=$_REQUEST['doctorlname'];

unset($doctorfname);
if(isset($_POST['doctorfname'])) 
	$doctorfname=$_POST['doctorfname'];
if(isset($_REQUEST['doctorfname'])) 
	$doctorfname=$_REQUEST['doctorfname'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(!empty($crid)) {
// get case, patient an ddoctor information
}
else {
// 
}

if( empty($mode) || ( empty($doctorlname) && empty($doctorfname) ) ) { // start at step 1 - Find number by area code
$pnum=$case['pnum'];
$patientname=$patient['fname'].' '.$patient['lname'];
?>

<div class="centerFieldset">
	<form method="post" name="doctorSearchByAreaCodeForm">
		<fieldset style="text-align:center;">
			<legend><?php echo "Doctor Search: Case Record ID $crid, Patient Record ID $pnum, $patientname" ?></legend>
			<table style="text-align:left;">
				<tr>
					<td valign="top" nowrap="nowrap">Doctor Last Name</td>
					<td><div id="doctorNameSelectdoctorlname" style="display:block">
							<input type="text" name="doctorlname" size="35" maxlength="35" value="<?php if(isset($doctorlname)) echo $doctorlname; ?>" />
						</div></td>
				</tr>
				<tr>
					<td valign="top" nowrap="nowrap">Doctor First Name</td>
					<td><div id="doctorNameSelectdoctorlname" style="display:block">
							<input type="text" name="doctorfname" size="35" maxlength="35" value="<?php if(isset($doctorfname)) echo $doctorfname; ?>" />
						</div></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="mode" id="mode" value="Search for doctor" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php
}
else {
	if($mode=='Search for doctor') {
		$doctorlname3=trim(substr($doctorlname,0,3));
		$doctorfname3=trim(substr($doctorfname,0,3));
		$query="
			SELECT * 
			FROM doctors
			WHERE dmlname LIKE '$doctorlname3%' and dmfname LIKE '$doctorfname3%'
		";
		if($result=mysqli_query($dbhandle,$query)) { ?>
<div id="doctorlist">
	<form method="post" name="doctorSearchByAreaCodeForm">
		<fieldset style="text-align:center;">
			<legend><?php echo "Doctor Search: Case Record ID $crid, Patient Record ID $pnum, $patientname" ?></legend>
			<table>
				<tr>
					<th>&nbsp; </th>
					<th>Doctor Last Name </th>
					<th>Doctor First Name </th>
				</tr>
				<?php
			while($doctors=mysqli_fetch_assoc($result)) { ?>
				<tr>
					<td><input type="submit" name="dmid[<?php echo $doctors['dmid']; ?>]" value="Select Doctor" /></td>
					<td><?php echo $doctors['dmlname']; ?></td>
					<td><?php echo $doctors['dmfname']; ?></td>
				</tr>
				<?php
			} ?>
			</table>
		</fieldset>
	</form>
</div>
<?php
		}
	}
}
if(isset($_POST['dlidShowAll'])) {
	if($_POST['dlidShowAll']=='Show All')
		$_POST['dlid_show_all']='1';
	else
		$_POST['dlid_show_all']='0';
	displaysitemessages();
}

if(isset($_POST['dlsidShowAll'])) {
	if($_POST['dlsidShowAll']=='Show All')
		$_POST['dlsid_show_all']='1';
	else
		if($_POST['dlsidShowAll']=='Show Doc')
			$_POST['dlsid_show_all']='2';
		else
			if($_POST['dlsidShowAll']=='Show Loc')
				$_POST['dlsid_show_all']='3';
			else
				$_POST['dlsid_show_all']='0';
	displaysitemessages();
}

if(isset($_POST['AdddoctorName'])) {
	require_once('doctorSQLFunctions.php');
	if($insertid = doctorName('INSERT',NULL,$_POST))
		$_POST['dmid'] = $insertid;
	displaysitemessages();
}

if(isset($_POST['UpdatedoctorName'])) {
	require_once('doctorSQLFunctions.php');
	doctorName('UPDATE',$_POST['dmid'],$_POST);
	displaysitemessages();
}

if(isset($_POST['AdddoctorLocation'])) {
	require_once('doctorSQLFunctions.php');
	if($insertid = doctorLocation('INSERT',NULL,$_POST));
		$_POST['dlid'] = $insertid; 
	displaysitemessages();
}
if(isset($_POST['UpdatedoctorLocation'])) {
	require_once('doctorSQLFunctions.php');
	doctorLocation('UPDATE',$_POST['dlid'],$_POST);
	displaysitemessages();
}

if(isset($_POST['AdddoctorContact'])) {
	require_once('doctorSQLFunctions.php');
	if($insertid = doctorContact('INSERT',NULL,$_POST));
		$_POST['dlsid'] = $insertid;
	displaysitemessages();
}
if(isset($_POST['UpdatedoctorContact'])) {
	require_once('doctorSQLFunctions.php');
	doctorContact('UPDATE',$_POST['dlsid'],$_POST);
	displaysitemessages();
}

if(isset($_POST['submitbutton'])) {
	require_once('doctorSQLUpdate.php');
?>
<script language="javascript" type="text/javascript">
window.opener.location.href = window.opener.location.href;
if (window.opener.progressWindow) {
	window.opener.progressWindow.close()
}
window.close();
</script>
<?php
}

if(empty($crid)) {
	error("001","No Case identifier ($crid)");
	displaysitemessages(); ?>
<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />
<?	exit();
}

// Retrieve values from database if form was never loaded

$buttonvalue = "Update Doctor";
if(!isset($_POST['init'])) {
// Display Patient Information
	$script = 'doctorEditForm';
	$table = 'cases';
	$keyfield = 'crid';
	$fields[$table]=array(
				'crid'=>'integer', 			// Case Unique Identifier
				'crpnum'=>'varchar', 		// PTOS Case Number
				'crrefdmid'=>'integer', 	// Doctor Identifier 
				'crrefdlid'=>'integer', 	// Doctor Location Identifier
			);

	$fieldslist = implode(", ", array_keys($fields[$table]));
	$query = "
		SELECT $fieldslist, paid, palname, pafname, dmid, dmlname, dmfname, dlid, dlname, dladdress, dlcity, dlstate, dlzip, dlphone, dlfax, dlsid
		FROM $table 
		LEFT JOIN patients ON crpaid=paid 
		LEFT JOIN doctors ON crrefdmid=dmid 
		LEFT JOIN doctor_locations ON crrefdlid=dlid 
		LEFT JOIN doctor_relationships ON crrefdmid=drdmid and crrefdlid=drdlid
		LEFT JOIN doctor_locations_contacts ON drdlsid=dlsid
		WHERE crid='$crid'
	";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			foreach($row as $fieldname=>$fieldvalue) {
//				dump("Fieldname $fieldname:",$fieldvalue);
				if($fields["$table"]["$fieldname"]=='date') {
					if($fieldvalue == '1999-11-30 00:00:00')
						$fieldvalue=NULL;
				}
				if(!empty($fieldvalue))
					$_POST[$fieldname] = $fieldvalue;
			}
//			$_POST['dmid'] = $_POST['crrefdmid'];
//			$_POST['dlid'] = $_POST['crrefdlid'];
//			$_POST['dlsid'] = $_POST['crdlsid1'];
//dumppost();
		}
		else
			error('001', "FETCH:$query<br>".mysqli_error($dbhandle));	
	}
	else
		error('002', "SELECT:$query<br>".mysqli_error($dbhandle));	
}
	if(errorcount() == 0) {
		$dmid = $_POST['dmid'];
		$dlid = $_POST['dlid'];
		$dlsid = $_POST['drdlsid'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
		$doctorlistoptions="";
		$doctorlocationlistoptions="";
		$doctorlocationcontactlistoptions="";
		$doctorlocationdisabled='disabled="disabled"';
		$doctorlocationcontactdisabled='disabled="disabled"';

		$doctorlist = getDoctorList();
		$dminfo="<br>";
		$dlinfo="<br><br><br><br><br><br><br><br><br>";
		$dlsinfo="<br><br><br><br><br><br><br><br><br>";
		if($doctorlist) {
			if(count($doctorlist) > 0) {
				if(is_array($doctorlist) && !empty($dmid) && array_key_exists($dmid, $doctorlist)) {
					foreach($doctorlist[$dmid] as $key=>$val) {
//						dump("Key $key:",$val);
						$_POST[$key]=$val;
					}
				}
				$doctorlistoptions =  getSelectOptions(
					$arrayofarrayitems=$doctorlist, 
					$optionvaluefield='dmid', 
					$arrayofoptionfields=array(
						'dmlname'=>', ', 
						'dmfname'=>'' 
						), 
					$defaultoption=$dmid, 
					$addblankoption=TRUE, 
					$arraykey='', 
					$arrayofmatchvalues=array()); 
				if(!empty($dmid) ) {
					$doctorlocationdisabled="";
					if($_POST['dlid_show_all']=='1') 
						$doctorlocationlist  = getDoctorLocationsList();
					else
						$doctorlocationlist  = getDoctorLocationsList($dmid);
					if(is_array($doctorlocationlist) && !empty($_POST['dlid']) && array_key_exists($_POST['dlid'],$doctorlocationlist) ) {
						foreach($doctorlocationlist[$_POST['dlid']] as $key=>$val) {
//							dump("Key $key:",$val);
							$_POST[$key]=$val;
						}
					}
					$doctorlocationlistoptions = getSelectOptions(
						$arrayofarrayitems=$doctorlocationlist, 
						$optionvaluefield='dlid', 
						$arrayofoptionfields=array(
							'dlphone'=>', ', 
							'dlcity'=>', ', 
							'dlname'=>'' 
							), 
						$defaultoption=$dlid, 
						$addblankoption=TRUE, 
						$arraykey='', 
						$arrayofmatchvalues=array());
//dump("dm", $dmid);
//dump("dl", $dlid);
//dump("dls", $dlsid);
					if(!empty($dmid) && !empty($dlid)) {
						$doctorlocationcontactdisabled="";
//						dump("_POST['dlsid_show_all']",$_POST['dlsid_show_all']);
						if($_POST['dlsid_show_all']=='1') 
							$doctorlocationcontactlist  = getDoctorLocationsContactsList();
						else
							if($_POST['dlsid_show_all']=='2') 
								$doctorlocationcontactlist  = getDoctorLocationsContactsList($dmid, NULL);
							else
								if($_POST['dlsid_show_all']=='3') 
									$doctorlocationcontactlist  = getDoctorLocationsContactsList(NULL, $dlid);
								else
									$doctorlocationcontactlist  = getDoctorLocationsContactsList($dmid, $dlid);
//dump("doctorlocationcontactlist", $doctorlocationcontactlist);
						if(is_array($doctorlocationcontactlist) && !empty($_POST['dlsid']) && array_key_exists($_POST['dlsid'], $doctorlocationcontactlist) ) {
							foreach($doctorlocationcontactlist[$_POST['dlsid']] as $key=>$val) {
//								dump("Key $key:",$val);
								$_POST[$key]=$val;
							}
						}
						$doctorlocationcontactlistoptions = getSelectOptions(
							$arrayofarrayitems=$doctorlocationcontactlist, 
							$optionvaluefield='dlsid', 
							$arrayofoptionfields=array(
								'dlstitle'=>', ', 
								'dlsname'=>' ', 
								'dlsphone'=>' ', 
								'dlsfax'=>'' 
								), 
							$defaultoption=$dlsid, 
							$addblankoption=TRUE, 
							$arraykey='', 
							$arrayofmatchvalues=array());
					}
					else
						$doctorlocationcontactlistoptions = '<option value="">Select a Doctor and Location...</option>';
				}
				else {
					$doctorlocationlistoptions = '<option value="">Select a Doctor...</option>';
					$doctorlocationcontactlistoptions = '<option value="">Select a Doctor and Location...</option>';
				}
			}
			else
				echo("Error-No Doctors in Doctors table.");
		}
		else
			echo("Error-getdoctorlist.");
	}
	else
		displaysitemessages();

if(!empty($_POST['crpnum'])) 
	$pnumhtml = $_POST['crpnum'];
else
	$pnumhtml = '(not assigned)';

if(!empty($_POST['dmid'])) 
	$doctorNameEditButton = '<input type="button" value="Edit" onclick="doctorNameEdit()" />';
$doctorNameNewButton = '<input type="button" value="New" onclick="doctorNameNew()" />';
if(!empty($_POST['dlid'])) 
	$doctorLocationEditButton = '<input type="button" value="Edit" onclick="doctorLocationEdit()" />';
$doctorLocationNewButton = '<input type="button" value="New" '.$doctorlocationdisabled.' onclick="doctorLocationNew()" />';

if(empty($_POST['dlid_show_all']))
	$doctorLocationShowButton = '<input name="dlidShowAll" type="submit" value="Show All" />';
else
	$doctorLocationShowButton = '<input name="dlidShowAll" type="submit" value="Hide Others" />';

if(!empty($_POST['dlsid'])) 
	$doctorContactEditButton = '<input type="button" value="Edit" onclick="doctorContactEdit()" />';
$doctorContactNewButton = '<input type="button" value="New" '.$doctorlocationcontactdisabled.' onclick="doctorContactNew()" />';


if(isset($_POST['dlsidShowAll'])) {
	if($_POST['dlsid_show_all']=='1')
		$doctorLocationContactShowButton = '<input name="dlsidShowAll" type="submit" value="Show Doc" />';
	else
		if($_POST['dlsid_show_all']=='2')
			$doctorLocationContactShowButton = '<input name="dlsidShowAll" type="submit" value="Show Loc" />';
		else
			$doctorLocationContactShowButton = '<input name="dlsidShowAll" type="submit" value="Hide Others" />';
}
else 
	$doctorLocationContactShowButton = '<input name="dlsidShowAll" type="submit" value="Show All" />';
?>
<div class="centerFieldset">
	<form method="post" name="doctorEditForm">
		<fieldset style="text-align:center;">
			<legend><?php echo $buttonvalue; ?> Information for Patient #<?php echo $_POST['paid'] . " " . $_POST['palname'] . ", " . $_POST['pafname']; ?></legend>
			<table style="text-align:left;">
				<tr>
					<td valign="top" nowrap="nowrap">Doctor Name Code</td>
					<td><div id="doctorNameSelect" style="display:block">
							<select name="dmid" type="text" size="1" maxlength="30" value="<?php if(isset($dmid)) echo $dmid; ?>" onchange="javascript:submit();" />
							
							<?php echo $doctorlistoptions; ?>
							</select>
							<?php echo $doctorNameEditButton.$doctorNameNewButton; ?> </div>
						<table>
							<tr>
								<td>Last Name</td>
								<td><input id="dmlname" name="dmlname" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['dmlname']; ?>" onchange="upperCase(this.id)" /></td>
							</tr>
							<tr>
								<td>First Name</td>
								<td><input id="dmfname" name="dmfname" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['dmfname']; ?>" onchange="upperCase(this.id)" /></td>
							</tr>
						</table>
						<div id="doctorNameAdd" style="display: none">
							<input name="AdddoctorName" type="submit" value="Add Doctor Name" />
							<input type="button" value="Cancel" onclick="doctorNameSelect()" />
						</div>
						<div id="doctorNameUpdate" style="display: none">
							<input name="UpdatedoctorName" type="submit" value="Update Doctor Name" />
							<input type="button" value="Cancel" onclick="doctorNameSelect()" />
						</div></td>
				</tr>
				<tr>
					<td valign="top" nowrap="nowrap">Doctor Location Code</td>
					<td><div id="doctorLocationSelect" style="display:block">
							<select id="dlid" name="dlid" type="text" size="1" maxlength="30" value="<?php if(isset($dlid)) echo $dlid;?>" <?php if(isset($doctorlocationdisabled)) echo $doctorlocationdisabled;?> onchange="javascript:submit();" />
							
							<?php echo $doctorlocationlistoptions; ?>
							</select>
							<?php echo $doctorLocationEditButton.$doctorLocationNewButton.$doctorLocationShowButton; ?> </div>
						<table id="doctorlocationTable">
							<tr>
								<td> Location</td>
								<td><input id="dlname" name="dlname" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['dlname'] ?>" onchange="upperCase(this.id)" /></td>
							</tr>
							<tr>
								<td> Address</td>
								<td><input id="dladdress" name="dladdress" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['dladdress'] ?>" onchange="upperCase(this.id)" /></td>
							</tr>
							<tr>
								<td> City </td>
								<td><input id="dlcity" name="dlcity" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['dlcity'] ?>" onchange="upperCase(this.id)" />
									State
									<input id="dlstate" name="dlstate" type="text" size="2" maxlength="2" disabled="disabled" value="<?php echo $_POST['dlstate'] ?>" onchange="upperCase(this.id)" />
									Zip
									<input id="dlzip" name="dlzip" type="text" size="10" maxlength="10" disabled="disabled" value="<?php echo $_POST['dlzip'] ?>"  onchange="displayzip(this.id)"/></td>
							</tr>
							<tr>
								<td>Phone</td>
								<td><input id="dlphone" name="dlphone" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo displayPhone($_POST['dlphone']) ?>" onchange="displayphone(this.id)" /></td>
							</tr>
							<tr>
								<td>Fax</td>
								<td><input id="dlfax" name="dlfax" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo displayPhone($_POST['dlfax']) ?>" onchange="displayphone(this.id)" /></td>
							</tr>
							<tr> </tr>
							<tr>
								<td> email </td>
								<td><input id="dlemail" name="dlemail" type="text" size="30" maxlength="64" disabled="disabled" value="<?php echo $_POST['dlemail'] ?>" /></td>
							</tr>
							<tr>
								<td> office hours</td>
								<td><input id="dlofficehours" name="dlofficehours" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['dlofficehours'] ?>"  onchange="upperCase(this.id)"/></td>
							</tr>
						</table>
						<div id="doctorLocationAdd" style="display: none">
							<input style="float:left" name="AdddoctorLocation" type="submit" value="Add Doctor Location" />
							<input style="float:right" type="button" value="Cancel" onclick="doctorLocationSelect()" />
						</div>
						<div id="doctorLocationUpdate" style="display: none">
							<input style="float:left" name="UpdatedoctorLocation" type="submit" value="Update Doctor Location" />
							<input style="float:right" type="button" value="Cancel" onclick="doctorLocationSelect()" />
						</div></td>
				</tr>
				<tr>
					<td valign="top">Primary Contact Code</td>
					<td><div id="doctorContactSelect" style="display:block">
							<select id="dlsid" name="dlsid" type="text" size="1" maxlength="30" value="<?php if(isset($dlsid)) echo $dlsid;?>" <?php if(isset($doctorlocationcontactdisabled)) echo $doctorlocationcontactdisabled;?> onchange="javascript:submit();" />
							
							<?php echo $doctorlocationcontactlistoptions; ?>
							</select>
							<?php echo $doctorContactEditButton.$doctorContactNewButton.$doctorLocationContactShowButton; ?> </div>
						<table id="doctorlocationTable">
							<tr>
								<td>Title</td>
								<td><input id="dlstitle" name="dlstitle" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['dlstitle'] ?>" onchange="upperCase(this.id)" /></td>
							</tr>
							<tr>
								<td>Name</td>
								<td><input id="dlsname" name="dlsname" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['dlsname'] ?>" onchange="upperCase(this.id)" /></td>
							</tr>
							<tr>
								<td>Phone</td>
								<td><input id="dlsphone" name="dlsphone" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['dlsphone'] ?>" onchange="displayphone(this.id)" /></td>
							</tr>
							<tr>
								<td>Fax</td>
								<td><input id="dlsfax" name="dlsfax" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['dlsfax'] ?>" onchange="displayphone(this.id)" /></td>
							</tr>
							<tr> </tr>
							<tr>
								<td>email</td>
								<td><input id="dlsemail" name="dlsemail" type="text" size="30" maxlength="64" disabled="disabled" value="<?php echo $_POST['dlsemail'] ?>" /></td>
							</tr>
						</table>
						<div id="doctorContactAdd" style="display: none">
							<input style="float:left" name="AdddoctorContact" type="submit" value="Add Doctor Location Contact" />
							<input style="float:right" type="button" value="Cancel" onclick="doctorContactSelect()" />
						</div>
						<div id="doctorContactUpdate" style="display: none">
							<input style="float:left" name="UpdatedoctorContact" type="submit" value="Update Doctor Location Contact" />
							<input style="float:right" type="button" value="Cancel" onclick="doctorContactSelect()" />
						</div></td>
				</tr>
				<tr>
					<td colspan="2"><div>
							<div style="float:left; margin:20px;">
								<input name="cancel" type="button" value="Cancel" onclick="window.close()" />
							</div>
							<div style="float:left; margin:20px;">
								<input name="submitbutton" type="submit" value="<?php echo $buttonvalue; ?>" />
							</div>
						</div>
						<input name="crid" type="hidden" value="<?php echo $crid; ?>"/>
						<input name="crpnum" type="hidden" value="<?php echo $_POST['crpnum']; ?>">
						<input name="paid" type="hidden" value="<?php echo $_POST['paid']; ?>">
						<input name="palname" type="hidden" value="<?php echo $_POST['palname']; ?>">
						<input name="pafname" type="hidden" value="<?php echo $_POST['pafname']; ?>">
						<input name="init" type="hidden" value="1"/>
						<input name="dlid_show_all" type="hidden" value="<?php echo $_POST['dlid_show_all']; ?>"/>
						<input name="dlsid_show_all" type="hidden" value="<?php echo $_POST['dlsid_show_all']; ?>"/></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
