<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(17); 
$dmid=$_REQUEST['crrefdmid'];
if(isset($_POST['updatedoctor'])){
	errorclear();
	if(isset($_POST['dmid'])) {
		$script = 'SQLUpdate';
		$table = 'doctors';
		$keyfield = 'dmid';
		$fields[$table]=array(
						'dminactive'=>'boolean',
						'dmsname'=>'name',
						'dmfname'=>'name',
						'dmlname'=>'name',
						'dmnpi'=>'name',
						'dmupin'=>'name',
						'dmdescphys'=>'memo',
						'dmdob'=>'date',
						'dmdscode'=>'dscode',
						'dmdclass'=>'dclass',
						'dmdescwork'=>'memo',
						'dmwcmix'=>'percentage',
						'dmpimix'=>'percentage',
						'dmothermix'=>'percentage',
						'dmestrefer'=>'integer',
                        'dmemail'=>'email'
					);
	// trim and strip all input
		foreach($_POST as $key=>$val) {
			if($key != 'button') {
				if(is_string($_POST[$key]))
					$_POST[$key] = stripslashes(strip_tags(trim($val)));
			}
		}
	// Validate form fields
//		require_once('validation.php');
		if(errorcount() == 0) {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
			$dbhandle = dbconnect();
			
			$set=array();
			$query = "UPDATE $table ";
	
			foreach($fields[$table] as $fieldname=>$fieldtype) {
				if(isset($_POST["$fieldname"])) {
					switch($fieldtype):
						case 'boolean' :
							$values["$fieldname"] = ($_POST["$fieldname"]=='1');
							break;
						case 'name' :
							$values["$fieldname"] = strtoupper($_POST["$fieldname"]);
							break;
						case 'date' :
							if(!empty($_POST["$fieldname"]))
								$values["$fieldname"] = dbDate($_POST["$fieldname"]);
							else
								$values["$fieldname"] = NULL;
							break;
                        case 'email' :
                            $values["$fieldname"] = strtolower($_POST["$fieldname"]);
                            break;
						default:
							$values["$fieldname"] = $_POST["$fieldname"];
							break;
					endswitch;
				}
				if(!empty($values["$fieldname"]))
					$values["$fieldname"] = "'" . mysqli_real_escape_string($dbhandle,$values["$fieldname"]) . "'";
				else
					$values["$fieldname"] = "NULL";
			}
            
	
			if(count($values) > 0) {
				$auditfields = getauditfields();
				$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
				$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
				$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
				$set=array();
				foreach($values as $fieldname=>$fieldvalue) 
					$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
				$query .= "SET " . implode(', ', $set) . " ";
				
				$query .= "WHERE $keyfield='" . $dmid . "'";
	//execute the SQL query 
				$result = mysqli_query($dbhandle,$query);
				if($result) {
					notify("001", "Doctor " . $values['dmlname'] . ", " . $values['dmfname'] . " successfully updated.");
//					foreach($fields[$table] as $fieldname=>$fieldtype) 
//						unset($_POST["$fieldname"]);
//					unset($_SESSION['button']);
//					$_POST['buttonSetSearchDoctor']='1';
//					$_POST['searchdoctor']['dmid'] = $_SESSION['id'];
				}
				else 
					error("001", "MYSQL" . mysqli_error($dbhandle));
			}
			mysqli_close($dbhandle);
		}
	}
	else
		error("001", "id field error (should never happen).");
?>
<script type="text/javascript" language="javascript">
	window.opener.location.href = window.opener.location.href;
	if (window.opener.progressWindow) {
		window.opener.progressWindow.close()
	}
	window.close();
  	</script>
<?php
}

function getdscodes() {
	if(!isset($_SESSION['dscodes']) || (isset($_SESSION['dscodes']) && (count($_SESSION['dscodes'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT dscode, dsdesc FROM doctor_specialties ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$dscodesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$dscodesarray[$result['dscode']] = $result['dsdesc'];
		}
		return($dscodesarray);
	}
	else
		return($_SESSION['dscodes']);
}

function getdclasses() {
	if(!isset($_SESSION['dclasses']) || (isset($_SESSION['dclasses']) && (count($_SESSION['dclasses'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT dccode, dcdesc FROM doctor_classes ";
//		dump("query",$query);
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$dccodesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$dccodesarray[$result['dccode']] = $result['dcdesc'];
		}
		return($dccodesarray);
	}
	else
		return($_SESSION['dclasses']);
}

$_SESSION['dscodes']=getdscodes();
$_SESSION['dclasses']=getdclasses();

$script = 'editForm';
$table = 'doctors';
$keyfield = 'dmid';
$fields[$table]=array(
				'dminactive'=>'boolean',
				'dmsname'=>'name',
				'dmfname'=>'name',
				'dmlname'=>'name',
				'dmnpi'=>'name',
				'dmupin'=>'name',
				'dmdescphys'=>'memo',
				'dmdob'=>'date',
				'dmdscode'=>'dscode',
				'dmdclass'=>'dclass',
				'dmdescwork'=>'memo',
				'dmwcmix'=>'percentage',
				'dmpimix'=>'percentage',
				'dmothermix'=>'percentage',
				'dmestrefer'=>'integer',
                'dmemail'=>'email'
			);

$buttonvalue = 'Confirm Add Doctor';
if(!empty($dmid)) {
	$buttonvalue = 'Confirm Update Doctor';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM $table WHERE $keyfield='$dmid'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			foreach($result as $fieldname=>$fieldvalue) {
				$_POST[$fieldname] = $fieldvalue;
			}
		}
		else
			error('001', "Non-unique field error (should never happen).");	
	}
	else
		error('002', mysqli_error($dbhandle));
}

if(errorcount() == 0) {
	// Clear Selected Specialty Codes
	foreach($_SESSION['dscodes'] as $key=>$val)
		$selected_dscode[$key]='';
	// Save Posted Specialty Code as Selected Specialty Code
	if(isset($_POST['dmdscode']) && !empty($_POST['dmdscode'])) 
		$selected_dscode[$_POST['dmdscode']] = ' selected ';
	
	// Clear Selected Doctor Classes
	foreach($_SESSION['dclasses'] as $key=>$val)
		$selected_dclass[$key]='';
	// Save Posted Doctor Classes as Selected Doctor Class
	if(isset($_POST['dmdclass']) && !empty($_POST['dmdclass'])) 
		$selected_dclass[$_POST['dmdclass']] = ' selected ';

?>

<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editDoctorForm" >
		<input name="dmid" type="hidden" value="<?php echo $dmid; ?>" />
		<fieldset>
		<legend>Edit Doctor Information</legend>
		<table>
			<tr>
				<td>
					Inactive
				</td>
				<td>
					<input name="dminactive" type="checkbox" value="1" <?php if(isset($_POST['dminactive']) && $_POST['dminactive'] == '1') echo "checked"; ?> />
		</td>
		</tr>
		<tr>
			<td>Short Name </td>
			<td><input name="dmsname" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmsname'])) echo $_POST['dmsname'];?>" />
			</td>
		</tr>
		<tr>
			<td>Last Name </td>
			<td><input name="dmlname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dmlname'])) echo $_POST['dmlname'];?>" />
				<a href="https://nppes.cms.hhs.gov/NPPES/NPIRegistryPaginate.do?searchNpi=&city=&firstName=<?php echo $_POST['dmfname']; ?>&orgName=&searchType=ind&state=&orgDba=&nextPage=1&lastName=<?php echo $_POST['dmlname']; ?>&zip=" target="_blank">lookup</a> </td>
		</tr>
		<tr>
			<td>First Name </td>
			<td><input name="dmfname" type="text" size="30" maxlength="50" value="<?php if(isset($_POST['dmfname'])) echo $_POST['dmfname'];?>" />
			</td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input name="dmemail" type="email" size="30" maxlength="30" value="<?php if(isset($_POST['dmemail'])) echo $_POST['dmemail'];?>" />
			</td>
		</tr>
		<tr>
			<td>NPI#</td>
			<td><input name="dmnpi" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['dmnpi'])) echo $_POST['dmnpi'];?>" />
				<a href="https://npiregistry.cms.hhs.gov/?searchNpi=<?php echo $_POST['dmnpi']; ?>&city=&firstName=&orgName=&searchType=ind&state=&orgDba=&nextPage=1&lastName=&zip=" target="_blank">lookup</a> </td>
		</tr>
		<tr>
			<td>UPIN#</td>
			<td><input name="dmupin" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['dmupin'])) echo $_POST['dmupin'];?>" />
			</td>
		</tr>
		<tr>
			<td>Specialty </td>
			<td><select name="dmdscode" size="1">
					<option label=""></option>
					<?php
							foreach($_SESSION['dscodes'] as $key=>$val)
								echo "<option " . $selected_dscode[$key] . " value='" . $key . "'>" . $_SESSION['dscodes'][$key] . "</option>"; 
						?>
				</select></td>
		</tr>
		<tr>
			<td>MD Class </td>
			<td><select name="dmdclass" size="1">
					<option label=""></option>
					<?php
							foreach($_SESSION['dclasses'] as $key=>$val)
								echo "<option " . $selected_dclass[$key] . " value='" . $key . "'>" . $_SESSION['dclasses'][$key] . "</option>"; 
						?>
				</select></td>
		</tr>
		<tr>
			<td>Estimated Referrals </td>
			<td><input name="dmestrefer" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmestrefer'])) echo $_POST['dmestrefer'];?>" />
			</td>
		</tr>
		<tr>
			<td>Date of Birth </td>
			<td><input name="dmdob" type="text" size="10" maxlength="10" value="<?php if(!empty($_POST['dmdob'])) echo date('m/d/Y', strtotime($_POST["dmdob"])); ?>" />
			</td>
		</tr>
		<tr>
			<td>Physical Description </td>
			<td><input name="dmdescphys" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['dmdescphys'])) echo $_POST["dmdescphys"]; ?>" />
			</td>
		</tr>
		<tr>
			<td>Description of Work/Practice </td>
			<td><input name="dmdescwork" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['dmdescwork'])) echo $_POST["dmdescwork"]; ?>" />
			</td>
		</tr>
		<tr>
			<td>Work Comp Percentage (Format 999.99) </td>
			<td><input name="dmwcmix" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmwcmix'])) echo $_POST["dmwcmix"]; ?>" />
			</td>
		</tr>
		<tr>
			<td>Personal Injury Percentage </td>
			<td><input name="dmpimix" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmpimix'])) echo $_POST["dmpimix"]; ?>" />
			</td>
		</tr>
		<tr>
			<td>Other Type of Work Percentage </td>
			<td><input name="dmothermix" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmothermix'])) echo $_POST["dmothermix"]; ?>" />
			</td>
		</tr>
		</table>
		<div class="containedBox">
			<div style="float:left; margin:10px;">
				<input name="button[]" type="button" value="Cancel" onclick="javascript:window.close();" />
			</div>
			<div style="float:left; margin:10px;">
				<input name="updatedoctor" type="submit" value="Confirm Update Doctor" />
			</div>
		</div>
		</fieldset>
	</form>
</div>
<?php
}
?>
